const chatBtn = document.getElementById('chatBubbleBtn');
const chatWindow = document.getElementById('chatWindow');
const chatCloseBtn = document.getElementById('chatCloseBtn');

chatBtn.addEventListener('click', () => {
    chatWindow.classList.toggle('open');
});

chatCloseBtn.addEventListener('click', () => {
    chatWindow.classList.remove('open');
});