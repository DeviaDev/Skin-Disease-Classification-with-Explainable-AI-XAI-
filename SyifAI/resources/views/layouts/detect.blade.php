<!DOCTYPE html>
<html lang="en">
<head>
    @include('layouts.header')
    <link rel="stylesheet" href="{{ asset('css/styles_detec.css') }}">
</head>
<body id="page-top">
        @include('layouts.navbar')

    <main>
        @yield('content')
    </main>
        @include('layouts.footer')
    </body>
</html>

