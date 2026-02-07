<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_MAINFILE')) {
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
