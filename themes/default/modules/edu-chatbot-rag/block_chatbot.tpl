<!-- BEGIN: main -->
<div id="edu-chatbot-container">
    <div id="edu-chatbot-button" onclick="toggleChatbot()">
        <i class="fa fa-comments"></i>
    </div>

    <div id="edu-chatbot-window" class="panel panel-default shadow d-none" style="display: none;">
        <div class="panel-heading bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="panel-title m-0"><i class="fa fa-robot"></i> Trợ lý ảo trường học</h5>
            <button type="button" class="btn btn-sm btn-light close-btn" onclick="toggleChatbot()">&times;</button>
        </div>
        <div class="panel-body chatbot-messages" id="chatbot-messages">
            <div class="chat-message ai-message">
                <div class="message-content bg-light p-2 rounded">Xin chào! Tôi là trợ lý ảo của trường. Tôi có thể giúp gì cho bạn?</div>
            </div>
        </div>
        <div class="panel-footer">
            <form id="chatbot-form" onsubmit="sendChatMessage(event)">
                <div class="input-group">
                    <input type="text" id="chatbot-input" class="form-control" placeholder="Nhập câu hỏi của bạn..." required>
                    <div class="input-group-btn">
                        <button type="submit" class="btn btn-primary" id="chatbot-send-btn"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #edu-chatbot-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 9999;
        font-family: Arial, sans-serif;
    }

    #edu-chatbot-button {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background-color: #007bff;
        color: white;
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 24px;
        cursor: pointer;
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        transition: transform 0.3s;
    }

    #edu-chatbot-button:hover {
        transform: scale(1.1);
    }

    #edu-chatbot-window {
        position: absolute;
        bottom: 80px;
        right: 0;
        width: 350px;
        border: 1px solid #ccc;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        background: white;
        overflow: hidden;
    }

    .chatbot-messages {
        height: 300px;
        overflow-y: auto;
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        background-color: #f9f9f9;
    }

    .chat-message {
        display: flex;
        width: 100%;
    }

    .user-message {
        justify-content: flex-end;
    }

    .ai-message {
        justify-content: flex-start;
    }

    .message-content {
        max-width: 80%;
        padding: 8px 12px;
        border-radius: 15px;
        word-wrap: break-word;
    }

    .user-message .message-content {
        background-color: #007bff;
        color: white;
        border-bottom-right-radius: 0;
    }

    .ai-message .message-content {
        background-color: #e9ecef;
        color: black;
        border-bottom-left-radius: 0;
    }

    .panel-heading.bg-primary {
        background-color: #007bff !important;
        color: white !important;
        padding: 10px 15px;
    }

    .close-btn {
        background: transparent;
        border: none;
        color: white;
        font-size: 20px;
        cursor: pointer;
        float: right;
        margin-top: -3px;
    }

    .typing-indicator {
        font-style: italic;
        color: #888;
        font-size: 0.9em;
    }
</style>

<script>
    var isChatbotOpen = false;

    function toggleChatbot() {
        var windowEl = document.getElementById('edu-chatbot-window');
        if (isChatbotOpen) {
            windowEl.style.display = 'none';
            isChatbotOpen = false;
        } else {
            windowEl.style.display = 'block';
            isChatbotOpen = true;
            document.getElementById('chatbot-input').focus();
        }
    }

    function appendMessage(text, isUser) {
        var messagesContainer = document.getElementById('chatbot-messages');
        var messageDiv = document.createElement('div');
        messageDiv.className = 'chat-message ' + (isUser ? 'user-message' : 'ai-message');

        var contentDiv = document.createElement('div');
        contentDiv.className = 'message-content';
        contentDiv.innerHTML = text;

        messageDiv.appendChild(contentDiv);
        messagesContainer.appendChild(messageDiv);

        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function sendChatMessage(event) {
        event.preventDefault();
        var inputEl = document.getElementById('chatbot-input');
        var sendBtn = document.getElementById('chatbot-send-btn');
        var question = inputEl.value.trim();

        if (!question) return;

        // Display user message
        appendMessage(question, true);
        inputEl.value = '';
        inputEl.disabled = true;
        sendBtn.disabled = true;

        // Show typing indicator
        var messagesContainer = document.getElementById('chatbot-messages');
        var typingDiv = document.createElement('div');
        typingDiv.id = 'chatbot-typing';
        typingDiv.className = 'chat-message ai-message typing-indicator';
        typingDiv.innerHTML = '<div class="message-content">AI đang suy nghĩ...</div>';
        messagesContainer.appendChild(typingDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // AJAX POST to chat backend
        $.ajax({
            type: 'POST',
            url: '{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=chat',
            data: {
                ajax: 1,
                question: question,
                checksess: '{NV_CHECK_SESSION}'
            },
            dataType: 'json',
            success: function(response) {
                document.getElementById('chatbot-typing').remove();

                if (response.status === 'success') {
                    appendMessage(response.answer, false);
                } else {
                    appendMessage('Lỗi: ' + response.message, false);
                }
            },
            error: function() {
                document.getElementById('chatbot-typing').remove();
                appendMessage('Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.', false);
            },
            complete: function() {
                inputEl.disabled = false;
                sendBtn.disabled = false;
                inputEl.focus();
            }
        });
    }
</script>
<!-- END: main -->