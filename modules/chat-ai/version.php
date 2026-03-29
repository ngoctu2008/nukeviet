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

$module_version = array(
    'name' => 'Chat AI',
    'modfuncs' => 'main,ajax',
    'change_alias' => 'main,ajax',
    'submenu' => 'main,knowledge',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.07',
    'date' => 'Mon, 28 Oct 2024 00:00:00 GMT',
    'author' => 'Jules',
    'uploads_dir' => array($module_name),
    'note' => 'Module Chat AI with RAG support'
);
