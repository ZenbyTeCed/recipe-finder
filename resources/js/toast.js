function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    // set class (resets previous)
    toast.className = 'toast ' + type;

    const cleanMessage = message.replace(/<svg[\s\S]*?<\/svg>/gi, '');
    toastMessage.textContent = cleanMessage;

    // show and auto-hide
    toast.classList.remove('show');
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => toast.classList.remove('show'), 3000);
}

function initToast() {
    const successMsg = document.querySelector('meta[name="flash-success"]');
    const errorMsg = document.querySelector('meta[name="flash-error"]');

    if (successMsg) showToast(successMsg.content, 'success');
    if (errorMsg) showToast(errorMsg.content, 'error');
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initToast);
} else {
    initToast();
}

window.showToast = showToast;

// Goal modal
function showGoalModal() {
    const overlay = document.getElementById('goalModalOverlay');
    if (overlay) {
        overlay.classList.add('open');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.getElementById('goalModalClose');
    const overlay  = document.getElementById('goalModalOverlay');

    if (closeBtn) {
        closeBtn.addEventListener('click', () => {
            overlay.classList.remove('open');
            // If page should reload when user closes the goal modal, do so
            if (window._shouldReloadOnGoalClose) {
                setTimeout(() => location.reload(), 300);
            }
        });
    }

    if (overlay) {
        overlay.addEventListener('click', (e) => {
            if (e.target === overlay) {
                overlay.classList.remove('open');
            }
        });
    }
});

window.showGoalModal = showGoalModal;