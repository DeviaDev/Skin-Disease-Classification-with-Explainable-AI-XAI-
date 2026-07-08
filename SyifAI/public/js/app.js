/**
 * app.js
 * ------------------------------------------------------------------
 * Controller untuk halaman hasil deteksi SyifAI.
 * Tidak mengubah kontrak backend sama sekali:
 *   - endpoint tetap dipanggil via window.__SYIFAI__.predictUrl
 *   - field response yang dipakai tetap sama:
 *       predicted_class, confidence, probabilities[], gradcam_base64
 *
 * Perbaikan dibanding versi lama:
 *   1. Tidak lagi pakai innerHTML dengan data dari server tanpa
 *      escaping (celah XSS bila label berisi karakter HTML).
 *   2. Validasi file di sisi klien (tipe & ukuran) sebelum upload.
 *   3. AbortController + timeout supaya request tidak menggantung.
 *   4. Skeleton loading & pesan error yang lebih informatif.
 *   5. Badge "perlu perhatian medis" untuk kelas berisiko tinggi.
 *   6. Panel referensi ilmiah per hasil prediksi (DermNet NZ / PERDOSKI).
 *   7. Tombol cetak/ekspor ringkasan hasil.
 */

import { resolveDiseaseInfo } from "./disease-catalog.js";

const CONFIG = window.__SYIFAI__ || {};
const MAX_FILE_SIZE_MB = 10;
const ALLOWED_TYPES = ["image/jpeg", "image/png", "image/webp"];
const REQUEST_TIMEOUT_MS = 30000;

// ---------------------------------------------------------------
// DOM refs
// ---------------------------------------------------------------
const el = (id) => document.getElementById(id);

const fileInput = el("fileInput");
const uploadZone = el("uploadZone");
const previewWrap = el("previewWrap");
const previewImg = el("previewImg");
const fileNameLabel = el("fileNameLabel");
const fileSizeLabel = el("fileSizeLabel");
const analyzeBtn = el("analyzeBtn");
const uploadState = el("uploadState");
const resultState = el("resultState");
const infoPanel = el("infoPanel");
const errorBanner = el("errorBanner");

let selectedFile = null;
let activeController = null;

// ---------------------------------------------------------------
// Utilities
// ---------------------------------------------------------------
function formatMB(bytes) {
  return (bytes / (1024 * 1024)).toFixed(2) + " MB";
}

function clearNode(node) {
  while (node.firstChild) node.removeChild(node.firstChild);
}

function showError(message) {
  if (!errorBanner) {
    alert(message);
    return;
  }
  errorBanner.textContent = message;
  errorBanner.classList.remove("hidden");
  errorBanner.setAttribute("role", "alert");
}

function clearError() {
  if (!errorBanner) return;
  errorBanner.textContent = "";
  errorBanner.classList.add("hidden");
}

function validateFile(file) {
  if (!ALLOWED_TYPES.includes(file.type)) {
    return "Format file tidak didukung. Gunakan JPG, PNG, atau WEBP.";
  }
  if (file.size > MAX_FILE_SIZE_MB * 1024 * 1024) {
    return `Ukuran file terlalu besar (maks ${MAX_FILE_SIZE_MB}MB).`;
  }
  return null;
}

function setAnalyzeLoading(isLoading) {
  analyzeBtn.disabled = isLoading;
  clearNode(analyzeBtn);
  if (isLoading) {
    const spinner = document.createElement("span");
    spinner.className = "spinner";
    analyzeBtn.appendChild(spinner);
    analyzeBtn.appendChild(document.createTextNode(" Menganalisis..."));
  } else {
    analyzeBtn.appendChild(document.createTextNode("⚡ ANALISIS DENGAN AI"));
  }
}

// ---------------------------------------------------------------
// Upload handling
// ---------------------------------------------------------------
function handleFile(file) {
  const validationError = validateFile(file);
  if (validationError) {
    showError(validationError);
    return;
  }
  clearError();
  selectedFile = file;

  const reader = new FileReader();
  reader.onload = (e) => {
    previewImg.src = e.target.result;
    fileNameLabel.textContent = file.name;
    fileSizeLabel.textContent = formatMB(file.size);
    previewWrap.classList.remove("hidden");
  };
  reader.onerror = () => showError("Gagal membaca file gambar.");
  reader.readAsDataURL(file);
}

function resetAll() {
  if (activeController) activeController.abort();
  selectedFile = null;
  fileInput.value = "";
  previewWrap.classList.add("hidden");
  uploadState.classList.remove("hidden");
  resultState.classList.add("hidden");
  infoPanel.classList.remove("hidden");
  clearError();
}

uploadZone.addEventListener("click", () => fileInput.click());
uploadZone.addEventListener("keydown", (e) => {
  if (e.key === "Enter" || e.key === " ") fileInput.click();
});

