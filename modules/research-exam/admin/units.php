<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['unit_manager'];
$error = '';

// Process Form Submit
if ($nv_Request->isset_request('save', 'post')) {
    $row = array();
    $row['id'] = $nv_Request->get_int('id', 'post', 0);
    $row['title'] = $nv_Request->get_string('title', 'post', '');
    $row['note'] = $nv_Request->get_string('note', 'post', '');
    $row['weight'] = $nv_Request->get_int('weight', 'post', 0);
    $row['status'] = $nv_Request->get_int('status', 'post', 1);

    if (empty($row['title'])) {
        $error = $lang_module['error_title_empty'];
    } else {
        if ($row['id'] > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_units SET title = :title, note = :note, weight = :weight, status = :status WHERE id = :id");
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_units (title, note, weight, status) VALUES (:title, :note, :weight, :status)");
        }
        $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $stmt->bindParam(':note', $row['note'], PDO::PARAM_STR);
        $stmt->bindParam(':weight', $row['weight'], PDO::PARAM_INT);
        $stmt->bindParam(':status', $row['status'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=units');
            die();
        } else {
            $error = $lang_global['error_save'];
        }
    }
}

// Process Delete
if ($nv_Request->isset_request('delete', 'post')) {
    $id = $nv_Request->get_int('delete', 'post', 0);
    if ($id > 0) {
        $stmt = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_units WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            die('OK');
        }
    }
    die('NO');
}

$xtpl = new XTemplate('units.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'units');

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// List Units
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_units ORDER BY weight ASC, id DESC";
$result = $db->query($sql);
$row = array();

while ($item = $result->fetch()) {
    $xtpl->assign('ROW', $item);
    $xtpl->parse('main.list.row');
}
$xtpl->parse('main.list');

// Add/Edit Form (Modal or separate - implementing simple inline-like or separate page logic in template)
// For simplicity in this plan, I'll pass data if editing
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_units WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
} else {
    $row = array('id' => 0, 'title' => '', 'note' => '', 'weight' => 0, 'status' => 1);
}

$xtpl->assign('DATA', $row);
$xtpl->assign('STATUS_CHECKED', $row['status'] == 1 ? 'checked' : '');

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
