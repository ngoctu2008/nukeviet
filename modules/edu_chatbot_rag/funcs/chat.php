<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_EDU_CHATBOT_RAG')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('ajax', 'post')) {
    header('Content-Type: application/json; charset=utf-8');

    if (!check_sess_token($nv_Request->get_string('checksess', 'post', ''))) {
        echo json_encode(['status' => 'error', 'message' => 'Token không hợp lệ.']);
        die();
    }

    $question = $nv_Request->get_title('question', 'post', '');
    $session_id = session_id();

    if (empty($question)) {
        echo json_encode(['status' => 'error', 'message' => 'Vui lòng nhập câu hỏi.']);
        die();
    }

    require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/functions.php';

    // Get current config
    $sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
    $result = $db->query($sql);
    $mod_config = [];
    while ($row = $result->fetch()) {
        $mod_config[$row['config_name']] = $row['config_value'];
    }

    // Default messages
    $fallback_message = 'Tôi không có thông tin về vấn đề này, vui lòng liên hệ phòng Giáo vụ.';

    // 1. Get embedding for user question
    $question_vector = nv_edu_chatbot_rag_get_embedding($question, $mod_config['openai_api_key']);

    if (!$question_vector) {
        echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối OpenAI API (Embedding).']);
        die();
    }

    // 2. Query Pinecone for Top 3 matches
    $matches = nv_edu_chatbot_rag_pinecone_query($question_vector, 3, $mod_config['pinecone_api_key'], $mod_config['pinecone_url']);

    $context = '';
    $context_chunks = [];
    $similarity_threshold = floatval($mod_config['similarity_threshold']);

    if ($matches && is_array($matches)) {
        foreach ($matches as $match) {
            if (isset($match['score']) && $match['score'] >= $similarity_threshold) {
                if (isset($match['metadata']['text'])) {
                    $context_chunks[] = $match['metadata']['text'];
                }
            }
        }
    }

    $ai_answer = '';

    if (empty($context_chunks)) {
        $ai_answer = $fallback_message;
        $context = 'Không tìm thấy context phù hợp (Độ đo tương đồng < ' . $similarity_threshold . ').';
    } else {
        $context = implode("\n\n---\n\n", $context_chunks);

        // 3. Construct prompt
        $system_prompt = str_replace(['{context}', '{user_question}'], [$context, $question], $mod_config['system_prompt']);

        // 4. Call OpenAI Chat Completion
        $ai_answer = nv_edu_chatbot_rag_get_chat_completion($system_prompt, $question, $mod_config['openai_api_key']);

        if (!$ai_answer) {
             echo json_encode(['status' => 'error', 'message' => 'Lỗi kết nối OpenAI API (Chat Completion).']);
             die();
        }
    }

    // 5. Save log
    $stmt = $db->prepare("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (session_id, user_question, ai_answer, reference_data, add_time) VALUES (:session_id, :user_question, :ai_answer, :reference_data, " . NV_CURRENTTIME . ")");
    $stmt->bindParam(':session_id', $session_id, PDO::PARAM_STR);
    $stmt->bindParam(':user_question', $question, PDO::PARAM_STR);
    $stmt->bindParam(':ai_answer', $ai_answer, PDO::PARAM_STR);
    $stmt->bindParam(':reference_data', $context, PDO::PARAM_STR);
    $stmt->execute();

    echo json_encode([
        'status' => 'success',
        'answer' => nl2br(htmlspecialchars($ai_answer))
    ]);
    die();
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
