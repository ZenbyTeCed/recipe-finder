let debounceTimer;
document.getElementById('search-input').addEventListener('input', function () {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(function () {
        document.getElementById('search-form').submit();
    }, 600);
});

const reloadBtn = document.getElementById('reload-btn');

if (reloadBtn) {
    reloadBtn.addEventListener('click', function () {
        this.classList.add('spinning');
        setTimeout(function () {
            window.location.href = '/home';
        }, 100);
    });
}