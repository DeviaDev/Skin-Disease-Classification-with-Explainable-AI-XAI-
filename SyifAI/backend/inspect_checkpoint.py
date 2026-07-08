"""
inspect_checkpoint.py

Jalankan ini dulu sebelum edit predict_gradcam.py:

    python3 inspect_checkpoint.py python/EXP01_model_fold_4.pth

Ini akan print semua key state_dict yang berhubungan dengan 'fc' supaya kita
tahu persis struktur classifier head yang dipakai waktu training, tanpa
harus nebak.
"""

import sys
import torch

path = sys.argv[1] if len(sys.argv) > 1 else "python/EXP01_model_fold_4.pth"

ckpt = torch.load(path, map_location="cpu")

print("Tipe checkpoint:", type(ckpt))

if isinstance(ckpt, dict):
    # Handle wrapper dicts like {"model_state_dict": ..., "val_loss": ..., "f1": ..., "fold": ...}
    state_dict = None
    if "state_dict" in ckpt:
        state_dict = ckpt["state_dict"]
        print("Checkpoint dibungkus dengan key 'state_dict'. Keys level atas:", list(ckpt.keys()))
    elif "model_state_dict" in ckpt:
        state_dict = ckpt["model_state_dict"]
        print("Checkpoint dibungkus dengan key 'model_state_dict'. Keys level atas:", list(ckpt.keys()))
        extra = {k: v for k, v in ckpt.items() if k != "model_state_dict"}
        print("Metadata tambahan:", extra)
    else:
        state_dict = ckpt
        print("Ini kemungkinan besar state_dict langsung.")

    print("\n=== Semua key yang mengandung 'fc' ===")
    for k, v in state_dict.items():
        if "fc" in k:
            print(f"{k:40s} shape={tuple(v.shape)}")

    print("\n=== Jumlah kelas output (dari layer fc terakhir) ===")
    fc_keys = [k for k in state_dict.keys() if "fc" in k and "weight" in k]
    if fc_keys:
        last_fc = fc_keys[-1]
        print(f"{last_fc} -> shape {tuple(state_dict[last_fc].shape)} (baris pertama biasanya = jumlah kelas)")

    print("\n=== Semua top-level nama module (prefix sebelum titik pertama) ===")
    top_level = sorted(set(k.split(".")[0] for k in state_dict.keys()))
    print(top_level)
else:
    print("Ini full model object (bukan state_dict), classifier head:")
    print(ckpt.fc if hasattr(ckpt, "fc") else "(tidak ada atribut .fc)")
