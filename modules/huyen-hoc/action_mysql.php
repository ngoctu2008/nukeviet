<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();
$sql_create_module = $sql_drop_module;

// Sanitize module_data for table names (replace hyphen with underscore)
$_module_data = str_replace('-', '_', $module_data);

// Define tables
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_customers (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  fullname varchar(255) NOT NULL,
  birth_date int(11) NOT NULL,
  gender tinyint(1) NOT NULL DEFAULT '1',
  phone varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  created_at int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_logs (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  customer_id int(11) unsigned NOT NULL,
  action_type varchar(50) NOT NULL,
  action_data text,
  created_at int(11) NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  star_key varchar(50) NOT NULL,
  palace_key varchar(50) NOT NULL,
  topic varchar(50) DEFAULT 'main',
  content text NOT NULL,
  PRIMARY KEY (id),
  KEY star_palace (star_key, palace_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'all', 'nature', 'Đế Tinh, chủ về quyền lực, danh vọng, giải ách chế hóa.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'menh', 'meaning', 'Người có Tử Vi thủ mệnh thường có dáng người đậm, mặt vuông hoặc tròn, tính tình đôn hậu, trọng danh dự, có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'all', 'nature', 'Thiện Tinh, chủ về trí tuệ, mưu lược, tính toán, huynh đệ.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'menh', 'meaning', 'Người có Thiên Cơ thủ mệnh thường thông minh, khéo léo, hay suy tính, thích hợp với các nghề cần sự khéo léo hoặc tham mưu.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'all', 'nature', 'Quyền Tinh, chủ về quan lộc, danh tiếng, cha, chồng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'all', 'nature', 'Tài Tinh, chủ về tiền bạc, sự cô độc.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'all', 'nature', 'Phúc Tinh, chủ về hưởng thụ, thay đổi, phúc thọ.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'all', 'nature', 'Đào Hoa Tinh, Tù Tinh, chủ về kỷ luật, nóng nảy, tình cảm.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'all', 'nature', 'Tài Tinh, Kho Tinh, chủ về tài lộc, sự che chở, bao dung.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'all', 'nature', 'Phú Tinh, chủ về điền sản, mẹ, vợ, sự dịu dàng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'all', 'nature', 'Đào Hoa Tinh, chủ về dục vọng, giao tế, tu hành.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'all', 'nature', 'Ám Tinh, chủ về ngôn ngữ, thị phi, nghiên cứu.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'all', 'nature', 'Quyền Tinh, Ấn Tinh, chủ về quyền hành, sự tương trợ, y thực.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'all', 'nature', 'Ấm Tinh, Thọ Tinh, chủ về sự che chở, dạy dỗ, thọ trường, nguyên tắc.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'all', 'nature', 'Quyền Tinh, Dũng Tinh, chủ về sát phạt, uy quyền, cô khắc.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'all', 'nature', 'Hao Tinh, chủ về hao tán, phá cũ đổi mới, phu thê, tử tức.')";

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_customers";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_logs";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations";
