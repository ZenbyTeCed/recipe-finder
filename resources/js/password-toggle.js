document.addEventListener('DOMContentLoaded', function() {
    const passwordToggles = document.querySelectorAll('.lp-password-toggle, .rp-password-toggle');

    passwordToggles.forEach(toggle => {
        const targetId = toggle.getAttribute('data-target');
        const input = document.getElementById(targetId);

        if (!input) return;

        const eyeIcon = toggle.querySelector('.lucide-eye');
        const eyeOffIcon = toggle.querySelector('.lucide-eye-closed-icon');

        function updateIcon() {
            if (input.type === 'password') {
                eyeIcon.style.display = 'block';
                eyeOffIcon.style.display = 'none';
            } else {
                eyeIcon.style.display = 'none';
                eyeOffIcon.style.display = 'block';
            }
        }

        updateIcon();

        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            input.type = input.type === 'password' ? 'text' : 'password';
            updateIcon();
        });
    });
});