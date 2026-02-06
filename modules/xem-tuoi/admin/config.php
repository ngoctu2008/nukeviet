<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

if ($nv_Request->isset_request('save_config', 'post')) {
    // CSRF Protection
    if (!defined('NV_IS_AJAX') && !defined('NV_API_EXEC') && !defined('NV_IS_SPIDER')) {
        $checkss = $nv_Request->get_string('checkss', 'post', '');
        if ($checkss != md5($global_config['site_key'] . session_id())) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
        }
    }

    $array_config = [];
    $array_config['advice_high'] = $nv_Request->get_editor('advice_high', '', NV_ALLOWED_HTML_TAGS);
    $array_config['advice_medium'] = $nv_Request->get_editor('advice_medium', '', NV_ALLOWED_HTML_TAGS);
    $array_config['advice_low'] = $nv_Request->get_editor('advice_low', '', NV_ALLOWED_HTML_TAGS);

    try {
        $sth = $db->prepare("REPLACE INTO " . NV_CONFIG_GLOBALTABLE . " (lang, module, config_name, config_value) VALUES (:lang, :module_name, :config_name, :config_value)");

        foreach ($array_config as $config_name => $config_value) {
            $sth->bindParam(':lang', $lang, PDO::PARAM_STR);
            $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
            $sth->bindParam(':config_name', $config_name, PDO::PARAM_STR);
            $sth->bindParam(':config_value', $config_value, PDO::PARAM_STR);
            $sth->execute();
        }

        $nv_Cache->delMod($module_name);
        Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config&msg=saved');
        die();
    } catch (PDOException $e) {
        trigger_error($e->getMessage());
    }
}

$xtpl = new XTemplate('config.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=config');
$xtpl->assign('CHECKSS', md5($global_config['site_key'] . session_id()));

if ($nv_Request->isset_request('msg', 'get') && $nv_Request->get_string('msg', 'get') == 'saved') {
    $xtpl->parse('main.saved');
}

// Editor
if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    require_once NV_ROOTDIR . '/' . NV_EDITORSDIR . '/' . NV_EDITOR . '/nv.php';
}

$advice_high = $module_config[$module_name]['advice_high'] ?? $lang_module['advice_high'];
$advice_medium = $module_config[$module_name]['advice_medium'] ?? $lang_module['advice_medium'];
$advice_low = $module_config[$module_name]['advice_low'] ?? $lang_module['advice_low'];

if (defined('NV_EDITOR') and nv_function_exists('nv_aleditor')) {
    $xtpl->assign('EDITOR_HIGH', nv_aleditor('advice_high', '100%', '200px', $advice_high));
    $xtpl->assign('EDITOR_MEDIUM', nv_aleditor('advice_medium', '100%', '200px', $advice_medium));
    $xtpl->assign('EDITOR_LOW', nv_aleditor('advice_low', '100%', '200px', $advice_low));
} else {
    $xtpl->assign('EDITOR_HIGH', '<textarea style="width:100%;height:200px" name="advice_high">' . htmlspecialchars($advice_high) . '</textarea>');
    $xtpl->assign('EDITOR_MEDIUM', '<textarea style="width:100%;height:200px" name="advice_medium">' . htmlspecialchars($advice_medium) . '</textarea>');
    $xtpl->assign('EDITOR_LOW', '<textarea style="width:100%;height:200px" name="advice_low">' . htmlspecialchars($advice_low) . '</textarea>');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
