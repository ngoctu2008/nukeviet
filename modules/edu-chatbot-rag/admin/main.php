<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$page_title = $lang_module['config'];

// Get current config
$sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config";
$result = $db->query($sql);
$mod_config = [];
while ($row = $result->fetch()) {
    $mod_config[$row['config_name']] = $row['config_value'];
}

$error = '';
if ($nv_Request->isset_request('save', 'post')) {
    if (!check_sess_token($nv_Request->get_string('checksess', 'post', ''))) {
        $error = $lang_global['error_token'];
    } else {
        $data = [
            'openai_api_key' => $nv_Request->get_title('openai_api_key', 'post', ''),
            'pinecone_api_key' => $nv_Request->get_title('pinecone_api_key', 'post', ''),
            'pinecone_url' => $nv_Request->get_title('pinecone_url', 'post', ''),
            'system_prompt' => $nv_Request->get_editor('system_prompt', '', NV_ALLOWED_HTML_TAGS),
            'similarity_threshold' => $nv_Request->get_float('similarity_threshold', 'post', 0.7)
        ];

        foreach ($data as $config_name => $config_value) {
            $stmt = $db->prepare("UPDATE " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_config SET config_value = :config_value WHERE config_name = :config_name");
            $stmt->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $stmt->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $stmt->execute();
        }

        $nv_Cache->delMod($module_name);
        Header("Location: " . NV_BASE_ADMINURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&" . NV_NAME_VARIABLE . "=" . $module_name . "&" . NV_OP_VARIABLE . "=" . $op);
        die();
    }
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('MODULE_UPLOAD', $module_upload);
$xtpl->assign('OP', $op);
$xtpl->assign('DATA', $mod_config);

if (!empty($error)) {
    $xtpl->assign('ERROR', $error);
    $xtpl->parse('main.error');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
