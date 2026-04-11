const summaryText = {
    'Today': 'Total nutrition for today',
    'This Week': 'Total nutrition for this week',
    'All Time': 'Total nutrition for all time',
};

const periodMap = {
    'Today': 'today',
    'This Week': 'week',
    'All Time': 'alltime',
};

// Format date to readable format (e.g., "April 10, 2026")
function formatDateReadable(dateString) {
    const date = new Date(dateString + 'T00:00:00');
    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
}

// Get formatted date range for the week
function getWeekRangeReadable() {
    const today = new Date();
    const startOfWeek = new Date(today);
    startOfWeek.setDate(today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1));
    
    const endOfWeek = new Date(startOfWeek);
    endOfWeek.setDate(startOfWeek.getDate() + 6);
    
    const startStr = startOfWeek.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
    const endStr = endOfWeek.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
    
    return `${startStr} - ${endStr}`;
}

const mlAddBtn           = document.getElementById('mlAddBtn');
const mlModalOverlay     = document.getElementById('mlModalOverlay');
const mlModalClose       = document.getElementById('mlModalClose');
const mlModalForm        = document.getElementById('mlModalForm');
const mlActionBar        = document.getElementById('mlActionBar');
const mlSelectBtn        = document.getElementById('mlSelectBtn');
const mlDeleteSelectedBtn = document.getElementById('mlDeleteSelectedBtn');
const mlCancelBtn        = document.getElementById('mlCancelBtn');
const mlSelectedCount    = document.getElementById('mlSelectedCount');

let isSelectionMode = false;
const selectedMeals = new Set();

// Toggle selection mode
function toggleSelectionMode() {
    isSelectionMode = !isSelectionMode;
    mlActionBar.style.display = isSelectionMode ? 'flex' : 'none';
    selectedMeals.clear();
    updateCheckboxesVisibility();
    updateSelectedCount();
}

function updateCheckboxesVisibility() {
    document.querySelectorAll('.ml-entry-checkbox').forEach(checkbox => {
        checkbox.style.display = isSelectionMode ? 'inline-block' : 'none';
        checkbox.checked = false;
    });
}

function updateSelectedCount() {
    mlSelectedCount.textContent = selectedMeals.size + ' selected';
}

// Add select mode button event listener (need to find it first)
document.addEventListener('DOMContentLoaded', () => {
    const selectModeBtn = document.querySelector('.ml-log-header + .ml-action-bar button:first-child') || 
                         document.createElement('button');
    
    // If button doesn't exist, we'll create it via the "Select All" button instead
    const existingSelectBtn = document.querySelector('button[id="mlSelectBtn"]');
    if (!existingSelectBtn && mlSelectBtn) {
        // Select mode triggered by first interaction
    }
});

// Checkbox selection
document.addEventListener('change', (e) => {
    if (e.target.classList.contains('ml-entry-checkbox')) {
        const mealEntry = e.target.closest('.ml-log-entry');
        const mealKey = mealEntry.getAttribute('data-meal-key');
        
        if (e.target.checked) {
            selectedMeals.add(mealKey);
        } else {
            selectedMeals.delete(mealKey);
        }
        
        updateSelectedCount();
    }
});

// Select All button
if (mlSelectBtn) {
    mlSelectBtn.addEventListener('click', () => {
        const checkboxes = document.querySelectorAll('.ml-entry-checkbox');
        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = !allChecked;
            const mealEntry = checkbox.closest('.ml-log-entry');
            const mealKey = mealEntry.getAttribute('data-meal-key');
            
            if (!allChecked) {
                selectedMeals.add(mealKey);
            } else {
                selectedMeals.delete(mealKey);
            }
        });
        
        mlSelectBtn.textContent = allChecked ? 'Select All' : 'Deselect All';
        updateSelectedCount();
    });
}

// Delete Selected button
if (mlDeleteSelectedBtn) {
    mlDeleteSelectedBtn.addEventListener('click', async () => {
        if (selectedMeals.size === 0) {
            showToast('Please select at least one meal to delete', 'error');
            return;
        }

        if (!confirm(`Delete ${selectedMeals.size} meal(s)?`)) {
            return;
        }

        const keys = Array.from(selectedMeals);

        try {
            const response = await fetch('/meal-log/delete-multiple', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ keys }),
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Error deleting meals: ' + error.message, 'error');
        }
    });
}

// Cancel selection mode
if (mlCancelBtn) {
    mlCancelBtn.addEventListener('click', () => {
        toggleSelectionMode();
    });
}

// Add event listener to header for toggling selection mode
// Listen for a button click outside of existing handlers
document.addEventListener('DOMContentLoaded', () => {
    // Create a trigger to enter selection mode - we'll use a modified approach
    // Let the user enter selection mode by clicking a button in the header
    const logHeaderDiv = document.querySelector('.ml-log-header');
    if (logHeaderDiv) {
        
        const selectionToggleBtn = document.createElement('button');
        selectionToggleBtn.className = 'ml-select-mode-toggle';
        selectionToggleBtn.textContent = 'Select';
        
        selectionToggleBtn.addEventListener('click', toggleSelectionMode);
        logHeaderDiv.appendChild(selectionToggleBtn);
    }
});

