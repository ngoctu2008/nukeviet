<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_MOD_POPUP')) {
    die('Stop!!!');
}

$action = $nv_Request->get_string('action', 'post', '');

if ($action == 'log') {
    $id = $nv_Request->get_int('id', 'post', 0);
    $type = $nv_Request->get_string('type', 'post', '');

    if ($id > 0 && in_array($type, ['view', 'click', 'close'])) {
        $today = mktime(0, 0, 0, date("m"), date("d"), date("Y"));

        $views = ($type == 'view') ? 1 : 0;
        $clicks = ($type == 'click') ? 1 : 0;
        $closes = ($type == 'close') ? 1 : 0;

        // Use standard NV4 db->query for ON DUPLICATE KEY UPDATE as it's cleaner than PDO bind for dynamic updates often
        // But let's try to be safe.

        $table = $db_config['prefix'] . "_" . NV_LANG_DATA . "_popup_stats";

        $sql = "INSERT INTO " . $table . " (popup_id, add_time, views, clicks, closes)
                VALUES (" . $id . ", " . $today . ", " . $views . ", " . $clicks . ", " . $closes . ")
                ON DUPLICATE KEY UPDATE
                views = views + " . $views . ",
                clicks = clicks + " . $clicks . ",
                closes = closes + " . $closes;

        $db->query($sql);
    }
    die('OK');
}
