
@php
    $isAuthenticated = session()->has('firebase_uid');
@endphp

<nav class="wellcook-header">
    <div class="wellcook-header-inner">
        <div>
            <a href="/home" class="logo">
                <img src="{{ asset('images/WellCook-logo2.png') }}" alt="WellCook Logo">
                <span>WellCook</span>
            </a>
        </div>
        <button type="button" class="mobile-menu-toggle" id="mobileMenuToggle">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="4" x2="20" y1="12" y2="12"/>
                <line x1="4" x2="20" y1="6" y2="6"/>
                <line x1="4" x2="20" y1="18" y2="18"/>
            </svg>
        </button>
        <div class="nav-links" id="mobileNav">
            <div class="mobile-nav-top">
                <div class="mobile-brand">
                    <img src="{{ asset('images/WellCook-logo2.png') }}" alt="WellCook Logo">
                    <span>WellCook</span>
                </div>

                <button type="button" class="mobile-nav-close" id="mobileNavClose">✕</button>
            </div>

            <a href="/home" class="{{ request()->is('home') || request()->is('recipe*') ? 'active' : '' }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                Home
            </a>

            @if ($isAuthenticated)
                <a href="/dashboard" class="{{ request()->is('dashboard') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect width="7" height="9" x="3" y="3" rx="1"/><rect width="7" height="5" x="14" y="3" rx="1"/><rect width="7" height="9" x="14" y="12" rx="1"/><rect width="7" height="5" x="3" y="16" rx="1"/></svg>
                    Dashboard
                </a>

                <a href="/meal-log" class="{{ request()->is('meal-log') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 7v14"/><path d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z"/></svg>
                    Meal Log
                </a>

                <a href="/favorites" class="{{ request()->is('favorites') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 9.5a5.5 5.5 0 0 1 9.591-3.676.56.56 0 0 0 .818 0A5.49 5.49 0 0 1 22 9.5c0 2.29-1.5 4-3 5.5l-5.492 5.313a2 2 0 0 1-3 .019L5 15c-1.5-1.5-3-3.2-3-5.5"/></svg>
                    Favorites
                </a>

                <div class="user-wrapper">
                    <div class="user-actions-divider"></div>

                    <div class="user-actions">
                        <a class="user-btn" href="/profile">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span>{{ session('user_fullname') ?: 'Profile' }}</span>
                        </a>

                        <form action="{{ route('logout') }}" method="POST" class="logout-form">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/></svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="/login" class="{{ request()->is('login') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" x2="3" y1="12" y2="12"/></svg>
                    Login
                </a>

                <a href="/register" class="{{ request()->is('register') ? 'active' : '' }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><line x1="19" x2="19" y1="8" y2="14"/><line x1="22" x2="16" y1="11" y2="11"/></svg>
                    Register
                </a>
            @endif
        </div>
    </div>
</nav>

<div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('mobileMenuToggle');
    const navLinks = document.getElementById('mobileNav');
    const overlay = document.getElementById('mobileNavOverlay');
    const closeBtn = document.getElementById('mobileNavClose');

    const burgerIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="4" x2="20" y1="6" y2="6"/>
        <line x1="4" x2="20" y1="12" y2="12"/>
        <line x1="4" x2="20" y1="18" y2="18"/>
    </svg>`;

    const closeIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="18" y1="6" x2="6" y2="18"/>
        <line x1="6" y1="6" x2="18" y2="18"/>
    </svg>`;

    function openMenu() {
        navLinks.classList.add('open');
        overlay.classList.add('show');
        menuToggle.innerHTML = closeIcon;
    }

    function closeMenu() {
        navLinks.classList.remove('open');
        overlay.classList.remove('show');
        document.body.style.overflow = '';
        menuToggle.innerHTML = burgerIcon;
    }

    if (menuToggle) menuToggle.addEventListener('click', function (e) {
        e.stopPropagation();

        if (navLinks.classList.contains('open')) {
            closeMenu();
        } else {
            openMenu();
        }
    });

    if (overlay) overlay.addEventListener('click', closeMenu);
    if (closeBtn) closeBtn.addEventListener('click', closeMenu);

    document.addEventListener('click', function (e) {
        if (!navLinks || !menuToggle || !navLinks.classList.contains('open')) {
            return;
        }

        if (navLinks.contains(e.target) || menuToggle.contains(e.target)) {
            return;
        }

        closeMenu();
    });
});
</script>
