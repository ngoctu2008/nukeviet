<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['report'];

$sql = "SELECT s.popup_id, r.title, SUM(s.views) as total_views, SUM(s.clicks) as total_clicks, SUM(s.closes) as total_closes
        FROM " . NV_PREFIXLANG . "_" . $module_data . "_stats s
        LEFT JOIN " . NV_PREFIXLANG . "_" . $module_data . "_rows r ON s.popup_id = r.id
        GROUP BY s.popup_id
        ORDER BY total_views DESC";

$result = $db->query($sql);
$array_data = [];

while ($row = $result->fetch()) {
    if (empty($row['title'])) {
        $row['title'] = "Popup ID: " . $row['popup_id'] . " (Deleted)";
    }
    $row['ctr'] = ($row['total_views'] > 0) ? round(($row['total_clicks'] / $row['total_views']) * 100, 2) : 0;
    $array_data[] = $row;
}

$xtpl = new XTemplate('report.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

foreach ($array_data as $row) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
