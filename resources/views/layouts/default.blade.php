<!DOCTYPE html>
<html lang="en">
<head>
    <title>WellCook</title>
    <link rel="icon" type="image/png" href="{{ asset('images/WellCook.png') }}">
    @vite('public/css/login.css')
    @vite('public/css/register.css')
    @vite('public/css/general.css')
</head>
<body>

    <main class="login-register-content">
        @yield('content')
        <p class="login-register-footer">MMSU - College of Computing and Information Sciences</p>
    </main>
</body>
</html>