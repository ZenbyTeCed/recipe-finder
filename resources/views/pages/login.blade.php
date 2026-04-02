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
                <p class="lp-fc-icon">🔍</p>
                <h3>Recipe Search</h3>
                <p>Filter by ingredients, cuisine, and dietary needs</p>
            </div>

            <div class="lp-features-card">
                <p class="lp-fc-icon">📊</p>
                <h3>Nutrition Tracking</h3>
                <p>Detailed macros and calorie information</p>
            </div>
            
            <div class="lp-features-card">
                <p class="lp-fc-icon">🎯</p>
                <h3>Goal Dashboard</h3>
                <p>Track daily progress toward your targets</p>
            </div>

            <div class="lp-features-card">
                <p class="lp-fc-icon">📝</p>
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
                </div>

                <button type="submit">Login</button>
            </form>

            <div class="lp-lf-divider">
                <hr>
                <p>OR</p>
                <hr>
            </div>

            <button class="lp-login-with-google">
                Login with Google <img alt="google">
            </button>

            <div class="lp-login-create">
                <p>Don't have an account? <a href="/register">Create Account</a></p>
            </div>
        </div>
    </div>
</div>

@endsection