// Fetch and render meals function
async function fetchAndRenderMeals(period, date = null) {
    const entriesContainer = document.getElementById('mlLogEntries');
    
    try {
        // Show loading indicator
        entriesContainer.innerHTML = `
            <div class="ml-loading">
                <div class="ml-spinner"></div>
                <p>Loading meals...</p>
            </div>
        `;

        let url = `/meal-log/get-meals?period=${period}`;
        if (date) {
            url += `&date=${date}`;
        }

        const response = await fetch(url);
        const data = await response.json();

        if (!data.success) {
            showToast('Error fetching meals', 'error');
            entriesContainer.innerHTML = '<div class="ml-empty"><p>Failed to load meals. Please try again.</p></div>';
            return;
        }

        // Update summary cards
        document.querySelector('.ml-calories span').textContent = data.totals.calories;
        document.querySelector('.ml-protein span').textContent = data.totals.protein + 'g';
        document.querySelector('.ml-carbs span').textContent = data.totals.carbs + 'g';
        document.querySelector('.ml-fat span').textContent = data.totals.fat + 'g';

        // Update log total (in the log section header)
        document.getElementById('mlLogTotal').textContent = data.totals.calories + ' cal';

        // Update log entries
        entriesContainer.innerHTML = '';

        if (data.meals.length === 0) {
            entriesContainer.innerHTML = '<div class="ml-empty"><p>No meals logged for this period.</p></div>';
            return;
        }

        data.meals.forEach(meal => {
            const entryHTML = `
                <div class="ml-log-entry" data-meal-key="${meal.key}">
                    <input type="checkbox" class="ml-entry-checkbox" style="display: none; margin-right: 10px;">
                    <div class="ml-entry-info">
                        <h4>${meal.name ?? 'Unnamed'}</h4>
                        <p>${meal.serving ?? 'No Serving'}</p>
                        <div class="ml-entry-macros">
                            <div class="ml-entry-macro">
                                <p>Calories</p>
                                <span>${meal.calories}</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Protein</p>
                                <span>${meal.protein}g</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Carbs</p>
                                <span>${meal.carbs}g</span>
                            </div>
                            <div class="ml-entry-macro">
                                <p>Fat</p>
                                <span>${meal.fat}g</span>
                            </div>
                        </div>
                    </div>
                    <div class="ml-update-delete">
                        <button class="ml-update-btn meal-edit-btn" data-meal-key="${meal.key}" data-meal-name="${meal.name}" data-meal-serving="${meal.serving}" data-meal-type="${meal.meal_type ?? 'Lunch'}" data-meal-calories="${meal.calories}" data-meal-protein="${meal.protein}" data-meal-carbs="${meal.carbs}" data-meal-fat="${meal.fat}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-pencil-icon lucide-pencil"><path d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z"/><path d="m15 5 4 4"/></svg>
                        </button>
                        <form action="/meal-log/delete" method="POST" style="display:inline;">
                            <input type="hidden" name="_token" value="${document.querySelector('meta[name="csrf-token"]').content}">
                            <input type="hidden" name="key" value="${meal.key}">
                            <button type="submit" class="ml-delete-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            `;
            entriesContainer.innerHTML += entryHTML;
        });

        // Re-attach edit button event listeners
        attachEditButtonListeners();
    } catch (error) {
        showToast('Error fetching meals: ' + error.message, 'error');
        entriesContainer.innerHTML = '<div class="ml-empty"><p>Failed to load meals. Please try again.</p></div>';
    }
}

// Tab switching
document.querySelectorAll('.ml-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.ml-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const tabText = tab.textContent.trim();
        document.querySelector('.ml-summary-header p').textContent = summaryText[tabText];
        document.getElementById('mlLogTitle').textContent = tabText;

        const period = periodMap[tabText];
        // Clear date input when clicking tabs
        document.getElementById('mlDateInput').value = '';
        fetchAndRenderMeals(period);
    });
});

// Date input listener
document.getElementById('mlDateInput').addEventListener('change', (e) => {
    const selectedDate = e.target.value;
    if (selectedDate) {
        // Remove active state from tabs and use custom date
        document.querySelectorAll('.ml-tab').forEach(t => t.classList.remove('active'));
        document.querySelector('.ml-summary-header p').textContent = 'Total nutrition for ' + formatDateReadable(selectedDate);
        document.getElementById('mlLogTitle').textContent = formatDateReadable(selectedDate);
        
        // Fetch meals for the specific date
        fetchAndRenderMeals('today', selectedDate);
    }
});

