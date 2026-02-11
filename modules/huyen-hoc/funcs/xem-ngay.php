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

$page_title = $lang_module['xem_ngay'];

$d = $nv_Request->get_int('d', 'post,get', date('d'));
$m = $nv_Request->get_int('m', 'post,get', date('m'));
$y = $nv_Request->get_int('y', 'post,get', date('Y'));
$purpose = $nv_Request->get_string('purpose', 'post,get', 'generic');
$birthYear = $nv_Request->get_int('birth_year', 'post,get', 0);

// Convert current selection to Lunar
$lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
$lunar['leap_msg'] = isset($lunar['leap']) && $lunar['leap'] ? '(Nhuận)' : '';

// Calculate Can Chi: Year/Month from Lunar, Day from Solar (JD)
$ccLunar = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], 1, 0);
$ccSolar = LunarCalendar::getCanChi($y, $m, $d, 0);

$canChi = [
    'canYear' => $ccLunar['canYear'],
    'chiYear' => $ccLunar['chiYear'],
    'canMonth' => $ccLunar['canMonth'],
    'chiMonth' => $ccLunar['chiMonth'],
    'canDay' => $ccSolar['canDay'],
    'chiDay' => $ccSolar['chiDay']
];

// Map Can Chi to Text
$canList = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
$chiList = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

$canChiText = [
    'year' => $canList[$canChi['canYear']] . ' ' . $chiList[$canChi['chiYear']],
    'month' => $canList[$canChi['canMonth']] . ' ' . $chiList[$canChi['chiMonth']],
    'day' => $canList[$canChi['canDay']] . ' ' . $chiList[$canChi['chiDay']]
];

// Check current day status
$info = XemNgay::checkNgayTotTheoMucDich($lunar['day'], $lunar['month'], $lunar['year'], $canChi['chiDay'], $purpose, $birthYear);
$info['alert_type'] = $info['is_good'] ? 'success' : 'danger';
$info['details_text'] = isset($info['details']) && is_array($info['details']) ? implode('<br>', $info['details']) : '';

$gioHoangDao = XemNgay::getGioHoangDao($canChi['chiDay']);

// Purposes Map
$purposes = [
    'generic' => 'Xem chung',
    'cuoi_hoi' => 'Cưới hỏi',
    'khai_truong' => 'Khai trương',
    'dong_tho' => 'Động thổ',
    'xuat_hanh' => 'Xuất hành',
    'lam_nha' => 'Làm nhà'
];
$purposeTitle = isset($purposes[$purpose]) ? $purposes[$purpose] : $purposes['generic'];


// Generate list of good days in month if purpose is specific
$goodDays = [];
if ($purpose != 'generic') {
    $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $m, $y);
    for ($i = 1; $i <= $daysInMonth; $i++) {
        $lunarDay = LunarCalendar::convertSolar2Lunar($i, $m, $y);
        $cc = LunarCalendar::getCanChi($lunarDay['year'], $lunarDay['month'], $lunarDay['day'], 0);
        $check = XemNgay::checkNgayTotTheoMucDich($lunarDay['day'], $lunarDay['month'], $lunarDay['year'], $cc['chiDay'], $purpose, $birthYear);

        if ($check['is_good']) {
            $ghd = XemNgay::getGioHoangDao($cc['chiDay']);
            // Map gio indices to names (Ty, Suu...)
            $ghdNames = [];
            $gioNamesMap = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
            foreach ($ghd as $idx) {
                if (isset($gioNamesMap[$idx])) $ghdNames[] = $gioNamesMap[$idx];
            }

            $goodDays[] = array(
                'day' => $i,
                'lunar_day' => $lunarDay['day'],
                'lunar_month' => $lunarDay['month'],
                'truc' => $check['truc'],
                'hours' => implode(', ', $ghdNames)
            );
        }
    }
}

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
$xtpl->assign('CANCHI', $canChi);
$xtpl->assign('CANCHI_TEXT', $canChiText);

// Parse Age Analysis
if (isset($info['age_analysis']) && !empty($info['age_analysis'])) {
    foreach ($info['age_analysis'] as $analysis) {
        $analysis['status_class'] = $analysis['bad'] ? 'danger' : 'success';
        $analysis['icon'] = $analysis['bad'] ? 'fa-times' : 'fa-check';
        $xtpl->assign('AGE_CHECK', $analysis);
        $xtpl->parse('main.age_check');
    }
}

// Map Gio Hoang Dao indices to names for current day
$gioNamesFull = ['Tý (23-1h)', 'Sửu (1-3h)', 'Dần (3-5h)', 'Mão (5-7h)', 'Thìn (7-9h)', 'Tỵ (9-11h)',
             'Ngọ (11-13h)', 'Mùi (13-15h)', 'Thân (15-17h)', 'Dậu (17-19h)', 'Tuất (19-21h)', 'Hợi (21-23h)'];

foreach($gioHoangDao as $g) {
    if (isset($gioNamesFull[$g])) {
        $xtpl->assign('GIO', array('name' => $gioNamesFull[$g]));
        $xtpl->parse('main.gio');
    }
}

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
