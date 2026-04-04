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

        <div class="profile-card">
            <div class="profile-card-header">
                <h4>Personal Information</h4>
                <p>Update your account details</p>
            </div>
            <div class="profile-field">
                <label for="fullname">Name</label>
                <input id="fullname" type="text" value="{{ session('user_fullname') }}" placeholder="Your name">
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
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-dot"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="1"/></svg>
                    <h4>Nutritional Goals</h4>
                </div>
                <p>Set your daily macro targets</p>
            </div>
            <div class="profile-field">
                <label for="calorie">Daily Calorie Goal</label>
                <input id="calorie" type="number" placeholder="2000">
                <p class="profile-field-hint">Recommended: 1500-2500 calories</p>
            </div>
            <div class="profile-macros">
                <div class="profile-field">
                    <label>Protein (grams)</label>
                    <input type="number" placeholder="150">
                </div>
                <div class="profile-field">
                    <label>Carbs (grams)</label>
                    <input type="number" placeholder="200">
                </div>
                <div class="profile-field">
                    <label>Fat (grams)</label>
                    <input type="number" placeholder="65">
                </div>
            </div>
            <div class="profile-macro-hint">
                <p>💡 Your macro goals should add up to approximately your calorie goal:<br>
                <span>(150 × 4) + (200 × 4) + (65 × 9) = 1985 calories</span></p>
            </div>
        </div>

        <div class="profile-actions">
            <button class="profile-cancel-btn">Cancel</button>
            <button class="profile-save-btn">Save Changes</button>
        </div>

    </div>
</div>

@endsection