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

// Include initial data insertion logic
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php')) {
    include NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php';
    if (isset($sql_insert_bad_dates)) {
        $sql_create_module[] = $sql_insert_bad_dates;
    }
}
