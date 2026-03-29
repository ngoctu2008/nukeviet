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
$xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=bad_dates');

$module_table_name = str_replace('-', '_', $module_data);
$table_bad_dates = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_bad_dates";

// Handle Delete
$del_id = $nv_Request->get_int('del_id', 'get', 0);
if ($del_id > 0) {
    $db->query("DELETE FROM " . $table_bad_dates . " WHERE id = " . $del_id);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=bad_dates');
}

// Handle Add
if ($nv_Request->isset_request('save', 'post')) {
    $month = $nv_Request->get_int('month', 'post', 0);
    $type = $nv_Request->get_string('type', 'post', '');
    $day_chi = $nv_Request->get_int('day_chi', 'post', -1);
    $day_lunar = $nv_Request->get_int('day_lunar', 'post', 0);
    $description = $nv_Request->get_string('description', 'post', '');

    if ($month > 0 && !empty($type) && !empty($description)) {
        $sql = "INSERT INTO " . $table_bad_dates . " (month, day_chi, day_lunar, type, description) VALUES (:month, :day_chi, :day_lunar, :type, :description)";
        $data_insert = [
            'month' => $month,
            'day_chi' => ($day_chi >= 0) ? $day_chi : null,
            'day_lunar' => ($day_lunar > 0) ? $day_lunar : null,
            'type' => $type,
            'description' => $description
        ];
        $db->insert_id($sql, 'id', $data_insert);
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=bad_dates');
    }
}

// Render Form Options
$chi_names = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
foreach ($chi_names as $k => $v) {
    $xtpl->assign('CHI', ['key' => $k, 'title' => $v]);
    $xtpl->parse('main.chi_option');
}

$types = [
    'sat_chu' => $lang_module['type_sat_chu'],
    'tho_tu' => $lang_module['type_tho_tu'],
    'duong_cong' => $lang_module['type_duong_cong']
];
foreach ($types as $k => $v) {
    $xtpl->assign('TYPE', ['key' => $k, 'title' => $v]);
    $xtpl->parse('main.type_option');
}

// List Data
$sql = "SELECT * FROM " . $table_bad_dates . " ORDER BY month ASC, type ASC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['type'] = isset($lang_module['type_' . $row['type']]) ? $lang_module['type_' . $row['type']] : $row['type'];
    $row['delete_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=bad_dates&del_id=' . $row['id'];

    if ($row['day_chi'] !== null) {
        $row['day_chi_name'] = isset($chi_names[$row['day_chi']]) ? $chi_names[$row['day_chi']] : '';
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
