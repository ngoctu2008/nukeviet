<?php

/**
 * @Project NUKEVIET 4.5.07
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2026 Phạm Ngọc Tú. All rights reserved
 * @Createdate Sat, 07/02/2026 06:27:27 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$sql_drop_module = [];
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_stats";

$sql_create_module = $sql_drop_module;

// Table: Popup definitions
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_rows (
    id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL DEFAULT '',
    content mediumtext NOT NULL,
    type varchar(50) NOT NULL DEFAULT 'modal',
    display_pages text COMMENT 'JSON: List of modules',
    user_groups text COMMENT 'JSON: List of user groups',
    device_type varchar(20) NOT NULL DEFAULT 'all',
    trigger_config text COMMENT 'JSON: {type: immediate|delay|scroll, value: int}',
    begin_time int(11) unsigned NOT NULL DEFAULT '0',
    end_time int(11) unsigned NOT NULL DEFAULT '0',
    frequency int(11) unsigned NOT NULL DEFAULT '0' COMMENT 'Minutes',
    priority smallint(5) unsigned NOT NULL DEFAULT '0',
    status tinyint(1) unsigned NOT NULL DEFAULT '1',
    PRIMARY KEY (id),
    KEY status (status),
    KEY priority (priority)
) ENGINE=MyISAM";

// Table: Statistics
$sql_create_module[] = "CREATE TABLE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_stats (
    popup_id mediumint(8) unsigned NOT NULL,
    add_time int(11) unsigned NOT NULL COMMENT 'Midnight timestamp',
    views int(11) unsigned NOT NULL DEFAULT '0',
    clicks int(11) unsigned NOT NULL DEFAULT '0',
    closes int(11) unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (popup_id, add_time)
) ENGINE=MyISAM";
