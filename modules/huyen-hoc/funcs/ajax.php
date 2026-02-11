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
$classes = ['TuViLapSo', 'TuViLuanGiai', 'FengShuiUtils', 'LunarCalendar', 'LichVanNien', 'TuViAdvanced', 'TuViYLy', 'TuViHuongNghiep', 'TuViSaoHan'];
foreach ($classes as $cls) {
    $file = NV_ROOTDIR . '/modules/' . $module_file . '/classes/' . $cls . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

use NukeViet\Module\HuyenHoc\TuViLapSo;
use NukeViet\Module\HuyenHoc\TuViLuanGiai;
use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\LichVanNien;
use NukeViet\Module\HuyenHoc\TuViAdvanced;
use NukeViet\Module\HuyenHoc\TuViYLy;
use NukeViet\Module\HuyenHoc\TuViHuongNghiep;
use NukeViet\Module\HuyenHoc\TuViSaoHan;

// Ensure JSON header if AJAX
if ($nv_Request->isset_request('nv_ajax', 'get,post')) {
    // header('Content-Type: application/json; charset=utf-8'); // Allow HTML return for xem_han
}

$action = $nv_Request->get_string('action', 'get,post', '');
$ajax_get_han = $nv_Request->get_int('ajax_get_han', 'get,post', 0);

if ($action == 'block_calendar') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $day = $nv_Request->get_int('day', 'get', date('j'));
        $month = $nv_Request->get_int('month', 'get', date('n'));
        $year = $nv_Request->get_int('year', 'get', date('Y'));

        // Solar Info
        $daysOfWeek = ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'];
        $timestamp = mktime(12, 0, 0, $month, $day, $year);
        $wday = date('w', $timestamp);

        $data = [
            'solar_day' => $day,
            'solar_month' => $month,
            'solar_year' => $year,
            'day_of_week' => $daysOfWeek[$wday]
        ];

        $app = new LichVanNien();
        $info = $app->getInfo($day, $month, $year, 12);

        $data['lunar_day'] = $info['am_lich']['day'];
        $data['lunar_month'] = $info['am_lich']['month'];
        $data['lunar_year'] = $info['am_lich']['year'];
        $data['is_leap'] = $info['am_lich']['leap'];

        $data['can_chi_day'] = $info['can_chi']['ngay'];
        $data['can_chi_month'] = $info['can_chi']['thang'];
        $data['can_chi_year'] = $info['can_chi']['nam'];

        $data['tiet_khi'] = $info['tiet_khi'];
        $data['ngay_hoang_dao'] = $info['ngay_hoang_dao']['msg'];
        $data['ngay_hoang_dao_type'] = $info['ngay_hoang_dao']['type'];

        $data['ly_thuan_phong'] = $info['ly_thuan_phong'];
        $data['tuoi_xung'] = $info['tuoi_xung'];
        $data['huong_xuat_hanh'] = $info['huong_xuat_hanh'];

        // Lucky Hours
        $chiNgay = $info['ids']['chi_ngay'];
        $data['gio_hoang_dao'] = LunarCalendar::getGioHoangDao($chiNgay);

        echo json_encode(['status' => 'success', 'data' => $data]);
        die();

    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        die();
    }
}

