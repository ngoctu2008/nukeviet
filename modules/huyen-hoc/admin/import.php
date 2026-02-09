<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = 'Nhập Dữ Liệu Luận Giải';

// Get the correct table name
$table_interpretations = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . str_replace('-', '_', $module_data) . "_interpretations";

$error = '';
$success = '';

if ($nv_Request->isset_request('import', 'post')) {
    $jsonFile = NV_ROOTDIR . '/modules/' . $module_file . '/data/tu_vi_sample_full.json';

    if (file_exists($jsonFile)) {
        $jsonContent = file_get_contents($jsonFile);
        $data = json_decode($jsonContent, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
            // Truncate table
            $db->query("TRUNCATE TABLE " . $table_interpretations);

            $count = 0;
            // Prepare statement for efficiency
            $sql = "INSERT INTO " . $table_interpretations . " (star_key, palace_key, topic, content) VALUES (:star, :palace, :topic, :content)";
            $stmt = $db->prepare($sql);

            foreach ($data as $row) {
                if (isset($row['star_key'], $row['palace_key'], $row['content'])) {
                    $topic = isset($row['topic']) ? $row['topic'] : 'main';
                    $stmt->bindValue(':star', $row['star_key']);
                    $stmt->bindValue(':palace', $row['palace_key']);
                    $stmt->bindValue(':topic', $topic);
                    $stmt->bindValue(':content', $row['content']);
                    if ($stmt->execute()) {
                        $count++;
                    }
                }
            }

            $success = "Đã nhập thành công " . $count . " bản ghi vào cơ sở dữ liệu.";
            $nv_Cache->delMod($module_name);
        } else {
            $error = "Lỗi: File JSON không hợp lệ hoặc bị hỏng.";
        }
    } else {
        $error = "Lỗi: Không tìm thấy file dữ liệu tại " . $jsonFile;
    }
}

$xtpl = new XTemplate('import.tpl', NV_ROOTDIR . '/themes/admin_default/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('CHECKSS', md5($global_config['sitekey'] . session_id()));

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}
if (!empty($success)) {
    $xtpl->assign('SUCCESS', $success);
    $xtpl->parse('main.success');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
