<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['config'] = $lang_module['config'];
$submenu['main'] = $lang_module['log_list'];
$allow_func = array('main', 'config');
