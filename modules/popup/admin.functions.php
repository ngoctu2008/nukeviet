<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$submenu['main'] = $lang_module['main'];
$submenu['content'] = $lang_module['add_popup'];
$submenu['report'] = $lang_module['report'];

$allow_func = ['main', 'content', 'report', 'change_status', 'del'];

define('NV_IS_FILE_ADMIN', true);
