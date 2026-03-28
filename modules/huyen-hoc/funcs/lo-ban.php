<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_HUYEN_HOC')) {
    die('Stop!!!');
}

use NukeViet\Module\HuyenHoc\LoBan;

$page_title = $lang_module['lo_ban'];

$result = array();
$length = $nv_Request->get_float('length', 'post,get', 0);

if ($length > 0) {
    $result = LoBan::calculate($length);
}

$contents = nv_theme_huyen_hoc_lo_ban($result, $length);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
