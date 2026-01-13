<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['knowledge'];
$module_data_table = str_replace('-', '_', $module_data);

// Handle Delete
if ($nv_Request->isset_request('delete_id', 'post')) {
    $id = $nv_Request->get_int('delete_id', 'post', 0);
    if ($id > 0) {
        $db->query("DELETE FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_knowledge WHERE id=" . $id);
        $nv_Cache->delMod($module_name);
        die('OK');
    }
    die('NO');
}

// Handle Add/Edit
if ($nv_Request->isset_request('save', 'post')) {
    $id = $nv_Request->get_int('id', 'post', 0);
    $title = $nv_Request->get_string('title', 'post', '');
    $content = $nv_Request->get_string('content', 'post', '');
    $status = $nv_Request->get_int('status', 'post', 1);

    if (empty($title)) {
        $error = $lang_module['error_empty_title']; // Make sure to define this if needed
    } else {
        if ($id > 0) {
            $sql = "UPDATE " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_knowledge SET title=" . $db->quote($title) . ", content=" . $db->quote($content) . ", status=" . $status . ", edit_time=" . NV_CURRENTTIME . " WHERE id=" . $id;
            $db->query($sql);
        } else {
            $sql = "INSERT INTO " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_knowledge (title, content, status, add_time, edit_time) VALUES (" . $db->quote($title) . ", " . $db->quote($content) . ", " . $status . ", " . NV_CURRENTTIME . ", " . NV_CURRENTTIME . ")";
            $db->query($sql);
        }
        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=knowledge');
        die();
    }
}

$xtpl = new XTemplate('knowledge.tpl', NV_ROOTDIR . '/themes/admin_default/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', 'knowledge');

// List Data
$sql = "SELECT * FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_knowledge ORDER BY id DESC";
$result = $db->query($sql);

while ($row = $result->fetch()) {
    $row['status_text'] = ($row['status'] == 1) ? $lang_module['active'] : $lang_module['inactive'];
    $row['link_edit'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=knowledge&id=' . $row['id'];
    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.loop');
}

// Check if editing
$id = $nv_Request->get_int('id', 'get', 0);
$row_edit = array('id' => 0, 'title' => '', 'content' => '', 'status' => 1);
if ($id > 0) {
    $sql = "SELECT * FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_knowledge WHERE id=" . $id;
    $result = $db->query($sql);
    $row_edit = $result->fetch();
}

$xtpl->assign('DATA', $row_edit);
if ($row_edit['status'] == 1) $xtpl->assign('STATUS_ACTIVE', 'selected="selected"');
else $xtpl->assign('STATUS_INACTIVE', 'selected="selected"');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
