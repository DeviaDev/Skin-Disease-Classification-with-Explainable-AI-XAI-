@extends('layouts.detect')

@section('styles')
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="{{ asset('css/styles_detec.css') }}" rel="stylesheet">
@endsection

@section('content')

<div class="hero">
  <h1>Hasil Analisis</h1>
  <p>Deteksi penyakit kulit menggunakan AI</p>
</div>

<div class="container">

  <!-- Upload state -->
  <div id="uploadState" class="grid" style="grid-template-columns: 1fr;">
    <div class="panel" style="max-width:640px; margin:0 auto; width:100%;">
      <h2>📤 Upload Gambar Kulit</h2>
      <input type="file" id="fileInput" accept="image/*" class="hidden">
      <div class="upload-zone" id="uploadZone">
        <div style="font-size:38px;">📷</div>
        <p class="title">Drag &amp; Drop atau Klik</p>
        <p class="sub">Format: JPG, PNG • Max: 10MB</p>
      </div>

      <div id="previewWrap" class="hidden">
        <img id="previewImg" class="preview-img" alt="preview">
        <div class="file-meta">
          <span id="fileNameLabel"></span>
          <span id="fileSizeLabel"></span>
        </div>
        <button class="btn" id="analyzeBtn">⚡ ANALISIS DENGAN AI</button>
        <button class="btn secondary" id="resetBtnUpload">Reset</button>
      </div>
    </div>
  </div>

  <!-- Result state -->
  <div id="resultState" class="hidden">
    <div class="grid grid-3">
      <div class="panel">
        <h2>🖼️ Gambar Input</h2>
        <img id="resultImg" class="preview-img" alt="input">
        <div class="file-meta">
          <span id="resultFileName"></span>
          <span id="resultFileSize"></span>
        </div>
      </div>

      <div class="panel">
        <h2>🧠 Hasil Prediksi</h2>
        <div style="font-size:12px; color:var(--text-dim); text-transform:uppercase; letter-spacing:.04em;">Prediksi</div>
        <div class="prediction-name" id="predictionName">-</div>
        <div style="font-size:12px; color:var(--text-dim); text-transform:uppercase; letter-spacing:.04em; margin-top:14px;">Tingkat Keyakinan</div>
        <div class="confidence-value" id="confidenceValue">0%</div>
        <div class="confidence-bar-track"><div class="confidence-bar-fill" id="confidenceBarFill" style="width:0%;"></div></div>

        <div style="margin-top:20px; font-size:12px; color:var(--text-dim); text-transform:uppercase; letter-spacing:.04em;">Probabilitas Kelas Lain</div>
        <div id="probList" style="margin-top:10px;"></div>
      </div>

      <div class="panel">
        <h2>☀️ Visualisasi Grad-CAM</h2>
        <img id="gradcamImg" class="gradcam-img" alt="grad-cam">
        <div class="colorbar-legend">
          <span>Rendah</span>
          <div class="colorbar-strip"></div>
          <span>Tinggi</span>
        </div>
      </div>
    </div>

    <div class="grid" style="grid-template-columns: 2fr 1fr; margin-top:20px;">
      <div class="panel">
        <h2>📄 Deskripsi AI</h2>
        <p id="diseaseDescription" style="color:var(--text-dim); line-height:1.7;"></p>
        <div style="margin-top:14px;">
          <div style="font-size:12px; color:var(--text-dim); text-transform:uppercase; letter-spacing:.04em; margin-bottom:8px;">Gejala Umum</div>
          <div class="chip-list" id="symptomChips"></div>
        </div>
      </div>
      <div class="panel">
        <h2>🛡️ Pola yang Teridentifikasi</h2>
        <div id="causesChips" class="chip-list"></div>
        <div style="margin-top:16px; font-size:12px; color:var(--text-dim); text-transform:uppercase; letter-spacing:.04em;">Saran Perawatan</div>
        <div id="treatmentChips" class="chip-list"></div>
      </div>
    </div>

    <button class="btn secondary" id="resetBtnResult" style="max-width:280px; margin: 20px auto 0; display:block;">↩ Analisis Gambar Baru</button>

    <div class="disclaimer">
      ⚠️ <strong>Catatan Medis:</strong> Hasil ini merupakan prediksi AI dan tidak menggantikan diagnosis dokter spesialis kulit.
      Selalu konsultasikan kondisi kulit Anda dengan tenaga medis profesional.
    </div>
  </div>

  <!-- Info panel shown only before first prediction -->
  <div id="infoPanel" class="panel" style="margin-top:20px;">
    <h2>ℹ️ Cara Penggunaan &amp; Teknologi AI</h2>
    <div class="steps">
      <div class="step">
        <div class="step-num">1</div>
        <div><h4>Upload Gambar</h4><p>Pilih foto kulit yang jelas dan berkualitas baik dengan pencahayaan yang cukup.</p></div>
      </div>
      <div class="step">
        <div class="step-num">2</div>
        <div><h4>Analisis AI</h4><p>Model ResNet18 menganalisis pola visual dan menghasilkan peta perhatian Grad-CAM.</p></div>
      </div>
      <div class="step">
        <div class="step-num">3</div>
        <div><h4>Hasil &amp; Info</h4><p>Dapatkan prediksi, tingkat keyakinan, dan area kulit yang menjadi fokus model.</p></div>
      </div>
    </div>
  </div>

