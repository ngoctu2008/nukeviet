<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_MOD_AVATAR')) {
    die('Stop!!!');
}

$action = $nv_Request->get_string('action', 'post', '');
$id = $nv_Request->get_int('id', 'post', 0);

if ($id > 0) {
    if ($action == 'view') {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . $module_data . '_rows SET views=views+1 WHERE id=' . $id;
        $db->query($sql);
        die('OK');
    } elseif ($action == 'download') {
        $sql = 'UPDATE ' . $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . $module_data . '_rows SET downloads=downloads+1 WHERE id=' . $id;
        $db->query($sql);
        die('OK');
    }
}

die('Error');
