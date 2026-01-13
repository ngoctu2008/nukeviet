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

$page_title = $lang_module['config'];

// Ensure we use the underscore version for table queries
$module_data_table = str_replace('-', '_', $module_data);

if ($nv_Request->isset_request('save', 'post')) {
    $array_config = array();
    $array_config['provider'] = $nv_Request->get_string('provider', 'post', 'openai');
    $array_config['api_key'] = $nv_Request->get_string('api_key', 'post', '');
    $array_config['model'] = $nv_Request->get_string('model', 'post', 'gpt-3.5-turbo');
    $array_config['system_prompt'] = $nv_Request->get_string('system_prompt', 'post', '');
    $array_config['use_news'] = $nv_Request->get_int('use_news', 'post', 0);
    $array_config['use_laws'] = $nv_Request->get_int('use_laws', 'post', 0);
    $array_config['search_limit'] = $nv_Request->get_int('search_limit', 'post', 3);
    $array_config['history_limit'] = $nv_Request->get_int('history_limit', 'post', 5);

    foreach ($array_config as $config_name => $config_value) {
        $db->query("REPLACE INTO " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_config (config_name, config_value) VALUES (" . $db->quote($config_name) . ", " . $db->quote($config_value) . ")");
    }

    $nv_Cache->delMod($module_name);
    Header('Location: ' . NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=main');
    die();
}

$sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_table . "_config";
$result = $db->query($sql);
$array_config = array();
while ($row = $result->fetch()) {
    $array_config[$row['config_name']] = $row['config_value'];
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/admin_default/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('OP', 'main');

$xtpl->assign('DATA', $array_config);

if ($array_config['provider'] == 'openai') {
    $xtpl->assign('SELECTED_OPENAI', 'selected="selected"');
} elseif ($array_config['provider'] == 'gemini') {
    $xtpl->assign('SELECTED_GEMINI', 'selected="selected"');
}

if (!empty($array_config['use_news'])) {
    $xtpl->assign('CHECKED_NEWS', 'checked="checked"');
}
if (!empty($array_config['use_laws'])) {
    $xtpl->assign('CHECKED_LAWS', 'checked="checked"');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
