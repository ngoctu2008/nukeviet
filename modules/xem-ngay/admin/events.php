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

$page_title = "Quản lý Sự kiện khác";
$module_table_name = str_replace('-', '_', $module_data);
$table_events = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_events";

$xtpl = new XTemplate('events.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION_URL', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');

// Handle Delete
$del_id = $nv_Request->get_int('del_id', 'get', 0);
if ($del_id > 0) {
    $db->query("DELETE FROM " . $table_events . " WHERE id = " . $del_id);
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');
}

// Fetch Data for Edit
$id = $nv_Request->get_int('id', 'get', 0);
$row_edit = [];
if ($id > 0) {
    $sql = "SELECT * FROM " . $table_events . " WHERE id = " . $id;
    $result = $db->query($sql);
    $row_edit = $result->fetch();
    if (!empty($row_edit)) {
        $xtpl->assign('DATA', $row_edit);
        $xtpl->assign('FORM_TITLE', 'Sửa sự kiện');
    }
} else {
    $xtpl->assign('FORM_TITLE', 'Thêm sự kiện mới');
}

// Handle Save (Add/Edit)
if ($nv_Request->isset_request('save', 'post')) {
    $save_id = $nv_Request->get_int('id', 'post', 0);
    $title = $nv_Request->get_string('title', 'post', '');
    $description = $nv_Request->get_string('description', 'post', '');

    if (!empty($title)) {
        if ($save_id > 0) {
            $sql = "UPDATE " . $table_events . " SET title = :title, description = :description WHERE id = " . $save_id;
            $db->query($sql, [':title' => $title, ':description' => $description]);
        } else {
            $sql = "INSERT INTO " . $table_events . " (title, description) VALUES (:title, :description)";
            $data_insert = [
                'title' => $title,
                'description' => $description
            ];
            $db->insert_id($sql, 'id', $data_insert);
        }
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events');
    }
}

// List Data
// Check if table exists (simplified check)
try {
    $sql = "SELECT * FROM " . $table_events . " ORDER BY id DESC";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['edit_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events&id=' . $row['id'];
        $row['delete_url'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $op . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=events&del_id=' . $row['id'];
        $xtpl->assign('ROW', $row);
        $xtpl->parse('main.row');
    }
} catch (PDOException $e) {
    // Table might not exist if update. Ideally action_mysql handles this.
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
