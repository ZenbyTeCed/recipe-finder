const chatBtn = document.getElementById('chatBubbleBtn');
const chatWindow = document.getElementById('chatWindow');
const chatCloseBtn = document.getElementById('chatCloseBtn');
const chatInput = document.querySelector('.chat-input');
const chatSendBtn = document.querySelector('.chat-send-btn');
const chatMessages = document.getElementById('chatMessages');
const chatQuickQuestions = document.getElementById('chatQuickQuestions');
const chatQuickClose = document.getElementById('chatQuickClose');

// Toggle open/close
chatBtn.addEventListener('click', () => {
    chatWindow.classList.toggle('open');
});

chatCloseBtn.addEventListener('click', () => {
    chatWindow.classList.remove('open');
});

chatQuickClose.addEventListener('click', () => {
    chatQuickQuestions.style.display = 'none';
});

// Send message
function getCurrentTime() {
    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function formatMessage(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g, '<a href="$2" class="chat-link">$1</a>')
        // Convert plain URLs that aren't already inside an <a> tag
        .replace(/(^|[\s\n])(https?:\/\/[^\s<]+)/g, '$1<a href="$2" class="chat-link">View Recipe →</a>')
        .replace(/\n/g, '<br>');
}

function appendMessage(text, type) {
    const msg = document.createElement('div');
    msg.classList.add('chat-message', type);
    const formatted = type === 'bot' ? formatMessage(text) : text;
    msg.innerHTML = `<p>${formatted}</p><span class="chat-time">${getCurrentTime()}</span>`;
    chatMessages.appendChild(msg);
    chatMessages.scrollTop = chatMessages.scrollHeight;
}

function appendTyping() {
    const typing = document.createElement('div');
    typing.classList.add('chat-message', 'bot', 'typing-indicator');
    typing.innerHTML = `<p>...</p>`;
    chatMessages.appendChild(typing);
    chatMessages.scrollTop = chatMessages.scrollHeight;
    return typing;
}

async function sendMessage(message) {
    if (!message.trim()) return;

    appendMessage(message, 'user');
    chatInput.value = '';

    const typing = appendTyping();

    const response = await fetch('/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        },
        body: JSON.stringify({ message }),
    });

    const data = await response.json();
    typing.remove();
    appendMessage(data.reply, 'bot');

    if (data.mealLogged) {
        window.showToast('Meal logged successfully!', 'success');
        setTimeout(() => location.reload(), 2000);
    }

    if (data.nameUpdated) {
        window.showToast('Name updated successfully!', 'success');
        setTimeout(() => location.reload(), 2000);
    }

    if (data.goalsUpdated) {
        window.showToast('Goals updated successfully!', 'success');
        setTimeout(() => location.reload(), 2000);
    }
}

// Send on button click
chatSendBtn.addEventListener('click', () => {
    sendMessage(chatInput.value);
});

// Send on Enter key
chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') {
        sendMessage(chatInput.value);
    }
});

// Quick question buttons
document.querySelectorAll('.chat-quick-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        sendMessage(btn.textContent.trim());
    });
});