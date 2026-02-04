<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'get', 0);
$status = $nv_Request->get_int('status', 'get', 0);

if ($id > 0) {
    $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET status=" . $status . " WHERE id=" . $id);
    $nv_Cache->delMod($module_name);
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name);
