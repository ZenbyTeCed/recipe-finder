<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
    @vite('public/css/general.css')
</head>
<body>
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