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

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

// Get top level categories
$sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . "_cat WHERE parentid=0 AND status=1 ORDER BY weight ASC";
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

$contents = nv_theme_avatar_main($array_cat);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
