<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";

$sql_create_module = $sql_drop_module;

// 1. Categories Table
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_cat (
  catid int(11) unsigned NOT NULL AUTO_INCREMENT,
  parentid int(11) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL DEFAULT '',
  description text,
  image varchar(255) DEFAULT '',
  groups_view varchar(255) DEFAULT '',
  groups_use varchar(255) DEFAULT '',
  weight int(11) NOT NULL DEFAULT '0',
  sort int(11) NOT NULL DEFAULT '0',
  lev int(11) NOT NULL DEFAULT '0',
  viewcat varchar(50) NOT NULL DEFAULT 'view_grid',
  numsubcat int(11) NOT NULL DEFAULT '0',
  subcatid varchar(255) DEFAULT '',
  status tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (catid),
  UNIQUE KEY alias (alias),
  KEY parentid (parentid),
  KEY status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 2. Rows Table (Items)
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  catid int(11) unsigned NOT NULL DEFAULT '0',
  title varchar(250) NOT NULL,
  alias varchar(250) NOT NULL DEFAULT '',
  image varchar(255) NOT NULL DEFAULT '',
  description text,
  body text,
  views int(11) unsigned NOT NULL DEFAULT '0',
  downloads int(11) unsigned NOT NULL DEFAULT '0',
  add_time int(11) unsigned NOT NULL DEFAULT '0',
  edit_time int(11) unsigned NOT NULL DEFAULT '0',
  weight int(11) NOT NULL DEFAULT '0',
  status tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (id),
  UNIQUE KEY alias (alias),
  KEY catid (catid),
  KEY status (status),
  KEY weight (weight)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// 3. Config Table
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config (
  config_name varchar(30) NOT NULL,
  config_value varchar(255) NOT NULL,
  UNIQUE KEY config_name (config_name)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

// Default Config
$sql_create_module[] = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config VALUES
('per_page_cat', '20'),
('per_page_row', '20')
";
