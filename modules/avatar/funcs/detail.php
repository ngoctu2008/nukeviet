<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Copyright (C) 2024 Phạm Ngọc Tú. All rights reserved
 *
 * @Createdate Dec 19, 2025
 */

if (!defined('NV_IS_MOD_AVATAR')) {
    die('Stop!!!');
}

// Global variables set in functions.php
global $id;

if (empty($id) || $id == 0) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}
$id = intval($id); // Security: cast to int

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

// Update views
$db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET views=views+1 WHERE id=" . $id);

$cat_info = nv_avatar_get_cat($row['catid']);
// Check permissions via category
if (!defined('NV_IS_ADMIN')) {
    if (!empty($cat_info['groups_view'])) {
        $groups_view = explode(',', $cat_info['groups_view']);
        if (!nv_user_in_groups($groups_view)) {
             nv_info_die($lang_global['error_403_title'], $lang_global['error_403_title'], $lang_global['error_403_content']);
        }
    }
}

if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
    $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
}
$cat_info['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'];

$page_title = $row['title'];
$key_words = $row['title'];

$xtpl = new XTemplate('detail.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ROW', $row);
$xtpl->assign('CAT_INFO', $cat_info);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
