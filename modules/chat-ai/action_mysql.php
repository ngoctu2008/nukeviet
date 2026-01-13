<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_knowledge";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sessions";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_messages";

$sql_create_module = $sql_drop_module;

// Table config: Stores API settings, etc.
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
  config_name varchar(30) NOT NULL,
  config_value text NOT NULL,
  UNIQUE KEY config_name (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table knowledge: Custom text data for RAG
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_knowledge (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  content text NOT NULL,
  status tinyint(1) NOT NULL DEFAULT '1',
  add_time int(11) NOT NULL DEFAULT '0',
  edit_time int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  FULLTEXT KEY content (title, content)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table sessions: Chat sessions
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_sessions (
  session_code varchar(32) NOT NULL,
  user_id int(11) NOT NULL DEFAULT '0',
  created_at int(11) NOT NULL DEFAULT '0',
  updated_at int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (session_code),
  KEY user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Table messages: Chat history
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_messages (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  session_code varchar(32) NOT NULL,
  role enum('user','assistant') NOT NULL,
  content text NOT NULL,
  created_at int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY session_code (session_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Insert default config
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (config_name, config_value) VALUES
('provider', 'openai'),
('api_key', ''),
('model', 'gpt-3.5-turbo'),
('system_prompt', 'Bạn là trợ lý ảo thông minh của website. Hãy trả lời thân thiện và ngắn gọn.'),
('use_news', '0'),
('use_laws', '0'),
('search_limit', '3'),
('history_limit', '5')";
