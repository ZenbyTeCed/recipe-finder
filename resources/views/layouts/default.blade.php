<!DOCTYPE html>
<html lang="en">
<head>
    <title>@yield('title')</title>
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