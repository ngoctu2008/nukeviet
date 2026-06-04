<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs";

$sql_create_module = $sql_drop_module;

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
  config_name varchar(30) NOT NULL,
  config_value text NOT NULL,
  UNIQUE KEY config_name (config_name)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_documents (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(250) NOT NULL,
  file_path varchar(250) NOT NULL,
  is_vectorized tinyint(1) NOT NULL DEFAULT '0',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  session_id varchar(100) NOT NULL,
  user_question text NOT NULL,
  ai_answer text NOT NULL,
  reference_data text NOT NULL,
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=MyISAM";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (config_name, config_value) VALUES
('openai_api_key', ''),
('pinecone_api_key', ''),
('pinecone_url', ''),
('system_prompt', 'Bạn là trợ lý học đường. Chỉ sử dụng thông tin trong phần [TÀI LIỆU NHÀ TRƯỜNG] dưới đây để trả lời câu hỏi. Không thêm thắt. [TÀI LIỆU NHÀ TRƯỜNG]: {context}. Câu hỏi: {user_question}. Nếu không có thông tin trong tài liệu nhà trường, chatbot phải trả lời: \"Tôi không có thông tin về vấn đề này, vui lòng liên hệ phòng Giáo vụ\".'),
('similarity_threshold', '0.7')";
