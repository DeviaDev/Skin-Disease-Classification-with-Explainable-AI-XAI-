@extends('layouts.app')

@section('content')
{{-- Introduction --}}
        <div class="masthead">
            <div class="container px-5">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-6">
                        <!-- Mashead text and app badges-->
                        <div class="mb-5 mb-lg-0 text-center text-lg-start">
                            <h1 class="display-1 lh-1 mb-3">Skin Diseases Detection.</h1>
                            <p class="lead fw-normal text-muted mb-5">SyifAI membantu memberikan prediksi awal penyakit kulit melalui analisis gambar berbasis Artificial Intelligence (AI). Unggah gambar kulit Anda untuk memperoleh hasil prediksi yang dilengkapi visualisasi Grad-CAM, sehingga proses analisis model dapat dipahami dengan lebih mudah.</p>
                            <div class="d-flex flex-column align-items-center">
                                <a class="btn btn-primary btn-lg" href="{{ route('detection') }}">Try Now</a>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <!-- Masthead device mockup feature-->
                        <div class="masthead-device-mockup">  
                            <div class="device-wrapper">
                                <video muted="muted" autoplay="" loop="" style="max-width: 150%; height: 150%"><source src="assets/img/demo-screen1.mp4" type="video/mp4" /></video>
                                </div>
                            </div>
                    </div>

                    </div>
                </div>
            </div>
        </div>

{{-- Fitur Utama --}}
        <div id="features">
            <aside class="text-light bg-gradient">
            <div class="container ">
                <div class="row gx-5 align-items-center">
                    <div class="col-lg-8 order-lg-1 mb-5 mb-lg-0">
                        <div class="container-fluid px-5">
                            <div class="row gx-5">
                                
                                <div class="col-md-6 mb-5">
                                    <!-- Feature item-->
                                    <div class="text-center">
                                        <i class="bi bi-laptop icon-feature text-white d-block mb-3"></i>
                                        <h3 class="font-alt">Prediksi Penyakit Kulit</h3>
                                        <p class="text-white mb-0">Membantu memberikan prediksi awal penyakit kulit melalui analisis gambar berbasis Artificial Intelligence (AI).</p>
                                    </div>
                                </div>
                                
                                <div class="col-md-6 mb-5">
                                    <!-- Feature item-->
                                    <div class="text-center">
                                        <i class="bi bi-bar-chart-line icon-feature text-white d-block mb-3"></i>
                                        <h3 class="font-alt">Hasil Prediksi dan Persentase</h3>
                                        <p class="text-white mb-0">Menampilkan hasil prediksi beserta tingkat keyakinan/ Persentase (confidence score).</p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-5 mb-md-0">
                                    <!-- Feature item-->
                                    <div class="text-center">
                                        <i class="bi bi-image icon-feature text-white d-block mb-3"></i>
                                        <h3 class="font-alt">Visualisasi Grad-CAM</h3>
                                        <p class="text-white mb-0">Menampilkan area gambar yang menjadi fokus model selama proses analisis.</p>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Feature item-->
                                    <div class="text-center">
                                        <i class="bi bi-file-earmark-medical icon-feature text-white d-block mb-3"></i>
                                        <h3 class="font-alt">Informasi Penyakit</h3>
                                        <p class="text-white mb-0">Menyajikan informasi singkat mengenai penyakit kulit hasil prediksi.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 order-lg-0">
                        <!-- Masthead device mockup feature-->
                        <div class="masthead-device-mockup">  
                            <div class="device-wrapper">
                                <img src="assets/img/prediksi.png" alt="gambar1" style="max-width: 150%; height: 150% " />
                                {{-- <video muted="muted" autoplay="" loop="" style="max-width: 130%; height: 130%"><source src="assets/img/demo-screen1.mp4" type="video/mp4" /></video> --}}
                                </div>
                            </div>
                    </div>

                </div>
            </div>
            </aside>
        </div>

{{-- Penyakit yang dapat dideteksi --}}  
        <div class="py-5 bg-light" id="diseases">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="fw-bold">Jenis Penyakit Kulit</h2>
            <p class="text-muted">
                Beberapa jenis penyakit kulit yang dapat dikenali oleh sistem.
            </p>
        </div>

        <div class="row g-4 justify-content-center">

            <!-- Acne -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('assets/img/diseases/acne.png') }}"
                        class="card-img-top p-3"
                        style="height:180px; object-fit:contain;"
                        alt="Acne">

                    <div class="card-body text-center">
                        <h5 class="fw-bold">Acne & Rosacea</h5>

                        <p class="text-muted small">
                            Gangguan kulit yang menyebabkan komedo, jerawat,
                            dan peradangan pada folikel rambut.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Psoriasis -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('assets/img/diseases/psoriasis.png') }}"
                        class="card-img-top p-3"
                        style="height:180px; object-fit:contain;"
                        alt="Psoriasis">

                    <div class="card-body text-center">
                        <h5 class="fw-bold">Psoriasis</h5>

                        <p class="text-muted small">
                            Penyakit autoimun kronis yang menyebabkan kulit
                            menebal dan bersisik.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Warts -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <img src="{{ asset('assets/img/diseases/warts_molluscum.png') }}"
                        class="card-img-top p-3"
                        style="height:180px; object-fit:contain;"
                        alt="Warts">

                    <div class="card-body text-center">
                        <h5 class="fw-bold">
                            Warts & Molluscum
                        </h5>

                        <p class="text-muted small">
                            Infeksi virus yang menyebabkan munculnya
                            benjolan kecil atau kutil pada kulit.
                        </p>
                    </div>
                </div>
            </div>

        </div>

        <!-- Button -->
        <div class="text-center mt-5">
            <a href="{{ route('diseases') }}"
                class="btn btn-primary rounded-pill px-4 py-2">

                Lihat Semua Penyakit
                <i class="bi bi-arrow-right ms-2"></i>

            </a>
        </div>

    </div>
</div>

{{-- Disclaimer --}}
        <aside class="text-center bg-gradient">
            <div class="container px-5">
                <div class="row gx-5 justify-content-center">
                    <div class="col-xl-8">
                        <div class="h2 fs-1 text-white mb-4">⚠️ Catatan Penting</div>
                        <div class="h4 small fs-5 text-white mb-10">SyifAI membantu memberikan prediksi awal terhadap kemungkinan penyakit kulit berdasarkan analisis gambar menggunakan Artificial Intelligence (AI). Hasil prediksi hanya bersifat sebagai informasi awal dan tidak menggantikan diagnosis maupun konsultasi dengan dokter atau tenaga medis profesional.</div><br>
                        <img src="assets/img/brand-name.png" alt="SyifAI-logo" style="height: 3rem " />
                    </div>
                </div>
            </div>
        </aside>


@endsection