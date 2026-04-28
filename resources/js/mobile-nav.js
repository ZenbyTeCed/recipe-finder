document.addEventListener('DOMContentLoaded', () => {
    const mobileBottomNav = document.querySelector('.mobile-bottom-nav');
    const mobileAccountSheet = document.getElementById('mobileAccountSheet');
    const mobileAccountToggle = document.getElementById('mobileAccountToggle');
    const scrollingElement = document.scrollingElement || document.documentElement || document.body;

    if (!mobileBottomNav) {
        return;
    }

    const getScrollTop = () => Math.max(
        window.pageYOffset || 0,
        document.documentElement.scrollTop || 0,
        document.body.scrollTop || 0,
        scrollingElement ? scrollingElement.scrollTop || 0 : 0,
    );

    let lastScrollTop = getScrollTop();
    let navHidden = false;
    let accumulatedDown = 0;
    let accumulatedUp = 0;
    let ticking = false;

    function setBottomNavVisible(isVisible) {
        navHidden = !isVisible;
        mobileBottomNav.style.transform = isVisible ? 'translateY(0)' : 'translateY(calc(100% + 20px))';
        mobileBottomNav.style.opacity = isVisible ? '1' : '0';

        if (!isVisible && mobileAccountSheet && mobileAccountToggle) {
            mobileAccountSheet.classList.remove('open');
            mobileAccountToggle.classList.remove('open');
            mobileAccountToggle.setAttribute('aria-expanded', 'false');
        }
    }

    function syncBottomNavWithScroll() {
        if (window.innerWidth > 1024) {
            if (navHidden) {
                setBottomNavVisible(true);
            }
            lastScrollTop = getScrollTop();
            accumulatedDown = 0;
            accumulatedUp = 0;
            ticking = false;
            return;
        }

        const currentScrollTop = Math.max(0, getScrollTop());
        const delta = currentScrollTop - lastScrollTop;
        const minDelta = 1;
        const hideDistance = 24;
        const showDistance = 12;

        if (currentScrollTop <= 24) {
            if (navHidden) {
                setBottomNavVisible(true);
            }
            accumulatedDown = 0;
            accumulatedUp = 0;
            lastScrollTop = currentScrollTop;
            ticking = false;
            return;
        }

        if (Math.abs(delta) < minDelta) {
            ticking = false;
            return;
        }

        if (delta > 0) {
            accumulatedDown += delta;
            accumulatedUp = 0;

            if (accumulatedDown >= hideDistance && !navHidden) {
                setBottomNavVisible(false);
                accumulatedDown = 0;
            }
        } else {
            accumulatedUp += Math.abs(delta);
            accumulatedDown = 0;

            if (accumulatedUp >= showDistance && navHidden) {
                setBottomNavVisible(true);
                accumulatedUp = 0;
            }
        }

        lastScrollTop = currentScrollTop;
        ticking = false;
    }

    function requestBottomNavSync() {
        if (ticking) {
            return;
        }

        ticking = true;
        window.requestAnimationFrame(syncBottomNavWithScroll);
    }

    function closeSheet() {
        if (!mobileAccountSheet || !mobileAccountToggle) {
            return;
        }

        mobileAccountSheet.classList.remove('open');
        mobileAccountToggle.classList.remove('open');
        mobileAccountToggle.setAttribute('aria-expanded', 'false');
    }

    function openSheet() {
        if (!mobileAccountSheet || !mobileAccountToggle) {
            return;
        }

        mobileAccountSheet.classList.add('open');
        mobileAccountToggle.classList.add('open');
        mobileAccountToggle.setAttribute('aria-expanded', 'true');
    }

    if (mobileAccountToggle && mobileAccountSheet) {
        mobileAccountToggle.addEventListener('click', (event) => {
            event.stopPropagation();

            if (mobileAccountSheet.classList.contains('open')) {
                closeSheet();
            } else {
                openSheet();
            }
        });

        document.addEventListener('click', (event) => {
            if (!mobileAccountSheet.classList.contains('open')) {
                return;
            }

            if (mobileAccountSheet.contains(event.target) || mobileAccountToggle.contains(event.target)) {
                return;
            }

            closeSheet();
        });
    }

    window.addEventListener('scroll', requestBottomNavSync, { passive: true });
    document.addEventListener('scroll', requestBottomNavSync, { passive: true });

    if (scrollingElement && scrollingElement !== document.documentElement && scrollingElement !== document.body) {
        scrollingElement.addEventListener('scroll', requestBottomNavSync, { passive: true });
    }

    document.addEventListener('touchmove', requestBottomNavSync, { passive: true });
    window.addEventListener('resize', () => {
        if (window.innerWidth > 1024) {
            closeSheet();
        }
        requestBottomNavSync();
    });

    setBottomNavVisible(true);
    syncBottomNavWithScroll();
});
