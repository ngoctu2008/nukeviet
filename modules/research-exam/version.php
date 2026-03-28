<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

$module_version = array(
    'name' => 'Research Exam',
    'modfuncs' => 'main,detail,test,history',
    'change_alias' => 'main,detail,test,history',
    'submenu' => 'main,detail,test,history',
    'is_sysmod' => 0,
    'virtual' => 1,
    'version' => '4.5.07',
    'date' => 'Wed, 23 Oct 2024 00:00:00 GMT',
    'author' => 'Jules',
    'uploads_dir' => array($module_name),
    'note' => 'Module tổ chức cuộc thi trực tuyến tìm hiểu về nghị quyết, văn bản pháp luật'
);
