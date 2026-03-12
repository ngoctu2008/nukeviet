<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules <jules@nukeviet.vn>
 * @Copyright (C) 2023 Jules. All rights reserved
 * @License: Not free read more http://nukeviet.vn/vi/store/modules/nvtools/
 * @Createdate Mon, 10 Mar 2025 00:00:00 GMT
 */

if (!defined('NV_IS_FILE_MODULES')) {
    die('Stop!!!');
}

$module_version = [
    'name' => 'edu-chatbot-rag',
    'modfuncs' => 'main,chat',
    'change_alias' => 'main',
    'submenu' => 'main,documents,logs',
    'is_sysmod' => 0,
    'virtual' => 0,
    'version' => '1.0.0',
    'date' => 'Mon, 10 Mar 2025 00:00:00 GMT',
    'author' => 'Jules <jules@nukeviet.vn>',
    'uploads_dir' => [$module_name],
    'note' => ''
];
