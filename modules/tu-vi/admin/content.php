<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$page_title = "Manage Interpretations";
$table_name = $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . str_replace('-', '_', $module_data) . '_interpretations';

$error = '';
$row = [];

// Process Form
if ($nv_Request->isset_request('save', 'post')) {
    $row['star_key'] = $nv_Request->get_title('star_key', 'post', '');
    $row['palace_key'] = $nv_Request->get_title('palace_key', 'post', '');
    $row['content'] = $nv_Request->get_string('content', 'post', '', true); // HTML content
    $row['topic'] = $nv_Request->get_title('topic', 'post', 'tong_quan');

    if (empty($row['star_key'])) $error = "Star Key required";
    else {
        // Insert
        $sql = "INSERT INTO " . $table_name . "
        (star_key, palace_key, topic, content, weight)
        VALUES (:star_key, :palace_key, :topic, :content, 0)";

        $sth = $db->prepare($sql);
        $sth->bindValue(':star_key', $row['star_key'], PDO::PARAM_STR);
        $sth->bindValue(':palace_key', $row['palace_key'], PDO::PARAM_STR);
        $sth->bindValue(':topic', $row['topic'], PDO::PARAM_STR);
        $sth->bindValue(':content', $row['content'], PDO::PARAM_STR);

        if ($sth->execute()) {
            $error = "Saved successfully";
        } else {
            $error = "DB Error";
        }
    }
}

$xtpl = new XTemplate('content.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ERROR', $error);

// Render Import/Export section
$xtpl->parse('main.import_export');

// Render List
$sql = "SELECT * FROM " . $table_name . " ORDER BY id DESC LIMIT 50";

// Handle table existence safety (in case user hasn't reinstalled)
try {
    $result = $db->query($sql);
    while ($item = $result->fetch()) {
        $xtpl->assign('ITEM', $item);
        $xtpl->parse('main.list.row');
    }
    $xtpl->parse('main.list');
} catch (PDOException $e) {
    $error = "Table not found. Please Deactivate and Reactivate the module to create tables.";
    $xtpl->assign('ERROR', $error);
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
