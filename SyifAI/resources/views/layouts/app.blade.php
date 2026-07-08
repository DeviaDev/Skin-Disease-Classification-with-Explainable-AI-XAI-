<!DOCTYPE html>
<html lang="en">
    <head>
      @include('layouts.header')
      @stack('styles')
    </head>
    <body id="page-top">
        @include('layouts.navbar')

    <main>
        @yield('content')
    </main>
        @include('layouts.footer')

        @stack('script')
    </body>
</html>
