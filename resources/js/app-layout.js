const loader = document.getElementById('top-loader');
let isLoading = false;
let progressInterval;
const loaderNavigationKey = 'wellcook_loader_navigation';

function startLoader() {
    if (!loader || isLoading) {
        return;
    }

    isLoading = true;
    sessionStorage.setItem(loaderNavigationKey, '1');
    loader.style.opacity = '1';
    loader.style.width = '10%';

    let width = 10;
    progressInterval = window.setInterval(() => {
        if (width < 90) {
            width += Math.random() * 5;
            loader.style.width = `${width}%`;
        }
    }, 200);
}

function finishLoader() {
    if (!loader || !isLoading) {
        return;
    }

    window.clearInterval(progressInterval);

    window.setTimeout(() => {
        loader.style.width = '100%';
    }, 200);

    window.setTimeout(() => {
        loader.style.opacity = '0';
    }, 550);

    window.setTimeout(() => {
        loader.style.width = '0%';
        isLoading = false;
    }, 900);
}

document.querySelectorAll('a[href]').forEach((link) => {
    link.addEventListener('click', (event) => {
        const url = link.getAttribute('href');

        if (
            !url ||
            url.startsWith('#') ||
            url.startsWith('javascript') ||
            link.target === '_blank'
        ) {
            return;
        }

        event.preventDefault();
        startLoader();

        window.setTimeout(() => {
            window.location.href = url;
        }, 300);
    });
});

window.addEventListener('DOMContentLoaded', () => {
    if (!loader) {
        return;
    }

    const shouldFinishLoader = sessionStorage.getItem(loaderNavigationKey) === '1';

    if (!shouldFinishLoader) {
        loader.style.opacity = '0';
        loader.style.width = '0%';
        return;
    }

    sessionStorage.removeItem(loaderNavigationKey);
    loader.style.opacity = '1';
    loader.style.width = '82%';

    window.requestAnimationFrame(() => {
        window.requestAnimationFrame(() => {
            loader.style.width = '100%';
        });
    });

    window.setTimeout(() => {
        loader.style.opacity = '0';
    }, 450);

    window.setTimeout(() => {
        loader.style.width = '0%';
    }, 800);
});

window.startLoader = startLoader;
window.finishLoader = finishLoader;