</div>

<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('dateBadge').textContent = new Date().toLocaleDateString('id-ID', {
  day: 'numeric', month: 'long', year: 'numeric'
}) + ' • ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

// === Mapping backend label -> frontend disease-info key (same as your React app) ===
const backendToFrontendMapping = {
  "Acne and Rosacea": "Acne and Rosacea Photos",
  "Actinic Keratosis, Basal Cell Carcinoma & other Malignant Lesions": "Actinic Keratosis Basal Cell Carcinoma and other Malignant Lesions",
  "Atopic Dermatitis": "Atopic Dermatitis Photos",
  "Bullous Disease": "Bullous Disease Photos",
  "Cellulitis, Impetigo & other Bacterial Infections": "Cellulitis Impetigo and other Bacterial Infections",
  "Eczema": "Eczema Photos",
  "Exanthems & Drug Eruptions": "Exanthems and Drug Eruptions",
  "Hair Loss, Alopecia & other Hair Diseases": "Hair Loss Photos Alopecia and other Hair Diseases",
  "Herpes, HPV & other STDs": "Herpes HPV and other STDs Photos",
  "Light Diseases & Disorders of Pigmentation": "Light Diseases and Disorders of Pigmentation",
  "Lupus & other Connective Tissue diseases": "Lupus and other Connective Tissue diseases",
  "Melanoma, Skin Cancer, Nevi & Moles": "Melanoma Skin Cancer Nevi and Moles",
  "Nail Fungus & other Nail Disease": "Nail Fungus and other Nail Disease",
  "Poison Ivy & other Contact Dermatitis": "Poison Ivy Photos and other Contact Dermatitis",
  "Psoriasis, Lichen Planus & related diseases": "Psoriasis pictures Lichen Planus and related diseases",
  "Scabies, Lyme Disease & other Infestations & Bites": "Scabies Lyme Disease and other Infestations and Bites",
  "Seborrheic Keratoses & other Benign Tumors": "Seborrheic Keratoses and other Benign Tumors",
  "Systemic Disease": "Systemic Disease",
  "Tinea, Ringworm, Candidiasis & other Fungal Infections": "Tinea Ringworm Candidiasis and other Fungal Infections",
  "Urticaria / Hives": "Urticaria Hives",
  "Vascular Tumors": "Vascular Tumors",
  "Vasculitis": "Vasculitis Photos",
  "Warts, Molluscum & other Viral Infections": "Warts Molluscum and other Viral Infections",
};

