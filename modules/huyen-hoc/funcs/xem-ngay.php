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

// Include classes manually to prevent "Class not found" error
$classes = ['TuViConstants', 'LunarCalendar', 'XemNgay', 'TuViMuonTuoi', 'TuViXemNgay'];
foreach ($classes as $cls) {
    $file = NV_ROOTDIR . '/modules/' . $module_file . '/classes/' . $cls . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

use NukeViet\Module\HuyenHoc\XemNgay;
use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViMuonTuoi;
use NukeViet\Module\HuyenHoc\TuViXemNgay;

$page_title = $lang_module['xem_ngay'];

$func = $nv_Request->get_string('func', 'post,get', 'xem_ngay');

if ($func == 'trung_tang') {
    $deceasedYear = $nv_Request->get_int('deceased_year', 'post', 0);
    $deceasedGender = $nv_Request->get_int('deceased_gender', 'post', 1);
    $deathTimeStr = $nv_Request->get_string('death_time', 'post', '');
    $headYear = $nv_Request->get_int('head_year', 'post', 0);
    $relativesStr = $nv_Request->get_string('relatives_list', 'post', '');

    $xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('OP', $op);
    $xtpl->assign('ACTIVE_TAB_TANG_LE', 'active');

    // Keep inputs
    $xtpl->assign('INPUT_TT', [
        'deceased_year' => $deceasedYear,
        'death_time' => $deathTimeStr,
        'head_year' => $headYear,
        'relatives_list' => $relativesStr
    ]);

    if ($deceasedYear > 0 && !empty($deathTimeStr)) {
        // Parse Death Time
        $dt = strtotime($deathTimeStr);
        $d = date('j', $dt);
        $m = date('n', $dt);
        $y = date('Y', $dt);
        $h = date('G', $dt);

        // Convert to Lunar for calculation
        $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
        $canChi = LunarCalendar::getCanChi($y, $m, $d, $h);

        // Deceased Info
        $age = $lunar['year'] - $deceasedYear + 1;
        $deceasedInfo = [
            'year' => $deceasedYear,
            'gender' => $deceasedGender,
            'age' => $age,
            'death_time' => [
                'd' => $lunar['day'],
                'm' => $lunar['month'],
                'y' => $lunar['year'],
                'h_chi' => $canChi['chiHour']
            ]
        ];

        // Relatives
        $relatives = [];
        if (!empty($relativesStr)) {
            $parts = explode(',', $relativesStr);
            foreach ($parts as $p) {
                $rYear = (int)trim($p);
                if ($rYear > 0) $relatives[] = $rYear;
            }
        }

        $app = new TuViXemNgay($deceasedYear, $deceasedGender, "$y-$m-$d");
        $result = $app->xemTrungTang($deceasedInfo, $headYear, $relatives);

        // Assign Result
        $xtpl->assign('TT_RESULT', $result);

        if (!empty($result['conflicts'])) {
            foreach ($result['conflicts'] as $c) {
                $xtpl->assign('CONFLICT', $c);
                $xtpl->parse('main.trung_tang_result.conflict');
            }
        }

        $xtpl->parse('main.trung_tang_result');
    }

    $xtpl->parse('main');
    $contents = $xtpl->text('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

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

    // Candidates Loop...
    if (!empty($candidates)) {
        foreach ($candidates as $cand) {
            $candComment = implode(', ', $cand['comment']);
            $xtpl->assign('CANDIDATE', array_merge($cand, ['comment_str' => $candComment]));
            $xtpl->parse('main.lam_nha_result.candidate');
        }
    } elseif ($ownerYear > 0) {
        $xtpl->parse('main.lam_nha_result.no_candidate');
    }
    $xtpl->parse('main.lam_nha_result');
    $xtpl->assign('ACTIVE_TAB_LAM_NHA', 'active');

    $xtpl->parse('main');
    $contents = $xtpl->text('main');
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents);
    include NV_ROOTDIR . '/includes/footer.php';
    exit();
}

// Default View
$d = $nv_Request->get_int('d', 'post,get', date('d'));
$m = $nv_Request->get_int('m', 'post,get', date('m'));
$y = $nv_Request->get_int('y', 'post,get', date('Y'));
$tab = $nv_Request->get_string('tab', 'get', 'general');
$purpose = $nv_Request->get_string('purpose', 'get', 'generic');
$birthYear = $nv_Request->get_int('birth_year', 'post,get', 0);

// Basic Lunar Calc for General Tab
$lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
$lunar['leap_msg'] = isset($lunar['leap']) && $lunar['leap'] ? '(Nhuận)' : '';
$ccLunar = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], 1, 0);
$ccSolar = LunarCalendar::getCanChi($y, $m, $d, 0);
$canList = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
$chiList = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
$canChiText = [
    'year' => $canList[$ccLunar['canYear']] . ' ' . $chiList[$ccLunar['chiYear']],
    'month' => $canList[$ccLunar['canMonth']] . ' ' . $chiList[$ccLunar['chiMonth']],
    'day' => $canList[$ccSolar['canDay']] . ' ' . $chiList[$ccSolar['chiDay']]
];

$xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('INPUT', ['d'=>$d, 'm'=>$m, 'y'=>$y, 'birth_year'=>$birthYear]);

// Tab Active Logic
$activeTab = 'ACTIVE_TAB_' . strtoupper($tab);
if ($tab == 'khai_truong') $activeTab = 'ACTIVE_TAB_KHAI_TRUONG';
$xtpl->assign($activeTab, 'active');

// Handle Specific Tab Logic
if ($tab == 'general') {
    $app = new TuViXemNgay(0, 1, "$y-$m-$d");
    $info = $app->phanTichNgay('GENERIC');
    $info['alert_type'] = ($info['diem_so'] >= 0) ? 'success' : 'danger';
    $xtpl->assign('LUNAR', $lunar);
    $xtpl->assign('CANCHI_TEXT', $canChiText);
    $xtpl->assign('INFO', $info);
    foreach ($info['binh_giai'] as $bg) {
        $xtpl->assign('DETAIL', $bg);
        $xtpl->parse('main.general_result.detail');
    }
    $xtpl->parse('main.general_result');
} elseif ($tab == 'khai_truong') {
    if ($birthYear > 0) {
        $app = new TuViXemNgay($birthYear, 1, "$y-$m-01"); // Dummy date, just need year/month
        $goodDays = $app->goiYNgayTotTrongThang($m, $y, 'KHAI_TRUONG');

        $listHtml = '<div class="table-responsive"><table class="table table-bordered"><thead><tr><th>Ngày Dương</th><th>Ngày Âm</th><th>Can Chi</th><th>Điểm</th><th>Lý do</th></tr></thead><tbody>';
        foreach ($goodDays as $gd) {
            $listHtml .= "<tr><td>{$gd['day']}/$m</td><td>{$gd['lunar_day']}/{$gd['lunar_month']}</td><td>{$gd['can_chi']}</td><td>{$gd['diem']}</td><td>{$gd['ly_do']}</td></tr>";
        }
        $listHtml .= '</tbody></table></div>';
        $xtpl->assign('GOOD_DAYS_LIST', $listHtml);
    } else {
        $xtpl->assign('GOOD_DAYS_LIST', '<div class="alert alert-warning">Vui lòng nhập năm sinh để xem ngày tốt.</div>');
    }
    $xtpl->parse('main.khai_truong_result');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
