<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_SYSTEM')) {
    exit('Stop!!!');
}

define('NV_IS_MOD_TU_VI', true);

// Include Core Logic
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/Lunisolar.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/Horoscope.php';

// Helper to check for existing tables or mapping if needed
$module_table_prefix = str_replace('-', '_', $module_data);
define('NV_PRE_TUVI', $db_config['prefix'] . '_' . $lang . '_' . $module_table_prefix);

/**
 * Common functions for Tu Vi can be added here
 */
