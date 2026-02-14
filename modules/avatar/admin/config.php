<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

if ($nv_Request->isset_request('save', 'post')) {
    $array_config = array();
    $array_config['per_page_cat'] = $nv_Request->get_int('per_page_cat', 'post', 20);
    $array_config['per_page_row'] = $nv_Request->get_int('per_page_row', 'post', 20);

    foreach ($array_config as $config_name => $config_value) {
        $stmt = $db->prepare("REPLACE INTO " . NV_PREFIXLANG . "_" . $module_data . "_config (config_name, config_value) VALUES (:config_name, :config_value)");
        $stmt->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $stmt->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $stmt->execute();
    }

    $nv_Cache->delMod($module_name);
    Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
    die();
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$array_config = nv_avatar_get_config($module_data);

// Defaults if missing
if (!isset($array_config['per_page_cat'])) $array_config['per_page_cat'] = 20;
if (!isset($array_config['per_page_row'])) $array_config['per_page_row'] = 20;

$xtpl->assign('DATA', $array_config);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
