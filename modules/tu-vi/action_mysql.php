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
$array_table = [
    'users',
    'interpretations',
    'config'
];

// Handle table prefix mapping tu-vi -> tu_vi
$module_table_prefix = str_replace('-', '_', $module_data);

$table = $db_config['prefix'] . '_' . $lang . '_' . $module_table_prefix;

// DROP TABLES
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table . '_users';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table . '_interpretations';
$sql_drop_module[] = 'DROP TABLE IF EXISTS ' . $table . '_config';

$sql_create_module = $sql_drop_module;

// TABLE: USERS (History)
$sql_create_module[] = "CREATE TABLE " . $table . "_users (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  full_name varchar(255) NOT NULL DEFAULT '',
  birth_day tinyint(2) unsigned NOT NULL DEFAULT '0',
  birth_month tinyint(2) unsigned NOT NULL DEFAULT '0',
  birth_year smallint(4) unsigned NOT NULL DEFAULT '0',
  birth_hour tinyint(2) unsigned NOT NULL DEFAULT '0',
  gender tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Male, 0: Female',
  calendar_type tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '1: Solar, 0: Lunar',
  created_at int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: INTERPRETATIONS
$sql_create_module[] = "CREATE TABLE " . $table . "_interpretations (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  star_key varchar(50) NOT NULL DEFAULT '',
  palace_key varchar(50) NOT NULL DEFAULT '',
  topic varchar(50) NOT NULL DEFAULT 'tong_quan',
  content mediumtext,
  PRIMARY KEY (id),
  KEY star_palace (star_key, palace_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// TABLE: CONFIG
$sql_create_module[] = "CREATE TABLE " . $table . "_config (
  config_name varchar(100) NOT NULL DEFAULT '',
  config_value mediumtext,
  PRIMARY KEY (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";

// Insert default config
$sql_create_module[] = "INSERT INTO " . $table . "_config (config_name, config_value) VALUES ('horoscope_method', 'nam_phai')";
