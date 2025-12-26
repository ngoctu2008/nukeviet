<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

// Use admin theme correctly
$tp = NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/modules/' . $module_file;

$xtpl = new XTemplate('config.tpl', $tp);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

if ($nv_Request->isset_request('save', 'post')) {
    $cfg = array();
    $cfg['per_page'] = $nv_Request->get_int('per_page', 'post', 20);

    foreach ($cfg as $config_name => $config_value) {
        $stmt = $db->prepare("REPLACE INTO " . NV_PREFIXLANG . "_" . $module_data . "_config (config_name, config_value) VALUES (:config_name, :config_value)");
        $stmt->bindParam(':config_name', $config_name, PDO::PARAM_STR);
        $stmt->bindParam(':config_value', $config_value, PDO::PARAM_STR);
        $stmt->execute();
    }

    $nv_Cache->delMod($module_name);
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
    die();
}

// Load config from local table
$sql = "SELECT config_name, config_value FROM " . NV_PREFIXLANG . "_" . $module_data . "_config";
$result = $db->query($sql);
$data = array();
while ($row = $result->fetch()) {
    $data[$row['config_name']] = $row['config_value'];
}

if (!isset($data['per_page'])) $data['per_page'] = 20;

$xtpl->assign('DATA', $data);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
