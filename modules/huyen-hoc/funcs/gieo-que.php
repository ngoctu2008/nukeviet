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

// Include class manually
require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/GieoQue.php';

use NukeViet\Module\HuyenHoc\GieoQue;

$page_title = $lang_module['gieo_que'];

// Handle AJAX Request
if ($nv_Request->isset_request('api_get_result', 'post')) {
    $duration = $nv_Request->get_int('duration', 'post', 0);

    try {
        $app = new GieoQue();
        // Use duration as seed? Or just random.
        // random_int is better.
        $app->gieoNgauNhien();
        $result = $app->layKetQua();
    } catch (\Throwable $e) {
        $result = ['error' => 'System error: ' . $e->getMessage()];
    }

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