// === Same disease database as your React app (trimmed fields we actually render) ===
const diseaseInfo = {
  "Acne and Rosacea Photos": { name: "Jerawat dan Rosacea", description: "Kondisi kulit yang ditandai dengan komedo, papula, pustula, atau kemerahan pada wajah.", symptoms: ["Komedo","Papula","Pustula","Kemerahan"], causes: ["Produksi minyak berlebih","Bakteri P. acnes","Hormon","Stres"], treatment: ["Pembersih wajah lembut","Obat topikal","Konsultasi dokter kulit"] },
  "Actinic Keratosis Basal Cell Carcinoma and other Malignant Lesions": { name: "Keratosis Aktinik & Karsinoma Basal Sel", description: "Lesi kulit yang bersifat prakanker atau kanker, biasanya muncul akibat paparan sinar matahari berlebih.", symptoms: ["Bercak kasar","Kemerahan","Luka yang sulit sembuh"], causes: ["Paparan sinar UV","Usia lanjut","Kulit terang"], treatment: ["Cryotherapy","Biopsi dan eksisi","Terapi laser"] },
  "Atopic Dermatitis Photos": { name: "Dermatitis Atopik (Eksim)", description: "Kondisi kulit kronis yang menyebabkan kulit kering, gatal, dan ruam merah.", symptoms: ["Kulit kering","Ruam merah","Gatal"], causes: ["Genetik","Alergi","Stres"], treatment: ["Pelembab rutin","Kortikosteroid topikal","Hindari pemicu"] },
  "Bullous Disease Photos": { name: "Penyakit Bullous", description: "Gangguan kulit yang menyebabkan lepuhan besar berisi cairan di permukaan kulit.", symptoms: ["Lepuhan besar","Gatal","Nyeri kulit"], causes: ["Autoimun","Infeksi","Genetik"], treatment: ["Kortikosteroid","Imunosupresan","Perawatan luka"] },
  "Cellulitis Impetigo and other Bacterial Infections": { name: "Infeksi Bakteri Kulit", description: "Infeksi kulit yang disebabkan oleh bakteri, seperti selulitis atau impetigo.", symptoms: ["Kemerahan","Bengkak","Nyeri"], causes: ["Bakteri Staphylococcus","Bakteri Streptococcus","Cedera kulit"], treatment: ["Antibiotik oral/topikal","Perawatan luka","Konsultasi dokter"] },
  "Eczema Photos": { name: "Eksim", description: "Kondisi kulit kering dan meradang yang sering kambuh.", symptoms: ["Kulit kering","Gatal","Ruam merah"], causes: ["Genetik","Alergi","Iritasi"], treatment: ["Pelembab rutin","Kortikosteroid topikal","Hindari pemicu"] },
  "Exanthems and Drug Eruptions": { name: "Ruam & Reaksi Obat", description: "Ruam kulit yang muncul akibat infeksi atau reaksi terhadap obat tertentu.", symptoms: ["Bercak merah","Gatal","Bintik-bintik"], causes: ["Infeksi virus/bakteri","Reaksi obat"], treatment: ["Hentikan obat penyebab","Antihistamin","Perawatan simptomatik"] },
  "Hair Loss Photos Alopecia and other Hair Diseases": { name: "Rambut Rontok & Alopecia", description: "Kehilangan rambut sebagian atau total yang bisa bersifat sementara atau permanen.", symptoms: ["Rontok rambut","Botak sebagian","Penipisan rambut"], causes: ["Genetik","Autoimun","Stres"], treatment: ["Minoxidil","Kortikosteroid","Terapi PRP"] },
  "Herpes HPV and other STDs Photos": { name: "Infeksi Menular Seksual", description: "Infeksi kulit akibat virus, termasuk herpes dan HPV.", symptoms: ["Luka/lesi kulit","Gatal","Nyeri"], causes: ["Virus HSV","Virus HPV","Kontak seksual"], treatment: ["Antivirus","Perawatan simptomatik","Konsultasi dokter"] },
  "Light Diseases and Disorders of Pigmentation": { name: "Gangguan Pigmentasi", description: "Perubahan warna kulit akibat melanin yang berlebihan atau berkurang.", symptoms: ["Bercak putih/gelap","Kulit tidak merata"], causes: ["Genetik","Paparan sinar","Autoimun"], treatment: ["Krim pencerah","Terapi laser","Konsultasi dokter"] },
  "Lupus and other Connective Tissue diseases": { name: "Lupus & Penyakit Jaringan Ikat", description: "Penyakit autoimun yang dapat mempengaruhi kulit, sendi, dan organ lainnya.", symptoms: ["Ruam wajah","Nyeri sendi","Kelelahan"], causes: ["Autoimun","Genetik","Lingkungan"], treatment: ["Imunosupresan","Kortikosteroid","Perawatan simptomatik"] },
  "Melanoma Skin Cancer Nevi and Moles": { name: "Melanoma & Tahi Lalat", description: "Kanker kulit yang berkembang dari sel melanosit atau tahi lalat abnormal.", symptoms: ["Bercak hitam/gelap","Tidak simetris","Berubah bentuk"], causes: ["Paparan sinar UV","Genetik","Kulit terang"], treatment: ["Eksisi bedah","Kemoterapi/topikal","Pemantauan rutin"] },
  "Nail Fungus and other Nail Disease": { name: "Infeksi Kuku & Jamur", description: "Infeksi pada kuku yang dapat menyebabkan perubahan warna, ketebalan, dan bentuk kuku.", symptoms: ["Kuku tebal","Kuku rapuh","Perubahan warna"], causes: ["Jamur dermatofit","Luka kuku","Kelembaban tinggi"], treatment: ["Antijamur topikal/oral","Perawatan kuku","Konsultasi dokter"] },
  "Poison Ivy Photos and other Contact Dermatitis": { name: "Dermatitis Kontak", description: "Ruam kulit akibat kontak dengan alergen atau iritan seperti poison ivy.", symptoms: ["Ruam merah","Gatal","Lepuhan kecil"], causes: ["Tanaman alergen","Kosmetik","Logam"], treatment: ["Antihistamin","Salep antiinflamasi","Hindari pemicu"] },
  "Psoriasis pictures Lichen Planus and related diseases": { name: "Psoriasis & Lichen Planus", description: "Penyakit autoimun yang menyebabkan plak bersisik dan gatal pada kulit.", symptoms: ["Plak bersisik","Kulit menebal","Gatal"], causes: ["Genetik","Autoimun","Stres"], treatment: ["Terapi UV","Obat imunosupresan","Salep kortikosteroid"] },
  "Scabies Lyme Disease and other Infestations and Bites": { name: "Skabies, Lyme & Gigitan Serangga", description: "Infestasi kulit oleh parasit atau gigitan serangga yang menimbulkan gatal dan ruam.", symptoms: ["Gatal hebat","Bintik merah","Lepuhan kecil"], causes: ["Parasit","Kutu","Gigitan serangga"], treatment: ["Obat anti-parasit","Krim topikal","Konsultasi dokter"] },
  "Seborrheic Keratoses and other Benign Tumors": { name: "Seboroik Keratosis & Tumor Jinak", description: "Pertumbuhan kulit jinak, biasanya berwarna coklat atau hitam dan menonjol.", symptoms: ["Lesi coklat/hitam","Permukaan kasar"], causes: ["Penuaan","Genetik"], treatment: ["Cryotherapy","Eksisi jika mengganggu","Pemantauan rutin"] },
  "Systemic Disease": { name: "Penyakit Sistemik", description: "Penyakit yang mempengaruhi organ dan sistem tubuh, juga bisa memunculkan gejala kulit.", symptoms: ["Ruam","Nyeri sendi","Kelelahan"], causes: ["Autoimun","Infeksi","Genetik"], treatment: ["Terapi medis sesuai diagnosis","Konsultasi dokter"] },
  "Tinea Ringworm Candidiasis and other Fungal Infections": { name: "Infeksi Jamur Kulit", description: "Infeksi kulit yang disebabkan oleh jamur dermatofit atau kandida.", symptoms: ["Lepuhan merah","Gatal","Kuku berubah warna"], causes: ["Jamur dermatofit","Kelembaban tinggi","Kontak kulit"], treatment: ["Antijamur topikal/oral","Perawatan kebersihan kulit"] },
  "Urticaria Hives": { name: "Urtikaria (Biduran)", description: "Reaksi kulit berupa bentol merah gatal akibat alergi atau iritasi.", symptoms: ["Bentol merah","Gatal hebat","Hilang-timbul"], causes: ["Alergi makanan","Obat","Stres"], treatment: ["Antihistamin","Hindari pemicu","Kompres dingin"] },
  "Vascular Tumors": { name: "Tumor Vaskular", description: "Pertumbuhan abnormal pembuluh darah pada kulit.", symptoms: ["Noda merah","Benjolan kecil","Nyeri ringan"], causes: ["Genetik","Pertumbuhan abnormal pembuluh darah"], treatment: ["Eksisi jika perlu","Pemantauan rutin"] },
  "Vasculitis Photos": { name: "Vaskulitis", description: "Peradangan pembuluh darah yang bisa menimbulkan bercak dan luka pada kulit.", symptoms: ["Bercak merah/ungu","Nyeri","Luka kecil"], causes: ["Autoimun","Infeksi","Obat"], treatment: ["Kortikosteroid","Imunosupresan","Pemantauan dokter"] },
  "Warts Molluscum and other Viral Infections": { name: "Kutil & Infeksi Virus", description: "Pertumbuhan kulit akibat virus seperti HPV atau molluscum contagiosum.", symptoms: ["Benjolan kecil","Kutil menonjol","Tidak gatal/nyeri"], causes: ["Virus HPV","Molluscum contagiosum","Kontak kulit"], treatment: ["Eksisi","Krioterapi","Perawatan simptomatik"] },
};

