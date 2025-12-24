<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOUR NAME (email@domain.com)
 * @Copyright (C) 2025
 * @License GNU/GPLv3
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

$per_page = isset($module_config['per_page']) ? intval($module_config['per_page']) : 20;

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

$xtpl = new XTemplate($viewcat . '.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('CAT_INFO', $cat_info);
$xtpl->assign('GENERATE_PAGE', $generate_page);

foreach ($list as $row) {
    if (!empty($row['image']) and is_file(NV_UPLOADS_REAL_DIR . '/' . $module_upload . '/' . $row['image'])) {
        $row['image'] = NV_BASE_SITEURL . NV_UPLOADS_DIR . '/' . $module_upload . '/' . $row['image'];
    }
    // Link to detail view: domain.com/avatar/cat-alias/item-alias-id
    $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $cat_info['alias'] . "/" . $row['alias'] . "-" . $row['id'];

    $xtpl->assign('ROW', $row);
    $xtpl->parse('main.row');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
