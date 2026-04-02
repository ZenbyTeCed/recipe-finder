@extends('layouts.default')

@section('content')

<div class="register-page">
    <div class="rp-section1">

        <div class="lp-logo">
            <img src="{{ asset('images/WellCook.png') }}" alt="WellCook Logo">
            <h1>WellCook</h1>
        </div>

        <div class="rp-description">
            <p>Start Your Healthy Journey Today</p>
            <p>
                Join thousands of users who are achieving their nutrition goals with RecipeFinder. Create your free account and get started in seconds.
            </p>
        </div>

        <div class="rp-features">
            <div class="rp-features-card">
                <div class="rp-features-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="rp-features-card-text">
                    <h3>Personalized Goals</h3>
                    <p>Set custom calorie and macro targets based on your fitness objectives</p>
                </div>
            </div>

            <div class="rp-features-card">
                <div class="rp-features-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="rp-features-card-text">
                    <h3>Recipe Discovery</h3>
                    <p>Browse curated recipes filtered by your dietary preferences</p>
                </div>
            </div>

            <div class="rp-features-card">
                <div class="rp-features-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="rp-features-card-text">
                    <h3>Progress Tracking</h3>
                    <p>Monitor your daily intake with beautiful visual dashboards</p>
                </div>
            </div>

            <div class="rp-features-card">
                <div class="rp-features-card-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="rp-features-card-text">
                    <h3>Meal History</h3>
                    <p>Keep a complete log of all your meals and favorites</p>
                </div>
            </div>
        </div>

    </div>

    <div class="rp-section2">
        <div class="rp-register-form">
            <h3>Create Account</h3>
            <p>Sign up to start tracking your nutrition goals</p>
            <form class="rp-register-form-fields">

                <label for="fullname">Full Name</label>
                <div class="rp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="rp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                    </svg>
                    <input type="text" id="fullname" name="fullname" placeholder="John Doe" required>
                </div>

                <label for="email">Email Address</label>
                <div class="rp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="rp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                    <input type="email" id="email" name="email" placeholder="you@example.com" required>
                </div>

                <label for="password">Password</label>
                <div class="rp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="rp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required>
                </div>

                <label for="confirm_password">Confirm Password</label>
                <div class="rp-input-wrapper">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="rp-input-icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                    </svg>
                    <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter your password" required>
                </div>

                <button type="submit">Create Account</button>
            </form>

            <div class="rp-register-create">
                <p>Already have an account? <a href="/login">Login</a></p>
            </div>
        </div>
    </div>
</div>

@endsection