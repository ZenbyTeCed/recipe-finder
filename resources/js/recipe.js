// Base nutrition values
const baseCalories = 450;
const baseProtein  = 35;
const baseCarbs    = 28;
const baseFat      = 22;

// Modal open/close
const logMealBtn     = document.getElementById('logMealBtn');
const logMealOverlay = document.getElementById('logMealOverlay');
const modalCloseBtn  = document.getElementById('modalCloseBtn');
const servingsInput  = document.getElementById('servings');

const favoriteForm = document.getElementById("favoriteForm");
const favoriteBtn = document.getElementById("favoriteBtn");

logMealBtn.addEventListener('click', () => {
    logMealOverlay.classList.add('open');
});

modalCloseBtn.addEventListener('click', () => {
    logMealOverlay.classList.remove('open');
});

logMealOverlay.addEventListener('click', (e) => {
    if (e.target === logMealOverlay) {
        logMealOverlay.classList.remove('open');
    }
});

// Update nutrition values when servings change
servingsInput.addEventListener('input', () => {
    const servings = parseFloat(servingsInput.value) || 1;

    document.getElementById('modalCalories').textContent = Math.round(baseCalories * servings);
    document.getElementById('modalProtein').textContent  = Math.round(baseProtein  * servings);
    document.getElementById('modalCarbs').textContent    = Math.round(baseCarbs    * servings);
    document.getElementById('modalFat').textContent      = Math.round(baseFat      * servings);

    document.getElementById('caloriesInput').value = Math.round(baseCalories * servings);
    document.getElementById('proteinInput').value  = Math.round(baseProtein  * servings);
    document.getElementById('carbsInput').value    = Math.round(baseCarbs    * servings);
    document.getElementById('fatInput').value      = Math.round(baseFat      * servings);
    document.getElementById('servingInput').value  = servings + ' serving' + (servings !== 1 ? 's' : '');
});

// Log meal form submit
document.getElementById('logMealForm').addEventListener('submit', async (e) => {
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
        logMealOverlay.classList.remove('open');
        showToast(data.message, 'success');

        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach((msg, index) => {
                setTimeout(() => {
                    showToast(msg, 'success');
                }, (index + 1) * 1500);
            });

            // Show big modal if all 4 goals are hit
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

if (favoriteForm && favoriteBtn) {
    favoriteForm.addEventListener("submit", async function (e) {
        e.preventDefault();

        const formData = new FormData(favoriteForm);
        const action = favoriteForm.getAttribute("action");
        const svg = favoriteBtn.querySelector("svg");
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

        try {
            favoriteBtn.disabled = true;

            const response = await fetch(action, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrf,
                    "Accept": "application/json",
                },
                body: formData,
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                throw new Error(data.message || "Something went wrong.");
            }

            if (data.favorited) {
                favoriteForm.setAttribute("action", "/favorites/remove");
                favoriteBtn.classList.add("active");
                favoriteBtn.setAttribute("title", "Remove from favorites");
                svg.setAttribute("fill", "currentColor");
            } else {
                favoriteForm.setAttribute("action", "/favorites/add");
                favoriteBtn.classList.remove("active");
                favoriteBtn.setAttribute("title", "Add to favorites");
                svg.setAttribute("fill", "none");
            }

            // if your toast.js has a global function, use it here
            if (window.showToast) {
                window.showToast(data.message, data.favorited ? "success" : "info");
            }
        } catch (error) {
            if (window.showToast) {
                window.showToast(error.message || "Favorite action failed.", "error");
            } else {
                console.error(error);
                alert(error.message || "Favorite action failed.");
            }
        } finally {
            favoriteBtn.disabled = false;
        }
    });
}

// Favorite form submit
// document.getElementById('favoriteForm').addEventListener('submit', async (e) => {
//     e.preventDefault();

//     const form = e.target;
//     const formData = new FormData(form);

//     const response = await fetch('/favorites/add', {
//         method: 'POST',
//         headers: {
//             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
//             'Accept': 'application/json',
//         },
//         body: formData,
//     });

//     const data = await response.json();

//     if (data.success) {
//         showToast(data.message, 'success');
//         document.getElementById('favoriteBtn').classList.add('favorited');
//     } else {
//         showToast(data.message, 'error');
//     }
// });