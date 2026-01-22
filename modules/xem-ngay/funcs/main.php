<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_XEMNGAY')) {
    die('Stop!!!');
}

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$contents = "Under Construction";

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
