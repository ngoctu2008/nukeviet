<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_SYSTEM')) {
    die('Stop!!!');
}

define('NV_IS_MOD_HUYEN_HOC', true);

// Autoload classes if needed (or rely on NukeViet's autoloader if configured,
// but usually modules need to require their classes if they are not in standard system paths)
// However, since we used namespaces and structure, we might need to include them manually
// or register an autoloader. For simplicity in this skeleton, we'll require them in controllers
// or here if they are global.

require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/FengShuiUtils.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/LunarCalendar.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/TuViLapSo.php';

// Require other classes
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/XemTuoi.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/XemNgay.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/LoBan.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/SimPhongThuy.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/NameAnalysis.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/Divination.php';
