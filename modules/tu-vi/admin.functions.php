<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

define('NV_IS_TU_VI_ADMIN', true);

// Add admin menu
$allow_func = ['main', 'content', 'config', 'import', 'export'];

// Include functions.php to get constants and classes
// NukeViet admin doesn't always load frontend functions.php automatically
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/functions.php')) {
    require_once NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
}

// Ensure NV_PRE_TUVI is defined if functions.php failed to define it (e.g. wrong context)
if (!defined('NV_PRE_TUVI')) {
    $module_table_prefix = str_replace('-', '_', $module_data);
    define('NV_PRE_TUVI', NV_PREFIXLANG . '_' . $module_table_prefix);
}

define('NV_IS_FILE_ADMIN', true);
