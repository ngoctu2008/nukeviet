<?php

/**
 * @version 4.5.07
 * @author Jules
 * @license GNU/GPL version 2 or any later version
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$db_table_name = str_replace('-', '_', $module_data);

$sql_drop_module = array();
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $db_table_name . "_logs";

$sql_create_module = $sql_drop_module;
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $db_table_name . "_logs (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  action_type varchar(50) NOT NULL,
  file_name varchar(255) NOT NULL,
  action_time int(11) unsigned NOT NULL DEFAULT '0',
  ip varchar(45) NOT NULL,
  user_id int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
