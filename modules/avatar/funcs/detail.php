<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Phạm Ngọc Tú <ngoctu.dnkd@gmail.com>
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
$id = intval($id);

$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_rows WHERE id=" . $id;
$row = $db->query($sql)->fetch();

if (empty($row)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

// Update views
$db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . "_rows SET views=views+1 WHERE id=" . $id);

$cat_info = nv_avatar_get_cat($row['catid']);

// Check View Permissions
if (!defined('NV_IS_ADMIN')) {
    if (!empty($cat_info['groups_view'])) {
        $groups_view = explode(',', $cat_info['groups_view']);
        if (!nv_user_in_groups($groups_view)) {
             nv_info_die($lang_global['error_403_title'], $lang_global['error_403_title'], $lang_global['error_403_content']);
        }
    }
}

// Check Use Permissions
$allow_use = true;
if (!defined('NV_IS_ADMIN')) {
    if (!empty($cat_info['groups_use'])) {
        $groups_use = explode(',', $cat_info['groups_use']);
        if (!nv_user_in_groups($groups_use)) {
             $allow_use = false;
        }
    }
}

$cat_info['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'];

$page_title = $row['title'];
$key_words = $row['title'];

$contents = nv_theme_avatar_detail($row, $cat_info, $allow_use);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
