<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_TU_VI')) {
    die('Stop!!!');
}

// Check Permissions
$module_table_name = str_replace('-', '_', $module_data);
$table_config = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_config";
// Note: In merged config, we might want to namespace this key if tu-vi has its own groups_view.
// For now assuming shared or unique enough.
$sql = "SELECT config_value FROM " . $table_config . " WHERE config_name = 'groups_view'";
$result = $db->query($sql);
$row = $result->fetch();

if (!empty($row)) {
    $allowed_groups = explode(',', $row['config_value']);
    if (!empty($allowed_groups) && !nv_user_in_groups($allowed_groups)) {
        $contents = "Bạn không có quyền xem nội dung này. Vui lòng đăng nhập hoặc liên hệ quản trị viên.";
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        exit;
    }
}

$page_title = $lang_module['date_main_title'];
$key_words = $module_info['keywords'];

// Load CSS (Assuming tu-vi.css contains merged styles)
$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/css/tu-vi.css">';

$xtpl = new XTemplate('date.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('URL_FUNERAL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=funeral');
$xtpl->assign('URL_WEDDING', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=wedding');
$xtpl->assign('URL_CONSTRUCTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=construction');
$xtpl->assign('URL_GRAND_OPENING', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=grand_opening');

// Fetch Custom Events
$module_table_name = str_replace('-', '_', $module_data);
$table_events = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_events";
try {
    $sql = "SELECT id, title, description FROM " . $table_events . " ORDER BY id ASC";
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $row['url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=custom&id=' . $row['id'];
        $xtpl->assign('EVENT', $row);
        $xtpl->parse('main.event_loop');
    }
} catch (PDOException $e) {
    // Ignore if table missing
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
