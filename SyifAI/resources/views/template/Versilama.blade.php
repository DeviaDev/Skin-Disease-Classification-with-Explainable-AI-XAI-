<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Skin Detector</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
        .animate-fadeIn { animation: fadeIn 0.8s ease-out; }
        .animate-slideIn { animation: slideIn 0.8s ease-out; }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 relative overflow-x-hidden">

    <!-- Background blobs -->
    <div class="absolute inset-0 opacity-20 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-pink-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
    </div>

    <!-- Header -->
    <div class="relative z-10 backdrop-blur-xl bg-white/10 border-b border-white/20 shadow-2xl">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex items-center justify-center space-x-4">
                <div class="relative bg-gradient-to-r from-cyan-500 to-purple-600 p-4 rounded-full shadow-2xl">
                    🧠
                </div>
                <div class="text-center">
                    <h1 class="text-4xl md:text-5xl font-black bg-gradient-to-r from-cyan-400 via-purple-400 to-pink-400 bg-clip-text text-transparent">
                        AI SKIN DETECTOR
                    </h1>
                    <p class="text-lg md:text-xl text-gray-300 mt-2 font-medium">
                        Powered by <span class="text-cyan-400">Huntrix Group</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 py-12">
        <div class="grid xl:grid-cols-2 gap-8">

            <!-- Kolom Upload & Hasil -->
            <div class="space-y-8">
                <div class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-8 border border-white/20">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <span class="bg-gradient-to-r from-cyan-500 to-purple-500 p-2 rounded-xl mr-3">⬆️</span>
                        Upload Gambar Kulit
                    </h2>

                    <input id="fileInput" type="file" accept="image/*" class="hidden">

                    <div id="dropArea" class="relative group cursor-pointer">
                        <div class="relative border-4 border-dashed border-white/30 rounded-2xl p-12 text-center bg-white/5 hover:bg-white/10 transition-all duration-300">
                            <div class="text-6xl mb-6">📷</div>
                            <p class="text-2xl font-bold text-white mb-2">Klik untuk Upload</p>
                            <p class="text-lg text-gray-300">Format: JPG, PNG • Max: 10MB</p>
                        </div>
                    </div>

                    <div id="previewWrap" class="mt-8 hidden">
                        <div class="relative">
                            <img id="previewImg" class="w-full h-80 object-cover rounded-2xl shadow-2xl border-4 border-white/20" alt="Preview">
                            <div class="absolute top-4 right-4">
                                <button id="resetBtn" class="bg-gradient-to-r from-red-500 to-pink-600 text-white px-6 py-2 rounded-full font-bold hover:from-red-600 hover:to-pink-700 transition-all shadow-xl">
                                    Reset
                                </button>
                            </div>
                        </div>

                        <div id="analyzeWrap" class="mt-6">
                            <button id="analyzeBtn" class="w-full bg-gradient-to-r from-emerald-600 via-cyan-600 to-purple-700 text-white py-6 px-8 rounded-2xl font-bold text-xl hover:from-emerald-700 hover:via-cyan-700 hover:to-purple-800 transition-all shadow-2xl">
                                <span id="analyzeBtnText">⚡ ANALISIS DENGAN AI →</span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Hasil Prediksi -->
                <div id="resultCard" class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-8 border border-white/20 animate-fadeIn hidden">
                    <h2 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <span class="bg-gradient-to-r from-green-500 to-emerald-500 p-2 rounded-xl mr-3">✅</span>
                        Hasil Analisis AI
                    </h2>

                    <div id="resultInner" class="rounded-2xl p-8 border-2 border-white/30 shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-2xl font-black text-gray-800">DIAGNOSIS:</h3>
                            <div id="severityBadge" class="px-4 py-2 rounded-full font-bold text-sm bg-white/50">
                                🛡️ <span id="severityText"></span>
                            </div>
                        </div>

                        <p id="diseaseName" class="text-3xl font-black mb-6"></p>

                        <div class="bg-white/70 rounded-2xl p-6 mb-6 shadow-xl">
                            <div class="flex items-center justify-between mb-4">
                                <span class="text-gray-800 font-bold text-lg">Tingkat Keyakinan AI:</span>
                                <span id="confidenceText" class="text-4xl font-black text-emerald-600"></span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-6 overflow-hidden">
                                <div id="confidenceBar" class="h-6 rounded-full bg-gradient-to-r from-emerald-400 via-cyan-500 to-purple-500" style="width: 0%"></div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-100 to-orange-100 border-2 border-yellow-300 rounded-2xl p-6 shadow-xl">
                            <p class="text-yellow-800 font-bold text-lg mb-2">⚠️ Penting!</p>
                            <p class="text-yellow-700 font-medium">Hasil ini adalah estimasi AI berdasarkan analisis visual. Untuk diagnosis yang akurat, konsultasikan dengan dokter spesialis kulit!</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Info -->
            <div id="infoDefaultPanel" class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-8 border border-white/20">
                <h2 class="text-2xl font-bold text-white mb-6">ℹ️ Cara Penggunaan & Teknologi AI</h2>
                <div class="space-y-4">
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10">
                        <h4 class="font-bold text-white text-lg mb-2">1. Upload Gambar</h4>
                        <p class="text-gray-300">Pilih foto kulit yang jelas dengan pencahayaan cukup.</p>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10">
                        <h4 class="font-bold text-white text-lg mb-2">2. Analisis AI</h4>
                        <p class="text-gray-300">Model ResNet18 menganalisis pola visual kulit.</p>
                    </div>
                    <div class="bg-white/5 p-6 rounded-2xl border border-white/10">
                        <h4 class="font-bold text-white text-lg mb-2">3. Hasil & Info</h4>
                        <p class="text-gray-300">Dapatkan prediksi beserta informasi lengkapnya.</p>
                    </div>
                </div>
            </div>

            <div id="infoDetailPanel" class="backdrop-blur-xl bg-white/10 rounded-3xl shadow-2xl p-8 border border-white/20 animate-slideIn hidden">
                <h2 class="text-2xl font-bold text-white mb-6">ℹ️ Informasi Lengkap</h2>
                <div class="space-y-8">
                    <div class="bg-white/10 rounded-2xl p-6 border border-white/20">
                        <h3 class="font-black text-white mb-4 text-xl">Deskripsi</h3>
                        <p id="infoDescription" class="text-gray-200 leading-relaxed bg-white/5 p-4 rounded-xl border border-white/10"></p>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6 border border-white/20">
                        <h3 class="font-black text-white mb-4 text-xl">Gejala Umum</h3>
                        <div id="infoSymptoms" class="space-y-3"></div>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6 border border-white/20">
                        <h3 class="font-black text-white mb-4 text-xl">Penyebab</h3>
                        <div id="infoCauses" class="space-y-3"></div>
                    </div>
                    <div class="bg-white/10 rounded-2xl p-6 border border-white/20">
                        <h3 class="font-black text-white mb-4 text-xl">Saran Pengobatan</h3>
                        <div id="infoTreatment" class="space-y-3"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    // === Mapping backend label -> key diseaseInfo ===
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

    // === Database info penyakit (isi sesuai kebutuhanmu, sama seperti versi React) ===
    const diseaseInfo = {
        "Acne and Rosacea Photos": {
            name: "Jerawat dan Rosacea",
            description: "Kondisi kulit yang ditandai dengan komedo, papula, pustula, atau kemerahan pada wajah.",
            symptoms: ["Komedo", "Papula", "Pustula", "Kemerahan"],
            causes: ["Produksi minyak berlebih", "Bakteri P. acnes", "Hormon", "Stres"],
            treatment: ["Pembersih wajah lembut", "Obat topikal", "Konsultasi dokter kulit"],
            severity: "Ringan - Sedang",
            textColor: "text-orange-500",
            bgGradient: "linear-gradient(to bottom right, #fff7ed, #fef2f2)"
        },
        // ... tambahkan sisanya persis seperti object diseaseInfo di file React kamu
        // (dipangkas di sini biar respons tidak kepanjangan — tinggal copy-paste seluruh
        // isi diseaseInfo dari SkinDiseasePredictor.jsx ke sini dengan struktur yang sama)
    };

    const fileInput = document.getElementById('fileInput');
    const dropArea = document.getElementById('dropArea');
    const previewWrap = document.getElementById('previewWrap');
    const previewImg = document.getElementById('previewImg');
    const analyzeBtn = document.getElementById('analyzeBtn');
    const analyzeBtnText = document.getElementById('analyzeBtnText');
    const resetBtn = document.getElementById('resetBtn');
    const resultCard = document.getElementById('resultCard');
    const infoDefaultPanel = document.getElementById('infoDefaultPanel');
    const infoDetailPanel = document.getElementById('infoDetailPanel');

    dropArea.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = (ev) => {
            previewImg.src = ev.target.result;
            previewWrap.classList.remove('hidden');
            resultCard.classList.add('hidden');
            infoDetailPanel.classList.add('hidden');
            infoDefaultPanel.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    });

    resetBtn.addEventListener('click', () => {
        fileInput.value = '';
        previewWrap.classList.add('hidden');
        resultCard.classList.add('hidden');
        infoDetailPanel.classList.add('hidden');
        infoDefaultPanel.classList.remove('hidden');
    });

    analyzeBtn.addEventListener('click', async () => {
        if (!fileInput.files[0]) return;

        analyzeBtn.disabled = true;
        analyzeBtnText.textContent = 'AI Sedang Menganalisis Gambar...';

        const formData = new FormData();
        formData.append('file', fileInput.files[0]);

        try {
            const res = await fetch("{{ route('skin.predict') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await res.json();

            if (!res.ok || data.error) {
                throw new Error(data.error || 'Gagal melakukan prediksi.');
            }

            const mappedKey = backendToFrontendMapping[data.predicted_class] || data.predicted_class;
            renderResult(mappedKey, data.confidence);

        } catch (err) {
            console.error(err);
            alert('Gagal melakukan prediksi. ' + err.message);
        } finally {
            analyzeBtn.disabled = false;
            analyzeBtnText.textContent = '⚡ ANALISIS DENGAN AI →';
        }
    });

    function renderResult(diseaseKey, confidence) {
        const info = diseaseInfo[diseaseKey];

        document.getElementById('diseaseName').textContent = info ? info.name : diseaseKey;
        document.getElementById('severityText').textContent = info ? info.severity : 'Unknown';
        document.getElementById('confidenceText').textContent = confidence.toFixed(1) + '%';
        document.getElementById('confidenceBar').style.width = confidence + '%';

        const resultInner = document.getElementById('resultInner');
        resultInner.style.background = info ? info.bgGradient : '#f3f4f6';

        const diseaseNameEl = document.getElementById('diseaseName');
        diseaseNameEl.className = 'text-3xl font-black mb-6 ' + (info ? info.textColor : 'text-gray-800');

        resultCard.classList.remove('hidden');

        if (info) {
            document.getElementById('infoDescription').textContent = info.description;
            fillList('infoSymptoms', info.symptoms, 'bg-red-500/20 border-red-400/30');
            fillList('infoCauses', info.causes, 'bg-yellow-500/20 border-yellow-400/30');
            fillList('infoTreatment', info.treatment, 'bg-green-500/20 border-green-400/30');

            infoDefaultPanel.classList.add('hidden');
            infoDetailPanel.classList.remove('hidden');
        }
    }

    function fillList(containerId, items, classes) {
        const container = document.getElementById(containerId);
        container.innerHTML = '';
        items.forEach((item) => {
            const div = document.createElement('div');
            div.className = `flex items-center ${classes} p-4 rounded-xl border`;
            div.innerHTML = `<div class="w-3 h-3 bg-white/60 rounded-full mr-4"></div><span class="text-white font-medium">${item}</span>`;
            container.appendChild(div);
        });
    }
    </script>

</body>
</html>
