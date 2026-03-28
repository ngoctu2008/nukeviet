<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

$allow_func = array('main', 'config', 'tu-vi', 'xem-tuoi', 'xem-ngay', 'lo-ban', 'dat-ten', 'sim-so', 'gieo-que');

define('NV_IS_FILE_ADMIN', true);
