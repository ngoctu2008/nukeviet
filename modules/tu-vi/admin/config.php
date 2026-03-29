<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_TU_VI_ADMIN')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
}

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);

if ($nv_Request->isset_request('submit', 'post')) {
    $config = [];
    $config['horoscope_method'] = $nv_Request->get_title('horoscope_method', 'post', 'nam_phai');

    // Groups View
    $groups_view = $nv_Request->get_array('groups_view', 'post', []);
    $config['groups_view'] = implode(',', $groups_view);

    // Xem Tuoi Advice
    $config['advice_high'] = $nv_Request->get_string('advice_high', 'post', '', 1); // 1 = HTML? No, just string is fine.
    $config['advice_medium'] = $nv_Request->get_string('advice_medium', 'post', '');
    $config['advice_low'] = $nv_Request->get_string('advice_low', 'post', '');

    foreach ($config as $config_name => $config_value) {
        $stmt = $db->prepare("REPLACE INTO " . NV_PRE_TUVI . "_config (config_name, config_value) VALUES (:name, :value)");
        $stmt->bindParam(':name', $config_name);
        $stmt->bindParam(':value', $config_value);
        $stmt->execute();
    }

    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config');
}

// Load Config
$row = [];
$result = $db->query("SELECT * FROM " . NV_PRE_TUVI . "_config");
while ($item = $result->fetch()) {
    $row[$item['config_name']] = $item['config_value'];
}

// Groups View processing
$groups_list = nv_groups_list();
$groups_view = explode(',', isset($row['groups_view']) ? $row['groups_view'] : '');
foreach ($groups_list as $group_id => $group_title) {
    $xtpl->assign('GROUPS_VIEW', [
        'id' => $group_id,
        'title' => $group_title,
        'checked' => in_array($group_id, $groups_view) ? 'checked' : ''
    ]);
    $xtpl->parse('main.groups_view');
}

$xtpl->assign('ROW', $row);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
