import io
from fastapi import FastAPI, File, UploadFile
from fastapi.middleware.cors import CORSMiddleware
from PIL import Image
import torch
import torchvision.transforms as transforms
import torch.nn as nn
import torchvision.models as models
from torchvision.models import ResNet50_Weights
import torch.nn.functional as F

# === Build Model ===
def build_model(num_classes: int):
    model = models.resnet50(weights=ResNet50_Weights.DEFAULT)

    # Freeze semua layer
    for param in model.parameters():
        param.requires_grad = False

    # Unfreeze layer4
    for param in model.layer4.parameters():
        param.requires_grad = True

    # FC Head (sama persis dengan training)
    model.fc = nn.Sequential(
        nn.Linear(model.fc.in_features, 512),
        nn.BatchNorm1d(512),
        nn.ReLU(),
        nn.Dropout(0.3),
        nn.Linear(512, num_classes)
    )

    return model

# === Load Model ===
# === Load Model ===
model_path = "EXP01_model_fold_4.pth"

try:
    model = build_model(num_classes=23)

    checkpoint = torch.load(model_path, map_location="cpu")

    if "model_state_dict" in checkpoint:
        model.load_state_dict(checkpoint["model_state_dict"])
        print("✅ Model berhasil dimuat dari model_state_dict.")
    else:
        model.load_state_dict(checkpoint)
        print("✅ Model berhasil dimuat dari state_dict.")

    model.eval()

except Exception as e:
    print("❌ Gagal memuat model:", e)
    model = None

# === FastAPI App ===
app = FastAPI()
app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],
    allow_credentials=True,
    allow_methods=["*"],
    allow_headers=["*"],
)

@app.get("/")
def root():
    return {"message": "Skin Detector API aktif 🚀"}

# === Transformasi Gambar ===
transform = transforms.Compose([
    transforms.Resize((224, 224)),
    transforms.ToTensor(),
    transforms.Normalize(mean=[0.485, 0.456, 0.406],
                         std=[0.229, 0.224, 0.225])
])

# === Class Labels Sesuai Dataset ===
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
    "Warts, Molluscum & other Viral Infections"
]

# === Endpoint Prediksi ===
# @app.post("/predict/")
# async def predict(file: UploadFile = File(...)):
#     try:
#         content = await file.read()
#         image = Image.open(io.BytesIO(content)).convert("RGB")
#         image = transform(image).unsqueeze(0)

#         with torch.inference_mode():
#             outputs = model(image)
#             probs = F.softmax(outputs, dim=1)
#             confidence, predicted = torch.max(probs, 1)
#             class_idx = predicted.item()
#             confidence = confidence.item() * 100

#         result = class_labels[class_idx]

#         return {
#             "predicted_class": result,
#             "confidence": round(confidence, 2)  # persentase keyakinan
#         }

#     except Exception as e:
#         return {"error": str(e)}