["dragover", "dragenter"].forEach((evt) =>
  uploadZone.addEventListener(evt, (e) => {
    e.preventDefault();
    uploadZone.classList.add("drag-active");
  })
);
["dragleave", "drop"].forEach((evt) =>
  uploadZone.addEventListener(evt, (e) => {
    e.preventDefault();
    uploadZone.classList.remove("drag-active");
  })
);
uploadZone.addEventListener("drop", (e) => {
  if (e.dataTransfer.files.length) handleFile(e.dataTransfer.files[0]);
});
fileInput.addEventListener("change", (e) => {
  if (e.target.files.length) handleFile(e.target.files[0]);
});

el("resetBtnUpload").addEventListener("click", resetAll);
el("resetBtnResult").addEventListener("click", resetAll);

// ---------------------------------------------------------------
// Prediction request
// ---------------------------------------------------------------
async function runPrediction() {
  if (!selectedFile) return;
  clearError();
  setAnalyzeLoading(true);

  const formData = new FormData();
  formData.append("image", selectedFile);

  activeController = new AbortController();
  const timeoutId = setTimeout(() => activeController.abort(), REQUEST_TIMEOUT_MS);

  try {
    const res = await fetch(CONFIG.predictUrl, {
      method: "POST",
      headers: { "X-CSRF-TOKEN": CONFIG.csrfToken },
      body: formData,
      signal: activeController.signal,
    });

    const data = await res.json().catch(() => ({}));

    if (!res.ok || data.error) {
      showError("Gagal melakukan prediksi: " + (data.error || `HTTP ${res.status}`));
      return;
    }

    renderResult(data);
  } catch (err) {
    if (err.name === "AbortError") {
      showError("Permintaan memakan waktu terlalu lama. Silakan coba lagi.");
    } else {
      showError("Gagal terhubung ke server: " + err.message);
    }
  } finally {
    clearTimeout(timeoutId);
    setAnalyzeLoading(false);
  }
}

analyzeBtn.addEventListener("click", runPrediction);

// ---------------------------------------------------------------
// Rendering (DOM-safe, no innerHTML with untrusted data)
// ---------------------------------------------------------------
function buildChip(text) {
  const chip = document.createElement("div");
  chip.className = "chip";
  chip.textContent = text;
  return chip;
}

function renderChipList(containerId, items) {
  const container = el(containerId);
  clearNode(container);
  items.forEach((item) => container.appendChild(buildChip(item)));
}

function renderProbabilities(probabilities) {
  const probList = el("probList");
  clearNode(probList);

  (probabilities || []).slice(1).forEach((p) => {
    const info = resolveDiseaseInfo(p.label);

    const row = document.createElement("div");
    row.className = "prob-row";

    const label = document.createElement("div");
    label.className = "prob-label";
    label.textContent = info.name;

    const track = document.createElement("div");
    track.className = "prob-track";
    const fill = document.createElement("div");
    fill.className = "prob-fill";
    fill.style.width = `${Math.max(0, Math.min(100, p.confidence))}%`;
    track.appendChild(fill);

    const value = document.createElement("div");
    value.className = "prob-value";
    value.textContent = `${p.confidence.toFixed(1)}%`;

    row.append(label, track, value);
    probList.appendChild(row);
  });
}

function renderSources(sources) {
  const container = el("sourcesList");
  if (!container) return;
  clearNode(container);

  (sources || []).forEach((s) => {
    const item = document.createElement("a");
    item.className = "source-chip";
    item.href = s.url;
    item.target = "_blank";
    item.rel = "noopener noreferrer";
    item.textContent = s.name;
    container.appendChild(item);
  });
}

function renderUrgencyBadge(isUrgent) {
  const badge = el("urgencyBadge");
  if (!badge) return;
  if (isUrgent) {
    badge.textContent = "⚠ Disarankan konsultasi ke dokter spesialis kulit segera";
    badge.classList.remove("hidden");
    badge.classList.add("urgent");
  } else {
    badge.classList.add("hidden");
    badge.classList.remove("urgent");
  }
}

function renderResult(data) {
  const info = resolveDiseaseInfo(data.predicted_class);

  el("resultImg").src = previewImg.src;
  el("resultFileName").textContent = fileNameLabel.textContent;
  el("resultFileSize").textContent = fileSizeLabel.textContent;

  el("predictionName").textContent = info.name;
  el("confidenceValue").textContent = `${data.confidence.toFixed(1)}%`;
  el("confidenceBarFill").style.width = `${data.confidence}%`;

  renderProbabilities(data.probabilities);

  el("gradcamImg").src = "data:image/png;base64," + data.gradcam_base64;

  el("diseaseDescription").textContent = info.description;
  renderChipList("symptomChips", info.symptoms);
  renderChipList("causesChips", info.causes);
  renderChipList("treatmentChips", info.treatment);
  renderSources(info.sources);
  renderUrgencyBadge(info.urgent);

  uploadState.classList.add("hidden");
  infoPanel.classList.add("hidden");
  resultState.classList.remove("hidden");
  resultState.scrollIntoView({ behavior: "smooth", block: "start" });
}

// ---------------------------------------------------------------
// Print / export ringkasan
// ---------------------------------------------------------------
const printBtn = el("printResultBtn");
if (printBtn) {
  printBtn.addEventListener("click", () => window.print());
}
