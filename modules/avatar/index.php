<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOUR NAME (email@domain.com)
 * @Copyright (C) 2025
 * @License GNU/GPLv3
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_AVATAR', true);

if ($op == 'main') {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/funcs/main.php';
} elseif ($op == 'viewcat') {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/funcs/viewcat.php';
} elseif ($op == 'detail') {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/funcs/detail.php';
} else {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/funcs/main.php';
}
