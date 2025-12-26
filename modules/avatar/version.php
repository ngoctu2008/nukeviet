<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Tạo avatar',
    'modfuncs' => 'main,viewcat,detail',
    'change_alias' => 'main,viewcat,detail',
    'submenu' => 'main,viewcat,detail',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.07',
    'date' => 'Fri, 19 Dec 2025 00:00:00 GMT',
    'author' => 'Phạm Ngọc Tú',
    'uploads_dir' => array($module_name),
    'note' => 'Module tạo khung hình avatar'
);
