<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

$submenu['main'] = $lang_module['config'];
$submenu['documents'] = $lang_module['documents'];
$submenu['logs'] = $lang_module['logs'];

$allow_func = ['main', 'documents', 'logs'];
