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

$page_title = $lang_module['topic_manager'];
$error = '';

// Check and Create Table if missing (Auto-fix for update)
$table_name = NV_PREFIXLANG . "_" . $module_data . "_topics";
$sql_check = "SHOW TABLES LIKE '" . $table_name . "'";
if ($db->query($sql_check)->fetchColumn() != $table_name) {
    $sql_create = "CREATE TABLE " . $table_name . " (
        id mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
        title varchar(255) NOT NULL,
        note text,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    $db->query($sql_create);
}

// Process Form Submit
if ($nv_Request->isset_request('save', 'post')) {
    $row = array();
    $row['id'] = $nv_Request->get_int('id', 'post', 0);
    $row['title'] = $nv_Request->get_string('title', 'post', '');
    $row['note'] = $nv_Request->get_string('note', 'post', '');

    if (empty($row['title'])) {
        $error = $lang_module['error_title_empty'];
    } else {
        if ($row['id'] > 0) {
            $stmt = $db->prepare("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_topics SET title = :title, note = :note WHERE id = :id");
            $stmt->bindParam(':id', $row['id'], PDO::PARAM_INT);
        } else {
            $stmt = $db->prepare("INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . "_topics (title, note) VALUES (:title, :note)");
        }
        $stmt->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $stmt->bindParam(':note', $row['note'], PDO::PARAM_STR);

        if ($stmt->execute()) {
            Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_global['abbr'] . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=topics');
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
        $stmt = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_topics WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            die('OK');
        }
    }
    die('NO');
}

$xtpl = new XTemplate('topics.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', 'topics');

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

// List Topics
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topics ORDER BY id DESC";
$result = $db->query($sql);
$row = array();

while ($item = $result->fetch()) {
    $xtpl->assign('ROW', $item);
    $xtpl->parse('main.list.row');
}
$xtpl->parse('main.list');

// Add/Edit Form
$id = $nv_Request->get_int('id', 'get', 0);
if ($id > 0) {
    $stmt = $db->prepare("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_topics WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch();
} else {
    $row = array('id' => 0, 'title' => '', 'note' => '');
}

$xtpl->assign('DATA', $row);

$xtpl->parse('main.form');
$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
