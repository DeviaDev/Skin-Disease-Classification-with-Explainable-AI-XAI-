@extends('layouts.app')

@push('styles')
  <link href="{{ asset('css/styles_detec.css') }}" rel="stylesheet">
  <style>
    /* ============================================================
       DISEASE INFO SECTION — theme selaras dengan landing page
       (navy gradient + biru aksen). Semua warna pakai var() dengan
       fallback, jadi aman kalau styles_detec.css sudah punya variabel
       serupa (--text-dim dll sudah dipakai di file lama).
       ============================================================ */
    :root {
      --di-bg-panel: var(--panel-bg, #101a3d);
      --di-bg-panel-2: var(--panel-bg-alt, #0c1530);
      --di-border: var(--border-color, rgba(148, 163, 233, 0.16));
      --di-accent: var(--accent, #3b6fe8);
      --di-accent-2: var(--accent-2, #5a8dff);
      --di-text: var(--text, #eef1fb);
      --di-text-dim: var(--text-dim, #93a0c4);
      --di-good: #22c39a;
      --di-warn: #f0b23c;
      --di-danger: #ef5a6f;
      --bs-gradient: linear-gradient(180deg, rgba(0, 4, 61, 0.925), rgba(0, 135, 212, 0.932));
    }

    .disease-info-section {
      margin-top: 20px;
      background: linear-gradient(160deg, var(--di-bg-panel) 0%, var(--di-bg-panel-2) 100%);
      border: 1px solid var(--di-border);
      border-radius: 18px;
      padding: 28px;
    }

    .disease-info-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      flex-wrap: wrap;
      gap: 12px;
      margin-bottom: 22px;
    }

    .disease-info-header h2 {
      margin: 0;
      font-size: 20px;
      color: var(--di-text);
    }

    .disease-info-header .disease-pill {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: rgba(59, 111, 232, 0.14);
      border: 1px solid rgba(59, 111, 232, 0.35);
      color: var(--di-accent-2);
      font-size: 12px;
      font-weight: 600;
      letter-spacing: .03em;
      padding: 6px 14px;
      border-radius: 999px;
      text-transform: uppercase;
    }

    .disease-info-summary {
      color: var(--di-text-dim);
      line-height: 1.75;
      font-size: 14.5px;
      margin-bottom: 22px;
      padding-bottom: 20px;
      border-bottom: 1px solid var(--di-border);
    }

    /* ---------- Accordion (dropdown buka/tutup per baris) ---------- */
    .accordion-list {
      display: flex;
      flex-direction: column;
      gap: 10px;
    }

    .acc-item {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid var(--di-border);
      border-left: 3px solid var(--di-accent-2);
      border-radius: 12px;
      padding: 16px 18px 18px;
    }

    .acc-header {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 12px;
    }

    .acc-header-left {
      display: flex;
      align-items: center;
      gap: 12px;
      min-width: 0;
    }

    .acc-step-num {
      width: 22px;
      height: 22px;
      border-radius: 50%;
      background: rgba(90, 141, 255, 0.15);
      color: var(--di-accent-2);
      font-size: 11px;
      font-weight: 700;
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
    }

    .acc-body {
      padding: 0;
    }


    .info-card-icon {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 16px;
      flex-shrink: 0;
    }
    .icon-early     { background: rgba(240, 178, 60, 0.16); }
    .icon-progress  { background: rgba(239, 90, 111, 0.16); }
    .icon-cause     { background: rgba(90, 141, 255, 0.16); }
    .icon-prevent   { background: rgba(34, 195, 154, 0.16); }
    .icon-treatment { background: rgba(163, 108, 255, 0.16); }


    .title {
      font-size: 14px;
      font-weight: 600;
      color: var(--di-text);
    }
    .info-card-title {
      font-size: 13.5px;
      font-weight: 700;
      color: var(--di-text);
      letter-spacing: .01em;
    }
    .info-card-title small {
      display: block;
      font-weight: 400;
      color: var(--di-text-dim);
      font-size: 11px;
      margin-top: 1px;
    }

    .info-list {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
      flex-direction: column;
      gap: 8px;
    }
    .info-list li {
      display: flex;
      align-items: flex-start;
      gap: 8px;
      font-size: 13px;
      line-height: 1.5;
      color: var(--di-text-dim);
    }
    .info-list li::before {
      content: "";
      width: 6px;
      height: 6px;
      border-radius: 50%;
      background: var(--di-accent-2);
      margin-top: 6px;
      flex-shrink: 0;
    }

    .source-panel {
      margin-top: 22px;
      padding-top: 18px;
      border-top: 1px solid var(--di-border);
      display: flex;
      gap: 14px;
      align-items: flex-start;
    }
    .source-panel .source-icon {
      width: 36px;
      height: 36px;
      border-radius: 10px;
      background: rgba(59, 111, 232, 0.14);
      display: flex;
      align-items: center;
      justify-content: center;
      flex-shrink: 0;
      font-size: 16px;
    }
    .source-panel h4 {
      margin: 0 0 6px;
      font-size: 13px;
      color: var(--di-text);
    }
    .source-panel p {
      margin: 0 0 8px;
      font-size: 12.5px;
      color: var(--di-text-dim);
      line-height: 1.6;
    }
    .source-badges {
      display: flex;
      flex-wrap: wrap;
      gap: 8px;
    }
    .source-badge {
      font-size: 11px;
      color: var(--di-accent-2);
      background: rgba(59, 111, 232, 0.1);
      border: 1px solid rgba(59, 111, 232, 0.3);
      padding: 4px 10px;
      border-radius: 999px;
      text-decoration: none;
    }
    .source-badge:hover { background: rgba(59, 111, 232, 0.2); }

    .severity-note {
      display: flex;
      align-items: center;
      gap: 8px;
      font-size: 12px;
      margin-top: 14px;
      color: var(--di-warn);
    }
  </style>
@endpush

@section('content')
<div class="detection-page">
  <div class="date-badge" id="dateBadge"></div>

  <div class="hero">
    <h1>Hasil Analisis</h1>
    <p>Deteksi penyakit kulit menggunakan AI</p>
  </div>

  <div class="container">

    <!-- Upload state -->
    <div id="uploadState" class="grid" style="grid-template-columns: 1fr;">
      <div id="uploadAlert" class="alert alert-danger mt-3 hidden" role="alert"></div>
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

      <!-- ===================== INFORMASI PENYAKIT (BARU) ===================== -->
      <div class="disease-info-section" id="diseaseInfoSection">
        <div class="disease-info-header">
          <h2>📋 Informasi Lengkap Penyakit</h2>
          <span class="disease-pill" id="diseaseCategoryPill">Dermatologi</span>
        </div>

        <p class="disease-info-summary" id="diseaseSummary"></p>

        <div class="accordion-list" id="diseaseAccordion">
          <div class="acc-item" data-acc="early">
            <div class="acc-header">
              <span class="acc-header-left">
                <span class="acc-step-num">1</span>
                <span class="info-card-icon icon-early">🟡</span>
                <span class="info-card-title">Gejala Awal<small>Tanda pertama yang muncul</small></span>
              </span>
            </div>
            <div class="acc-body">
              <ul class="info-list" id="earlySymptomsList"></ul>
            </div>
          </div>

          <div class="acc-item" data-acc="progress">
            <div class="acc-header">
              <span class="acc-header-left">
                <span class="acc-step-num">2</span>
                <span class="info-card-icon icon-progress">🔴</span>
                <span class="info-card-title">Gejala Saat Berkembang<small>Jika dibiarkan / memburuk</small></span>
              </span>
            </div>
            <div class="acc-body">
              <ul class="info-list" id="progressSymptomsList"></ul>
            </div>
          </div>

          <div class="acc-item" data-acc="causes">
            <div class="acc-header">
              <span class="acc-header-left">
                <span class="acc-step-num">3</span>
                <span class="info-card-icon icon-cause">🔵</span>
                <span class="info-card-title">Penyebab<small>Faktor pemicu utama</small></span>
              </span>
            </div>
            <div class="acc-body">
              <ul class="info-list" id="causesList"></ul>
            </div>
          </div>

          <div class="acc-item" data-acc="prevention">
            <div class="acc-header">
              <span class="acc-header-left">
                <span class="acc-step-num">4</span>
                <span class="info-card-icon icon-prevent">🟢</span>
                <span class="info-card-title">Pencegahan<small>Langkah preventif</small></span>
              </span>
            </div>
            <div class="acc-body">
              <ul class="info-list" id="preventionList"></ul>
            </div>
          </div>

          <div class="acc-item" data-acc="treatment">
            <div class="acc-header">
              <span class="acc-header-left">
                <span class="acc-step-num">5</span>
                <span class="info-card-icon icon-treatment">🟣</span>
                <span class="info-card-title">Pengobatan<small>Penanganan umum</small></span>
              </span>
            </div>
            <div class="acc-body">
              <ul class="info-list" id="treatmentList"></ul>
            </div>
          </div>
        </div>

        <div class="severity-note">
          ⏱️ Semakin cepat gejala dikenali dan diperiksakan, semakin besar peluang penanganan berhasil.
        </div>

        <div class="source-panel">
          <div class="source-icon">🔖</div>
          <div>
            <h4>Sumber Referensi</h4>
            <p>
              Informasi disusun berdasarkan referensi dari sumber medis dan kesehatan resmi berikut.
              Data prediksi AI ini bersifat skrining awal, bukan diagnosis final.
            </p>
            <div class="source-badges">
              <a class="source-badge" href="https://dermnetnz.org" target="_blank" rel="noopener">DermNet NZ</a>
              <a class="source-badge" href="https://www.perdoski.or.id" target="_blank" rel="noopener">PERDOSKI</a>
              <a class="source-badge" href="https://www.kemkes.go.id" target="_blank" rel="noopener">Kementerian Kesehatan RI</a>
              <a class="source-badge" href="https://www.who.int" target="_blank" rel="noopener">WHO</a>
              <a class="source-badge" href="https://www.niams.nih.gov" target="_blank" rel="noopener">NIH / NIAMS</a>
            </div>
          </div>
        </div>
      </div>
      <!-- =================== /INFORMASI PENYAKIT (BARU) ===================== -->

      <button class="btn bg-gradient" id="resetBtnResult" style="max-width:280px; margin: 20px auto 0; display:block;">↩ Analisis Gambar Baru</button>

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
          <div><h4 class="title">Upload Gambar</h4><p>Pilih foto kulit yang jelas dan berkualitas baik dengan pencahayaan yang cukup.</p></div>
        </div>
        <div class="step">
          <div class="step-num">2</div>
          <div><h4 class="title">Analisis AI</h4><p>Model ResNet18 menganalisis pola visual dan menghasilkan peta perhatian Grad-CAM.</p></div>
        </div>
        <div class="step">
          <div class="step-num">3</div>
          <div><h4 class="title">Hasil &amp; Info</h4><p>Dapatkan prediksi, tingkat keyakinan, area kulit yang menjadi fokus model, serta informasi lengkap penyakit.</p></div>
        </div>
      </div>
    </div>

  </div>
</div>

@endsection


@push('script')
<script>
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

document.getElementById('dateBadge').textContent = new Date().toLocaleDateString('id-ID', {
  day: 'numeric', month: 'long', year: 'numeric'
}) + ' • ' + new Date().toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' }) + ' WIB';

// === Mapping backend label -> frontend disease-info key (tidak diubah, sama seperti sebelumnya) ===
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

// === Disease knowledge base — DIPERLUAS ===
// category      : label singkat kategori medis (ditampilkan sebagai pill)
// summary        : ringkasan umum penyakit
// earlySymptoms  : gejala awal / tanda pertama
// progressSymptoms: gejala saat kondisi berkembang / memburuk jika dibiarkan
// causes         : penyebab / faktor risiko
// prevention     : langkah pencegahan
// treatment      : penanganan / pengobatan umum
const diseaseInfo = {
  "Acne and Rosacea Photos": {
    name: "Jerawat dan Rosacea", category: "Peradangan Kulit",
    summary: "Kondisi peradangan kulit wajah yang ditandai komedo, papula, pustula, atau kemerahan menetap, sering dipicu produksi minyak berlebih dan peradangan folikel.",
    earlySymptoms: ["Komedo hitam/putih di area T-zone", "Kulit tampak lebih berminyak", "Kemerahan ringan yang hilang-timbul"],
    progressSymptoms: ["Papula dan pustula meradang", "Nodul/kista yang nyeri", "Kemerahan menetap disertai pembuluh darah tampak (rosacea)"],
    causes: ["Produksi sebum berlebih", "Bakteri Cutibacterium acnes", "Fluktuasi hormon", "Stres dan pola makan tertentu"],
    prevention: ["Cuci wajah 2x sehari dengan pembersih lembut", "Hindari memencet jerawat", "Gunakan produk non-komedogenik", "Kelola stres dan pola tidur"],
    treatment: ["Retinoid atau benzoyl peroxide topikal", "Antibiotik topikal/oral pada kasus sedang-berat", "Konsultasi dokter kulit untuk rosacea"]
  },
  "Actinic Keratosis Basal Cell Carcinoma and other Malignant Lesions": {
    name: "Keratosis Aktinik & Karsinoma Sel Basal", category: "Lesi Prakanker/Kanker Kulit",
    summary: "Kelompok lesi kulit akibat kerusakan sel akibat paparan sinar UV kronis, berpotensi berkembang menjadi kanker kulit bila tidak ditangani.",
    earlySymptoms: ["Bercak kasar seperti amplas", "Warna kemerahan atau kecoklatan pada area yang sering terpapar matahari", "Tekstur kulit terasa berbeda saat diraba"],
    progressSymptoms: ["Luka yang tidak kunjung sembuh lebih dari 4 minggu", "Benjolan mengkilap dengan pembuluh darah terlihat", "Perdarahan atau kerak berulang pada lesi"],
    causes: ["Paparan sinar UV kumulatif jangka panjang", "Usia lanjut", "Kulit terang / riwayat terbakar sinar matahari", "Riwayat imunosupresi"],
    prevention: ["Gunakan tabir surya SPF 30+ setiap hari", "Kenakan pakaian pelindung dan topi saat di luar ruangan", "Hindari paparan matahari jam 10.00–16.00", "Periksa kulit rutin secara mandiri"],
    treatment: ["Cryotherapy untuk lesi awal", "Eksisi bedah atau kuretase", "Terapi topikal (5-fluorouracil/imiquimod)", "Rujukan ke dokter spesialis kulit/onkologi bila dicurigai maligna"]
  },
  "Atopic Dermatitis Photos": {
    name: "Dermatitis Atopik (Eksim)", category: "Peradangan Kulit Kronis",
    summary: "Penyakit kulit inflamasi kronis-residif yang membuat kulit kering, gatal, dan meradang, umum terjadi pada individu dengan riwayat alergi/atopi.",
    earlySymptoms: ["Kulit terasa kering dan kasar", "Gatal ringan terutama malam hari", "Bercak kemerahan samar di lipatan siku/lutut"],
    progressSymptoms: ["Ruam merah meradang yang meluas", "Kulit menebal (likenifikasi) akibat garukan berulang", "Lecet/infeksi sekunder akibat garukan"],
    causes: ["Faktor genetik (riwayat keluarga atopi)", "Disfungsi sawar kulit", "Alergen lingkungan (debu, tungau)", "Stres dan perubahan cuaca"],
    prevention: ["Gunakan pelembab (emolien) segera setelah mandi", "Hindari sabun/detergen keras", "Kenakan pakaian berbahan katun", "Identifikasi dan hindari pemicu alergi"],
    treatment: ["Emolien rutin sebagai terapi dasar", "Kortikosteroid topikal saat kambuh", "Antihistamin untuk mengurangi gatal", "Konsultasi dokter kulit untuk kasus persisten"]
  },
  "Bullous Disease Photos": {
    name: "Penyakit Bulosa", category: "Kelainan Autoimun Kulit",
    summary: "Kelompok gangguan kulit yang ditandai lepuhan (bula) besar berisi cairan, sering berkaitan dengan proses autoimun yang menyerang lapisan kulit.",
    earlySymptoms: ["Kulit terasa gatal atau perih sebelum lepuh muncul", "Bercak kemerahan ringan", "Kulit tampak lebih sensitif"],
    progressSymptoms: ["Lepuhan besar berisi cairan jernih", "Luka terbuka setelah lepuh pecah", "Nyeri kulit yang signifikan, dapat meluas ke mukosa"],
    causes: ["Reaksi autoimun terhadap protein kulit", "Efek samping obat tertentu", "Infeksi pemicu", "Faktor genetik pada beberapa jenis"],
    prevention: ["Hindari gesekan/trauma kulit berlebihan", "Kontrol rutin bila punya riwayat autoimun", "Laporkan reaksi obat baru ke dokter segera"],
    treatment: ["Kortikosteroid sistemik dosis terkontrol", "Obat imunosupresan", "Perawatan luka steril untuk mencegah infeksi sekunder", "Rujukan ke dokter spesialis kulit"]
  },
  "Cellulitis Impetigo and other Bacterial Infections": {
    name: "Infeksi Bakteri Kulit (Selulitis, Impetigo)", category: "Infeksi Bakteri",
    summary: "Infeksi kulit akibat bakteri (umumnya Staphylococcus atau Streptococcus) yang dapat menyebar dari lapisan permukaan hingga jaringan lebih dalam.",
    earlySymptoms: ["Kemerahan lokal dan hangat saat diraba", "Nyeri ringan di area terinfeksi", "Bintik/lepuh kecil berisi nanah (impetigo)"],
    progressSymptoms: ["Bengkak yang meluas cepat", "Demam dan tubuh terasa lemas", "Kulit mengeras, kerak berwarna madu, atau abses"],
    causes: ["Luka terbuka yang terinfeksi bakteri", "Kontak langsung dengan sumber infeksi", "Sistem imun menurun", "Kebersihan kulit yang kurang terjaga"],
    prevention: ["Bersihkan dan tutup luka segera", "Jaga kebersihan tangan dan kulit", "Hindari menggaruk luka/infeksi kulit orang lain", "Segera obati luka kecil sebelum meluas"],
    treatment: ["Antibiotik topikal untuk kasus ringan", "Antibiotik oral untuk infeksi lebih luas", "Rawat inap dan antibiotik IV untuk kasus berat", "Perawatan luka rutin"]
  },
  "Eczema Photos": {
    name: "Eksim", category: "Peradangan Kulit Kronis",
    summary: "Istilah umum untuk peradangan kulit yang menyebabkan kulit kering, gatal, dan mudah kambuh, tumpang tindih dengan dermatitis atopik.",
    earlySymptoms: ["Kulit kering bersisik halus", "Gatal ringan-sedang", "Kemerahan samar pada area tertentu"],
    progressSymptoms: ["Ruam merah meradang meluas", "Kulit pecah-pecah atau menebal", "Lecet akibat garukan berlebihan"],
    causes: ["Genetik/riwayat alergi keluarga", "Iritan (sabun, deterjen, bahan kimia)", "Cuaca kering atau dingin", "Stres"],
    prevention: ["Pelembab rutin minimal 2x sehari", "Gunakan air hangat (bukan panas) saat mandi", "Hindari bahan iritan/wangi berlebih", "Kelola stres dengan baik"],
    treatment: ["Pelembab sebagai terapi dasar", "Kortikosteroid topikal ringan-sedang", "Antihistamin bila gatal mengganggu tidur", "Hindari pemicu yang teridentifikasi"]
  },
  "Exanthems and Drug Eruptions": {
    name: "Ruam Eksantema & Reaksi Obat", category: "Reaksi Kulit Akut",
    summary: "Ruam kulit meluas yang muncul akibat infeksi virus/bakteri sistemik atau reaksi hipersensitivitas terhadap obat tertentu.",
    earlySymptoms: ["Bintik-bintik merah kecil yang muncul mendadak", "Gatal ringan", "Kadang disertai demam ringan"],
    progressSymptoms: ["Ruam meluas ke seluruh tubuh", "Bercak menyatu membentuk area luas", "Gejala sistemik: demam tinggi, bengkak wajah/bibir (tanda bahaya)"],
    causes: ["Infeksi virus (campak, rubella, dll)", "Reaksi hipersensitivitas obat (antibiotik, NSAID, dll)", "Reaksi imun terhadap patogen"],
    prevention: ["Informasikan riwayat alergi obat ke tenaga medis", "Hindari obat yang pernah memicu reaksi", "Segera periksa bila ruam muncul setelah minum obat baru"],
    treatment: ["Hentikan obat penyebab (dengan pengawasan dokter)", "Antihistamin untuk gejala ringan", "Kortikosteroid pada kasus sedang-berat", "Penanganan gawat darurat bila disertai sesak napas/bengkak wajah"]
  },
  "Hair Loss Photos Alopecia and other Hair Diseases": {
    name: "Rambut Rontok & Alopecia", category: "Gangguan Rambut",
    summary: "Kondisi kerontokan atau kebotakan rambut, bisa bersifat sementara maupun permanen, dengan berbagai penyebab mulai dari genetik hingga autoimun.",
    earlySymptoms: ["Rambut rontok lebih banyak dari biasanya saat menyisir", "Rambut tampak lebih tipis", "Garis rambut mulai mundur"],
    progressSymptoms: ["Area botak berbentuk bulat/oval jelas", "Kebotakan meluas dan permanen", "Kulit kepala tampak mengkilap tanpa folikel aktif"],
    causes: ["Faktor genetik (androgenetic alopecia)", "Gangguan autoimun (alopecia areata)", "Stres berat atau perubahan hormon", "Kekurangan nutrisi tertentu"],
    prevention: ["Hindari gaya rambut yang menarik kuat (traction alopecia)", "Kelola stres dan pola makan bergizi seimbang", "Perawatan kulit kepala yang lembut"],
    treatment: ["Minoxidil topikal", "Kortikosteroid untuk alopecia areata", "Terapi PRP (Platelet-Rich Plasma)", "Konsultasi dokter kulit/trikologi untuk kasus lanjut"]
  },
  "Herpes HPV and other STDs Photos": {
    name: "Infeksi Menular Seksual (Herpes, HPV)", category: "Infeksi Virus",
    summary: "Kelompok infeksi kulit dan mukosa yang ditularkan melalui kontak langsung/seksual, disebabkan oleh virus seperti HSV dan HPV.",
    earlySymptoms: ["Gatal atau sensasi terbakar di area genital/mulut", "Kemerahan ringan sebelum lesi muncul", "Rasa tidak nyaman ringan"],
    progressSymptoms: ["Lepuhan berkelompok yang nyeri (herpes)", "Kutil kelamin yang membesar (HPV)", "Luka terbuka disertai nyeri saat berkemih"],
    causes: ["Virus Herpes Simplex (HSV)", "Human Papillomavirus (HPV)", "Kontak seksual tanpa pelindung", "Kontak langsung dengan lesi aktif"],
    prevention: ["Gunakan pengaman saat berhubungan seksual", "Hindari kontak langsung dengan lesi aktif", "Vaksinasi HPV sesuai anjuran medis", "Skrining rutin bila berisiko tinggi"],
    treatment: ["Antivirus (asiklovir/valasiklovir) untuk herpes", "Krioterapi/topikal untuk kutil HPV", "Konsultasi dokter spesialis kulit-kelamin", "Edukasi pasangan untuk pencegahan penularan"]
  },
  "Light Diseases and Disorders of Pigmentation": {
    name: "Gangguan Pigmentasi Kulit", category: "Kelainan Pigmentasi",
    summary: "Perubahan warna kulit akibat produksi melanin yang tidak merata, dapat berupa bercak lebih terang (hipopigmentasi) atau lebih gelap (hiperpigmentasi).",
    earlySymptoms: ["Bercak kulit sedikit lebih terang/gelap dari sekitarnya", "Perubahan warna ringan yang belum simetris"],
    progressSymptoms: ["Bercak putih/gelap yang meluas dan jelas batasnya", "Perubahan warna kulit yang menetap dan mengganggu penampilan"],
    causes: ["Faktor genetik", "Paparan sinar matahari berlebih", "Proses autoimun (misalnya vitiligo)", "Peradangan kulit sebelumnya (pasca-inflamasi)"],
    prevention: ["Gunakan tabir surya untuk mencegah bercak makin gelap", "Hindari trauma/iritasi kulit berulang", "Periksa dini bila bercak meluas cepat"],
    treatment: ["Krim pencerah/perata warna kulit topikal", "Terapi laser atau fototerapi", "Kortikosteroid topikal untuk kasus autoimun", "Konsultasi dokter kulit untuk evaluasi penyebab"]
  },
  "Lupus and other Connective Tissue diseases": {
    name: "Lupus & Penyakit Jaringan Ikat", category: "Penyakit Autoimun Sistemik",
    summary: "Penyakit autoimun yang dapat menyerang kulit, sendi, dan organ dalam, dengan manifestasi kulit khas seperti ruam kupu-kupu di wajah.",
    earlySymptoms: ["Kelelahan berkepanjangan", "Ruam ringan di pipi dan hidung", "Nyeri sendi ringan yang hilang timbul"],
    progressSymptoms: ["Ruam kupu-kupu (malar rash) jelas di wajah", "Nyeri dan bengkak sendi meluas", "Sensitif terhadap sinar matahari, demam, kerontokan rambut"],
    causes: ["Reaksi autoimun tubuh menyerang jaringan sendiri", "Faktor genetik", "Pemicu lingkungan (sinar UV, infeksi, obat tertentu)"],
    prevention: ["Hindari paparan sinar matahari berlebih", "Kontrol rutin ke dokter spesialis penyakit dalam/reumatologi", "Kelola stres dan istirahat cukup"],
    treatment: ["Obat imunosupresan sesuai resep dokter", "Kortikosteroid untuk mengendalikan flare", "Obat anti-inflamasi", "Pemantauan jangka panjang oleh dokter spesialis"]
  },
  "Melanoma Skin Cancer Nevi and Moles": {
    name: "Melanoma & Kelainan Tahi Lalat", category: "Kanker Kulit",
    summary: "Melanoma adalah jenis kanker kulit paling serius yang berkembang dari sel penghasil pigmen (melanosit), sering diawali perubahan pada tahi lalat.",
    earlySymptoms: ["Tahi lalat baru atau berubah bentuk (asimetris)", "Tepi tidak rata", "Warna tidak seragam dalam satu lesi"],
    progressSymptoms: ["Diameter lebih dari 6 mm dan terus membesar", "Perdarahan atau gatal pada tahi lalat", "Lesi menonjol dengan tekstur tidak rata"],
    causes: ["Paparan sinar UV berlebih/riwayat terbakar matahari", "Faktor genetik dan riwayat keluarga", "Kulit terang dengan banyak tahi lalat", "Sistem imun menurun"],
    prevention: ["Periksa kulit mandiri rutin (metode ABCDE)", "Gunakan tabir surya dan hindari paparan UV berlebih", "Pemeriksaan dermatoskopi berkala bila berisiko tinggi"],
    treatment: ["Eksisi bedah sedini mungkin", "Biopsi untuk konfirmasi diagnosis", "Kemoterapi/imunoterapi pada stadium lanjut", "Rujukan segera ke dokter spesialis onkologi kulit"]
  },
  "Nail Fungus and other Nail Disease": {
    name: "Infeksi Jamur Kuku", category: "Infeksi Jamur",
    summary: "Infeksi pada lempeng kuku yang disebabkan oleh jamur, menyebabkan perubahan warna, ketebalan, dan tekstur kuku.",
    earlySymptoms: ["Bercak putih/kuning kecil di ujung kuku", "Kuku sedikit lebih rapuh"],
    progressSymptoms: ["Kuku menebal dan berubah warna gelap", "Kuku rapuh, mudah pecah, atau terlepas dari dasar kuku", "Bau tidak sedap pada kasus lanjut"],
    causes: ["Jamur dermatofit", "Kelembaban berlebih pada kaki/tangan", "Luka kecil di sekitar kuku", "Penggunaan alas kaki tertutup dalam waktu lama"],
    prevention: ["Jaga kaki/tangan tetap kering", "Gunakan alas kaki yang menyerap keringat", "Hindari berbagi alat manikur/pedikur", "Potong kuku secara teratur dan bersih"],
    treatment: ["Obat antijamur topikal", "Antijamur oral untuk kasus lebih berat", "Perawatan kebersihan kuku rutin", "Konsultasi dokter bila tidak membaik"]
  },
  "Poison Ivy Photos and other Contact Dermatitis": {
    name: "Dermatitis Kontak", category: "Reaksi Alergi/Iritasi Kulit",
    summary: "Ruam kulit yang timbul akibat kontak langsung dengan zat alergen atau iritan, seperti tanaman, kosmetik, atau logam tertentu.",
    earlySymptoms: ["Gatal dan kemerahan di area kontak", "Sensasi panas ringan pada kulit"],
    progressSymptoms: ["Ruam meluas dengan lepuhan kecil berair", "Kulit bengkak dan sangat gatal", "Kulit pecah-pecah pada kasus kronis"],
    causes: ["Kontak dengan tanaman alergen (poison ivy dll)", "Kosmetik atau produk perawatan kulit tertentu", "Logam seperti nikel", "Bahan kimia/deterjen"],
    prevention: ["Kenali dan hindari zat pemicu alergi", "Gunakan sarung tangan saat kontak bahan kimia", "Cuci kulit segera setelah terpapar zat pemicu"],
    treatment: ["Kompres dingin untuk meredakan gatal", "Antihistamin oral", "Salep kortikosteroid topikal", "Hindari paparan ulang terhadap pemicu"]
  },
  "Psoriasis pictures Lichen Planus and related diseases": {
    name: "Psoriasis & Lichen Planus", category: "Penyakit Autoimun Kulit",
    summary: "Penyakit kulit kronis berbasis autoimun yang menyebabkan pergantian sel kulit terlalu cepat, membentuk plak tebal bersisik.",
    earlySymptoms: ["Bercak merah dengan sisik halus", "Kulit terasa kering dan sedikit gatal"],
    progressSymptoms: ["Plak tebal bersisik keperakan yang meluas", "Kulit pecah-pecah dan dapat berdarah", "Nyeri sendi pada psoriasis artritis"],
    causes: ["Disfungsi sistem imun (autoimun)", "Faktor genetik", "Stres, infeksi, atau cedera kulit sebagai pemicu (Koebner phenomenon)"],
    prevention: ["Kelola stres dengan baik", "Jaga kelembaban kulit", "Hindari cedera/gesekan kulit berulang", "Kontrol rutin bila terdiagnosis"],
    treatment: ["Salep kortikosteroid dan analog vitamin D", "Terapi fototerapi (UV)", "Obat imunosupresan/biologik untuk kasus sedang-berat", "Konsultasi dokter spesialis kulit"]
  },
  "Scabies Lyme Disease and other Infestations and Bites": {
    name: "Skabies, Penyakit Lyme & Gigitan Serangga", category: "Infestasi Parasit",
    summary: "Kondisi kulit akibat infestasi parasit (seperti tungau Sarcoptes scabiei) atau gigitan serangga yang menimbulkan reaksi gatal dan ruam.",
    earlySymptoms: ["Gatal ringan terutama malam hari", "Bintik merah kecil di sela jari/lipatan tubuh"],
    progressSymptoms: ["Gatal hebat dan menyebar ke area lain", "Lepuhan/lesi berkerak akibat garukan", "Infeksi sekunder bila digaruk terus-menerus"],
    causes: ["Infestasi tungau Sarcoptes scabiei (skabies)", "Gigitan kutu Ixodes (Lyme disease)", "Gigitan serangga lain (nyamuk, kutu busuk)"],
    prevention: ["Hindari kontak kulit langsung dengan penderita skabies", "Cuci pakaian dan sprei dengan air panas", "Gunakan lotion anti-serangga saat beraktivitas di alam terbuka"],
    treatment: ["Krim permetrin untuk skabies", "Antibiotik untuk penyakit Lyme (sesuai anjuran dokter)", "Antihistamin untuk mengurangi gatal", "Obati seluruh anggota keluarga serumah bila skabies"]
  },
  "Seborrheic Keratoses and other Benign Tumors": {
    name: "Keratosis Seboroik & Tumor Jinak Kulit", category: "Pertumbuhan Kulit Jinak",
    summary: "Pertumbuhan kulit jinak yang umum terjadi seiring bertambahnya usia, tidak bersifat kanker namun bisa menyerupai lesi ganas secara visual.",
    earlySymptoms: ["Bercak kecoklatan datar yang mulai menonjol", "Permukaan sedikit kasar"],
    progressSymptoms: ["Lesi menebal dengan permukaan berkerak seperti lilin", "Warna makin gelap dan ukuran membesar perlahan"],
    causes: ["Proses penuaan kulit alami", "Faktor genetik/keturunan", "Paparan sinar matahari kumulatif"],
    prevention: ["Tidak ada pencegahan spesifik karena berkaitan usia", "Gunakan tabir surya untuk memperlambat perubahan kulit", "Periksa rutin bila muncul lesi baru mencurigakan"],
    treatment: ["Umumnya tidak perlu diobati bila tidak mengganggu", "Cryotherapy bila mengganggu penampilan/iritasi", "Eksisi jika diperlukan konfirmasi diagnosis"]
  },
  "Systemic Disease": {
    name: "Manifestasi Kulit dari Penyakit Sistemik", category: "Gejala Kulit Sekunder",
    summary: "Perubahan pada kulit yang muncul sebagai tanda dari penyakit yang mendasari pada organ atau sistem tubuh lain.",
    earlySymptoms: ["Perubahan warna kulit ringan", "Kelelahan disertai keluhan kulit ringan"],
    progressSymptoms: ["Ruam yang meluas seiring perkembangan penyakit dasar", "Nyeri sendi, demam, atau gejala sistemik lain yang menyertai"],
    causes: ["Penyakit autoimun", "Gangguan metabolik/endokrin", "Infeksi sistemik", "Efek samping pengobatan penyakit lain"],
    prevention: ["Kontrol rutin penyakit dasar yang sudah terdiagnosis", "Laporkan perubahan kulit baru ke dokter yang menangani"],
    treatment: ["Penanganan difokuskan pada penyakit dasar", "Perawatan simptomatik untuk keluhan kulit", "Kolaborasi dokter spesialis kulit dan penyakit dalam"]
  },
  "Tinea Ringworm Candidiasis and other Fungal Infections": {
    name: "Infeksi Jamur Kulit (Tinea, Kandidiasis)", category: "Infeksi Jamur",
    summary: "Infeksi kulit yang disebabkan oleh jamur dermatofit atau kandida, sering muncul di area lembab seperti lipatan kulit dan sela jari.",
    earlySymptoms: ["Bercak merah gatal berbentuk cincin", "Kulit sedikit bersisik di area terinfeksi"],
    progressSymptoms: ["Ruam melebar dengan tepi meninggi dan tengah lebih pucat", "Lepuhan kecil berisi cairan disertai gatal hebat", "Kulit pecah dan lembab pada infeksi kandida"],
    causes: ["Jamur dermatofit (Tinea)", "Jamur Candida", "Kelembaban tinggi dan kebersihan kulit kurang terjaga", "Kontak dengan penderita/hewan terinfeksi"],
    prevention: ["Jaga kulit tetap kering, terutama lipatan tubuh", "Hindari berbagi handuk/pakaian", "Gunakan alas kaki di tempat umum yang lembab"],
    treatment: ["Antijamur topikal (krim/salep)", "Antijamur oral untuk kasus luas/berulang", "Jaga kebersihan dan kekeringan kulit selama pengobatan"]
  },
  "Urticaria Hives": {
    name: "Urtikaria (Biduran)", category: "Reaksi Alergi Kulit",
    summary: "Reaksi kulit akut atau kronis berupa bentol merah yang gatal, umumnya dipicu oleh alergi terhadap makanan, obat, atau faktor lingkungan.",
    earlySymptoms: ["Bentol merah kecil yang gatal", "Muncul mendadak dan dapat berpindah lokasi"],
    progressSymptoms: ["Bentol membesar dan menyatu membentuk plak luas", "Bengkak pada wajah/bibir (angioedema) — tanda perlu perhatian medis segera"],
    causes: ["Alergi makanan atau obat", "Reaksi terhadap suhu ekstrem/tekanan pada kulit", "Infeksi yang memicu respons imun", "Stres"],
    prevention: ["Kenali dan hindari alergen pemicu", "Catat makanan/obat yang pernah memicu reaksi", "Kelola stres dengan baik"],
    treatment: ["Antihistamin sebagai lini pertama", "Kompres dingin untuk meredakan gatal", "Kortikosteroid pada kasus berat", "Segera ke UGD bila disertai sesak napas/bengkak wajah"]
  },
  "Vascular Tumors": {
    name: "Tumor Vaskular", category: "Kelainan Pembuluh Darah Kulit",
    summary: "Pertumbuhan abnormal pembuluh darah pada atau di bawah kulit, sebagian besar bersifat jinak seperti hemangioma.",
    earlySymptoms: ["Noda merah kecil pada kulit", "Benjolan lunak yang tidak nyeri"],
    progressSymptoms: ["Benjolan membesar seiring waktu", "Perubahan warna menjadi lebih gelap/kebiruan", "Nyeri bila tertekan atau mengalami trauma"],
    causes: ["Kelainan perkembangan pembuluh darah sejak lahir/awal kehidupan", "Faktor genetik", "Pertumbuhan sel endotel abnormal"],
    prevention: ["Tidak ada pencegahan spesifik untuk kasus bawaan", "Pantau perubahan ukuran/warna secara berkala"],
    treatment: ["Observasi rutin bila tidak mengganggu", "Terapi laser untuk tujuan kosmetik", "Eksisi bedah bila mengganggu fungsi atau membesar cepat"]
  },
  "Vasculitis Photos": {
    name: "Vaskulitis", category: "Peradangan Pembuluh Darah",
    summary: "Peradangan pada dinding pembuluh darah yang dapat menyebabkan bercak, luka, atau nekrosis pada kulit akibat gangguan aliran darah.",
    earlySymptoms: ["Bercak merah/ungu kecil (purpura) pada kulit", "Kulit terasa nyeri ringan di area bercak"],
    progressSymptoms: ["Bercak meluas dan dapat berkembang menjadi luka terbuka", "Nyeri sendi, demam, atau kelemahan tubuh sebagai gejala penyerta"],
    causes: ["Reaksi autoimun", "Infeksi yang memicu peradangan pembuluh darah", "Efek samping obat tertentu"],
    prevention: ["Kontrol rutin bila punya penyakit autoimun yang mendasari", "Laporkan gejala baru ke dokter segera"],
    treatment: ["Kortikosteroid untuk mengendalikan peradangan", "Obat imunosupresan pada kasus kronis", "Pemantauan ketat oleh dokter spesialis"]
  },
  "Warts Molluscum and other Viral Infections": {
    name: "Kutil & Infeksi Virus Lainnya", category: "Infeksi Virus Kulit",
    summary: "Pertumbuhan kulit jinak akibat infeksi virus seperti HPV (kutil) atau Molluscum contagiosum, menular melalui kontak langsung.",
    earlySymptoms: ["Benjolan kecil dengan permukaan kasar (kutil) atau mengkilap dengan lekukan tengah (molluscum)", "Tidak nyeri pada tahap awal"],
    progressSymptoms: ["Benjolan bertambah banyak dan menyebar", "Ukuran membesar, kadang disertai gatal ringan"],
    causes: ["Virus HPV (Human Papillomavirus)", "Virus Molluscum contagiosum", "Kontak kulit langsung dengan area/orang terinfeksi"],
    prevention: ["Hindari kontak langsung dengan lesi aktif orang lain", "Jangan menggaruk/memencet lesi agar tidak menyebar", "Jaga kebersihan kulit dan alat pribadi"],
    treatment: ["Krioterapi (pembekuan)", "Obat topikal keratolitik", "Kuretase oleh tenaga medis", "Observasi karena beberapa kasus dapat sembuh sendiri"]
  },
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
['dragover', 'drop'].forEach(evt => uploadZone.addEventListener(evt, e => e.preventDefault()));
uploadZone.addEventListener('drop', e => {
  if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
});
fileInput.addEventListener('change', e => {
  if (e.target.files.length) handleFile(e.target.files[0]);
});

function handleFile(file) {

  // Maksimal 10 MB
  const maxSize = 10 * 1024 * 1024;

  if (file.size > maxSize) {
    alert("⚠️ Ukuran gambar terlalu besar!\n\nMaksimal ukuran file adalah 10 MB.");

    fileInput.value = "";
    selectedFile = null;
    previewWrap.classList.add('hidden');
    return;
  }

  selectedFile = file;

  const reader = new FileReader();
  reader.onload = e => {
    previewImg.src = e.target.result;
    fileNameLabel.textContent = file.name;
    fileSizeLabel.textContent = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
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

function fillList(elementId, items) {
  const el = document.getElementById(elementId);
  el.innerHTML = '';
  (items || []).forEach(text => {
    const li = document.createElement('li');
    li.textContent = text;
    el.appendChild(li);
  });
}

function renderResult(data) {
  const mappedKey = backendToFrontendMapping[data.predicted_class] || data.predicted_class;
  const info = diseaseInfo[mappedKey] || {
    name: data.predicted_class, category: 'Tidak diketahui', summary: '',
    earlySymptoms: [], progressSymptoms: [], causes: [], prevention: [], treatment: []
  };

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

  // ==== Informasi Penyakit (baru, format list urut) ====
  document.getElementById('diseaseCategoryPill').textContent = info.category || 'Dermatologi';
  document.getElementById('diseaseSummary').textContent = info.summary || '';
  fillList('earlySymptomsList', info.earlySymptoms);
  fillList('progressSymptomsList', info.progressSymptoms);
  fillList('causesList', info.causes);
  fillList('preventionList', info.prevention);
  fillList('treatmentList', info.treatment);

  uploadState.classList.add('hidden');
  infoPanel.classList.add('hidden');
  resultState.classList.remove('hidden');

  analyzeBtn.disabled = false;
  analyzeBtn.innerHTML = '⚡ ANALISIS DENGAN AI';
  const uploadAlert = document.getElementById('uploadAlert');
}
</script>
@endpush
