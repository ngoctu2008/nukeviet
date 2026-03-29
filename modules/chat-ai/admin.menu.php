<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 28 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

// Ensure lang_module is available
if (!isset($lang_module)) {
    $lang_module = array();
    $lang_global = $global_config['site_lang'];
    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . $lang_global . '.php')) {
        include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . $lang_global . '.php';
    } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang_global . '.php')) {
        include NV_ROOTDIR . '/modules/' . $module_file . '/language/' . $lang_global . '.php';
    } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php')) {
        include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
    } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php')) {
        include NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
    }
}

$submenu['main'] = $lang_module['config'] ?? 'Configuration';
$submenu['knowledge'] = $lang_module['knowledge'] ?? 'Knowledge Base';
