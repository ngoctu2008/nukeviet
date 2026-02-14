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
global $catid, $module_config;

if ($catid == 0) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

$cat_info = nv_avatar_get_cat($catid);
if (empty($cat_info)) {
    nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content']);
}

// Check permissions
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

$per_page = isset($module_config['per_page_row']) ? intval($module_config['per_page_row']) : 20;

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'];
$page = $nv_Request->get_int('page', 'get', 1);

$db->sqlreset()
    ->select('COUNT(*)')
    ->from(NV_PREFIXLANG . '_' . $module_data . '_rows')
    ->where('status=1 AND catid=' . $catid);
$num_items = $db->query($db->sql())->fetchColumn();

$db->select('id, title, alias, image, description, body, views, downloads')
    ->limit($per_page)
    ->offset(($page - 1) * $per_page)
    ->order('weight ASC');
$list = $db->query($db->sql())->fetchAll();

$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$viewcat = !empty($cat_info['viewcat']) ? $cat_info['viewcat'] : 'view_grid';

$contents = nv_theme_avatar_viewcat($viewcat, $cat_info, $list, $generate_page, $allow_use);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
