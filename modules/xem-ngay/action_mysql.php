<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = array();
// Sanitize module data for table name (replace - with _)
$module_table_name = str_replace('-', '_', $module_data);

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_config";

$sql_create_module = $sql_drop_module;

// Table: Bad Dates
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates (
  id int(11) NOT NULL AUTO_INCREMENT,
  month int(2) NOT NULL COMMENT 'Lunar Month',
  day_chi int(2) DEFAULT NULL COMMENT 'Index of Chi (0-11) for cyclic bad days',
  day_lunar int(2) DEFAULT NULL COMMENT 'Specific lunar day (1-30) for fixed dates',
  type varchar(50) NOT NULL COMMENT 'Type: sat_chu, tho_tu, nguyet_pha, duong_cong',
  description varchar(255) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY month (month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

// Table: Config
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_config (
  config_name varchar(30) NOT NULL,
  config_value mediumtext NOT NULL,
  UNIQUE KEY config_name (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";

// Insert Data: Sat Chu
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates (month, day_chi, type, description) VALUES
(1, 5, 'sat_chu', 'Sát Chủ - Tỵ'),
(2, 0, 'sat_chu', 'Sát Chủ - Tý'),
(3, 7, 'sat_chu', 'Sát Chủ - Mùi'),
(4, 3, 'sat_chu', 'Sát Chủ - Mão'),
(5, 8, 'sat_chu', 'Sát Chủ - Thân'),
(6, 10, 'sat_chu', 'Sát Chủ - Tuất'),
(7, 11, 'sat_chu', 'Sát Chủ - Hợi'),
(8, 1, 'sat_chu', 'Sát Chủ - Sửu'),
(9, 6, 'sat_chu', 'Sát Chủ - Ngọ'),
(10, 1, 'sat_chu', 'Sát Chủ - Sửu'),
(11, 0, 'sat_chu', 'Sát Chủ - Tý'),
(12, 4, 'sat_chu', 'Sát Chủ - Thìn')";

// Insert Data: Tho Tu
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates (month, day_chi, type, description) VALUES
(1, 10, 'tho_tu', 'Thọ Tử - Tuất'),
(2, 4, 'tho_tu', 'Thọ Tử - Thìn'),
(3, 11, 'tho_tu', 'Thọ Tử - Hợi'),
(4, 5, 'tho_tu', 'Thọ Tử - Tỵ'),
(5, 0, 'tho_tu', 'Thọ Tử - Tý'),
(6, 6, 'tho_tu', 'Thọ Tử - Ngọ'),
(7, 1, 'tho_tu', 'Thọ Tử - Sửu'),
(8, 7, 'tho_tu', 'Thọ Tử - Mùi'),
(9, 2, 'tho_tu', 'Thọ Tử - Dần'),
(10, 8, 'tho_tu', 'Thọ Tử - Thân'),
(11, 3, 'tho_tu', 'Thọ Tử - Mão'),
(12, 9, 'tho_tu', 'Thọ Tử - Dậu')";

// Insert Data: Duong Cong Ky Nhat
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates (month, day_lunar, type, description) VALUES
(1, 13, 'duong_cong', 'Dương Công Kỵ Nhật'),
(2, 11, 'duong_cong', 'Dương Công Kỵ Nhật'),
(3, 9, 'duong_cong', 'Dương Công Kỵ Nhật'),
(4, 7, 'duong_cong', 'Dương Công Kỵ Nhật'),
(5, 5, 'duong_cong', 'Dương Công Kỵ Nhật'),
(6, 3, 'duong_cong', 'Dương Công Kỵ Nhật'),
(7, 8, 'duong_cong', 'Dương Công Kỵ Nhật'),
(7, 29, 'duong_cong', 'Dương Công Kỵ Nhật'),
(8, 27, 'duong_cong', 'Dương Công Kỵ Nhật'),
(9, 25, 'duong_cong', 'Dương Công Kỵ Nhật'),
(10, 23, 'duong_cong', 'Dương Công Kỵ Nhật'),
(11, 21, 'duong_cong', 'Dương Công Kỵ Nhật'),
(12, 19, 'duong_cong', 'Dương Công Kỵ Nhật')";