// === DOM refs ===
const fileInput = document.getElementById('fileInput');
const uploadZone = document.getElementById('uploadZone');
const previewWrap = document.getElementById('previewWrap');
const previewImg = document.getElementById('previewImg');
const fileNameLabel = document.getElementById('fileNameLabel');
const fileSizeLabel = document.getElementById('fileSizeLabel');
const analyzeBtn = document.getElementById('analyzeBtn');
const uploadState = document.getElementById('uploadState');
const resultState = document.getElementById('resultState');
const infoPanel = document.getElementById('infoPanel');

let selectedFile = null;

uploadZone.addEventListener('click', () => fileInput.click());
['dragover','drop'].forEach(evt => uploadZone.addEventListener(evt, e => e.preventDefault()));
uploadZone.addEventListener('drop', e => {
  if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
});
fileInput.addEventListener('change', e => {
  if (e.target.files.length) handleFile(e.target.files[0]);
});

function handleFile(file) {
  selectedFile = file;
  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    fileNameLabel.textContent = file.name;
    fileSizeLabel.textContent = (file.size / (1024*1024)).toFixed(2) + ' MB';
    previewWrap.classList.remove('hidden');
  };
  reader.readAsDataURL(file);
}

function resetAll() {
  selectedFile = null;
  fileInput.value = '';
  previewWrap.classList.add('hidden');
  uploadState.classList.remove('hidden');
  resultState.classList.add('hidden');
  infoPanel.classList.remove('hidden');
}
document.getElementById('resetBtnUpload').addEventListener('click', resetAll);
document.getElementById('resetBtnResult').addEventListener('click', resetAll);

