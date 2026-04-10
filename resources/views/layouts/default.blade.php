<!DOCTYPE html>
<html lang="en">
<head>
    <title>WellCook</title>
    <link rel="icon" type="image/png" href="{{ asset('images/WellCook.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @if (session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif

    @if (session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif

    @if ($errors->any())
        <meta name="flash-error" content="{{ $errors->first() }}">
    @endif

    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.8.0/firebase-auth-compat.js"></script>

    <script>
        window.firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
        };
    </script>

    @vite('public/css/login.css')
    @vite('public/css/register.css')
    @vite('public/css/general.css')

</head>
<body>

    <main class="login-register-content">
        @yield('content')
        <p class="login-register-footer">WellCook &copy; {{ date('Y') }}. All rights reserved.</p>
    </main>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <span id="toastMessage"></span>
    </div>

    @vite('resources/js/toast.js')
    @vite('resources/js/auth.js')
    @vite('resources/js/password-toggle.js')

    @stack('scripts')
</body>
</html>