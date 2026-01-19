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

$page_title = "Import Interpretations (JSON)";
$table_name = $db_config['prefix'] . '_' . $lang . '_' . str_replace('-', '_', $module_data) . '_interpretations';

$error = '';
$msg = '';

if ($nv_Request->isset_request('submit', 'post')) {
    if (isset($_FILES['import_file']) && $_FILES['import_file']['error'] == 0) {
        $content = file_get_contents($_FILES['import_file']['tmp_name']);
        $data = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            $count = 0;
            $stmt = $db->prepare("INSERT INTO " . $table_name . " (star_key, palace_key, topic, content, weight) VALUES (:star_key, :palace_key, :topic, :content, :weight)");

            foreach ($data as $row) {
                // Basic validation
                if (isset($row['star_key'])) {
                    $stmt->bindValue(':star_key', $row['star_key']);
                    $stmt->bindValue(':palace_key', $row['palace_key'] ?? '');
                    $stmt->bindValue(':topic', $row['topic'] ?? 'tong_quan');
                    $stmt->bindValue(':content', $row['content'] ?? '');
                    $stmt->bindValue(':weight', $row['weight'] ?? 0);
                    $stmt->execute();
                    $count++;
                }
            }
            $msg = "Imported $count records successfully.";
        } else {
            $error = "Invalid JSON format.";
        }
    } else {
        $error = "Upload failed.";
    }
}

$xtpl = new XTemplate('import.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ERROR', $error);
$xtpl->assign('MSG', $msg);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
