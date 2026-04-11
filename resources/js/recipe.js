// Base nutrition values
const baseCalories = Number(document.getElementById('caloriesInput')?.value || 0);
const baseProtein  = Number(document.getElementById('proteinInput')?.value || 0);
const baseCarbs    = Number(document.getElementById('carbsInput')?.value || 0);
const baseFat      = Number(document.getElementById('fatInput')?.value || 0);

// Main modal elements
const logMealBtn = document.getElementById('logMealBtn');
const logMealOverlay = document.getElementById('logMealOverlay');
const modalCloseBtn = document.getElementById('modalCloseBtn');
const servingsInput = document.getElementById('servings');

// Manual modal elements
const rdMealLogModal = document.getElementById('rdMealLogModalOverlay');
const rdMealLogModalClose = document.getElementById('rdMealLogModalClose');
const rdNutribotLink = document.getElementById('rdNutribotLink');
const rdMealLogModalForm = document.getElementById('rdMealLogModalForm');
const mealNameElement = document.getElementById('rd-meal-name');

// Favorite
const favoriteForm = document.getElementById('favoriteForm');
const favoriteBtn = document.getElementById('favoriteBtn');

function openModal(modal) {
    if (!modal) return;
    modal.classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal(modal) {
    if (!modal) return;
    modal.classList.remove('open');
    document.body.style.overflow = 'auto';
}

// Open the correct modal based on nutrition availability
if (logMealBtn) {
    logMealBtn.addEventListener('click', () => {
        const hasRealNutrition = logMealBtn.dataset.hasRealNutrition === '1';

        if (hasRealNutrition) {
            openModal(logMealOverlay);
        } else {
            openModal(rdMealLogModal);
        }
    });
}

// Close normal modal
if (modalCloseBtn) {
    modalCloseBtn.addEventListener('click', () => closeModal(logMealOverlay));
}

if (logMealOverlay) {
    logMealOverlay.addEventListener('click', (e) => {
        if (e.target === logMealOverlay) {
            closeModal(logMealOverlay);
        }
    });
}

// Close manual modal
if (rdMealLogModalClose) {
    rdMealLogModalClose.addEventListener('click', () => closeModal(rdMealLogModal));
}

if (rdMealLogModal) {
    rdMealLogModal.addEventListener('click', (e) => {
        if (e.target === rdMealLogModal) {
            closeModal(rdMealLogModal);
        }
    });
}

// NutriBot link
if (rdNutribotLink) {
    rdNutribotLink.addEventListener('click', () => {
        // Close manual modal
        closeModal(rdMealLogModal);

        // Open NutriBot chat
        const chatWindow = document.getElementById('chatWindow');
        if (chatWindow) {
            chatWindow.classList.add('open');
        }

        // Pre-fill message using recipe name
        const chatInput = document.querySelector('.chat-input');
        const mealName = mealNameElement?.value || 'this meal';

        if (chatInput) {
            chatInput.value = `What are the approximate macros (calories, protein, carbs, fat) for ${mealName}?`;
            chatInput.focus();
        }
    });
}

// Update nutrition values when servings change
if (servingsInput) {
    servingsInput.addEventListener('input', () => {
        const servings = parseFloat(servingsInput.value) || 1;

        document.getElementById('modalCalories').textContent = Math.round(baseCalories * servings);
        document.getElementById('modalProtein').textContent  = Math.round(baseProtein * servings);
        document.getElementById('modalCarbs').textContent    = Math.round(baseCarbs * servings);
        document.getElementById('modalFat').textContent      = Math.round(baseFat * servings);

        document.getElementById('caloriesInput').value = Math.round(baseCalories * servings);
        document.getElementById('proteinInput').value  = Math.round(baseProtein * servings);
        document.getElementById('carbsInput').value    = Math.round(baseCarbs * servings);
        document.getElementById('fatInput').value      = Math.round(baseFat * servings);
        document.getElementById('servingInput').value  = servings + ' serving' + (servings !== 1 ? 's' : '');
    });
}

// Log meal form submit
const logMealForm = document.getElementById('logMealForm');
if (logMealForm) {
    logMealForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        const response = await fetch('/meal-log/store', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: formData,
        });

        const data = await response.json();

        if (data.success) {
            closeModal(logMealOverlay);
            showToast(data.message, 'success');

            if (data.notifications && data.notifications.length > 0) {
                data.notifications.forEach((msg, index) => {
                    setTimeout(() => {
                        showToast(msg, 'success');
                    }, (index + 1) * 1500);
                });

                if (data.notifications.length >= 4) {
                    setTimeout(() => {
                        showGoalModal();
                    }, data.notifications.length * 1500 + 1000);
                }
            }
        } else {
            showToast(data.message, 'error');
        }
    });
}

if (rdMealLogModalForm) {
    rdMealLogModalForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(rdMealLogModalForm);

        try {
            const response = await fetch('/meal-log/store', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (data.success) {
                closeModal(rdMealLogModal);
                rdMealLogModalForm.reset();
                showToast(data.message, 'success');

                if (data.notifications && data.notifications.length > 0) {
                    data.notifications.forEach((msg, index) => {
                        setTimeout(() => {
                            showToast(msg, 'success');
                        }, (index + 1) * 1500);
                    });

                    if (data.allGoalsHit) {
                        setTimeout(() => {
                            showGoalModal();
                        }, data.notifications.length * 1500 + 1000);
                    }
                }
            } else {
                showToast(data.message || 'Failed to log meal.', 'error');
            }
        } catch (error) {
            showToast('Error logging meal: ' + error.message, 'error');
        }
    });
}

// Favorite submit
if (favoriteForm && favoriteBtn) {
    favoriteForm.addEventListener('submit', async function (e) {
        e.preventDefault();

        const formData = new FormData(favoriteForm);
        const action = favoriteForm.getAttribute('action');
        const svg = favoriteBtn.querySelector('svg');
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        try {
            favoriteBtn.disabled = true;

            const response = await fetch(action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'Accept': 'application/json',
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || 'Something went wrong.');
            }

            if (data.favorited) {
                favoriteForm.setAttribute('action', '/favorites/remove');
                favoriteBtn.classList.add('active');
                favoriteBtn.setAttribute('title', 'Remove from favorites');
                svg.setAttribute('fill', 'currentColor');
            } else {
                favoriteForm.setAttribute('action', '/favorites/add');
                favoriteBtn.classList.remove('active');
                favoriteBtn.setAttribute('title', 'Add to favorites');
                svg.setAttribute('fill', 'none');
            }

            if (window.showToast) {
                window.showToast(data.message, data.favorited ? 'success' : 'info');
            }
        } catch (error) {
            if (window.showToast) {
                window.showToast(error.message || 'Favorite action failed.', 'error');
            } else {
                console.error(error);
                alert(error.message || 'Favorite action failed.');
            }
        } finally {
            favoriteBtn.disabled = false;
        }
    });
}