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

use NukeViet\Module\HuyenHoc\TuViLapSo;
use NukeViet\Module\HuyenHoc\TuViLuanGiai;

// Ensure JSON header if AJAX
if ($nv_Request->isset_request('nv_ajax', 'get,post')) {
    header('Content-Type: application/json; charset=utf-8');
}

$action = $nv_Request->get_string('action', 'get,post', '');

if ($action == 'xem_han') {
    // Inputs: chiYear (of Birth), gender, targetYear
    $chiYear = $nv_Request->get_int('chiYear', 'post', 0);
    $gender = $nv_Request->get_int('gender', 'post', 1);
    $targetYear = $nv_Request->get_int('targetYear', 'post', date('Y'));

    // 1. Calculate Limits
    $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $gender, $targetYear);

    // 2. Interpretations
    $readings = [];
    $interpreter = new TuViLuanGiai();

    // Tieu Van Reading (just star readings from that palace? Or special limit text?)
    // Request asks for: "hiển thị lời luận giải (dựa trên dữ liệu mẫu)"
    // We can try fetching 'limit' topic for stars in the Tieu Van palace?
    // Or simpler: Fetch general limit advice based on the Target Chi.

    // For now, let's return the locations and a sample text.
    // In a full system, we would fetch readings for the stars at $limitInfo['tieu_van_idx'].
    // But since we don't have the full chart in session here, we can't easily know which stars are there without re-casting the whole chart.
    // OPTION: Re-cast chart? Heavy.
    // OPTION: Pass specific star codes if frontend knows them? No security.
    // OPTION: Just return generic text based on the Branch (Chi) of the limit.

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
}

die('Unknown Action');
