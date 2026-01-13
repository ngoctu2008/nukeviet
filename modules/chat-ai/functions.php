<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_CHAT_AI', true);

/**
 * Get table name for a module (handling virtual modules)
 */
function nv_chat_get_table_name($module_virtual_name) {
    global $site_mods, $db_config, $module_data;

    // Check if the module exists in site_mods
    if (isset($site_mods[$module_virtual_name])) {
        // usually the table prefix follows the module data (directory name)
        // e.g. site_mods['tintuc']['module_data'] might be 'news'
        $mod_data = $site_mods[$module_virtual_name]['module_data'];
        return $db_config['prefix'] . '_' . $mod_data . '_rows';
    }
    return false;
}

/**
 * Search content in a specific table
 */
function nv_chat_search_table($table, $keyword, $limit = 3) {
    global $db;
    $results = array();

    if (empty($keyword)) return $results;

    // Search by phrase first
    $sql = "SELECT title, bodyhtml, hometext FROM " . $table . " WHERE (title LIKE :keyword OR hometext LIKE :keyword) AND status=1 LIMIT " . $limit;
    $sth = $db->prepare($sql);
    $sth->bindValue(':keyword', '%' . $keyword . '%', PDO::PARAM_STR);
    $sth->execute();

    while ($row = $sth->fetch()) {
        $results[] = strip_tags($row['title'] . ". " . $row['hometext'] . " " . $row['bodyhtml']);
    }

    // If not enough results, fallback to split keywords (nouns)
    if (count($results) < $limit) {
        $keywords = explode(' ', $keyword);
        // Filter small words (simple length check for now, can be improved)
        $keywords = array_filter($keywords, function($k) { return mb_strlen($k) > 3; });

        if (!empty($keywords)) {
            $sql_parts = [];
            foreach ($keywords as $k) {
                $sql_parts[] = "(title LIKE '%" . $db->dblikeescape($k) . "%' OR hometext LIKE '%" . $db->dblikeescape($k) . "%')";
            }
            $sql_where = implode(' OR ', $sql_parts);

            // Exclude already found items? Complex to track IDs across tables, so we just grab more and dedupe text later if needed.
            // Simplified: Just run the query
             $sql = "SELECT title, bodyhtml, hometext FROM " . $table . " WHERE (" . $sql_where . ") AND status=1 LIMIT " . ($limit - count($results));
             $result = $db->query($sql);
             while ($row = $result->fetch()) {
                 $results[] = strip_tags($row['title'] . ". " . $row['hometext'] . " " . $row['bodyhtml']);
             }
        }
    }

    return $results;
}

/**
 * Get Context (RAG)
 */
function nv_chat_get_context($user_message) {
    global $db, $db_config, $module_data, $site_mods;

    // Get config
    $sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_config";
    $result = $db->query($sql);
    $config = array();
    while ($row = $result->fetch()) {
        $config[$row['config_name']] = $row['config_value'];
    }

    $limit = isset($config['search_limit']) ? intval($config['search_limit']) : 3;
    $context_data = [];

    // Search Custom Knowledge
    $table_knowledge = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data . "_knowledge";
    // Custom logic for knowledge table (has 'content' column instead of hometext/bodyhtml)
    $sql = "SELECT title, content FROM " . $table_knowledge . " WHERE status=1 AND (title LIKE :keyword OR content LIKE :keyword) LIMIT " . $limit;
    $sth = $db->prepare($sql);
    $sth->bindValue(':keyword', '%' . $user_message . '%');
    $sth->execute();
    while ($row = $sth->fetch()) {
        $context_data[] = strip_tags($row['title'] . ": " . $row['content']);
    }

    // Search News
    if (!empty($config['use_news'])) {
        // Iterate through site_mods to find all 'news' modules
        foreach ($site_mods as $mod_name => $mod_info) {
            if ($mod_info['module_data'] == 'news') {
                $table_news = $db_config['prefix'] . "_" . $mod_info['module_data'] . "_rows"; // Standard news table
                // Check if table exists (optional but safe)
                $res = nv_chat_search_table($table_news, $user_message, $limit);
                $context_data = array_merge($context_data, $res);
            }
        }
    }

    // Search Laws
    if (!empty($config['use_laws'])) {
         foreach ($site_mods as $mod_name => $mod_info) {
            if ($mod_info['module_data'] == 'laws') { // Assuming laws module data is 'laws'
                $table_laws = $db_config['prefix'] . "_" . $mod_info['module_data'] . "_rows";
                $res = nv_chat_search_table($table_laws, $user_message, $limit);
                $context_data = array_merge($context_data, $res);
            }
        }
    }

    // Deduplicate and Truncate
    $context_data = array_unique($context_data);

    // Truncate total context to avoid token limits (e.g., 2000 chars)
    $final_context = implode("\n---\n", $context_data);
    if (mb_strlen($final_context) > 3000) {
        $final_context = mb_substr($final_context, 0, 3000) . "...";
    }

    return $final_context;
}
