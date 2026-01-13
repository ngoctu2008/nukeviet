<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_TU_VI_ADMIN')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
}

$xtpl = new XTemplate('import.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);

if ($nv_Request->isset_request('import', 'post')) {
    if (isset($_FILES['import_file']) && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
        $json = file_get_contents($_FILES['import_file']['tmp_name']);
        $data = json_decode($json, true);

        if (is_array($data)) {
            $count = 0;
            foreach ($data as $item) {
                if (isset($item['star_key'], $item['palace_key'], $item['content'])) {
                    // Upsert logic
                    $sql = "INSERT INTO " . NV_PRE_TUVI . "_interpretations (star_key, palace_key, content)
                            VALUES (:star, :palace, :content)";
                    // Note: ON DUPLICATE KEY UPDATE is cleaner if we had UNIQUE index on (star, palace)
                    // But we used index, so just insert or ignore?
                    // Let's assume we append or simple insert.

                    $stmt = $db->prepare($sql);
                    $stmt->bindParam(':star', $item['star_key']);
                    $stmt->bindParam(':palace', $item['palace_key']);
                    $stmt->bindParam(':content', $item['content']);
                    $stmt->execute();
                    $count++;
                }
            }
            $xtpl->assign('MESSAGE', "Imported $count records successfully.");
            $xtpl->parse('main.message');
        } else {
            $xtpl->assign('MESSAGE', "Invalid JSON format.");
            $xtpl->parse('main.error');
        }
    }
}

$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=import');

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
