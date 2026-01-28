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

use NukeViet\Module\HuyenHoc\Divination;

$page_title = $lang_module['gieo_que'];

// Handle AJAX Request
if ($nv_Request->isset_request('api_get_result', 'post')) {
    $duration = $nv_Request->get_int('duration', 'post', 0);

    $divination = new Divination();
    $result = $divination->getKhongMinhHexagram($duration);

    if (ob_get_length()) ob_end_clean();
    header('Content-Type: application/json');

    if ($result) {
        echo json_encode($result);
    } else {
        echo json_encode(['error' => 'No result found']);
    }
    die();
}

$contents = nv_theme_huyen_hoc_gieo_que();

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
