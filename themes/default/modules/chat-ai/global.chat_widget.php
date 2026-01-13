<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$xtpl = new XTemplate('block_chat_widget.tpl', NV_ROOTDIR . '/themes/default/modules/chat-ai');
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('LANG', $lang_module);

// Pass Language strings to JS
$js_lang = [
    'type_message' => $lang_module['type_message'],
    'error_empty' => $lang_module['error_empty']
];
$xtpl->assign('JS_LANG', json_encode($js_lang));

// Get Config for Position
// Note: We need to know which module data this is.
// Since this is a global block, we might not know the exact module name if it's not the active module.
// But we can assume the module directory is 'chat-ai' or check site mods.
// Standard way: Use $module_name if active, or hardcode/find 'chat-ai'.
// Since this block is part of 'chat-ai' module, we'll try to find its config.

$module_data_config = 'chat_ai'; // Default sanitized name
// Try to find if installed under a different alias
foreach ($site_mods as $mod => $info) {
    if ($info['module_file'] == 'chat-ai') {
        $module_data_config = str_replace('-', '_', $info['module_data']);
        break;
    }
}

$sql = "SELECT config_name, config_value FROM " . $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_data_config . "_config WHERE config_name IN ('widget_bottom', 'widget_right')";
$result = $db->query($sql);
$widget_config = ['widget_bottom' => 20, 'widget_right' => 20];

while ($row = $result->fetch()) {
    $widget_config[$row['config_name']] = intval($row['config_value']);
}

$xtpl->assign('WIDGET_BOTTOM', $widget_config['widget_bottom']);
$xtpl->assign('WIDGET_RIGHT', $widget_config['widget_right']);

$xtpl->parse('main');
$content = $xtpl->text('main');
