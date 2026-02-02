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
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'all', 'nature', 'Sao Tử Vi là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'all', 'nature', 'Sao Thiên Cơ là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'all', 'nature', 'Sao Thái Dương là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'all', 'nature', 'Sao Vũ Khúc là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'all', 'nature', 'Sao Thiên Đồng là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'all', 'nature', 'Sao Liêm Trinh là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'all', 'nature', 'Sao Thiên Phủ là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'all', 'nature', 'Sao Thái Âm là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'all', 'nature', 'Sao Tham Lang là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'all', 'nature', 'Sao Cự Môn là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'all', 'nature', 'Sao Thiên Tướng là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'all', 'nature', 'Sao Thiên Lương là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'all', 'nature', 'Sao Thất Sát là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'all', 'nature', 'Sao Phá Quân là một trong 14 chính tinh, có ảnh hưởng lớn đến vận mệnh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'menh', 'main', 'Người có Tử Vi thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'phu_mau', 'main', 'Tử Vi tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'phuc_duc', 'main', 'Tử Vi tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'dien_trach', 'main', 'Tử Vi tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'quan_loc', 'main', 'Tử Vi tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'no_boc', 'main', 'Tử Vi tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'thien_di', 'main', 'Tử Vi tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'tat_ach', 'main', 'Tử Vi tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'tai_bach', 'main', 'Tử Vi cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'tu_tuc', 'main', 'Tử Vi tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'phu_the', 'main', 'Tử Vi ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'huynh_de', 'main', 'Tử Vi tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'menh', 'main', 'Người có Thiên Cơ thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'phu_mau', 'main', 'Thiên Cơ tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'phuc_duc', 'main', 'Thiên Cơ tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'dien_trach', 'main', 'Thiên Cơ tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'quan_loc', 'main', 'Thiên Cơ tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'no_boc', 'main', 'Thiên Cơ tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'thien_di', 'main', 'Thiên Cơ tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'tat_ach', 'main', 'Thiên Cơ tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'tai_bach', 'main', 'Thiên Cơ cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'tu_tuc', 'main', 'Thiên Cơ tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'phu_the', 'main', 'Thiên Cơ ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'huynh_de', 'main', 'Thiên Cơ tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'menh', 'main', 'Người có Thái Dương thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'phu_mau', 'main', 'Thái Dương tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'phuc_duc', 'main', 'Thái Dương tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'dien_trach', 'main', 'Thái Dương tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'quan_loc', 'main', 'Thái Dương tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'no_boc', 'main', 'Thái Dương tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'thien_di', 'main', 'Thái Dương tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'tat_ach', 'main', 'Thái Dương tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'tai_bach', 'main', 'Thái Dương cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'tu_tuc', 'main', 'Thái Dương tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'phu_the', 'main', 'Thái Dương ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'huynh_de', 'main', 'Thái Dương tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'menh', 'main', 'Người có Vũ Khúc thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'phu_mau', 'main', 'Vũ Khúc tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'phuc_duc', 'main', 'Vũ Khúc tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'dien_trach', 'main', 'Vũ Khúc tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'quan_loc', 'main', 'Vũ Khúc tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'no_boc', 'main', 'Vũ Khúc tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'thien_di', 'main', 'Vũ Khúc tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'tat_ach', 'main', 'Vũ Khúc tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'tai_bach', 'main', 'Vũ Khúc cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'tu_tuc', 'main', 'Vũ Khúc tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'phu_the', 'main', 'Vũ Khúc ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'huynh_de', 'main', 'Vũ Khúc tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'menh', 'main', 'Người có Thiên Đồng thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'phu_mau', 'main', 'Thiên Đồng tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'phuc_duc', 'main', 'Thiên Đồng tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'dien_trach', 'main', 'Thiên Đồng tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'quan_loc', 'main', 'Thiên Đồng tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'no_boc', 'main', 'Thiên Đồng tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'thien_di', 'main', 'Thiên Đồng tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'tat_ach', 'main', 'Thiên Đồng tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'tai_bach', 'main', 'Thiên Đồng cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'tu_tuc', 'main', 'Thiên Đồng tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'phu_the', 'main', 'Thiên Đồng ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'huynh_de', 'main', 'Thiên Đồng tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'menh', 'main', 'Người có Liêm Trinh thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'phu_mau', 'main', 'Liêm Trinh tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'phuc_duc', 'main', 'Liêm Trinh tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'dien_trach', 'main', 'Liêm Trinh tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'quan_loc', 'main', 'Liêm Trinh tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'no_boc', 'main', 'Liêm Trinh tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'thien_di', 'main', 'Liêm Trinh tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'tat_ach', 'main', 'Liêm Trinh tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'tai_bach', 'main', 'Liêm Trinh cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'tu_tuc', 'main', 'Liêm Trinh tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'phu_the', 'main', 'Liêm Trinh ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'huynh_de', 'main', 'Liêm Trinh tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'menh', 'main', 'Người có Thiên Phủ thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'phu_mau', 'main', 'Thiên Phủ tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'phuc_duc', 'main', 'Thiên Phủ tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'dien_trach', 'main', 'Thiên Phủ tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'quan_loc', 'main', 'Thiên Phủ tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'no_boc', 'main', 'Thiên Phủ tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'thien_di', 'main', 'Thiên Phủ tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'tat_ach', 'main', 'Thiên Phủ tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'tai_bach', 'main', 'Thiên Phủ cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'tu_tuc', 'main', 'Thiên Phủ tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'phu_the', 'main', 'Thiên Phủ ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'huynh_de', 'main', 'Thiên Phủ tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'menh', 'main', 'Người có Thái Âm thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'phu_mau', 'main', 'Thái Âm tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'phuc_duc', 'main', 'Thái Âm tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'dien_trach', 'main', 'Thái Âm tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'quan_loc', 'main', 'Thái Âm tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'no_boc', 'main', 'Thái Âm tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'thien_di', 'main', 'Thái Âm tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'tat_ach', 'main', 'Thái Âm tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'tai_bach', 'main', 'Thái Âm cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'tu_tuc', 'main', 'Thái Âm tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'phu_the', 'main', 'Thái Âm ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'huynh_de', 'main', 'Thái Âm tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'menh', 'main', 'Người có Tham Lang thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'phu_mau', 'main', 'Tham Lang tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'phuc_duc', 'main', 'Tham Lang tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'dien_trach', 'main', 'Tham Lang tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'quan_loc', 'main', 'Tham Lang tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'no_boc', 'main', 'Tham Lang tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'thien_di', 'main', 'Tham Lang tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'tat_ach', 'main', 'Tham Lang tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'tai_bach', 'main', 'Tham Lang cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'tu_tuc', 'main', 'Tham Lang tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'phu_the', 'main', 'Tham Lang ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'huynh_de', 'main', 'Tham Lang tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'menh', 'main', 'Người có Cự Môn thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'phu_mau', 'main', 'Cự Môn tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'phuc_duc', 'main', 'Cự Môn tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'dien_trach', 'main', 'Cự Môn tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'quan_loc', 'main', 'Cự Môn tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'no_boc', 'main', 'Cự Môn tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'thien_di', 'main', 'Cự Môn tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'tat_ach', 'main', 'Cự Môn tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'tai_bach', 'main', 'Cự Môn cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'tu_tuc', 'main', 'Cự Môn tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'phu_the', 'main', 'Cự Môn ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'huynh_de', 'main', 'Cự Môn tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'menh', 'main', 'Người có Thiên Tướng thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'phu_mau', 'main', 'Thiên Tướng tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'phuc_duc', 'main', 'Thiên Tướng tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'dien_trach', 'main', 'Thiên Tướng tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'quan_loc', 'main', 'Thiên Tướng tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'no_boc', 'main', 'Thiên Tướng tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'thien_di', 'main', 'Thiên Tướng tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'tat_ach', 'main', 'Thiên Tướng tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'tai_bach', 'main', 'Thiên Tướng cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'tu_tuc', 'main', 'Thiên Tướng tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'phu_the', 'main', 'Thiên Tướng ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'huynh_de', 'main', 'Thiên Tướng tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'menh', 'main', 'Người có Thiên Lương thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'phu_mau', 'main', 'Thiên Lương tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'phuc_duc', 'main', 'Thiên Lương tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'dien_trach', 'main', 'Thiên Lương tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'quan_loc', 'main', 'Thiên Lương tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'no_boc', 'main', 'Thiên Lương tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'thien_di', 'main', 'Thiên Lương tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'tat_ach', 'main', 'Thiên Lương tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'tai_bach', 'main', 'Thiên Lương cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'tu_tuc', 'main', 'Thiên Lương tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'phu_the', 'main', 'Thiên Lương ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'huynh_de', 'main', 'Thiên Lương tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'menh', 'main', 'Người có Thất Sát thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'phu_mau', 'main', 'Thất Sát tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'phuc_duc', 'main', 'Thất Sát tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'dien_trach', 'main', 'Thất Sát tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'quan_loc', 'main', 'Thất Sát tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'no_boc', 'main', 'Thất Sát tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'thien_di', 'main', 'Thất Sát tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'tat_ach', 'main', 'Thất Sát tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'tai_bach', 'main', 'Thất Sát cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'tu_tuc', 'main', 'Thất Sát tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'phu_the', 'main', 'Thất Sát ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'huynh_de', 'main', 'Thất Sát tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'menh', 'main', 'Người có Phá Quân thủ Mệnh thường có cá tính mạnh mẽ, thông minh và có khả năng lãnh đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'phu_mau', 'main', 'Phá Quân tại cung Phụ Mẫu mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'phuc_duc', 'main', 'Phá Quân tại cung Phúc Đức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'dien_trach', 'main', 'Phá Quân tại cung Điền Trạch mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'quan_loc', 'main', 'Phá Quân tại Quan Lộc báo hiệu đường công danh thuận lợi, có chức quyền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'no_boc', 'main', 'Phá Quân tại cung Nô Bộc mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'thien_di', 'main', 'Phá Quân tại cung Thiên Di mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'tat_ach', 'main', 'Phá Quân tại cung Tật Ách mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'tai_bach', 'main', 'Phá Quân cư Tài Bạch chủ về tài lộc dồi dào, biết cách kiếm tiền và giữ tiền.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'tu_tuc', 'main', 'Phá Quân tại cung Tử Tức mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'phu_the', 'main', 'Phá Quân ở Phu Thê thì vợ chồng hòa thuận, hỗ trợ lẫn nhau trong sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'huynh_de', 'main', 'Phá Quân tại cung Huynh Đệ mang lại sự ổn định và cát lành cho phương diện này.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('kinh_duong', 'general', 'meaning', 'Sao Kình Dương có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('da_la', 'general', 'meaning', 'Sao Đà La có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('dia_khong', 'general', 'meaning', 'Sao Địa Không có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('dia_kiep', 'general', 'meaning', 'Sao Địa Kiếp có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('hoa_tinh', 'general', 'meaning', 'Sao Hỏa Tinh có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('linh_tinh', 'general', 'meaning', 'Sao Linh Tinh có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('hoa_loc', 'general', 'meaning', 'Sao Hóa Lộc có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('hoa_quyen', 'general', 'meaning', 'Sao Hóa Quyền có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('hoa_khoa', 'general', 'meaning', 'Sao Hóa Khoa có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('hoa_ky', 'general', 'meaning', 'Sao Hóa Kỵ có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('van_xuong', 'general', 'meaning', 'Sao Văn Xương có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('van_khuc', 'general', 'meaning', 'Sao Văn Khúc có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('ta_phu', 'general', 'meaning', 'Sao Tả Phù có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('huu_bat', 'general', 'meaning', 'Sao Hữu Bật có tác động bổ trợ hoặc gây trở ngại tùy thuộc vào các sao đi cùng.')";

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_customers";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_logs";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations";

// User provided content seeding
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'menh', 'main', 'Còn dài khuôn mặt đầy đặn thân, khí vũ hiên ngang, môi hồng răng trắng. Áo cơm không thiếu sót lộc khố ở. Thông minh cao ngạo, cá tính đơn thuần, làm người bảo thủ cẩn thận, thuở nhỏ sống an nhàn sung sướng. Tính cách gió chiều nào theo chiều nấy, đứng núi này trông núi nọ, không thể kiên định từ đầu đến cuối.
Có cát trợ giúp một bước lên mây phú quý lâm, bảo thủ phát triển, đa năng có không tệ của biểu hiện. Sát tụ cô lập gian trá tính khó hiểu.
Biệt tài vật tới truy đuổi muốn mạnh, giỏi giao thiệp. Nên xảo nghệ an thân.
Thiên phủ là nam đẩu lệnh chủ, nắm lộc khố, tính ôn hòa, học nhiều đa năng, thích quyền hành, hòa hợp thấy người sang bắt quàng làm họ.
Nhật nguyệt giáp mệnh là đắt cách, thích chỉ huy người khác, nhưng lại không động thủ.
Thiên phủ độc tọa ở thế miếu, phú quý song toàn. Nhưng thiên tướng ở bằng cung, lực lượng không đủ, vô cát tinh đến giúp, ngoại trừ cô lập bên ngoài, cũng ngại bảo thủ. Tử tham ở phúc đức, hai hạn đi tới, cũng có đứng núi này trông núi nọ, không thể chung thủy.
( sửu ) nhật nguyệt phản bối, học nhiều đa năng, hư danh nhẹ lợi nhuận. Dù có cát tinh tương trợ, vẫn bằng thêm phiền não.
( vị ) nhật nguyệt tịnh minh, gặp gỡ tốt hơn, thành tựu khá lớn.
Nữ mệnh giỏi giao thiệp, ứng đối tốt, thông minh tú lệ, có tử vi tam hợp chiếu, kim quan hà phối người quý phụ.
Nữ mệnh lục sát uy hiếp, tính cách dung thường nhiều hối trệ, cả đời khó có thể bình an hưởng thụ.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_am', 'huynh_de', 'main', '( Tý ) tình cảm không được tốt, gặp kị sát tinh, nên sớm độc lập là tốt. Hình khắc thiếu nợ hòa. Gặp cát tỉnh hoà bình.
( ngọ ) gặp cát tinh, huynh đệ đắc lực. Gặp sát, ý kiến không hợp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('liem_trinh', 'phu_the', 'main', 'Dễ hôn nhân không nghi thức. Tuổi tác nên tương đương.
Phi lễ thành hôn.
Vợ chồng bất hòa, sinh ly tử biệt, tái hôn.
Gặp xương khúc, lộc tồn, Khôi Việt, hình khắc hơi trễ.
Gặp Tả hữu, hỏa linh, đà la, không kiếp, khắc hai ba thê.
Nữ mệnh vô kỵ sát, cả đời suôn sẻ. Tài gặp hao tổn tinh, phải càng gả càng tốt.
Gặp kị sát tinh, nam nữ đều chủ 2 lần kết hôn. Tả hữu thêm cát, tái hôn tốt hơn.
Hôn phối mệnh nên là nhật nguyệt, thiên đồng, thiên tướng, liêm trinh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tham_lang', 'phu_the', 'main', 'Dễ hôn nhân không nghi thức. Tuổi tác nên tương đương.
Phi lễ thành hôn.
Vợ chồng bất hòa, sinh ly tử biệt, tái hôn.
Gặp xương khúc, lộc tồn, Khôi Việt, hình khắc hơi trễ.
Gặp Tả hữu, hỏa linh, đà la, không kiếp, khắc hai ba thê.
Nữ mệnh vô kỵ sát, cả đời suôn sẻ. Tài gặp hao tổn tinh, phải càng gả càng tốt.
Gặp kị sát tinh, nam nữ đều chủ 2 lần kết hôn. Tả hữu thêm cát, tái hôn tốt hơn.
Hôn phối mệnh nên là nhật nguyệt, thiên đồng, thiên tướng, liêm trinh.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('cu_mon', 'tu_tuc', 'main', 'Cá tính Hướng nội tốt hưởng thụ được.
Gặp cát tinh, có quý, hiếu thuận.
Sát tụ phòng tổn thương chiết.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_tuong', 'tai_bach', 'main', 'Trước kia tài không được tụ thủ, dễ bởi vì đào hoa tranh chấp rủi ro. Trung cuối đời tài vận tốt.
Có cát tinh, tích tài làm giàu, cũng góc có thiên tài vận.
Cung tài bạch vô chính diệu, gặp không kiếp: tiền đến tiền đi.
Gặp hỏa linh, cũng chủ hoành phát hậu rách nát cơ hội.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_dong', 'tat_ach', 'main', 'Thái âm sống dưới nước thiên cơ mộc, qua chín là mối họa.
Hệ thần kinh nhanh, bệnh trĩ, bệnh bao tử, can đảm chứng bệnh.
Phụ nữ ( quyền lộc ) tử cung ám tật, nội tiết.
Nữ mệnh Cơ Âm + thiên diêu, hàm trì, loan thích, tử cung ám tật, trong tử cung màng dị vị trí.
Gặp hỏa linh bệnh ngoài da.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_luong', 'tat_ach', 'main', 'Thái âm sống dưới nước thiên cơ mộc, qua chín là mối họa.
Hệ thần kinh nhanh, bệnh trĩ, bệnh bao tử, can đảm chứng bệnh.
Phụ nữ ( quyền lộc ) tử cung ám tật, nội tiết.
Nữ mệnh Cơ Âm + thiên diêu, hàm trì, loan thích, tử cung ám tật, trong tử cung màng dị vị trí.
Gặp hỏa linh bệnh ngoài da.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('tu_vi', 'thien_di', 'main', 'Gặp cát nên sớm ly hương phát triển, có thể áo gấm vinh quy.
Liêm Sát gặp kị sát tinh, không nên ly hương phát triển.
Gặp dương, hóa kị, cả đời nhiều ngoài ý muốn. Càng kị đầu máy. Hoặc gặp lưu manh.
Gặp không kiếp, chết bởi ngoại đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('pha_quan', 'thien_di', 'main', 'Gặp cát nên sớm ly hương phát triển, có thể áo gấm vinh quy.
Liêm Sát gặp kị sát tinh, không nên ly hương phát triển.
Gặp dương, hóa kị, cả đời nhiều ngoài ý muốn. Càng kị đầu máy. Hoặc gặp lưu manh.
Gặp không kiếp, chết bởi ngoại đạo.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_co', 'no_boc', 'main', 'Bạn bè thuộc hạ nhiều, trợ lực tốt.
Gặp sát tinh, tuy nhiều thiếu nợ lực.
Hợp với người có mệnh ở tý, tị, dậu. Không hợp với người có mệnh ở ngọ, mùi, thìn.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('general', 'quan_loc', 'main', 'Văn võ đều cát, cả đời ăn lộc. Nên nhà công nghiệp, dân đại, luật sư, y sư, học giả.
Thiên tướng không được gặp kị sát tinh, đa số trên trung bình tới nhân viên công vụ.
Không thấy lộc tồn, hóa lộc, nên công trình kỹ nghệ.
Gặp lộc, Xương khúc, Tả hữu, có phúc cầm quyền.
Sát tụ, phòng ngăn trở, có kinh thương, nhưng không thể hợp ý. Không nên võ chức.
Gặp hỏa linh, lên chức dễ có lực cản.
Gặp kị, dễ có không tốt văn kiện.
Đối cung vũ khúc hóa kị, không nên hùn vốn sự nghiệp.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thai_duong', 'dien_trach', 'main', 'Dễ bởi vì bất động sản phát sinh tranh chấp, chớ đảm bảo.
Không được gặp kị sát, kinh tế bình ổn. Cả đời không có trọng đại phá mất.
Gặp kị sát tinh, nhiều biến động. Lưu không được điền sản ruộng đất. Mua bán phòng văn thư khuyết điểm mà hồi tổn thất.
Nhà ở nên cao lầu, rẫy.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('vu_khuc', 'phuc_duc', 'main', 'Bận rộn, cuối đời hưởng phúc.
Gặp Tả hữu chủ dật.
Gặp hình sát chủ làm. Cả đời phúc bạc.
Gặp xương khúc, trống rỗng thiếu thực tế.
Kình Đà uy hiếp, nên kinh thương hoặc hiến thân tông giáo .')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('that_sat', 'phuc_duc', 'main', 'Bận rộn, cuối đời hưởng phúc.
Gặp Tả hữu chủ dật.
Gặp hình sát chủ làm. Cả đời phúc bạc.
Gặp xương khúc, trống rỗng thiếu thực tế.
Kình Đà uy hiếp, nên kinh thương hoặc hiến thân tông giáo .')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('general', 'phu_mau', 'main', 'Cùng cha mẹ dễ có ngăn cách, hoặc dễ là rời tông.
Cát tinh trợ giúp, có thể được phụ mẫu chi ân huệ.
Gặp kị sát không kiếp, trước kia dễ cùng cha mẹ tách rời, duyên mỏng ( mẫu thân là nhất ).')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'all', 'overview', 'Nhật nguyệt giáp mệnh là đắt cách. Thêm cát không quý thì phú.
Gặp cát thì cát, gặp hung thì hung.
Kình dương, hóa kị ở thiên di, phát sinh chuyện ngoài ý muốn.
Người sinh năm Mậu, Tham lang hóa lộc nhập phúc đức, lộc tồn nhập phu Quan, song nguyệt người mặc dù lao tâm lao lực, vẫn có trên trung bình thành tựu.')";
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_interpretations (star_key, palace_key, topic, content) VALUES ('thien_phu', 'all', 'limit', 'Nữ mệnh hạn đi phụ mẫu, Phu thê đại hạn vị trí thái dương gặp sát tinh, gặp mặt không nhận người, có ly dị.
Hạn đi điền trạch, hóa kị nhập bản mệnh tứ chính vị trí, có trọng ngoài ý muốn.
Bản mệnh tam hợp gặp sát tinh, hạn gởi công văn đi sách vị trí, hóa kị nhập Huynh người hầu, dễ lầm đường lạc lối.')";
