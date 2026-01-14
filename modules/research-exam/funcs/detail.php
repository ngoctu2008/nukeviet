<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_MOD_RESEARCH_EXAM')) {
    die('Stop!!!');
}

$exam_id = $nv_Request->get_int('id', 'get', 0);
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE id=" . $exam_id . " AND status=1";
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_module['exam_not_found']);
}

$page_title = $row['title'];

// Process Registration Form
if ($nv_Request->isset_request('start_exam', 'post')) {
    $user_info_exam = array(
        'fullname' => $nv_Request->get_string('fullname', 'post', ''),
        'phone' => $nv_Request->get_string('phone', 'post', ''),
        'address' => $nv_Request->get_string('address', 'post', ''),
        'unit_id' => $nv_Request->get_int('unit_id', 'post', 0),
        'exam_id' => $exam_id,
        'start_time' => NV_CURRENTTIME
    );

    if (!empty($user_info_exam['fullname']) && !empty($user_info_exam['phone']) && $user_info_exam['unit_id'] > 0) {
        $nv_Request->set_Session($module_data . '_user', $user_info_exam);
        Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=test&id=' . $exam_id);
        die();
    }
}

$xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'detail');
$xtpl->assign('ROW', $row);

// Units Dropdown
$sql_units = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_units WHERE status=1 ORDER BY weight ASC";
$res_units = $db->query($sql_units);
while ($unit = $res_units->fetch()) {
    $xtpl->assign('UNIT', $unit);
    $xtpl->parse('main.unit_loop');
}

// Check time
$current_time = NV_CURRENTTIME;
if ($current_time >= $row['time_start'] && $current_time <= $row['time_end']) {
    // Show form
    // Pre-fill if logged in
    $fullname = ''; $phone = '';
    if (defined('NV_IS_USER')) {
        $fullname = $user_info['full_name'];
        // $phone = $user_info['mobile']; // If available
    }
    $xtpl->assign('FULLNAME', $fullname);
    $xtpl->assign('PHONE', $phone);

    $xtpl->parse('main.form');
} elseif ($current_time < $row['time_start']) {
    $xtpl->parse('main.not_started');
} else {
    $xtpl->parse('main.ended');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
