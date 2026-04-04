function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toastMessage');

    toastMessage.textContent = message;
    toast.className = 'toast ' + type;

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