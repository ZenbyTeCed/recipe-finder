<!DOCTYPE html>
<html lang="en">
<head>
    <title>WellCook</title>
    <link rel="icon" type="image/png" href="{{ asset('images/WellCook.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>
    @vite('public/css/login.css')
    @vite('public/css/register.css')
    @vite('public/css/general.css')

    @vite('resources/js/auth.js')

    <script>
        window.firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        };
    </script>
    
</head>
<body>

    <main class="login-register-content">
        @yield('content')
        <p class="login-register-footer">MMSU - College of Computing and Information Sciences</p>
    </main>
</body>
</html>