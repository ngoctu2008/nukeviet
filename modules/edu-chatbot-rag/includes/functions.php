<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * Split text into chunks
 *
 * @param string $text
 * @param int $chunk_size Number of characters per chunk (approximate 500 tokens)
 * @param int $overlap Number of overlapping characters
 * @return array
 */
function nv_edu_chatbot_rag_chunk_text($text, $chunk_size = 1500, $overlap = 200)
{
    $chunks = [];
    $text = preg_replace('/\s+/', ' ', trim($text)); // Normalize whitespace
    $length = mb_strlen($text, 'UTF-8');
    $start = 0;

    while ($start < $length) {
        $chunk = mb_substr($text, $start, $chunk_size, 'UTF-8');
        $chunks[] = $chunk;
        $start += ($chunk_size - $overlap);
    }

    return $chunks;
}

/**
 * Call OpenAI API to get embeddings for a text
 *
 * @param string $text
 * @param string $api_key
 * @return array|bool Returns the vector array on success, false on failure
 */
function nv_edu_chatbot_rag_get_embedding($text, $api_key)
{
    $url = 'https://api.openai.com/v1/embeddings';
    $data = [
        'input' => $text,
        'model' => 'text-embedding-3-small'
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $result = json_decode($response, true);
        if (isset($result['data'][0]['embedding'])) {
            return $result['data'][0]['embedding'];
        }
    }

    // Log error if needed
    // trigger_error("OpenAI Embedding API error: " . $response, E_USER_WARNING);
    return false;
}

/**
 * Upsert vectors to Pinecone
 *
 * @param array $vectors Array of vectors to upsert. Format: [['id' => 'vec1', 'values' => [0.1, ...], 'metadata' => ['text' => '...']], ...]
 * @param string $api_key Pinecone API Key
 * @param string $pinecone_url Pinecone Index URL
 * @return bool True on success, false on failure
 */
function nv_edu_chatbot_rag_pinecone_upsert($vectors, $api_key, $pinecone_url)
{
    $url = rtrim($pinecone_url, '/') . '/vectors/upsert';
    $data = [
        'vectors' => $vectors
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Api-Key: ' . $api_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        return true;
    }

    // trigger_error("Pinecone Upsert API error: " . $response, E_USER_WARNING);
    return false;
}

/**
 * Query Pinecone for similar vectors
 *
 * @param array $vector The query vector
 * @param int $top_k Number of results to return
 * @param string $api_key Pinecone API Key
 * @param string $pinecone_url Pinecone Index URL
 * @return array|bool Array of matches or false on failure
 */
function nv_edu_chatbot_rag_pinecone_query($vector, $top_k, $api_key, $pinecone_url)
{
    $url = rtrim($pinecone_url, '/') . '/query';
    $data = [
        'vector' => $vector,
        'topK' => $top_k,
        'includeMetadata' => true
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Api-Key: ' . $api_key,
        'Content-Type: application/json'
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $result = json_decode($response, true);
        if (isset($result['matches'])) {
            return $result['matches'];
        }
    }

    // trigger_error("Pinecone Query API error: " . $response, E_USER_WARNING);
    return false;
}

/**
 * Call OpenAI API for Chat Completion
 *
 * @param string $system_prompt The system prompt instructing the AI
 * @param string $user_message The user's question
 * @param string $api_key OpenAI API Key
 * @return string|bool The AI's answer or false on failure
 */
function nv_edu_chatbot_rag_get_chat_completion($system_prompt, $user_message, $api_key)
{
    $url = 'https://api.openai.com/v1/chat/completions';
    $data = [
        'model' => 'gpt-4o-mini',
        'messages' => [
            [
                'role' => 'system',
                'content' => $system_prompt
            ],
            [
                'role' => 'user',
                'content' => $user_message
            ]
        ],
        'temperature' => 0 // Set temperature to 0 for strict factual responses
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $api_key
    ]);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($http_code == 200) {
        $result = json_decode($response, true);
        if (isset($result['choices'][0]['message']['content'])) {
            return $result['choices'][0]['message']['content'];
        }
    }

    // trigger_error("OpenAI Chat Completion API error: " . $response, E_USER_WARNING);
    return false;
}
