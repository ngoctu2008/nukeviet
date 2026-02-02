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

// Include classes manually if autoload fails
$classes = ['TuViLapSo', 'TuViLuanGiai', 'FengShuiUtils', 'LunarCalendar'];
foreach ($classes as $cls) {
    $file = NV_ROOTDIR . '/modules/' . $module_file . '/classes/' . $cls . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

use NukeViet\Module\HuyenHoc\TuViLapSo;
use NukeViet\Module\HuyenHoc\TuViLuanGiai;

// Ensure JSON header if AJAX
if ($nv_Request->isset_request('nv_ajax', 'get,post')) {
    header('Content-Type: application/json; charset=utf-8');
}

$action = $nv_Request->get_string('action', 'get,post', '');

if ($action == 'xem_han') {
    try {
        // Inputs: chiYear (of Birth), gender, targetYear
        $chiYear = $nv_Request->get_int('chiYear', 'post', 0);
        $gender = $nv_Request->get_int('gender', 'post', 1);
        $targetYear = $nv_Request->get_int('targetYear', 'post', date('Y'));

        // 1. Calculate Limits
        $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $gender, $targetYear);

        $html = '<div class="alert alert-info">';
        $html .= '<h4>Kết quả năm ' . $targetYear . ' (' . $limitInfo['target_chi'] . ')</h4>';
        $html .= '<p><strong>Tiểu vận tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['tieu_van_idx']] . '</p>';
        $html .= '<p><strong>Lưu Thái Tuế tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['luu_thai_tue_idx']] . '</p>';
        $html .= '<hr>';
        $html .= '<p><em>(Lời giải chi tiết đang được cập nhật từ dữ liệu mẫu...)</em></p>';
        $html .= '<p>Năm nay hành hạn đi vào cung ' . TuViLapSo::$DIA_CHI[$limitInfo['tieu_van_idx']] . ', cần chú ý các sao tọa thủ tại đây.</p>';
        $html .= '</div>';

        // Clean buffer
        if (ob_get_length()) ob_end_clean();
        echo json_encode(['status' => 'success', 'html' => $html]);
        die();
    } catch (\Exception $e) {
        if (ob_get_length()) ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        die();
    } catch (\Throwable $e) {
        if (ob_get_length()) ob_end_clean();
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        die();
    }
}

die('Unknown Action');
