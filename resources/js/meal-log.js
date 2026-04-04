const summaryText = {
    'Today': 'Total nutrition for today',
    'This Week': 'Total nutrition for this week',
    'All Time': 'Total nutrition for all time',
};

document.querySelectorAll('.ml-tab').forEach(tab => {
    tab.addEventListener('click', () => {
        document.querySelectorAll('.ml-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');

        const tabText = tab.textContent.trim();
        document.querySelector('.ml-summary-header p').textContent = summaryText[tabText];
    });
});