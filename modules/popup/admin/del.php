<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NV Systems
 * @Createdate 2023
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    die('Stop!!!');
}

$id = $nv_Request->get_int('id', 'post', 0);

if ($id > 0) {
    $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id);
    $db->query("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . "_stats WHERE popup_id=" . $id);

    // Clear cache if needed (optional)
    $nv_Cache->delMod($module_name);

    if ($nv_Request->isset_request('id', 'post')) {
        die('OK');
    }
}

nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name);
