<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['logs'];

$page = $nv_Request->get_int('page', 'get', 1);
$per_page = 20;

$sql = "SELECT SQL_CALC_FOUND_ROWS * FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs ORDER BY add_time DESC LIMIT " . (($page - 1) * $per_page) . ", " . $per_page;
$result = $db->query($sql);

$result_all = $db->query('SELECT FOUND_ROWS()');
list($all_page) = $result_all->fetch(3);

$xtpl = new XTemplate('logs.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

while ($row = $result->fetch()) {
    $row['add_time'] = nv_date('d/m/Y H:i:s', $row['add_time']);
    $row['reference_data'] = nl2br(htmlspecialchars($row['reference_data']));
    $row['user_question'] = htmlspecialchars($row['user_question']);
    $row['ai_answer'] = nl2br(htmlspecialchars($row['ai_answer']));

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$generate_page = nv_generate_page(NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $op, $all_page, $per_page, $page);
if (!empty($generate_page)) {
    $xtpl->assign('GENERATE_PAGE', $generate_page);
    $xtpl->parse('main.generate_page');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
