<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_CHAT_AI')) {
    die('Stop!!!');
}

// Get Config
$sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_config";
$result = $db->query($sql);
$chat_config = array();
while ($row = $result->fetch()) {
    $chat_config[$row['config_name']] = $row['config_value'];
}

// Session Management
$session_code = $nv_Request->get_string('chat_ai_session', 'cookie', '');
if (empty($session_code)) {
    $session_code = md5(session_id() . NV_CURRENTTIME . rand(1000, 9999));
    $nv_Request->set_Cookie('chat_ai_session', $session_code, NV_CURRENTTIME + 31536000); // 1 year
    // Create session in DB
    $db->query("INSERT INTO " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_sessions (session_code, user_id, created_at, updated_at) VALUES (" . $db->quote($session_code) . ", " . ($user_info['userid'] ?? 0) . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")");
} else {
    // Update timestamp
    $db->query("UPDATE " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_sessions SET updated_at=" . NV_CURRENTTIME . ", user_id=" . ($user_info['userid'] ?? 0) . " WHERE session_code=" . $db->quote($session_code));
}

// Handle User Message
$message = $nv_Request->get_string('message', 'post', '');

if (empty($message)) {
    echo json_encode(['status' => 'error', 'message' => $lang_module['error_empty']]);
    die();
}

// 1. Save User Message
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_messages (session_code, role, content, created_at) VALUES (" . $db->quote($session_code) . ", 'user', " . $db->quote($message) . ", " . NV_CURRENTTIME . ")");

// 2. Get Context (RAG)
$context_data = nv_chat_get_context($message);

// 3. Get History
$history_limit = isset($chat_config['history_limit']) ? intval($chat_config['history_limit']) : 5;
$sql = "SELECT role, content FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_messages WHERE session_code=" . $db->quote($session_code) . " ORDER BY id DESC LIMIT " . ($history_limit * 2);
$result = $db->query($sql);
$history = array();
while ($row = $result->fetch()) {
    $history[] = $row; // Will be reversed later
}
$history = array_reverse($history);

// 4. Construct API Payload
$messages_payload = [];

// System Prompt with Context
$system_prompt = $chat_config['system_prompt'];
if (!empty($context_data)) {
    $system_prompt .= "\n\nSử dụng thông tin ngữ cảnh sau để trả lời (nếu liên quan):\n" . $context_data;
} else {
    $system_prompt .= "\n\n(Không tìm thấy dữ liệu nội bộ liên quan, hãy trả lời dựa trên kiến thức chung của bạn)";
}

// API Handler
$api_key = $chat_config['api_key'];
$model = $chat_config['model'];
$provider = $chat_config['provider'];
$bot_reply = '';

if ($provider == 'openai') {
    $messages_payload[] = ['role' => 'system', 'content' => $system_prompt];
    foreach ($history as $msg) {
        $messages_payload[] = ['role' => $msg['role'], 'content' => $msg['content']];
    }
    // Note: The user message is already in history because we saved it first.
    // Wait, usually we don't save to DB until success to avoid duplicates if retry?
    // But for simplicity of history retrieval, we saved it.
    // Optimization: The last message in $history IS the current message.

    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => $model,
        'messages' => $messages_payload,
        'temperature' => 0.7
    ];

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $json = json_decode($response, true);
        $bot_reply = $json['choices'][0]['message']['content'] ?? 'Error parsing response';
    } else {
        $bot_reply = 'Error API: ' . $response;
    }

} elseif ($provider == 'gemini') {
    // Gemini API Structure (v1beta)
    // URL: https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent?key=API_KEY

    $url = "https://generativelanguage.googleapis.com/v1beta/models/" . $model . ":generateContent?key=" . $api_key;

    // Construct Gemini Content
    $contents = [];

    // System instruction is supported in some Gemini versions or simulated via first user message.
    // Gemini 1.5 Pro supports system_instruction, Gemini Pro 1.0 does not explicitly in the same way.
    // Safest way for generic Gemini: Prepend system prompt to first user message or use 'user' role.

    // Add History
    // Gemini roles: 'user' and 'model'
    $first_msg = true;
    foreach ($history as $msg) {
        $role = ($msg['role'] == 'user') ? 'user' : 'model';
        $text = $msg['content'];

        if ($first_msg && $role == 'user') {
             $text = $system_prompt . "\n\nUser Question: " . $text;
             $first_msg = false;
        }

        $contents[] = [
            'role' => $role,
            'parts' => [['text' => $text]]
        ];
    }

    // If history was empty (rare as we just inserted user msg), ensure we send something
    if (empty($contents)) {
         $contents[] = [
            'role' => 'user',
            'parts' => [['text' => $system_prompt . "\n\n" . $message]]
        ];
    }

    $data = [
        'contents' => $contents
    ];

    $headers = ['Content-Type: application/json'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $json = json_decode($response, true);
        $bot_reply = $json['candidates'][0]['content']['parts'][0]['text'] ?? 'Error parsing Gemini response';
    } else {
        $bot_reply = 'Error Gemini API: ' . $response;
    }
}

// 5. Save Bot Response
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_messages (session_code, role, content, created_at) VALUES (" . $db->quote($session_code) . ", 'assistant', " . $db->quote($bot_reply) . ", " . NV_CURRENTTIME . ")");

echo json_encode(['status' => 'success', 'message' => $bot_reply]);
die();
