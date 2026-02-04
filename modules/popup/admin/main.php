<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['main'];

// Handle Quick Priority Update
if ($nv_Request->isset_request('save_priority', 'post')) {
    $list_priority = $nv_Request->get_array('priority', 'post', []);
    foreach ($list_priority as $id => $prio) {
        $prio = intval($prio);
        $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET priority = " . $prio . " WHERE id = " . intval($id));
    }
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);
}

// Fetch List
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows ORDER BY priority DESC, id DESC";
$result = $db->query($sql);

$array_data = [];
$popup_types = nv_get_popup_types();

while ($row = $result->fetch()) {
    $row['type_text'] = isset($popup_types[$row['type']]) ? $popup_types[$row['type']] : $row['type'];
    $row['status_text'] = $row['status'] ? $lang_global['active'] : $lang_global['inactive'];
    $row['status_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=change_status&id=' . $row['id'] . '&status=' . ($row['status'] ? 0 : 1);
    $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=content&id=' . $row['id'];
    $row['del_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=del&id=' . $row['id'];
    $array_data[] = $row;
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

foreach ($array_data as $row) {
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
