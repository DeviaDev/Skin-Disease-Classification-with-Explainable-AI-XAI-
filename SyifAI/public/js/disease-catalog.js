/**
 * disease-catalog.js
 * ------------------------------------------------------------------
 * Katalog informasi 23 kelas penyakit kulit (DermNet dataset).
 *
 * SUMBER / REFERENSI (dicantumkan agar dapat dipertanggungjawabkan
 * secara akademis saat sidang skripsi):
 *   1) DermNet NZ            https://dermnetnz.org/topics-a-z
 *      - Materi ditulis & ditinjau oleh dokter spesialis kulit.
 *      - Dataset gambar yang dipakai untuk melatih model juga
 *        diturunkan dari sumber DermNet, sehingga konsisten secara
 *        metodologis dengan sumber data pelatihan.
 *   2) PERDOSKI (Perhimpunan Dokter Spesialis Kulit & Kelamin
 *      Indonesia)            https://perdoski.id
 *      - Organisasi profesi resmi dokter spesialis kulit di
 *        Indonesia, dipakai sebagai rujukan konteks lokal.
 *
 * CATATAN PENTING:
 *   - Konten di bawah ini bersifat KURASI STATIS (bukan hasil
 *     pemanggilan API real-time), karena tidak tersedia API publik
 *     resmi dari Kemenkes/PERDOSKI untuk data per-penyakit.
 *     Ini WAJIB dijelaskan di bab metodologi skripsi.
 *   - `sourceUrl` mengarah ke indeks topik DermNet NZ (bukan tautan
 *     dalam per-penyakit yang belum diverifikasi satu per satu).
 *     Jika ingin tautan yang lebih spesifik per kelas, verifikasi
 *     dulu slug halamannya satu per satu di dermnetnz.org sebelum
 *     dipakai di laporan resmi.
 *   - `urgent: true` menandai kelas yang berpotensi mengarah ke
 *     kondisi serius/keganasan, dipakai untuk menampilkan badge
 *     peringatan agar pengguna segera ke dokter spesialis.
 */