analyzeBtn.addEventListener('click', async () => {
  if (!selectedFile) return;
  analyzeBtn.disabled = true;
  analyzeBtn.innerHTML = '<span class="spinner"></span> Menganalisis...';

  const formData = new FormData();
  formData.append('image', selectedFile);

  try {
    const res = await fetch("{{ route('skin-detector.predict') }}", {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrfToken },
      body: formData,
    });
    const data = await res.json();

    if (!res.ok || data.error) {
      alert('Gagal melakukan prediksi: ' + (data.error || 'Unknown error'));
      analyzeBtn.disabled = false;
      analyzeBtn.innerHTML = '⚡ ANALISIS DENGAN AI';
      return;
    }

    renderResult(data);
  } catch (err) {
    alert('Gagal terhubung ke server: ' + err.message);
    analyzeBtn.disabled = false;
    analyzeBtn.innerHTML = '⚡ ANALISIS DENGAN AI';
  }
});

function renderResult(data) {
  const mappedKey = backendToFrontendMapping[data.predicted_class] || data.predicted_class;
  const info = diseaseInfo[mappedKey] || { name: data.predicted_class, description: '', symptoms: [], causes: [], treatment: [] };

  document.getElementById('resultImg').src = previewImg.src;
  document.getElementById('resultFileName').textContent = fileNameLabel.textContent;
  document.getElementById('resultFileSize').textContent = fileSizeLabel.textContent;

  document.getElementById('predictionName').textContent = info.name;
  document.getElementById('confidenceValue').textContent = data.confidence.toFixed(1) + '%';
  document.getElementById('confidenceBarFill').style.width = data.confidence + '%';

  const probList = document.getElementById('probList');
  probList.innerHTML = '';
  (data.probabilities || []).slice(1).forEach(p => {
    const label = (diseaseInfo[backendToFrontendMapping[p.label]] || {}).name || p.label;
    const row = document.createElement('div');
    row.className = 'prob-row';
    row.innerHTML = `
      <div class="prob-label">${label}</div>
      <div class="prob-track"><div class="prob-fill" style="width:${p.confidence}%;"></div></div>
      <div class="prob-value">${p.confidence.toFixed(1)}%</div>
    `;
    probList.appendChild(row);
  });

  document.getElementById('gradcamImg').src = 'data:image/png;base64,' + data.gradcam_base64;

  document.getElementById('diseaseDescription').textContent = info.description;

  const symptomChips = document.getElementById('symptomChips');
  symptomChips.innerHTML = '';
  info.symptoms.forEach(s => {
    const c = document.createElement('div'); c.className = 'chip'; c.textContent = s;
    symptomChips.appendChild(c);
  });

  const causesChips = document.getElementById('causesChips');
  causesChips.innerHTML = '';
  info.causes.forEach(s => {
    const c = document.createElement('div'); c.className = 'chip'; c.textContent = s;
    causesChips.appendChild(c);
  });

  const treatmentChips = document.getElementById('treatmentChips');
  treatmentChips.innerHTML = '';
  info.treatment.forEach(s => {
    const c = document.createElement('div'); c.className = 'chip'; c.textContent = s;
    treatmentChips.appendChild(c);
  });

  uploadState.classList.add('hidden');
  infoPanel.classList.add('hidden');
  resultState.classList.remove('hidden');

  analyzeBtn.disabled = false;
  analyzeBtn.innerHTML = '⚡ ANALISIS DENGAN AI';
}
</script>
@endsection