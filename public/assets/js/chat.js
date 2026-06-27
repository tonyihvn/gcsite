/* AI Chat Widget JavaScript */

let chatSessionId = localStorage.getItem('gintec_chat_session') || generateSessionId();
localStorage.setItem('gintec_chat_session', chatSessionId);

function generateSessionId() {
    return 'session_' + Date.now() + '_' + Math.random().toString(36).substr(2, 9);
}

function toggleChat() {
    const chatBody = document.getElementById('chat-body');
    if (chatBody.style.display === 'none') {
        chatBody.style.display = 'block';
    } else {
        chatBody.style.display = 'none';
    }
}

function sendMessage() {
    const userInput = document.getElementById('user-input');
    const message = userInput.value.trim();

    if (!message) return;

    // Add user message to chat
    addMessageToChat('user', message);
    userInput.value = '';

    // Send to server
    fetch('/api/chat', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            message: message,
            session_id: chatSessionId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.response) {
            addMessageToChat('assistant', data.response);
        }
    })
    .catch(error => console.error('Chat error:', error));
}

function addMessageToChat(role, content) {
    const messagesDiv = document.getElementById('messages');
    const messageDiv = document.createElement('div');
    messageDiv.className = 'message ' + role;
    messageDiv.textContent = content;
    messagesDiv.appendChild(messageDiv);
    messagesDiv.scrollTop = messagesDiv.scrollHeight;
}

// Allow sending message with Enter key
document.addEventListener('DOMContentLoaded', function() {
    const userInput = document.getElementById('user-input');
    if (userInput) {
        userInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    }
});
