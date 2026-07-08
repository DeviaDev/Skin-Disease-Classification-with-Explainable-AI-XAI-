@extends('layouts.app')

@section('content')

<section class="masthead-diseases">

@php
$diseases = [

[
'title'=>'Acne and Rosacea',
'image'=>'acne.png',
'desc'=>'Gangguan kulit yang menyebabkan komedo, papula, pustula, dan kemerahan akibat peradangan folikel rambut dan kelenjar minyak.'
],

[
'title'=>'Actinic Keratosis & Basal Cell Carcinoma',
'image'=>'actinic.png',
'desc'=>'Kelainan kulit akibat paparan sinar matahari jangka panjang yang berpotensi berkembang menjadi kanker kulit.'
],

[
'title'=>'Atopic Dermatitis',
'image'=>'atopic.png',
'desc'=>'Peradangan kulit kronis yang menyebabkan kulit kering, gatal, dan kemerahan.'
],

[
'title'=>'Bullous Disease',
'image'=>'bullous.png',
'desc'=>'Kelompok penyakit yang ditandai dengan terbentuknya lepuhan (blister) pada kulit.'
],

[
'title'=>'Cellulitis & Impetigo',
'image'=>'cellulitis.png',
'desc'=>'Infeksi bakteri pada kulit yang menyebabkan kemerahan, nyeri, bengkak, dan terkadang bernanah.'
],

[
'title'=>'Exanthems & Drug Eruptions',
'image'=>'erythema.png',
'desc'=>'Ruam kulit akibat infeksi virus, bakteri, maupun reaksi alergi terhadap obat.'
],

[
'title'=>'Hair Loss',
'image'=>'hair.png',
'desc'=>'Kerontokan rambut yang dapat disebabkan faktor genetik, autoimun, hormon, atau infeksi.'
],

[
'title'=>'Herpes, HPV & STDs',
'image'=>'miliria.png',
'desc'=>'Infeksi virus yang ditandai lepuh, kutil, atau lesi pada kulit dan area genital.'
],

[
'title'=>'Light Diseases & Pigmentation',
'image'=>'vitiligo.png',
'desc'=>'Kelainan pigmen kulit seperti vitiligo yang menyebabkan bercak putih pada kulit.'
],

[
'title'=>'Lupus & Connective Tissue Diseases',
'image'=>'lupus.png',
'desc'=>'Penyakit autoimun yang dapat menyerang kulit dan jaringan ikat sehingga muncul ruam khas.'
],

[
'title'=>'Melanoma, Nevi & Moles',
'image'=>'actinic2.png',
'desc'=>'Kelainan tahi lalat hingga melanoma, yaitu kanker kulit paling agresif.'
],

[
'title'=>'Nail Fungus & Nail Disease',
'image'=>'nail.png',
'desc'=>'Infeksi jamur maupun gangguan lain pada kuku yang menyebabkan perubahan warna dan bentuk.'
],

[
'title'=>'Poison Ivy & Contact Dermatitis',
'image'=>'poison.png',
'desc'=>'Peradangan kulit akibat kontak dengan tanaman atau bahan yang memicu reaksi alergi.'
],

[
'title'=>'Psoriasis & Lichen Planus',
'image'=>'psoriasis.png',
'desc'=>'Penyakit autoimun kronis yang menyebabkan penebalan kulit disertai sisik putih keperakan.'
],

[
'title'=>'Scabies & Lyme Disease',
'image'=>'scabies.png',
'desc'=>'Infeksi akibat tungau maupun gigitan serangga yang menyebabkan rasa gatal hebat.'
],

[
'title'=>'Seborrheic Keratosis',
'image'=>'seborrheic.png',
'desc'=>'Pertumbuhan kulit jinak yang sering muncul pada usia lanjut.'
],

[
'title'=>'Systemic Disease',
'image'=>'systemic.png',
'desc'=>'Manifestasi penyakit sistemik yang menimbulkan perubahan atau ruam pada kulit.'
],

[
'title'=>'Tinea, Ringworm & Fungal Infection',
'image'=>'tinea.png',
'desc'=>'Infeksi jamur pada kulit yang menyebabkan bercak melingkar, bersisik, dan gatal.'
],

[
'title'=>'Urticaria (Hives)',
'image'=>'utticia.png',
'desc'=>'Biduran berupa bentol merah yang muncul akibat reaksi alergi atau pemicu lainnya.'
],

[
'title'=>'Vascular Tumors',
'image'=>'vascular.png',
'desc'=>'Kelainan pembuluh darah seperti hemangioma yang tampak sebagai benjolan merah.'
],

[
'title'=>'Vasculitis',
'image'=>'vasculitis.png',
'desc'=>'Peradangan pembuluh darah yang menyebabkan ruam merah atau ungu pada kulit.'
],

[
'title'=>'Warts & Molluscum',
'image'=>'warts_molluscum.png',
'desc'=>'Infeksi virus yang menyebabkan kutil atau benjolan kecil pada kulit.'
],

[
'title'=>'Folliculitis',
'image'=>'folliculitis.png',
'desc'=>'Peradangan folikel rambut yang ditandai munculnya bintik merah atau pustula kecil.'
]

];
@endphp

<section class="py-5 bg-light" id="diseases">

<div class="container">
<a href="{{ route('home') }}" class="back-to-detection">← Kembali ke home</a>
<div class="text-center mb-5">
<h2 class="fw-bold">Jenis Penyakit Kulit</h2>

<p class="text-muted">
Beberapa jenis penyakit kulit yang dapat dikenali oleh sistem.
</p>

</div>

<div class="row g-4">

@foreach($diseases as $item)

<div class="col-md-6 col-lg-4">

<div class="card h-100 shadow-sm border-0">

<div class="card-body d-flex">

<img src="{{ asset('assets/img/diseases/'.$item['image']) }}"
width="90"
height="90"
class="me-3 rounded"
style="object-fit:contain;"
alt="{{ $item['title'] }}">

<div>

<h5 class="fw-bold mb-2">
{{ $item['title'] }}
</h5>

<p class="text-muted small mb-0">
{{ $item['desc'] }}
</p>

</div>

</div>

</div>

</div>

@endforeach

</div>

</div>

</section>



</section>
@endsection