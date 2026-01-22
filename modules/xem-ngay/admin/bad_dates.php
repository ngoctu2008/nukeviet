<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$xtpl = new XTemplate('bad_dates.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);

// Sanitize table name
$module_table_name = str_replace('-', '_', $module_data);
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_table_name . "_bad_dates ORDER BY month ASC, type ASC";
$result = $db->query($sql);

$chi_names = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

while ($row = $result->fetch()) {
    $row['type'] = isset($lang_module['type_' . $row['type']]) ? $lang_module['type_' . $row['type']] : $row['type'];

    if ($row['day_chi'] !== null) {
        $row['day_chi_name'] = $chi_names[$row['day_chi']];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row.chi');
    } else {
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row.lunar');
    }

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
