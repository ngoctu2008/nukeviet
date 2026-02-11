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
use NukeViet\Module\HuyenHoc\LunarCalendar;

// Ensure JSON header if AJAX
if ($nv_Request->isset_request('nv_ajax', 'get,post')) {
    header('Content-Type: application/json; charset=utf-8');
}

$action = $nv_Request->get_string('action', 'get,post', '');

if ($action == 'xem_han') {
    try {
        // Inputs
        $targetYear = $nv_Request->get_int('targetYear', 'post', date('Y'));
        $gender = $nv_Request->get_int('gender', 'post', 1);

        // Full Birth Data (Solar)
        $birthDay = $nv_Request->get_int('birthDay', 'post', 0);
        $birthMonth = $nv_Request->get_int('birthMonth', 'post', 0);
        $birthYear = $nv_Request->get_int('birthYear', 'post', 0);
        $birthHour = $nv_Request->get_int('birthHour', 'post', 0);

        // Fallback or Validate
        if ($birthDay == 0 || $birthYear == 0) {
             throw new Exception("Dữ liệu ngày sinh không hợp lệ.");
        }

        // 1. Convert Solar to Lunar & Get Chart
        // We need the full chart to know what stars are in each palace for the Monthly Limits
        $lunar = LunarCalendar::convertSolar2Lunar($birthDay, $birthMonth, $birthYear);
        $canChi = LunarCalendar::getCanChi($lunar['day'], $lunar['month'], $lunar['year'], $birthHour);

        $laSoData = TuViLapSo::lapLaSo(
            $lunar['day'],
            $lunar['month'],
            $lunar['year'],
            $birthHour,
            $gender,
            $canChi['canYear'],
            $canChi['chiYear'],
            'User' // Name doesn't matter for limits
        );

        $chart = $laSoData['dia_ban']; // 0..11 indexed
        $chiYear = $canChi['chiYear']; // Use calculated chiYear

        // 2. Calculate Limits
        // Ensure inputs are integers
        $targetYear = (int)$targetYear;
        $gender = (int)$gender;
        $birthYear = (int)$birthYear;

        // Validation
        if ($targetYear < $birthYear) {
             throw new Exception("Năm xem hạn phải lớn hơn hoặc bằng năm sinh ($birthYear).");
        }

        $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $gender, $targetYear, $birthYear);

        $html = '<div class="alert alert-info">';
        $html .= '<h4>Kết quả năm ' . $targetYear . ' (' . $limitInfo['target_chi'] . ')</h4>';
        $html .= '<p><strong>Tuổi Âm:</strong> ' . $limitInfo['age_am'] . ' tuổi</p>';

        $tieuVanPalace = isset($chart[$limitInfo['tieu_van_idx']]) ? $chart[$limitInfo['tieu_van_idx']]['palace_name'] : '';
        $html .= '<p><strong>Tiểu vận tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['tieu_van_idx']] . ' (' . $tieuVanPalace . ')</p>';

        $thaiTuePalace = isset($chart[$limitInfo['luu_thai_tue_idx']]) ? $chart[$limitInfo['luu_thai_tue_idx']]['palace_name'] : '';
        $html .= '<p><strong>Lưu Thái Tuế tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['luu_thai_tue_idx']] . ' (' . $thaiTuePalace . ')</p>';

        // Add 9 Stars & Han
        $saoInfo = $limitInfo['sao_han'];
        $html .= '<p><strong>Sao chiếu mệnh:</strong> <span class="text-' . ($saoInfo['type']=='tot'?'success':($saoInfo['type']=='xau'?'danger':'warning')) . '">' . $saoInfo['name'] . '</span> (' . ($saoInfo['type']=='tot'?'Tốt':($saoInfo['type']=='xau'?'Xấu':'Trung')) . ')</p>';

        $html .= '<p><strong>Hạn:</strong> ' . $limitInfo['han']['name'] . '</p>';
        $html .= '<p><strong>Tam Tai:</strong> ' . ($limitInfo['tam_tai'] ? '<span class="text-danger">Phạm Tam Tai</span>' : 'Không phạm') . '</p>';
        $html .= '<p><strong>Phạm Thái Tuế:</strong> ' . ($limitInfo['pham_thai_tue'] ? '<span class="text-danger">Có</span>' : 'Không') . '</p>';
        $html .= '</div>';

        // Luan Giai Chi Tiet Sao Luu
        $interpreter = new TuViLuanGiai();
        // Pass $limitInfo to interpreter if needed, or analyze manually
        // Since TuViLuanGiai might not have specific limit analysis methods exposed or context-aware without chart, we do basic here.
        // Actually, TuViLuanGiai might have analyzeLuuStars but we need to check if it accepts $limitInfo structure.
        // In previous steps, I might have added it. Let's assume yes or implement basic.
        // Checking previous read of ajax.php, it called $interpreter->analyzeLuuStars($limitInfo).

        if (method_exists($interpreter, 'analyzeLuuStars')) {
            $luuComments = $interpreter->analyzeLuuStars($limitInfo);
            if (!empty($luuComments)) {
                $html .= '<div class="card mb-3"><div class="card-header bg-primary text-white">Luận Giải Các Sao Lưu</div><div class="card-body">';
                foreach ($luuComments as $comment) {
                    $html .= '<p><i class="fa fa-star text-warning"></i> ' . $comment . '</p>';
                }
                $html .= '</div></div>';
            }
        }

        // Monthly Limits Table with Details
        $html .= '<div class="card mb-3"><div class="card-header bg-primary text-white">Vận Hạn Các Tháng (Nguyệt Hạn)</div><div class="card-body p-0">';
        $html .= '<table class="table table-striped table-bordered m-0"><thead><tr><th>Tháng</th><th>Cung Hạn</th><th>Sao Tọa Thủ (Diễn Biến)</th></tr></thead><tbody>';

        for ($m = 1; $m <= 12; $m++) {
            $monthIdx = TuViLapSo::getNguyetHan($limitInfo['tieu_van_idx'], $m, $gender);
            $palaceData = isset($chart[$monthIdx]) ? $chart[$monthIdx] : null;

            $palaceName = TuViLapSo::$DIA_CHI[$monthIdx];
            $detail = '';

            if ($palaceData) {
                $palaceName .= ' (' . $palaceData['palace_name'] . ')';

                // List Main Stars
                $stars = [];
                foreach ($palaceData['chinh_tinh'] as $s) {
                    $stars[] = '<strong>' . $s['name'] . '</strong>';
                }
                // List Good/Bad Stars (Selective)
                foreach ($palaceData['phu_tinh_xau'] as $s) {
                     // Highlight bad stars
                     if (in_array($s['code'], ['kinh_duong', 'da_la', 'hoa_tinh', 'linh_tinh', 'dia_khong', 'dia_kiep', 'hoa_ky'])) {
                         $stars[] = '<span class="text-danger">' . $s['name'] . '</span>';
                     }
                }
                 foreach ($palaceData['phu_tinh_tot'] as $s) {
                     // Highlight good stars
                     if (in_array($s['code'], ['loc_ton', 'hoa_loc', 'hoa_quyen', 'hoa_khoa', 'thien_khoi', 'thien_viet'])) {
                         $stars[] = '<span class="text-success">' . $s['name'] . '</span>';
                     }
                }

                if (!empty($stars)) {
                    $detail = implode(', ', $stars);
                } else {
                    $detail = 'Bình thường';
                }
            }

            $html .= '<tr><td class="text-center">' . $m . '</td><td>' . $palaceName . '</td><td>' . $detail . '</td></tr>';
        }
        $html .= '</tbody></table></div></div>';

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