if ($action == 'luan_giai_nguoi_than') {
    header('Content-Type: application/json; charset=utf-8');
    try {
        $relation = $nv_Request->get_string('relation', 'post', '');

        // Full Birth Data (Solar) - Reconstruct Chart
        $birthDay = $nv_Request->get_int('birthDay', 'post', 0);
        $birthMonth = $nv_Request->get_int('birthMonth', 'post', 0);
        $birthYear = $nv_Request->get_int('birthYear', 'post', 0);
        $birthHour = $nv_Request->get_int('birthHour', 'post', 0);
        $gender = $nv_Request->get_int('gender', 'post', 1);

        if ($birthDay == 0 || $birthYear == 0) {
             throw new Exception("Dữ liệu ngày sinh không hợp lệ.");
        }

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
            'User'
        );

        $interpreter = new TuViLuanGiai();
        $relativeData = $interpreter->luanGiaiNguoiThan($laSoData, $relation);

        if (!$relativeData) {
            throw new Exception("Không thể luận giải cho mối quan hệ này.");
        }

        $html = '<div class="alert alert-success text-center"><strong>' . $relativeData['title'] . '</strong></div>';
        $html .= '<div class="accordion" id="accordionRelative">';

        foreach ($relativeData['readings'] as $idx => $reading) {
            $collapseId = "collapseRel" . $idx;
            $html .= '<div class="card">';
            $html .= '<div class="card-header" id="heading' . $idx . '">';
            $html .= '<h5 class="mb-0"><button class="btn btn-link" type="button" data-toggle="collapse" data-target="#' . $collapseId . '">';
            $html .= $reading['name'] . ' (' . $reading['original_name'] . ')';
            $html .= '</button></h5></div>';

            $html .= '<div id="' . $collapseId . '" class="collapse ' . ($idx==0?'show':'') . '" data-parent="#accordionRelative">';
            $html .= '<div class="card-body">';
            $html .= '<p class="text-muted small"><em>' . $reading['desc'] . '</em></p>';
            $html .= '<p><strong>Sao chính:</strong> ' . $reading['stars'] . '</p>';

            if (!empty($reading['reading']['chinh_tinh'])) {
                 $html .= '<h6>Chính Tinh:</h6><ul>';
                 foreach ($reading['reading']['chinh_tinh'] as $ct) {
                     $html .= '<li><strong>' . $ct['star'] . ':</strong> ' . $ct['content'] . '</li>';
                 }
                 $html .= '</ul>';
            }
             if (!empty($reading['reading']['phu_tinh'])) {
                 $html .= '<h6>Phụ Tinh quan trọng:</h6><ul>';
                 foreach ($reading['reading']['phu_tinh'] as $pt) {
                     $html .= '<li><strong>' . $pt['star'] . ':</strong> ' . $pt['content'] . '</li>';
                 }
                 $html .= '</ul>';
            }
            if (!empty($reading['reading']['general'])) {
                 $html .= '<h6>Tổng Quan & Cách Cục:</h6><ul>';
                 foreach ($reading['reading']['general'] as $g) {
                     $html .= '<li><strong>' . $g['star'] . ':</strong> ' . $g['content'] . '</li>';
                 }
                 $html .= '</ul>';
            }

            $html .= '</div></div></div>';
        }
        $html .= '</div>';

        echo json_encode(['status' => 'success', 'html' => $html]);
        die();

    } catch (\Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        die();
    }
}

