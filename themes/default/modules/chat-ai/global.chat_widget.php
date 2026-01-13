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

$xtpl->parse('main');
$content = $xtpl->text('main');
