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

$page_title = $lang_module['main'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'detail');

// List Active Exams
$current_time = NV_CURRENTTIME;
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_exams WHERE status=1 ORDER BY id DESC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['link'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=detail&id=' . $row['id'];
    $row['time_start'] = date('d/m/Y H:i', $row['time_start']);
    $row['time_end'] = date('d/m/Y H:i', $row['time_end']);

    $status_text = '';
    if ($current_time < $row['time_start']) {
        $status_text = '<span class="text-warning">' . $lang_module['exam_not_started'] . '</span>';
        $row['btn_class'] = 'btn-secondary disabled';
    } elseif ($current_time > $row['time_end']) {
        $status_text = '<span class="text-danger">' . $lang_module['exam_ended'] . '</span>';
        $row['btn_class'] = 'btn-secondary disabled';
    } else {
        $status_text = '<span class="text-success">' . $lang_module['active'] . '</span>';
        $row['btn_class'] = 'btn-primary';
    }

    $row['status_text'] = $status_text;
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.list.row');
}
$xtpl->parse('main.list');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
