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
    
    @vite('public/css/general.css')
    @vite('public/css/footer.css')
    @vite('public/css/header.css')
    @vite('public/css/homepage.css')
    @vite('public/css/dashboard.css')    
    @vite('public/css/meal-log.css')
    @vite('public/css/favorites.css')
    @vite('public/css/profile.css')
    @vite('public/css/recipe.css')
    @vite('public/css/chat-bubble.css')

</head>
<body class="wellcook-body">
    <header>
        @include('partials.header')
    </header>

    <div id="top-loader"></div>

    <main>
        @yield('content')
    </main>

    <footer>
        @include('partials.footer')
    </footer>

    <!-- Chat Bubble Button -->
    <button class="chat-bubble-btn" id="chatBubbleBtn">
       <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-bot-icon lucide-bot"><path d="M12 8V4H8"/><rect width="16" height="12" x="4" y="8" rx="2"/><path d="M2 14h2"/><path d="M20 14h2"/><path d="M15 13v2"/><path d="M9 13v2"/></svg>
    </button>

    <!-- Chat Window -->
    <div class="chat-window" id="chatWindow">
        <div class="chat-header-wrapper">
            <div class="chat-header">
                <div class="chat-header-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                </div>
                <div class="chat-header-info">
                    <h4>NutriBot Assistant</h4>
                    <p>Powered by Gemini</p>
                </div>
                <button class="chat-close-btn" id="chatCloseBtn">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
                
            </div>
            <div class="chat-header-clear">
                <button class="chat-clear-btn" id="chatClearBtn">Clear</button>
            </div>
        </div>

        <div class="chat-messages" id="chatMessages">
            <div class="chat-message bot">
                <p>Hi! I'm NutriBot your Recipe & Nutrition AI Assistant! 🔍 I can help you with recipe suggestions, cooking tips, nutrition advice, and meal planning. What would you like to know?</p>
                <span class="chat-time">12:53 PM</span>
            </div>
        </div>

        <div class="chat-quick-questions" id="chatQuickQuestions">
            <div class="chat-quick-header">
                <p>Quick questions:</p>
                <button class="chat-quick-close" id="chatQuickClose">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </button>
            </div>
            <div class="chat-quick-btns">
                <button class="chat-quick-btn">🔍 Suggest a healthy breakfast</button>
                <button class="chat-quick-btn">🌮 Low-calorie lunch ideas</button>
                <button class="chat-quick-btn">💪 High-protein recipes</button>
                <button class="chat-quick-btn">📊 How to track macros?</button>
            </div>
        </div>

        <div class="chat-input-area">
            <input type="text" class="chat-input" placeholder="Ask me anything...">
            <button class="chat-send-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
            </button>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="toast" id="toast">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-check-icon lucide-circle-check"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
        <span id="toastMessage"></span>
    </div>

    <!-- Goal Achieved Modal -->
    <div class="goal-modal-overlay" id="goalModalOverlay">
        <div class="goal-modal">
            <div class="goal-modal-icon">🎉</div>
            <h2>All Goals Achieved!</h2>
            <p class="goal-modal-sub">Congratulations, <strong>{{ session('user_fullname') }}</strong>!</p>
            <p class="goal-modal-msg">You've crushed all your nutritional goals for today! Your dedication to a healthy lifestyle is truly inspiring. Rest well and come back tomorrow to keep the streak going! 💪</p>
            <button class="goal-modal-btn" id="goalModalClose">Let's Go! 🚀</button>
        </div>
    </div>

    @vite('resources/js/ai.js')
    @vite('resources/js/toast.js')
    <!-- @vite('resources/js/recipe.js') -->
    @stack('scripts')

    <script>
    const loader = document.getElementById("top-loader");
    let isLoading = false;
    let progressInterval;

    // start loader
    function startLoader() {
    if (isLoading) return;

    isLoading = true;
    loader.style.opacity = "1";
    loader.style.width = "10%";

    let width = 10;

    // fake smooth progress
    progressInterval = setInterval(() => {
        if (width < 90) {
        width += Math.random() * 5; // random growth
        loader.style.width = width + "%";
        }
    }, 200);
    }

    // finish loader
    function finishLoader() {
    if (!isLoading) return;

    clearInterval(progressInterval);

    setTimeout(() => {
    loader.style.width = "100%";
    }, 200);

    // WAIT so user can actually SEE it finish
    setTimeout(() => {
        loader.style.opacity = "0";
    }, 200); // delay before fade

    setTimeout(() => {
        loader.style.width = "0%";
        isLoading = false;
    }, 700);
    }
    </script>

    </script>

    <script>
        document.querySelectorAll("a[href]").forEach(link => {
        link.addEventListener("click", function (e) {
            const url = this.getAttribute("href");

            if (
            !url ||
            url.startsWith("#") ||
            url.startsWith("javascript") ||
            this.target === "_blank"
            ) return;

            e.preventDefault();

            startLoader();

            setTimeout(() => {
            window.location.href = url;
            }, 300);
        });
        });
    </script>

    <script>
    window.addEventListener("DOMContentLoaded", () => {
    const loader = document.getElementById("top-loader");

    loader.style.opacity = "1";
    loader.style.width = "85%";

    // smoothly finish
    setTimeout(() => {
        loader.style.width = "100%";
    }, 100);

    setTimeout(() => {
        loader.style.opacity = "0";
    }, 300);

    setTimeout(() => {
        loader.style.width = "0%";
    }, 600);
    });
    </script>
</body>
</html>