const summaryText = {
    'Today': 'Total nutrition for today',
    'This Week': 'Total nutrition for this week',
    'All Time': 'Total nutrition for all time',
};

const mlAddBtn      = document.getElementById('mlAddBtn');
const mlModalOverlay = document.getElementById('mlModalOverlay');
const mlModalClose  = document.getElementById('mlModalClose');
const mlModalForm   = document.getElementById('mlModalForm');

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