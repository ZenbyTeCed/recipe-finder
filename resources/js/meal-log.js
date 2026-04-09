const summaryText = {
    'Today': 'Total nutrition for today',
    'This Week': 'Total nutrition for this week',
    'All Time': 'Total nutrition for all time',
};

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


document.querySelectorAll('.ml-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.ml-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const tabText = tab.textContent.trim();
        document.querySelector('.ml-summary-header p').textContent = summaryText[tabText];
    });
});

mlAddBtn.addEventListener('click', () => {
    mlModalOverlay.classList.add('open');
});

mlModalClose.addEventListener('click', () => {
    mlModalOverlay.classList.remove('open');
});

mlModalOverlay.addEventListener('click', (e) => {
    if (e.target === mlModalOverlay) {
        mlModalOverlay.classList.remove('open');
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
        mlModalOverlay.classList.remove('open');
        mlModalForm.reset();
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

        setTimeout(() => location.reload(), 1000);
    } else {
        showToast(data.message, 'error');
    }
});

document.getElementById('mlNutribotLink').addEventListener('click', () => {
    mlModalOverlay.classList.remove('open');

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

        mlEditModalOverlay.classList.add('open');
    });
});

mlEditModalClose.addEventListener('click', () => {
    mlEditModalOverlay.classList.remove('open');
});

mlEditModalOverlay.addEventListener('click', (e) => {
    if (e.target === mlEditModalOverlay) {
        mlEditModalOverlay.classList.remove('open');
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
        mlEditModalForm.reset();
        showToast(data.message, 'success');
        setTimeout(() => location.reload(), 1000);
    } else {
        showToast(data.message, 'error');
    }
});