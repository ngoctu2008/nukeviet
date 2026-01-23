<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

$sql_drop_module = [];
// Handle table prefix mapping tu-vi -> tu_vi
$module_table_prefix = str_replace('-', '_', $module_data);
$table_prefix = $db_config['prefix'] . '_' . $lang . '_' . $module_table_prefix;

// DROP TABLES
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_users';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_interpretations';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_config';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_bad_dates';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_events';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_compatibility_logs';

$sql_create_module = $sql_drop_module;

// TABLE: USERS (Horoscope History)
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_users (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  userid int(11) unsigned NOT NULL DEFAULT '0',
  full_name varchar(255) NOT NULL DEFAULT '',
  gender tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Male, 0: Female',
  birth_day tinyint(2) unsigned NOT NULL DEFAULT '0',
  birth_month tinyint(2) unsigned NOT NULL DEFAULT '0',
  birth_year smallint(4) unsigned NOT NULL DEFAULT '0',
  birth_hour tinyint(2) unsigned NOT NULL DEFAULT '0',
  calendar_type tinyint(1) unsigned NOT NULL DEFAULT '1',
  created_at int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY userid (userid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: INTERPRETATIONS (Horoscope Data)
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_interpretations (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  star_key varchar(50) NOT NULL DEFAULT '',
  palace_key varchar(50) NOT NULL DEFAULT '',
  topic varchar(50) NOT NULL DEFAULT 'tong_quan',
  content mediumtext,
  weight int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
  KEY star_palace (star_key, palace_key),
  KEY topic (topic)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: CONFIG
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_config (
  config_name varchar(100) NOT NULL DEFAULT '',
  config_value mediumtext,
  PRIMARY KEY (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: BAD DATES (Xem Ngay)
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_bad_dates (
  id int(11) NOT NULL AUTO_INCREMENT,
  month int(2) NOT NULL COMMENT 'Lunar Month',
  day_chi int(2) DEFAULT NULL COMMENT 'Index of Chi (0-11)',
  day_lunar int(2) DEFAULT NULL COMMENT 'Specific lunar day',
  type varchar(50) NOT NULL,
  description varchar(255) DEFAULT NULL,
  PRIMARY KEY (id),
  KEY month (month)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: EVENTS (Xem Ngay - Custom Events)
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_events (
  id int(11) NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  description mediumtext,
  config mediumtext,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: COMPATIBILITY LOGS (Xem Tuoi)
$sql_create_module[] = "CREATE TABLE " . $table_prefix . "_compatibility_logs (
    id int(11) unsigned NOT NULL AUTO_INCREMENT,
    year_1 int(4) NOT NULL,
    gender_1 tinyint(1) NOT NULL,
    year_2 int(4) NOT NULL,
    gender_2 tinyint(1) NOT NULL,
    type varchar(20) DEFAULT 'business',
    result_score float DEFAULT 0,
    add_time int(11) unsigned NOT NULL DEFAULT '0',
    ip varchar(45) DEFAULT '',
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Insert default config
$sql_create_module[] = "INSERT INTO " . $table_prefix . "_config (config_name, config_value) VALUES ('horoscope_method', 'nam_phai')";
$sql_create_module[] = "INSERT INTO " . $table_prefix . "_config (config_name, config_value) VALUES ('allow_guest', '1')";

// Include initial data insertion logic
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php')) {
    include NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php';

    // Process Bad Dates
    if (isset($sql_insert_bad_dates)) {
        $sql_create_module[] = str_replace('{TABLE}', $table_prefix . '_bad_dates', $sql_insert_bad_dates);
    }

    // Process Interpretations (if tu-vi has them)
    if (isset($sql_insert_interpretations)) {
        $sql_create_module[] = str_replace('{TABLE}', $table_prefix . '_interpretations', $sql_insert_interpretations);
    }
}
