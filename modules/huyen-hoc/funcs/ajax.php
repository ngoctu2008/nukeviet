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
        // Inputs: chiYear (of Birth), gender, targetYear, birthYear
        $chiYear = $nv_Request->get_int('chiYear', 'post', 0);
        $gender = $nv_Request->get_int('gender', 'post', 1);
        $targetYear = $nv_Request->get_int('targetYear', 'post', date('Y'));
        $birthYear = $nv_Request->get_int('birthYear', 'post', date('Y')); // Added birthYear

        // 1. Calculate Limits
        $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $gender, $targetYear, $birthYear);

        $html = '<div class="alert alert-info">';
        $html .= '<h4>Kết quả năm ' . $targetYear . ' (' . $limitInfo['target_chi'] . ')</h4>';
        $html .= '<p><strong>Tuổi Âm:</strong> ' . $limitInfo['age_am'] . ' tuổi</p>'; // Added Age
        $html .= '<p><strong>Tiểu vận tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['tieu_van_idx']] . '</p>';
        $html .= '<p><strong>Lưu Thái Tuế tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['luu_thai_tue_idx']] . '</p>';

        // Add 9 Stars & Han
        $saoInfo = $limitInfo['sao_han'];
        $html .= '<p><strong>Sao chiếu mệnh:</strong> ' . $saoInfo['name'] . ' (' . ($saoInfo['type']=='tot'?'Tốt':($saoInfo['type']=='xau'?'Xấu':'Trung')) . ')</p>';
        $html .= '<p><strong>Hạn:</strong> ' . $limitInfo['han']['name'] . '</p>';
        $html .= '<p><strong>Tam Tai:</strong> ' . ($limitInfo['tam_tai'] ? '<span class="text-danger">Có</span>' : 'Không') . '</p>';
        $html .= '</div>';

        // Luan Giai Chi Tiet
        $interpreter = new TuViLuanGiai();

        // Luu Stars Reading
        $luuComments = $interpreter->analyzeLuuStars($limitInfo);
        if (!empty($luuComments)) {
            $html .= '<div class="card mb-3"><div class="card-header">Luận Giải Các Sao Lưu</div><div class="card-body">';
            foreach ($luuComments as $comment) {
                $html .= '<p><i class="fa fa-star-o"></i> ' . $comment . '</p>';
            }
            $html .= '</div></div>';
        }

        // Monthly Limits Table
        $html .= '<div class="card mb-3"><div class="card-header">Vận Hạn Các Tháng (Nguyệt Hạn)</div><div class="card-body">';
        $html .= '<table class="table table-bordered table-sm"><thead><tr><th>Tháng</th><th>Cung Hạn</th><th>Diễn Biến</th></tr></thead><tbody>';

        for ($m = 1; $m <= 12; $m++) {
            $monthIdx = TuViLapSo::getNguyetHan($limitInfo['tieu_van_idx'], $m, $gender);
            $palaceName = TuViLapSo::$DIA_CHI[$monthIdx];
            // Get reading for Nguyet Han at this palace
            // This requires full chart access which we don't have here easily without re-running LapSo.
            // But we can approximate or just list the Palace.
            // Ideally we should pass chart to ajax but chart is heavy.
            // For now, list Palace Name.
            $html .= '<tr><td>' . $m . '</td><td>' . $palaceName . '</td><td><em>Xem tại cung ' . $palaceName . '</em></td></tr>';
        }
        $html .= '</tbody></table></div>';

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
