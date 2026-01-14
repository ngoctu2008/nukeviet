<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs";

$sql_create_module = array();
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  year_1 int(4) NOT NULL DEFAULT '0',
  gender_1 tinyint(1) NOT NULL DEFAULT '1',
  year_2 int(4) NOT NULL DEFAULT '0',
  gender_2 tinyint(1) NOT NULL DEFAULT '0',
  type varchar(50) NOT NULL DEFAULT '',
  result_score int(3) NOT NULL DEFAULT '0',
  add_time int(11) NOT NULL DEFAULT '0',
  ip varchar(45) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
