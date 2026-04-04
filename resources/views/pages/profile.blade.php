@extends('layouts.app')

@section('content')

<div class="profile-page">
    <div class="profile-container">

        <div class="profile-header">
            <div>
                <h1>Profile Settings</h1>
                <p>Manage your account and nutritional goals</p>
            </div>
        </div>

        <form class="profile-form" action="/profile/update" method="POST">
            @csrf

            <div class="profile-card">
                <div class="profile-card-header">
                    <h4>Personal Information</h4>
                    <p>Update your account details</p>
                </div>
                <div class="profile-field">
                    <label for="fullname">Name</label>
                    <input id="fullname" name="fullname" type="text" value="{{ session('user_fullname') }}" placeholder="Your name">
                </div>
                <div class="profile-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" value="{{ session('user_email') }}" disabled>
                    <p class="profile-field-hint">Email cannot be changed</p>
                </div>
            </div>

            <div class="profile-card">
                <div class="profile-card-header">
                    <div class="profile-card-title">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-target-icon lucide-target"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></svg>
                        <h4>Nutritional Goals</h4>
                    </div>
                    <p>Set your daily macro targets</p>
                </div>
                <div class="profile-field">
                    <label for="calorie">Daily Calorie Goal</label>
                    <input id="calorie" name="calorie" type="number" placeholder="2000" value="{{ session('goals')['calories'] ?? 2000 }}">
                    <p class="profile-field-hint">Recommended: 1500-2500 calories</p>
                </div>
                <div class="profile-macros">
                    <div class="profile-field">
                        <label>Protein (grams)</label>
                        <input name="protein" type="number" placeholder="150" value="{{ session('goals')['protein'] ?? 150 }}">
                    </div>
                    <div class="profile-field">
                        <label>Carbs (grams)</label>
                        <input name="carbs" type="number" placeholder="200" value="{{ session('goals')['carbs'] ?? 200 }}">
                    </div>
                    <div class="profile-field">
                        <label>Fat (grams)</label>
                        <input name="fat" type="number" placeholder="65" value="{{ session('goals')['fat'] ?? 65 }}">
                    </div>
                </div>
                <div class="profile-macro-hint">
                    <p>💡 Your macro goals should add up to approximately your calorie goal:<br>
                    <span>(150 × 4) + (200 × 4) + (65 × 9) = 1985 calories</span></p>
                </div>
            </div>

            <div class="profile-actions">
                <a href="/home" class="profile-cancel-btn">Cancel</a>
                <button type="submit" class="profile-save-btn">Save Changes</button>
            </div>

        </form>

    </div>
</div>

@endsection