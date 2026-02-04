<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    die('Stop!!!');
}

// Load common definitions (Constants) from site functions
require_once NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';

$submenu['main'] = $lang_module['main'];
$submenu['content'] = $lang_module['add_popup'];
$submenu['report'] = $lang_module['report'];

$allow_func = ['main', 'content', 'report', 'change_status', 'del'];

define('NV_IS_FILE_ADMIN', true);

function nv_get_popup_types()
{
    global $lang_module;
    return [
        POPUP_TYPE_MODAL => $lang_module['type_modal'],
        POPUP_TYPE_BAR_TOP => $lang_module['type_bar_top'],
        POPUP_TYPE_BAR_BOTTOM => $lang_module['type_bar_bottom'],
        POPUP_TYPE_CORNER_LEFT => $lang_module['type_corner_left'],
        POPUP_TYPE_CORNER_RIGHT => $lang_module['type_corner_right'],
    ];
}

function nv_get_trigger_types()
{
    global $lang_module;
    return [
        POPUP_TRIGGER_IMMEDIATE => $lang_module['trigger_immediate'],
        POPUP_TRIGGER_DELAY => $lang_module['trigger_delay'],
        POPUP_TRIGGER_SCROLL => $lang_module['trigger_scroll'],
    ];
}