mlAddBtn.addEventListener('click', () => {
    mlModalOverlay.style.display = 'flex';
    document.body.style.overflow = 'hidden';
    requestAnimationFrame(() => {
        mlModalOverlay.classList.add('open');
    });
});

mlModalClose.addEventListener('click', () => {
    mlModalOverlay.classList.remove('open');
    setTimeout(() => {
        mlModalOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 300);
});

mlModalOverlay.addEventListener('click', (e) => {
    if (e.target === mlModalOverlay) {
        mlModalOverlay.classList.remove('open');
        setTimeout(() => {
            mlModalOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
});

mlModalForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(mlModalForm);

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
        // Close modal with animation, then hide
        mlModalOverlay.classList.remove('open');
        setTimeout(() => {
            mlModalOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
        mlModalForm.reset();
        showToast(data.message, 'success');

        if (data.notifications && data.notifications.length > 0) {
            data.notifications.forEach((msg, index) => {
                setTimeout(() => {
                    showToast(msg, 'success');
                }, (index + 1) * 1500);
            });

            const totalDelay = data.notifications.length * 1500 + 1000;

            if (data.allGoalsHit) {
                // show goal modal after notifications; wait for user to click "Let's Go"
                setTimeout(() => {
                    window._shouldReloadOnGoalClose = true;
                    if (typeof showGoalModal === 'function') showGoalModal();
                }, totalDelay);
                // do NOT auto-reload — wait for user action
            } else {
                // reload after notifications finished
                setTimeout(() => location.reload(), totalDelay + 1000);
            }
        } else {
            // no notifications
            if (data.allGoalsHit) {
                // show goal modal immediately and wait for user
                window._shouldReloadOnGoalClose = true;
                if (typeof showGoalModal === 'function') showGoalModal();
            } else {
                // quick reload
                setTimeout(() => location.reload(), 1000);
            }
        }
    } else {
        showToast(data.message, 'error');
    }
});

document.getElementById('mlNutribotLink').addEventListener('click', () => {
    mlModalOverlay.classList.remove('open');
    document.body.style.overflow = 'auto';

    // Open chat window
    const chatWindow = document.getElementById('chatWindow');
    chatWindow.classList.add('open');

    // Pre-fill the meal name if already typed
    const mealName = document.getElementById('ml-name').value;
    const serving  = document.getElementById('ml-serving').value;

    const chatInput = document.querySelector('.chat-input');
    if (mealName) {
        chatInput.value = `What are the approximate macros (calories, protein, carbs, fat) for ${mealName}${serving ? ' (' + serving + ')' : ''}?`;
    } else {
        chatInput.value = 'What are the approximate macros for ';
        chatInput.focus();
    }
});

// Edit Meal Functionality
const mlEditModalOverlay = document.getElementById('mlEditModalOverlay');
const mlEditModalClose = document.getElementById('mlEditModalClose');
const mlEditModalForm = document.getElementById('mlEditModalForm');

function attachEditButtonListeners() {
    document.querySelectorAll('.meal-edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.getElementById('ml-edit-key').value = btn.dataset.mealKey;
            document.getElementById('ml-edit-name').value = btn.dataset.mealName;
            document.getElementById('ml-edit-serving').value = btn.dataset.mealServing;
            document.getElementById('ml-edit-mealtype').value = btn.dataset.mealType;
            document.getElementById('ml-edit-calories').value = btn.dataset.mealCalories;
            document.getElementById('ml-edit-protein').value = btn.dataset.mealProtein;
            document.getElementById('ml-edit-carbs').value = btn.dataset.mealCarbs;
            document.getElementById('ml-edit-fat').value = btn.dataset.mealFat;

            mlEditModalOverlay.style.display = 'flex';
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => {
                mlEditModalOverlay.classList.add('open');
            });
        });
    });
}

mlEditModalClose.addEventListener('click', () => {
    mlEditModalOverlay.classList.remove('open');
    setTimeout(() => {
        mlEditModalOverlay.style.display = 'none';
        document.body.style.overflow = 'auto';
    }, 300);
});

mlEditModalOverlay.addEventListener('click', (e) => {
    if (e.target === mlEditModalOverlay) {
        mlEditModalOverlay.classList.remove('open');
        setTimeout(() => {
            mlEditModalOverlay.style.display = 'none';
            document.body.style.overflow = 'auto';
        }, 300);
    }
});

mlEditModalForm.addEventListener('submit', async (e) => {
    e.preventDefault();

    const formData = new FormData(mlEditModalForm);

    const response = await fetch('/meal-log/update', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        },
        body: formData,
    });

    const data = await response.json();

    if (data.success) {
        mlEditModalOverlay.classList.remove('open');
        document.body.style.overflow = 'auto';
        mlEditModalForm.reset();
        showToast(data.message, 'success');
        setTimeout(() => location.reload(), 1000);
    } else {
        showToast(data.message, 'error');
    }
});

// Attach initial edit listeners
attachEditButtonListeners();