// Handling Xem Han (Limits) - Return HTML Segment
if ($ajax_get_han == 1) {
    try {
        // Inputs: d, m, y, h, g, view_year
        $birthDay = $nv_Request->get_int('d', 'post', 0);
        $birthMonth = $nv_Request->get_int('m', 'post', 0);
        $birthYear = $nv_Request->get_int('y', 'post', 0);
        $birthHour = $nv_Request->get_int('h', 'post', 0);
        $gender = $nv_Request->get_int('g', 'post', 1);
        $viewYear = $nv_Request->get_int('view_year', 'post', date('Y'));
        $name = $nv_Request->get_string('name', 'post', 'Đương số');

        if ($birthDay == 0 || $birthYear == 0) {
             die('<div class="alert alert-danger">Dữ liệu ngày sinh không hợp lệ. Vui lòng tạo lá số trước.</div>');
        }

        // 1. Convert Solar to Lunar & Get Chart
        $lunar = LunarCalendar::convertSolar2Lunar($birthDay, $birthMonth, $birthYear);
        $canChi = LunarCalendar::getCanChi($lunar['day'], $lunar['month'], $lunar['year'], $birthHour);

        $laSoData = TuViLapSo::lapLaSo(
            $lunar['day'], $lunar['month'], $lunar['year'], $birthHour, $gender,
            $canChi['canYear'], $canChi['chiYear'], $name
        );

        $chart = $laSoData['dia_ban'];
        $chiYear = $canChi['chiYear'];

        // 2. Calculate Limits
        if ($viewYear < $birthYear) die('<div class="alert alert-warning">Năm xem hạn phải lớn hơn hoặc bằng năm sinh.</div>');

        $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $gender, $viewYear, $birthYear);

        // 3. Get Detailed Meanings for Sao/Han
        $saoHanDetails = TuViSaoHan::getSaoHanMeaning($limitInfo['sao_han']['name'], $limitInfo['han']['name']);

        $html = '<div class="alert alert-info border-info">';
        $html .= '<h4 class="text-center text-uppercase text-primary">Vận Hạn Năm ' . $viewYear . ' (' . $limitInfo['target_chi'] . ')</h4>';
        $html .= '<div class="row">';
        $html .= '<div class="col-md-12">';
        $html .= '<p><strong>Tuổi Âm:</strong> ' . $limitInfo['age_am'] . ' tuổi</p>';
        $tieuVanPalace = isset($chart[$limitInfo['tieu_van_idx']]) ? $chart[$limitInfo['tieu_van_idx']]['palace_name'] : '';
        $html .= '<p><strong>Tiểu vận tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['tieu_van_idx']] . ' (' . $tieuVanPalace . ')</p>';
        $thaiTuePalace = isset($chart[$limitInfo['luu_thai_tue_idx']]) ? $chart[$limitInfo['luu_thai_tue_idx']]['palace_name'] : '';
        $html .= '<p><strong>Lưu Thái Tuế tại cung:</strong> ' . TuViLapSo::$DIA_CHI[$limitInfo['luu_thai_tue_idx']] . ' (' . $thaiTuePalace . ')</p>';
        $html .= '</div>';

        $html .= '<div class="col-md-12">';
        $html .= '<p><strong>Sao chiếu mệnh:</strong> <span class="text-' . ($limitInfo['sao_han']['type']=='tot'?'success':($limitInfo['sao_han']['type']=='xau'?'danger':'warning')) . ' font-weight-bold">' . $limitInfo['sao_han']['name'] . '</span></p>';
        $html .= '<p class="small text-muted"><em>' . $saoHanDetails['sao_desc'] . '</em></p>';
        $html .= '<p><strong>Hạn:</strong> <span class="font-weight-bold">' . $limitInfo['han']['name'] . '</span></p>';
        $html .= '<p class="small text-muted"><em>' . $saoHanDetails['han_desc'] . '</em></p>';
        $html .= '</div></div>';

        $html .= '<div class="mt-2 pt-2 border-top">';
        $html .= '<span class="badge badge-' . ($limitInfo['tam_tai'] ? 'danger' : 'success') . ' mr-2">Tam Tai: ' . ($limitInfo['tam_tai'] ? 'Có' : 'Không') . '</span>';
        $html .= '<span class="badge badge-' . ($limitInfo['pham_thai_tue'] ? 'danger' : 'success') . '">Phạm Thái Tuế: ' . ($limitInfo['pham_thai_tue'] ? 'Có' : 'Không') . '</span>';
        $html .= '</div>';
        $html .= '</div>';

        // Monthly Limits Table
        $html .= '<div class="card mb-3"><div class="card-header bg-primary text-white font-weight-bold">Diễn Biến Các Tháng (Nguyệt Hạn)</div><div class="card-body p-0">';
        $html .= '<div class="table-responsive"><table class="table table-striped table-bordered m-0 table-hover"><thead><tr><th width="10%">Tháng</th><th width="30%">Cung Hạn</th><th>Sao Tọa Thủ (Diễn Biến)</th></tr></thead><tbody>';

        for ($m = 1; $m <= 12; $m++) {
            $monthIdx = TuViLapSo::getNguyetHan($limitInfo['tieu_van_idx'], $m, $gender);
            $palaceData = isset($chart[$monthIdx]) ? $chart[$monthIdx] : null;

            $palaceName = TuViLapSo::$DIA_CHI[$monthIdx];
            $detail = '';

            if ($palaceData) {
                $palaceName .= ' (' . $palaceData['palace_name'] . ')';
                $stars = [];
                // Main stars
                foreach ($palaceData['chinh_tinh'] as $s) {
                    $stars[] = '<strong>' . $s['name'] . '</strong>';
                }
                // Bad stars
                foreach ($palaceData['phu_tinh_xau'] as $s) {
                     if (in_array($s['code'], ['kinh_duong', 'da_la', 'hoa_tinh', 'linh_tinh', 'dia_khong', 'dia_kiep', 'hoa_ky', 'thien_khoc', 'thien_hu', 'co_than', 'qua_tu'])) {
                         $stars[] = '<span class="text-danger">' . $s['name'] . '</span>';
                     }
                }
                // Good stars
                 foreach ($palaceData['phu_tinh_tot'] as $s) {
                     if (in_array($s['code'], ['loc_ton', 'hoa_loc', 'hoa_quyen', 'hoa_khoa', 'thien_khoi', 'thien_viet', 'dao_hoa', 'hong_loan', 'thien_hy'])) {
                         $stars[] = '<span class="text-success">' . $s['name'] . '</span>';
                     }
                }

                if (!empty($stars)) {
                    $detail = implode(', ', $stars);
                } else {
                    $detail = '<span class="text-muted">Bình hòa</span>';
                }
            }
            $html .= '<tr><td class="text-center font-weight-bold">' . $m . '</td><td>' . $palaceName . '</td><td>' . $detail . '</td></tr>';
        }
        $html .= '</tbody></table></div></div>';

        die($html);

    } catch (\Exception $e) {
        die('<div class="alert alert-danger">Lỗi xử lý: ' . $e->getMessage() . '</div>');
    } catch (\Throwable $e) {
        die('<div class="alert alert-danger">Lỗi hệ thống: ' . $e->getMessage() . '</div>');
    }
}

die('Unknown Action');
