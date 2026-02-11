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

use NukeViet\Module\HuyenHoc\XemNgay;
use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViMuonTuoi;
use NukeViet\Module\HuyenHoc\TuViXemNgay;

$page_title = $lang_module['xem_ngay'];

$func = $nv_Request->get_string('func', 'get', 'xem_ngay');

if ($func == 'muon_tuoi') {
    if (!class_exists('NukeViet\Module\HuyenHoc\TuViMuonTuoi')) {
        require_once NV_ROOTDIR . '/modules/' . $module_file . '/classes/TuViMuonTuoi.php';
    }

    $targetYear = $nv_Request->get_int('target_year', 'get', date('Y'));
    $ownerYear = $nv_Request->get_int('owner_year', 'get', 0);

    $muonTuoi = new TuViMuonTuoi($targetYear);
    $candidates = $ownerYear > 0 ? $muonTuoi->timNguoiMuonTuoi($ownerYear) : [];

    $xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('TARGET_YEAR', $targetYear);
    $xtpl->assign('OWNER_YEAR', $ownerYear);
    $xtpl->assign('INPUT', ['birth_year' => $ownerYear]);

    if (!empty($candidates)) {
        foreach ($candidates as $cand) {
            $cand['age'] = $cand['age'];
            $cand['score'] = $cand['score'];

            foreach ($cand['comment'] as $c) {
                $xtpl->assign('DETAIL', $c);
                $xtpl->parse('main.muon_tuoi_result.candidate.detail');
            }

            $xtpl->assign('CANDIDATE', $cand);
            $xtpl->parse('main.muon_tuoi_result.candidate');
        }
    } elseif ($ownerYear > 0) {
        $xtpl->parse('main.muon_tuoi_result.no_candidate');
    }

    $xtpl->parse('main.muon_tuoi_result');

    $purposes = ['generic' => 'Xem chung', 'khai_truong' => 'Khai trương', 'lam_nha' => 'Làm nhà', 'cuoi_hoi' => 'Cưới hỏi', 'ma_chay' => 'Ma chay'];
    foreach ($purposes as $k => $v) {
        $xtpl->assign('PURPOSE', ['key' => $k, 'title' => $v, 'selected' => '']);
        $xtpl->parse('main.purpose_option');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

$d = $nv_Request->get_int('d', 'post,get', date('d'));
$m = $nv_Request->get_int('m', 'post,get', date('m'));
$y = $nv_Request->get_int('y', 'post,get', date('Y'));
$purpose = $nv_Request->get_string('purpose', 'post,get', 'generic');
$birthYear = $nv_Request->get_int('birth_year', 'post,get', 0);
$gender = $nv_Request->get_int('gender', 'post,get', 1);

// Convert current selection to Lunar for display
$lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
$lunar['leap_msg'] = isset($lunar['leap']) && $lunar['leap'] ? '(Nhuận)' : '';

// Calculate Can Chi for display
$ccLunar = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], 1, 0);
$ccSolar = LunarCalendar::getCanChi($y, $m, $d, 0);

$canList = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
$chiList = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

$canChiText = [
    'year' => $canList[$ccLunar['canYear']] . ' ' . $chiList[$ccLunar['chiYear']],
    'month' => $canList[$ccLunar['canMonth']] . ' ' . $chiList[$ccLunar['chiMonth']],
    'day' => $canList[$ccSolar['canDay']] . ' ' . $chiList[$ccSolar['chiDay']]
];

// Use TuViXemNgay Logic
$app = new TuViXemNgay($birthYear, $gender, "$y-$m-$d");
$info = $app->phanTichNgay(strtoupper($purpose));

// Map info to template vars
$info['alert_type'] = ($info['diem_so'] >= 5) ? 'success' : (($info['diem_so'] < 0) ? 'danger' : 'warning');
$info['details_text'] = implode('<br>', $info['binh_giai']);
$info['comment'] = $info['ket_luan'];
// Get Truc from old helper or inside TuViXemNgay (we need to expose it or recalc)
$info['truc'] = XemNgay::getTruc($lunar['month'], $ccSolar['chiDay']);
// Get Sao from details or recalc
// For now, details contain star info.

$gioHoangDaoIndices = XemNgay::getGioHoangDao($ccSolar['chiDay']);

// Purposes Map
$purposes = [
    'generic' => 'Xem chung',
    'khai_truong' => 'Khai trương',
    'dong_tho' => 'Động thổ',
    'lam_nha' => 'Làm nhà',
    'cuoi_hoi' => 'Cưới hỏi',
    'ma_chay' => 'Ma chay'
];
$purposeTitle = isset($purposes[$purpose]) ? $purposes[$purpose] : $purposes['generic'];

// Generate list of good days using new logic
$goodDays = $app->goiYNgayTotTrongThang($m, $y, strtoupper($purpose));

$xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('INPUT', array(
    'd' => $d,
    'm' => $m,
    'y' => $y,
    'purpose' => $purpose,
    'purpose_title' => $purposeTitle,
    'birth_year' => $birthYear ? $birthYear : ''
));
$xtpl->assign('LUNAR', $lunar);
$xtpl->assign('INFO', $info);
$xtpl->assign('CANCHI_TEXT', $canChiText);

// Show Hoa Giai if available
if (isset($info['hoa_giai']) && !empty($info['hoa_giai'])) {
    $xtpl->assign('HOA_GIAI_TITLE', $info['hoa_giai']['phuong_phap']);
    foreach ($info['hoa_giai']['danh_sach_goi_y'] as $suggestion) {
        $xtpl->assign('SUGGESTION', $suggestion);
        $xtpl->parse('main.date_info.hoa_giai.item');
    }
    $xtpl->parse('main.date_info.hoa_giai');
}

// Map Gio Hoang Dao indices to names for current day
$gioNamesFull = ['Tý (23-1h)', 'Sửu (1-3h)', 'Dần (3-5h)', 'Mão (5-7h)', 'Thìn (7-9h)', 'Tỵ (9-11h)',
             'Ngọ (11-13h)', 'Mùi (13-15h)', 'Thân (15-17h)', 'Dậu (17-19h)', 'Tuất (19-21h)', 'Hợi (21-23h)'];

foreach($gioHoangDaoIndices as $g) {
    if (isset($gioNamesFull[$g])) {
        $xtpl->assign('GIO', array('name' => $gioNamesFull[$g]));
        $xtpl->parse('main.date_info.gio');
    }
}

$xtpl->parse('main.date_info');

// Parse Purpose Options
foreach ($purposes as $k => $v) {
    $xtpl->assign('PURPOSE', array(
        'key' => $k,
        'title' => $v,
        'selected' => ($k == $purpose) ? 'selected' : ''
    ));
    $xtpl->parse('main.purpose_option');
}

// Parse Good Days List
if (!empty($goodDays)) {
    foreach ($goodDays as $gd) {
        $gd['hours'] = ''; // Populate if available in return
        // Truc is not returned in goodDays yet, can add if needed
        $gd['truc'] = '';
        $xtpl->assign('GD', $gd);
        $xtpl->parse('main.good_days.row');
    }
    $xtpl->parse('main.good_days');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
