"""
predict_gradcam.py

CLI inference script for the Laravel backend. Invoked like:

    python3 predict_gradcam.py <path_to_image> <path_to_model_pth>

Prints a single JSON object to stdout:
{
  "predicted_class": "Psoriasis, Lichen Planus & related diseases",
  "confidence": 93.6,
  "probabilities": [{"label": "...", "confidence": 93.6}, ...top 5],
  "gradcam_base64": "iVBORw0KG..."   # PNG, no data: prefix
}

Keeps the same model architecture / class label order as your original
FastAPI service so Fix_best_model.pth loads without changes.
"""

import sys
import io
import json
import base64
import traceback

import numpy as np
from PIL import Image

import torch
import torch.nn as nn
import torch.nn.functional as F
import torchvision.models as models
import torchvision.transforms as transforms
from torchvision.models import ResNet50_Weights


# === Same class order as your FastAPI script ===
class_labels = [
    "Acne and Rosacea",
    "Actinic Keratosis, Basal Cell Carcinoma & other Malignant Lesions",
    "Atopic Dermatitis",
    "Bullous Disease",
    "Cellulitis, Impetigo & other Bacterial Infections",
    "Eczema",
    "Exanthems & Drug Eruptions",
    "Hair Loss, Alopecia & other Hair Diseases",
    "Herpes, HPV & other STDs",
    "Light Diseases & Disorders of Pigmentation",
    "Lupus & other Connective Tissue diseases",
    "Melanoma, Skin Cancer, Nevi & Moles",
    "Nail Fungus & other Nail Disease",
    "Poison Ivy & other Contact Dermatitis",
    "Psoriasis, Lichen Planus & related diseases",
    "Scabies, Lyme Disease & other Infestations & Bites",
    "Seborrheic Keratoses & other Benign Tumors",
    "Systemic Disease",
    "Tinea, Ringworm, Candidiasis & other Fungal Infections",
    "Urticaria / Hives",
    "Vascular Tumors",
    "Vasculitis",
    "Warts, Molluscum & other Viral Infections",
]


def build_model(num_classes: int):
    model = models.resnet50(weights=ResNet50_Weights.DEFAULT)

    for p in model.parameters():
        p.requires_grad = False
    for p in model.layer3.parameters():
        p.requires_grad = True
    for p in model.layer4.parameters():
        p.requires_grad = True

    # Matches the actual training-time head confirmed via inspect_checkpoint.py:
    # fc.0 Linear(2048,512) -> fc.1 BatchNorm1d(512) -> fc.2 ReLU -> fc.3 Dropout -> fc.4 Linear(512,23)
    model.fc = nn.Sequential(
        nn.Linear(model.fc.in_features, 512),
        nn.BatchNorm1d(512),
        nn.ReLU(),
        nn.Dropout(0.35),
        nn.Linear(512, num_classes),
    )
    return model


def load_model(model_path: str):
    ckpt = torch.load(model_path, map_location="cpu")
    model = build_model(num_classes=len(class_labels))

    if isinstance(ckpt, dict):
        if "model_state_dict" in ckpt:
            state_dict = ckpt["model_state_dict"]
        elif "state_dict" in ckpt:
            state_dict = ckpt["state_dict"]
        else:
            state_dict = ckpt
        model.load_state_dict(state_dict)
    else:
        model = ckpt

    model.eval()
    return model


transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406],
                         std=[0.229, 0.224, 0.225]),
])


def jet_colormap(gray: np.ndarray) -> np.ndarray:
    """Map a 0..1 grayscale array to an RGB 'jet'-style heatmap without
    depending on matplotlib/cv2 being installed."""
    g = np.clip(gray, 0.0, 1.0)
    r = np.clip(1.5 - np.abs(4 * g - 3), 0, 1)
    gch = np.clip(1.5 - np.abs(4 * g - 2), 0, 1)
    b = np.clip(1.5 - np.abs(4 * g - 1), 0, 1)
    return np.stack([r, gch, b], axis=-1)


def generate_gradcam(model, input_tensor, class_idx, original_img: Image.Image):
    activations = {}
    gradients = {}

    target_layer = model.layer4[-1]

    def fwd_hook(_module, _inp, out):
        activations["value"] = out.detach()

    def bwd_hook(_module, _grad_in, grad_out):
        gradients["value"] = grad_out[0].detach()

    h1 = target_layer.register_forward_hook(fwd_hook)
    h2 = target_layer.register_full_backward_hook(bwd_hook)

    try:
        model.zero_grad()
        output = model(input_tensor)
        score = output[0, class_idx]
        score.backward()

        acts = activations["value"][0]      # C x H x W
        grads = gradients["value"][0]        # C x H x W
        weights = grads.mean(dim=(1, 2))     # C

        cam = torch.zeros(acts.shape[1:], dtype=torch.float32)
        for i, w in enumerate(weights):
            cam += w * acts[i]

        cam = F.relu(cam)
        cam = cam - cam.min()
        if cam.max() > 0:
            cam = cam / cam.max()
        cam = cam.numpy()
    finally:
        h1.remove()
        h2.remove()

    # Resize CAM to original image size
    cam_img = Image.fromarray((cam * 255).astype(np.uint8)).resize(
        original_img.size, resample=Image.BILINEAR
    )
    cam_resized = np.asarray(cam_img).astype(np.float32) / 255.0

    heatmap_rgb = (jet_colormap(cam_resized) * 255).astype(np.uint8)
    heatmap_img = Image.fromarray(heatmap_rgb).convert("RGB")

    base = original_img.convert("RGB")
    overlay = Image.blend(base, heatmap_img, alpha=0.55)

    buf = io.BytesIO()
    overlay.save(buf, format="PNG")
    return base64.b64encode(buf.getvalue()).decode("utf-8")


def main():
    if len(sys.argv) < 3:
        print(json.dumps({"error": "usage: predict_gradcam.py <image_path> <model_path>"}))
        sys.exit(1)

    image_path, model_path = sys.argv[1], sys.argv[2]

    try:
        model = load_model(model_path)
        original_img = Image.open(image_path).convert("RGB")
        input_tensor = transform(original_img).unsqueeze(0)
        input_tensor.requires_grad_(False)

        outputs = model(input_tensor)
        probs = F.softmax(outputs, dim=1)[0]
        top5_conf, top5_idx = torch.topk(probs, k=min(5, len(class_labels)))

        predicted_idx = int(top5_idx[0].item())
        predicted_class = class_labels[predicted_idx]
        confidence = round(float(top5_conf[0].item()) * 100, 2)

        probabilities = [
            {"label": class_labels[int(idx)], "confidence": round(float(c) * 100, 2)}
            for c, idx in zip(top5_conf.tolist(), top5_idx.tolist())
        ]

        gradcam_b64 = generate_gradcam(model, input_tensor, predicted_idx, original_img)

        print(json.dumps({
            "predicted_class": predicted_class,
            "confidence": confidence,
            "probabilities": probabilities,
            "gradcam_base64": gradcam_b64,
        }))

    except Exception as e:
        print(json.dumps({"error": str(e), "trace": traceback.format_exc()}))
        sys.exit(1)


if __name__ == "__main__":
    main()
