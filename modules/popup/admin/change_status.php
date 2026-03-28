<?php

/**
 * @Project NUKEVIET 4.5.07
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
 * @Copyright (C) 2026 Phạm Ngọc Tú. All rights reserved
 * @Createdate Sat, 07/02/2026 06:27:27 GMT
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

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
