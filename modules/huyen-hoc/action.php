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

$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_customers";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $_module_data . "_logs";
