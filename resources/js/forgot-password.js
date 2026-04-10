const forgotPasswordBtn = document.getElementById('forgotPasswordBtn');
const forgotPasswordOverlay = document.getElementById('forgotPasswordOverlay');
const forgotPasswordClose = document.getElementById('forgotPasswordClose');
const forgotPasswordForm = document.getElementById('forgotPasswordForm');
const forgotPasswordMessage = document.getElementById('forgotPasswordMessage');
const backToLoginBtn = document.getElementById('backToLoginBtn');

if (!forgotPasswordBtn) {
    console.error('forgotPasswordBtn element not found');
} else {
    // Open forgot password modal
    forgotPasswordBtn.addEventListener('click', (e) => {
        e.preventDefault();
        console.log('Forgot password clicked');
        if (forgotPasswordOverlay) {
            forgotPasswordOverlay.classList.add('open');
            document.body.style.overflow = 'hidden';
        } else {
            console.error('forgotPasswordOverlay element not found');
        }
    });
}

// Close forgot password modal
if (forgotPasswordClose) {
    forgotPasswordClose.addEventListener('click', () => {
        closeForgotPasswordModal();
    });
}

if (forgotPasswordOverlay) {
    forgotPasswordOverlay.addEventListener('click', (e) => {
        if (e.target === forgotPasswordOverlay) {
            closeForgotPasswordModal();
        }
    });
}

if (backToLoginBtn) {
    backToLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        closeForgotPasswordModal();
    });
}

function closeForgotPasswordModal() {
    forgotPasswordOverlay.classList.remove('open');
    document.body.style.overflow = 'auto';
    forgotPasswordForm.reset();
    forgotPasswordMessage.className = 'forgot-password-message';
    forgotPasswordMessage.textContent = '';
}

// Handle forgot password form submission
if (forgotPasswordForm) {
    forgotPasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const email = document.getElementById('forgotPasswordEmail').value.trim();
        const submitBtn = forgotPasswordForm.querySelector('.forgot-password-submit');

        if (!email) {
            showMessage('Please enter your email address', 'error');
            return;
        }

        submitBtn.disabled = true;
        submitBtn.textContent = 'Sending...';

        try {
            const response = await fetch('/forgot-password', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ email }),
            });

            const data = await response.json();

            if (data.success) {
                showMessage(data.message, 'success');
                setTimeout(() => {
                    closeForgotPasswordModal();
                }, 2000);
            } else {
                showMessage(data.message, 'error');
            }
        } catch (error) {
            console.error('Forgot password error:', error);
            showMessage('An error occurred. Please try again.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Send Reset Link';
        }
    });
} else {
    console.error('forgotPasswordForm element not found');
}

function showMessage(message, type) {
    forgotPasswordMessage.textContent = message;
    forgotPasswordMessage.className = 'forgot-password-message';
    forgotPasswordMessage.classList.add(type);
}