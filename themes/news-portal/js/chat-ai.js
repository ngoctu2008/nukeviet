function toggleChatWindow() {
    var chatWindow = document.getElementById('chat-window');
    if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
        chatWindow.style.display = 'flex';
        // Focus input
        setTimeout(function() { document.getElementById('chat-input').focus(); }, 100);
        // Load history if empty (first open)
        // Not implemented in this basic version to save requests, relies on session persistence logic on server if needed
    } else {
        chatWindow.style.display = 'none';
    }
}

function handleEnter(e) {
    if (e.key === 'Enter') {
        sendMessage();
    }
}

function sendMessage() {
    var input = document.getElementById('chat-input');
    var message = input.value.trim();

    if (message === '') return;

    // Add User Message to UI
    appendMessage('user', message);
    input.value = '';

    // Show Typing Indicator
    var chatBody = document.getElementById('chat-messages');
    var typingDiv = document.createElement('div');
    typingDiv.className = 'typing';
    typingDiv.id = 'typing-indicator';
    typingDiv.innerText = 'Bot đang trả lời...';
    chatBody.appendChild(typingDiv);
    chatBody.scrollTop = chatBody.scrollHeight;

    // Send AJAX
    $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&nocache=' + new Date().getTime(), {
        message: message
    }, function(res) {
        // Remove typing indicator
        var typing = document.getElementById('typing-indicator');
        if (typing) typing.remove();

        try {
            var json = JSON.parse(res);
            if (json.status === 'success') {
                appendMessage('assistant', json.message);
            } else {
                appendMessage('assistant', 'Error: ' + json.message);
            }
        } catch (e) {
            appendMessage('assistant', 'System Error: Invalid JSON response.');
            console.error(res);
        }
    });
}

function appendMessage(role, text) {
    var chatBody = document.getElementById('chat-messages');
    var msgDiv = document.createElement('div');
    msgDiv.className = 'message ' + role;

    if (role === 'assistant') {
        // Render Markdown
        msgDiv.innerHTML = marked.parse(text);
    } else {
        msgDiv.innerText = text;
    }

    chatBody.appendChild(msgDiv);
    chatBody.scrollTop = chatBody.scrollHeight;
}
