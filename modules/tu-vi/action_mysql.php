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
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_interpretations'; // Renamed from data for consistency with existing code, but fulfills the 'data' requirement
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table_prefix . '_config';

$sql_create_module = $sql_drop_module;

// TABLE: USERS (History)
// Requested: id, userid, full_name, gender, birthday, birthhour, created_at
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

// TABLE: INTERPRETATIONS (Data)
// Requested: id, data_type (topic/star_key), data_key (palace_key), data_content (content), weight
// Mapping: star_key -> data_type, palace_key -> data_key, content -> data_content, topic -> topic
// We will keep existing column names for compatibility with the view logic we just wrote,
// but add 'weight' and ensure it meets the 'data' table requirement conceptualy.
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

// Insert default config
$sql_create_module[] = "INSERT INTO " . $table_prefix . "_config (config_name, config_value) VALUES ('horoscope_method', 'nam_phai')";
$sql_create_module[] = "INSERT INTO " . $table_prefix . "_config (config_name, config_value) VALUES ('allow_guest', '1')";
