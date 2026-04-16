@extends('layouts.default')

@section('content')

<div class="login-page">
    <div class="lp-section1">

        <div class="lp-logo">
            <img src="{{ asset('images/WellCook.png') }}" alt="WellCook Logo">
            <h1>WellCook</h1>
        </div>

        <div class="lp-description">
            <p>
                Discover Delicious Recipes with Complete Nutrition Info
            </p>
            <p>
                Search thousands of recipes, track your daily nutrition goals, and maintain a healthy lifestyle with our comprehensive meal planning platform.
            </p>
        </div>

<div class="lp-features">
    <div class="lp-features-card">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="url(#grad-recipe)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <defs>
                <linearGradient id="grad-recipe" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ea580c"/>
                    <stop offset="50%" stop-color="#e07b39"/>
                    <stop offset="100%" stop-color="#fb923c"/>
                </linearGradient>
            </defs>
            <path d="M11 22H5.5a1 1 0 0 1 0-5h4.501"/>
            <path d="m21 22-1.879-1.878"/>
            <path d="M3 19.5v-15A2.5 2.5 0 0 1 5.5 2H18a1 1 0 0 1 1 1v8"/>
            <circle cx="17" cy="18" r="3"/>
        </svg>
        <h3>Recipe Search</h3>
        <p>Find recipes by ingredients or cuisine</p>
    </div>

    <div class="lp-features-card">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
            fill="none" stroke="url(#grad-analytics)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
            <defs>
                <linearGradient id="grad-analytics" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ea580c"/>
                    <stop offset="50%" stop-color="#e07b39"/>
                    <stop offset="100%" stop-color="#fb923c"/>
                </linearGradient>
            </defs>
            <path d="M3 3v18h18"/>
            <rect x="7" y="10" width="3" height="6"/>
            <rect x="12" y="6" width="3" height="10"/>
            <rect x="17" y="13" width="3" height="3"/>
        </svg>
        <h3>Nutrition Tracking</h3>
        <p>Detailed macros and calorie information</p>
    </div>

    <div class="lp-features-card">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
            fill="none" stroke="url(#grad-goal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <defs>
                <linearGradient id="grad-goal" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ea580c"/>
                    <stop offset="50%" stop-color="#e07b39"/>
                    <stop offset="100%" stop-color="#fb923c"/>
                </linearGradient>
            </defs>
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="6"/>
            <circle cx="12" cy="12" r="2"/>
        </svg>
        <h3>Goal Dashboard</h3>
        <p>Track daily progress toward your targets</p>
    </div>

    <div class="lp-features-card">
        <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
            fill="none" stroke="url(#grad-meal)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <defs>
                <linearGradient id="grad-meal" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="#ea580c"/>
                    <stop offset="50%" stop-color="#e07b39"/>
                    <stop offset="100%" stop-color="#fb923c"/>
                </linearGradient>
            </defs>
            <rect x="8" y="2" width="8" height="4" rx="1"/>
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <path d="M9 12h6"/>
            <path d="M9 16h6"/>
        </svg>
        <h3>Meal Logging</h3>
        <p>Keep a history of everything you eat</p>
    </div>
</div>

    </div>

    <div class="lp-section2">
        <div class="lp-login-form">
            <h3>Welcome Back</h3>
            <p>Enter your credentials to access you account</p>
            <form class="lp-login-form-fields" action="/login" method="POST">
                @csrf
                <label for="email">Email Address</label>
                <div class="lp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <label for="password">Password</label>
                <div class="lp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="lp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <input type="password" id="password" name="password" placeholder="Enter your password" required>
                    <button type="button" class="lp-password-toggle" data-target="password">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide-eye">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-eye-closed-icon lucide-eye-closed"><path d="m15 18-.722-3.25"/><path d="M2 8a10.645 10.645 0 0 0 20 0"/><path d="m20 15-1.726-2.05"/><path d="m4 15 1.726-2.05"/><path d="m9 18 .722-3.25"/></svg>
                    </button>
                </div>
                <a href="#" class="lp-forgot" id="forgotPasswordBtn">Forgot Password?</a>

                <button type="submit">Login</button>
            </form>

            <div class="lp-lf-divider">
                <hr>
                <p>OR</p>
                <hr>
            </div>

            <button class="lp-login-with-google" id="google-login-btn" type="button">
                <svg width="20" height="20" viewBox="0 0 48 48" style="margin-right: 8px;">
                    <path fill="#FFC107" d="M43.6 20.5H42V20H24v8h11.3C33.7 32.1 29.3 35 24 35c-6.6 0-12-5.4-12-12s5.4-12 
                    12-12c3 0 5.7 1.1 7.8 2.9l5.7-5.7C34.1 5.5 29.3 3.5 24 3.5 12.4 3.5 3.5 12.4 3.5 
                    24S12.4 44.5 24 44.5 44.5 35.6 44.5 24c0-1.2-.1-2.4-.4-3.5z"/>
                    <path fill="#FF3D00" d="M6.3 14.7l6.6 4.8C14.7 16.1 19 13 24 13c3 0 5.7 1.1 
                    7.8 2.9l5.7-5.7C34.1 5.5 29.3 3.5 24 3.5c-7.7 0-14.3 4.4-17.7 10.8z"/>
                    <path fill="#4CAF50" d="M24 44.5c5.2 0 9.9-2 13.5-5.3l-6.2-5.1c-2 1.4-4.5 
                    2.4-7.3 2.4-5.3 0-9.8-3-11.5-7.4l-6.5 5C9.5 40.3 16.2 44.5 24 44.5z"/>
                    <path fill="#1976D2" d="M43.6 20.5H42V20H24v8h11.3c-1.1 3.1-3.4 
                    5.6-6.2 7.1l6.2 5.1c3.6-3.3 5.9-8.2 5.9-14.2 0-1.2-.1-2.4-.4-3.5z"/>
                </svg>
                Login with Google
            </button>

            <div class="lp-login-create">
                <p>Don't have an account? <a href="{{ route('register') }}">Create Account</a></p>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="forgot-password-overlay" id="forgotPasswordOverlay">
    <div class="forgot-password-modal">
        <div class="forgot-password-header">
            <h3>Reset Password</h3>
            <button class="forgot-password-close" id="forgotPasswordClose">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
            </button>
        </div>

        <div class="forgot-password-body">
            <p class="forgot-password-description">Enter your email address and we'll send you a link to reset your password.</p>
            
            <form id="forgotPasswordForm">
                <label for="forgotPasswordEmail">Email Address</label>
                <div class="forgot-password-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <input type="email" id="forgotPasswordEmail" name="email" placeholder="you@example.com" required>
                </div>
                <button type="submit" class="forgot-password-submit">Send Reset Link</button>
            </form>

            <div class="forgot-password-message" id="forgotPasswordMessage"></div>
        </div>

        <div class="forgot-password-footer">
            <p>Remember your password? <a href="#" id="backToLoginBtn">Back to Login</a></p>
        </div>
    </div>
</div>

@push('scripts')
    @vite('resources/js/forgot-password.js')
@endpush

@endsection