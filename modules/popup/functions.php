<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_POPUP', true);

// Popup Types
define('POPUP_TYPE_MODAL', 'modal');
define('POPUP_TYPE_BAR_TOP', 'bar_top');
define('POPUP_TYPE_BAR_BOTTOM', 'bar_bottom');
define('POPUP_TYPE_CORNER_LEFT', 'corner_left');
define('POPUP_TYPE_CORNER_RIGHT', 'corner_right');

// Trigger Types
define('POPUP_TRIGGER_IMMEDIATE', 'immediate');
define('POPUP_TRIGGER_DELAY', 'delay');
define('POPUP_TRIGGER_SCROLL', 'scroll');

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
