const chatBtn = document.getElementById('chatBubbleBtn');
const chatWindow = document.getElementById('chatWindow');
const chatCloseBtn = document.getElementById('chatCloseBtn');
const chatInput = document.querySelector('.chat-input');
const chatSendBtn = document.querySelector('.chat-send-btn');
const chatMessages = document.getElementById('chatMessages');
const chatQuickQuestions = document.getElementById('chatQuickQuestions');
const chatQuickClose = document.getElementById('chatQuickClose');
const chatClearBtn = document.getElementById('chatClearBtn');

const STORAGE_KEY = 'nutribot_messages';

function getCurrentTime() {
    return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
}

function scrollToBottom() {
    if (chatMessages) {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
}

function hideQuickQuestions() {
    if (chatQuickQuestions) {
        chatQuickQuestions.style.display = 'none';
    }
}

function showQuickQuestionsIfDefault() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (!saved || JSON.parse(saved).length <= 1) {
        if (chatQuickQuestions) {
            chatQuickQuestions.style.display = 'block';
        }
    }
}

function getDefaultWelcomeMessage() {
    return `
        <div class="chat-message bot">
            <p>
                Hey there! 👋 I'm <strong>NutriBot</strong> 🍽️<br>
                Ask me about recipes, calories, or meal ideas — I got you! 💪
            </p>
            <span class="chat-time">${getCurrentTime()}</span>
        </div>
    `;
}

function saveMessages() {
    const messages = [];
    document.querySelectorAll('.chat-message:not(.typing-indicator)').forEach(msg => {
        messages.push({
            type: msg.classList.contains('bot') ? 'bot' : 'user',
            html: msg.querySelector('p').innerHTML,
            time: msg.querySelector('.chat-time')?.textContent ?? '',
        });
    });
    localStorage.setItem(STORAGE_KEY, JSON.stringify(messages));
}

function loadMessages() {
    const saved = localStorage.getItem(STORAGE_KEY);
    if (!saved) {
        scrollToBottom();
        return;
    }

    const messages = JSON.parse(saved);
    if (messages.length === 0) {
        scrollToBottom();
        return;
    }

    chatMessages.innerHTML = '';
    messages.forEach(msg => {
        const div = document.createElement('div');
        div.classList.add('chat-message', msg.type);
        div.innerHTML = `<p>${msg.html}</p><span class="chat-time">${msg.time}</span>`;
        chatMessages.appendChild(div);
    });

    if (messages.length > 1) {
        hideQuickQuestions();
    } else {
        showQuickQuestionsIfDefault();
    }

    scrollToBottom();
}

function formatMessage(text) {
    return text
        .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
        .replace(/\*(.*?)\*/g, '<em>$1</em>')
        .replace(/`(.*?)`/g, '<code>$1</code>')
        .replace(/\[([^\]]+)\]\((https?:\/\/[^\)]+)\)/g, '<a href="$2" class="chat-link">$1</a>')
        .replace(/(^|[\s\n])(https?:\/\/[^\s<]+)/g, '$1<a href="$2" class="chat-link">View Recipe →</a>')
        .replace(/\n/g, '<br>');
}

function appendMessage(text, type) {
    const msg = document.createElement('div');
    msg.classList.add('chat-message', type);
    const formatted = type === 'bot' ? formatMessage(text) : text;
    msg.innerHTML = `<p>${formatted}</p><span class="chat-time">${getCurrentTime()}</span>`;
    chatMessages.appendChild(msg);
    scrollToBottom();
    saveMessages();
}

function appendTyping() {
    const typing = document.createElement('div');
    typing.classList.add('chat-message', 'bot', 'typing-indicator');
    typing.innerHTML = `
        <p>
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
            <span class="typing-dot"></span>
        </p>
    `;
    chatMessages.appendChild(typing);
    scrollToBottom();
    return typing;
}

async function sendMessage(message) {
    if (!message.trim()) return;

    hideQuickQuestions();
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

chatBtn.addEventListener('click', () => {
    chatWindow.classList.toggle('open');
    chatBtn.classList.toggle('hidden');

    if (chatWindow.classList.contains('open')) {
        chatInput.focus();
        scrollToBottom();
    }
});

chatCloseBtn.addEventListener('click', () => {
    chatWindow.classList.remove('open');
    chatBtn.classList.remove('hidden');
});
chatQuickClose.addEventListener('click', () => chatQuickQuestions.style.display = 'none');

chatClearBtn.addEventListener('click', () => {
    localStorage.removeItem(STORAGE_KEY);
    chatMessages.innerHTML = getDefaultWelcomeMessage();
    showQuickQuestionsIfDefault();
    scrollToBottom();
    saveMessages();
});

chatSendBtn.addEventListener('click', () => sendMessage(chatInput.value));

chatInput.addEventListener('keypress', (e) => {
    if (e.key === 'Enter') sendMessage(chatInput.value);
});

document.querySelectorAll('.chat-quick-btn').forEach(btn => {
    btn.addEventListener('click', () => sendMessage(btn.textContent.trim()));
});

loadMessages();