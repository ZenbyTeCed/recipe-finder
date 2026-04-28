let debounceTimer;
const searchInput = document.getElementById('search-input');
const searchForm = document.getElementById('search-form');
const categoryFilter = document.getElementById('category-filter');
const areaFilter = document.getElementById('area-filter');

if (searchInput && searchForm) {
    searchInput.addEventListener('input', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            searchForm.submit();
        }, 600);
    });
}

[categoryFilter, areaFilter].forEach((filter) => {
    filter?.addEventListener('change', function () {
        searchForm?.submit();
    });
});

document.querySelectorAll('.home-page .recipe-card[data-href]').forEach((card) => {
    card.addEventListener('click', () => {
        window.location.href = card.dataset.href;
    });

    card.addEventListener('keydown', (event) => {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            window.location.href = card.dataset.href;
        }
    });
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