export const backendToFrontendMapping = {
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

const DERMNET_INDEX = "https://dermnetnz.org/topics-a-z";
const PERDOSKI = "https://perdoski.id";

// Helper supaya tiap entri tidak perlu mengulang struktur source.
function src(name, url) {
  return { name, url };
}

export const diseaseInfo = {
  "Acne and Rosacea Photos": {
    name: "Jerawat dan Rosacea",
    description: "Kondisi kulit yang ditandai dengan komedo, papula, pustula, atau kemerahan pada wajah.",
    symptoms: ["Komedo", "Papula", "Pustula", "Kemerahan"],
    causes: ["Produksi minyak berlebih", "Bakteri P. acnes", "Hormon", "Stres"],
    treatment: ["Pembersih wajah lembut", "Obat topikal", "Konsultasi dokter kulit"],
    urgent: false,
    sources: [src("DermNet NZ — Acne", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Actinic Keratosis Basal Cell Carcinoma and other Malignant Lesions": {
    name: "Keratosis Aktinik & Karsinoma Basal Sel",
    description: "Lesi kulit yang bersifat prakanker atau kanker, biasanya muncul akibat paparan sinar matahari berlebih.",
    symptoms: ["Bercak kasar", "Kemerahan", "Luka yang sulit sembuh"],
    causes: ["Paparan sinar UV", "Usia lanjut", "Kulit terang"],
    treatment: ["Cryotherapy", "Biopsi dan eksisi", "Terapi laser"],
    urgent: true,
    sources: [src("DermNet NZ — Skin cancer", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Atopic Dermatitis Photos": {
    name: "Dermatitis Atopik (Eksim)",
    description: "Kondisi kulit kronis yang menyebabkan kulit kering, gatal, dan ruam merah.",
    symptoms: ["Kulit kering", "Ruam merah", "Gatal"],
    causes: ["Genetik", "Alergi", "Stres"],
    treatment: ["Pelembab rutin", "Kortikosteroid topikal", "Hindari pemicu"],
    urgent: false,
    sources: [src("DermNet NZ — Atopic dermatitis", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Bullous Disease Photos": {
    name: "Penyakit Bullous",
    description: "Gangguan kulit yang menyebabkan lepuhan besar berisi cairan di permukaan kulit.",
    symptoms: ["Lepuhan besar", "Gatal", "Nyeri kulit"],
    causes: ["Autoimun", "Infeksi", "Genetik"],
    treatment: ["Kortikosteroid", "Imunosupresan", "Perawatan luka"],
    urgent: true,
    sources: [src("DermNet NZ — Bullous disease", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Cellulitis Impetigo and other Bacterial Infections": {
    name: "Infeksi Bakteri Kulit",
    description: "Infeksi kulit yang disebabkan oleh bakteri, seperti selulitis atau impetigo.",
    symptoms: ["Kemerahan", "Bengkak", "Nyeri"],
    causes: ["Bakteri Staphylococcus", "Bakteri Streptococcus", "Cedera kulit"],
    treatment: ["Antibiotik oral/topikal", "Perawatan luka", "Konsultasi dokter"],
    urgent: true,
    sources: [src("DermNet NZ — Bacterial infections", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Eczema Photos": {
    name: "Eksim",
    description: "Kondisi kulit kering dan meradang yang sering kambuh.",
    symptoms: ["Kulit kering", "Gatal", "Ruam merah"],
    causes: ["Genetik", "Alergi", "Iritasi"],
    treatment: ["Pelembab rutin", "Kortikosteroid topikal", "Hindari pemicu"],
    urgent: false,
    sources: [src("DermNet NZ — Eczema", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Exanthems and Drug Eruptions": {
    name: "Ruam & Reaksi Obat",
    description: "Ruam kulit yang muncul akibat infeksi atau reaksi terhadap obat tertentu.",
    symptoms: ["Bercak merah", "Gatal", "Bintik-bintik"],
    causes: ["Infeksi virus/bakteri", "Reaksi obat"],
    treatment: ["Hentikan obat penyebab", "Antihistamin", "Perawatan simptomatik"],
    urgent: true,
    sources: [src("DermNet NZ — Drug eruptions", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Hair Loss Photos Alopecia and other Hair Diseases": {
    name: "Rambut Rontok & Alopecia",
    description: "Kehilangan rambut sebagian atau total yang bisa bersifat sementara atau permanen.",
    symptoms: ["Rontok rambut", "Botak sebagian", "Penipisan rambut"],
    causes: ["Genetik", "Autoimun", "Stres"],
    treatment: ["Minoxidil", "Kortikosteroid", "Terapi PRP"],
    urgent: false,
    sources: [src("DermNet NZ — Hair disorders", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Herpes HPV and other STDs Photos": {
    name: "Infeksi Menular Seksual",
    description: "Infeksi kulit akibat virus, termasuk herpes dan HPV.",
    symptoms: ["Luka/lesi kulit", "Gatal", "Nyeri"],
    causes: ["Virus HSV", "Virus HPV", "Kontak seksual"],
    treatment: ["Antivirus", "Perawatan simptomatik", "Konsultasi dokter"],
    urgent: true,
    sources: [src("DermNet NZ — Viral infections", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Light Diseases and Disorders of Pigmentation": {
    name: "Gangguan Pigmentasi",
    description: "Perubahan warna kulit akibat melanin yang berlebihan atau berkurang.",
    symptoms: ["Bercak putih/gelap", "Kulit tidak merata"],
    causes: ["Genetik", "Paparan sinar", "Autoimun"],
    treatment: ["Krim pencerah", "Terapi laser", "Konsultasi dokter"],
    urgent: false,
    sources: [src("DermNet NZ — Pigmentation disorders", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Lupus and other Connective Tissue diseases": {
    name: "Lupus & Penyakit Jaringan Ikat",
    description: "Penyakit autoimun yang dapat mempengaruhi kulit, sendi, dan organ lainnya.",
    symptoms: ["Ruam wajah", "Nyeri sendi", "Kelelahan"],
    causes: ["Autoimun", "Genetik", "Lingkungan"],
    treatment: ["Imunosupresan", "Kortikosteroid", "Perawatan simptomatik"],
    urgent: true,
    sources: [src("DermNet NZ — Connective tissue disease", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Melanoma Skin Cancer Nevi and Moles": {
    name: "Melanoma & Tahi Lalat",
    description: "Kanker kulit yang berkembang dari sel melanosit atau tahi lalat abnormal.",
    symptoms: ["Bercak hitam/gelap", "Tidak simetris", "Berubah bentuk"],
    causes: ["Paparan sinar UV", "Genetik", "Kulit terang"],
    treatment: ["Eksisi bedah", "Kemoterapi/topikal", "Pemantauan rutin"],
    urgent: true,
    sources: [src("DermNet NZ — Melanoma", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Nail Fungus and other Nail Disease": {
    name: "Infeksi Kuku & Jamur",
    description: "Infeksi pada kuku yang dapat menyebabkan perubahan warna, ketebalan, dan bentuk kuku.",
    symptoms: ["Kuku tebal", "Kuku rapuh", "Perubahan warna"],
    causes: ["Jamur dermatofit", "Luka kuku", "Kelembaban tinggi"],
    treatment: ["Antijamur topikal/oral", "Perawatan kuku", "Konsultasi dokter"],
    urgent: false,
    sources: [src("DermNet NZ — Nail disease", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Poison Ivy Photos and other Contact Dermatitis": {
    name: "Dermatitis Kontak",
    description: "Ruam kulit akibat kontak dengan alergen atau iritan seperti poison ivy.",
    symptoms: ["Ruam merah", "Gatal", "Lepuhan kecil"],
    causes: ["Tanaman alergen", "Kosmetik", "Logam"],
    treatment: ["Antihistamin", "Salep antiinflamasi", "Hindari pemicu"],
    urgent: false,
    sources: [src("DermNet NZ — Contact dermatitis", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Psoriasis pictures Lichen Planus and related diseases": {
    name: "Psoriasis & Lichen Planus",
    description: "Penyakit autoimun yang menyebabkan plak bersisik dan gatal pada kulit.",
    symptoms: ["Plak bersisik", "Kulit menebal", "Gatal"],
    causes: ["Genetik", "Autoimun", "Stres"],
    treatment: ["Terapi UV", "Obat imunosupresan", "Salep kortikosteroid"],
    urgent: false,
    sources: [src("DermNet NZ — Psoriasis", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Scabies Lyme Disease and other Infestations and Bites": {
    name: "Skabies, Lyme & Gigitan Serangga",
    description: "Infestasi kulit oleh parasit atau gigitan serangga yang menimbulkan gatal dan ruam.",
    symptoms: ["Gatal hebat", "Bintik merah", "Lepuhan kecil"],
    causes: ["Parasit", "Kutu", "Gigitan serangga"],
    treatment: ["Obat anti-parasit", "Krim topikal", "Konsultasi dokter"],
    urgent: false,
    sources: [src("DermNet NZ — Scabies", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Seborrheic Keratoses and other Benign Tumors": {
    name: "Seboroik Keratosis & Tumor Jinak",
    description: "Pertumbuhan kulit jinak, biasanya berwarna coklat atau hitam dan menonjol.",
    symptoms: ["Lesi coklat/hitam", "Permukaan kasar"],
    causes: ["Penuaan", "Genetik"],
    treatment: ["Cryotherapy", "Eksisi jika mengganggu", "Pemantauan rutin"],
    urgent: false,
    sources: [src("DermNet NZ — Seborrhoeic keratosis", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Systemic Disease": {
    name: "Penyakit Sistemik",
    description: "Penyakit yang mempengaruhi organ dan sistem tubuh, juga bisa memunculkan gejala kulit.",
    symptoms: ["Ruam", "Nyeri sendi", "Kelelahan"],
    causes: ["Autoimun", "Infeksi", "Genetik"],
    treatment: ["Terapi medis sesuai diagnosis", "Konsultasi dokter"],
    urgent: true,
    sources: [src("DermNet NZ — Systemic disease", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Tinea Ringworm Candidiasis and other Fungal Infections": {
    name: "Infeksi Jamur Kulit",
    description: "Infeksi kulit yang disebabkan oleh jamur dermatofit atau kandida.",
    symptoms: ["Lepuhan merah", "Gatal", "Kuku berubah warna"],
    causes: ["Jamur dermatofit", "Kelembaban tinggi", "Kontak kulit"],
    treatment: ["Antijamur topikal/oral", "Perawatan kebersihan kulit"],
    urgent: false,
    sources: [src("DermNet NZ — Fungal infections", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Urticaria Hives": {
    name: "Urtikaria (Biduran)",
    description: "Reaksi kulit berupa bentol merah gatal akibat alergi atau iritasi.",
    symptoms: ["Bentol merah", "Gatal hebat", "Hilang-timbul"],
    causes: ["Alergi makanan", "Obat", "Stres"],
    treatment: ["Antihistamin", "Hindari pemicu", "Kompres dingin"],
    urgent: false,
    sources: [src("DermNet NZ — Urticaria", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Vascular Tumors": {
    name: "Tumor Vaskular",
    description: "Pertumbuhan abnormal pembuluh darah pada kulit.",
    symptoms: ["Noda merah", "Benjolan kecil", "Nyeri ringan"],
    causes: ["Genetik", "Pertumbuhan abnormal pembuluh darah"],
    treatment: ["Eksisi jika perlu", "Pemantauan rutin"],
    urgent: false,
    sources: [src("DermNet NZ — Vascular tumours", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Vasculitis Photos": {
    name: "Vaskulitis",
    description: "Peradangan pembuluh darah yang bisa menimbulkan bercak dan luka pada kulit.",
    symptoms: ["Bercak merah/ungu", "Nyeri", "Luka kecil"],
    causes: ["Autoimun", "Infeksi", "Obat"],
    treatment: ["Kortikosteroid", "Imunosupresan", "Pemantauan dokter"],
    urgent: true,
    sources: [src("DermNet NZ — Vasculitis", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
  "Warts Molluscum and other Viral Infections": {
    name: "Kutil & Infeksi Virus",
    description: "Pertumbuhan kulit akibat virus seperti HPV atau molluscum contagiosum.",
    symptoms: ["Benjolan kecil", "Kutil menonjol", "Tidak gatal/nyeri"],
    causes: ["Virus HPV", "Molluscum contagiosum", "Kontak kulit"],
    treatment: ["Eksisi", "Krioterapi", "Perawatan simptomatik"],
    urgent: false,
    sources: [src("DermNet NZ — Warts", DERMNET_INDEX), src("PERDOSKI", PERDOSKI)],
  },
};

/**
 * Resolve label backend -> info lengkap (dengan fallback aman
 * supaya UI tidak pernah crash walau backend mengirim label baru
 * yang belum terdaftar di katalog).
 */
export function resolveDiseaseInfo(backendLabel) {
  const mappedKey = backendToFrontendMapping[backendLabel] || backendLabel;
  return (
    diseaseInfo[mappedKey] || {
      name: backendLabel,
      description: "Deskripsi belum tersedia untuk kelas ini.",
      symptoms: [],
      causes: [],
      treatment: [],
      urgent: false,
      sources: [src("DermNet NZ (indeks topik)", DERMNET_INDEX)],
    }
  );
}
