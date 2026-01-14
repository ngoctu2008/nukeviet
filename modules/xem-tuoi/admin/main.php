<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

// Pagination
$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 20;
$base_url = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs ORDER BY id DESC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
$result = $db->query($sql);

$result_all = $db->query("SELECT FOUND_ROWS()")->fetchColumn();

while ($row = $result->fetch()) {
    $row['add_time'] = nv_date('d/m/Y H:i', $row['add_time']);

    // Parse Gender Text
    if ($row['gender_1'] == 1) {
        $xtpl->parse('main.loop.male1');
    } else {
        $xtpl->parse('main.loop.female1');
    }

    if ($row['gender_2'] == 1) {
        $xtpl->parse('main.loop.male2');
    } else {
        $xtpl->parse('main.loop.female2');
    }

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

$generate_page = nv_generate_page($base_url, $result_all, $per_page, $page);
$xtpl->assign('GENERATE_PAGE', $generate_page);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
