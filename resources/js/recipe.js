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

        // Show goal notifications with delay
        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach((msg, index) => {
                setTimeout(() => {
                    showToast(msg, 'success');
                }, (index + 1) * 1500);
            });
        }
    } else {
        showToast(data.message, 'error');
    }
});

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