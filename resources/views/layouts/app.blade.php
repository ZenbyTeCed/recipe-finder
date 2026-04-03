<!DOCTYPE html>
<html lang="en">
<head>
    <title>WellCook</title>
    <link rel="icon" type="image/png" href="{{ asset('images/WellCook.png') }}">
    @vite('public/css/general.css')
    @vite('public/css/footer.css')
    @vite('public/css/header.css')
    @vite('public/css/homepage.css')
</head>
<body class="wellcook-body">
    <header>
        @include('partials.header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('partials.footer')
    </footer>
</body>
</html>