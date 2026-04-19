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

        <form id="profileUpdateForm" class="profile-form" action="/profile/update" method="POST">
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
        </form>

        <div class="profile-card">
            <div class="profile-card-header">
                <h4>Manage Account</h4>
                <p>Permanently remove your account and data</p>
            </div>

            <div class="profile-delete-section">
                <div class="profile-delete-text">
                    <p>Deleting your account will permanently remove your profile, goals, and saved data.</p>
                    <span>This action cannot be undone.</span>
                </div>

                <button type="button" class="profile-delete-btn" id="openDeleteModal">
                    Delete Account
                </button>
            </div>
        </div>

        <div class="profile-actions">
            <a href="/home" class="profile-cancel-btn">Cancel</a>
            <button type="submit" form="profileUpdateForm" class="profile-save-btn">Save Changes</button>
        </div>

    </div>
</div>

<div class="delete-modal-overlay" id="deleteModalOverlay">
    <div class="delete-modal">

        <div class="delete-modal-header">
            <div>
                <h3>Delete Account</h3>
                <p>This action cannot be undone</p>
            </div>

            <button type="button" class="delete-modal-close" id="deleteModalClose">
                ✕
            </button>
        </div>

        <div class="delete-modal-body">
            <p>Are you sure you want to delete your account?</p>
            <span>Your profile, goals, and all saved data will be permanently removed.</span>
        </div>

        <div class="delete-modal-actions">
            <button type="button" class="delete-cancel-btn" id="deleteCancelBtn">
                Cancel
            </button>

            <form action="/profile/delete" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="delete-confirm-btn">
                    Delete Account
                </button>
            </form>
        </div>

    </div>
</div>

<script nonce="{{ request()->header('X-CSP-Nonce') }}">
document.addEventListener('DOMContentLoaded', function () {
    const deleteModalOverlay = document.getElementById('deleteModalOverlay');
    const openDeleteModal = document.getElementById('openDeleteModal');
    const deleteModalClose = document.getElementById('deleteModalClose');
    const deleteCancelBtn = document.getElementById('deleteCancelBtn');
    let deleteModalScrollY = 0;

    function openModal() {
        deleteModalScrollY = window.scrollY;
        deleteModalOverlay.classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        deleteModalOverlay.classList.remove('open');
        document.body.style.overflow = '';
        window.scrollTo(0, deleteModalScrollY);
    }

    openDeleteModal.addEventListener('click', function () {
        openModal();
    });

    deleteModalClose.addEventListener('click', function () {
        closeModal();
    });

    deleteCancelBtn.addEventListener('click', function () {
        closeModal();
    });

    deleteModalOverlay.addEventListener('click', function (e) {
        if (e.target === deleteModalOverlay) {
            closeModal();
        }
    });
});
</script>


@endsection
