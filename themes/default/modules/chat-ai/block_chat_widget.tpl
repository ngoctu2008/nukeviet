<!-- BEGIN: main -->
<div id="chat-ai-widget" style="bottom: {WIDGET_BOTTOM}px; right: {WIDGET_RIGHT}px;">
    <div class="chat-toggle-btn" onclick="toggleChatWindow()">
        <i class="fa fa-comments"></i>
    </div>

    <div class="chat-window" id="chat-window" style="bottom: 70px; right: 0;">
        <div class="chat-header">
            <span>{LANG.chat_title}</span>
            <span class="close-chat" onclick="toggleChatWindow()">&times;</span>
        </div>
        <div class="chat-body" id="chat-messages">
            <!-- Messages will be loaded here -->
            <div class="chat-welcome">
                Xin chào! Tôi có thể giúp gì cho bạn?
            </div>
        </div>
        <div class="chat-footer">
            <input type="text" id="chat-input" placeholder="{LANG.type_message}" onkeypress="handleEnter(event)" />
            <button onclick="sendMessage()"><i class="fa fa-paper-plane"></i></button>
        </div>
    </div>
</div>

<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/chat-ai.css">
<script src="{NV_BASE_SITEURL}modules/chat-ai/js/marked.min.js"></script>
<script>
var nv_base_siteurl = '{NV_BASE_SITEURL}';
var nv_lang_data = '{NV_LANG_DATA}';
var nv_module_name = 'chat-ai';
</script>
<script src="{NV_BASE_SITEURL}themes/default/js/chat-ai.js"></script>
<!-- END: main -->
