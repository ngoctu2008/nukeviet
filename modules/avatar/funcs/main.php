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

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$per_page = isset($module_config['per_page_cat']) ? intval($module_config['per_page_cat']) : 20;
$page = $nv_Request->get_int('page', 'get', 1);

// Count total top-level categories
$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=0 AND status=1";
$num_items = $db->query($sql)->fetchColumn();

// Get top level categories with pagination
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=0 AND status=1 ORDER BY weight ASC LIMIT " . (($page - 1) * $per_page) . "," . $per_page;
$result = $db->query($sql);
$array_cat = array();
while ($row = $result->fetch()) {
    // Check permissions
    $check_perm = true;
    if (!defined('NV_IS_ADMIN')) {
        if (!empty($row['groups_view'])) {
            $groups_view = explode(',', $row['groups_view']);
            if (!nv_user_in_groups($groups_view)) {
                 $check_perm = false;
            }
        }
    }

    if ($check_perm) {
        $row['link'] = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name . "&amp;" . NV_OP_VARIABLE . "=" . $row['alias'];
        $array_cat[] = $row;
    }
}

$base_url = NV_BASE_SITEURL . "index.php?" . NV_LANG_VARIABLE . "=" . NV_LANG_DATA . "&amp;" . NV_NAME_VARIABLE . "=" . $module_name;
$generate_page = nv_generate_page($base_url, $num_items, $per_page, $page);

$contents = nv_theme_avatar_main($array_cat, $generate_page);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
