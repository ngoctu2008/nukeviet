<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    die('Stop!!!');
}

define('NV_IS_FILE_ADMIN', true);

// Get list of units for dropdowns
function nv_get_units()
{
    global $db, $module_data;
    $sql = "SELECT id, title FROM " . NV_PREFIXLANG . "_" . $module_data . "_units WHERE status=1 ORDER BY weight ASC";
    $result = $db->query($sql);
    $units = array();
    while ($row = $result->fetch()) {
        $units[$row['id']] = $row['title'];
    }
    return $units;
}

$allow_func = array('main', 'exam', 'units', 'questions', 'report', 'result', 'save', 'topics');
