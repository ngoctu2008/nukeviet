<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
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
