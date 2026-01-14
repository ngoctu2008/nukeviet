<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_MOD_TU_VI')) {
    exit('Stop!!!');
}

$fullname = $nv_Request->get_title('fullname', 'post', '');
$day = $nv_Request->get_int('day', 'post', 1);
$month = $nv_Request->get_int('month', 'post', 1);
$year = $nv_Request->get_int('year', 'post', 1990);
$hour = $nv_Request->get_int('hour', 'post', 0); // 0-23
$gender = $nv_Request->get_int('gender', 'post', 1);

// Convert Solar to Lunar
$lunar = NukeViet\Module\TuVi\Lunisolar::convertSolar2Lunar($day, $month, $year, 7);

// Convert Hour to Chi ID
$hour_chi_id = floor(($hour + 1) / 2) % 12;

// Calculate Horoscope
$horoscope = new NukeViet\Module\TuVi\Horoscope($lunar, $hour_chi_id, $gender);
$chart = $horoscope->lapLaSo();

// Calculate Age and Sao/Han first to use in query
$current_year = date('Y');
$birth_year = $lunar['year'];
$age_am = $current_year - $birth_year + 1;
if ($age_am < 1) $age_am = 1;

$sao_han = $horoscope->getSaoHan($age_am, $gender);

// Fetch Interpretations from Database
$interpretations = [];
$lookups = [];

// Standard interpretations (Star in Palace)
foreach ($chart as $cung) {
    foreach ($cung['stars'] as $star) {
        $lookups[] = "(star_key = " . $db->quote($star['name']) . " AND palace_key = " . $db->quote($cung['name']) . ")";
        if ($cung['is_menh']) {
             $lookups[] = "(star_key = " . $db->quote($star['name']) . " AND palace_key = 'Mệnh')";
        }
    }
}

// Fetch general interpretations
$lookups[] = "(star_key = 'Tổng Quan' AND palace_key = 'Mệnh')";
$lookups[] = "(star_key = 'Vận Hạn' AND palace_key = 'Tiểu Vận')";

// Fetch Yearly Detail Interpretations
// Fetch generic "Bình Giải Năm" (Overview only)
$lookups[] = "(star_key = 'Bình Giải Năm' AND palace_key = 'Tổng Quan')";

// Fetch Specific Monthly Interpretations based on Sao Hạn
if (!empty($sao_han['sao'])) {
    $lookups[] = "(star_key = 'Sao Chiếu Mệnh' AND palace_key = " . $db->quote($sao_han['sao']) . ")";
    // Fetch monthly details for this Star
    // We look for star_key = [StarName] AND palace_key LIKE 'Tháng %'
    // Note: In data_vi.php we inserted: ('La Hầu', 'Tháng 1', ...)
    $lookups[] = "(star_key = " . $db->quote($sao_han['sao']) . " AND palace_key LIKE 'Tháng %')";
}

if (!empty($sao_han['han'])) {
    $lookups[] = "(star_key = 'Hạn' AND palace_key = " . $db->quote($sao_han['han']) . ")";
}

if (!empty($lookups)) {
    $sql_where = implode(' OR ', $lookups);
    $sql = "SELECT * FROM " . NV_PRE_TUVI . "_interpretations WHERE " . $sql_where;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $interpretations[] = $row;
    }
}

// New Calculations: Dai Van, Tieu Van, Bad Luck
$cuc = $horoscope->info['cuc'];
$dai_van_id = $horoscope->getDaiVan($age_am, $gender, $cuc);

$current_year_chi = ($current_year - 1900) % 12;
$birth_chi = $horoscope->info['chi_year_id'];

$tieu_van_id = $horoscope->getTieuVan($age_am, $gender, $birth_chi);

$bad_luck = [];
$tam_tai = $horoscope->getTamTai($current_year_chi, $birth_chi);
if ($tam_tai) $bad_luck[] = $tam_tai;

$kim_lau = $horoscope->getKimLau($age_am);
if ($kim_lau) $bad_luck[] = $kim_lau;

$hoang_oc = $horoscope->getHoangOc($age_am);
if ($hoang_oc) $bad_luck[] = $hoang_oc;

$data = [
    'info' => [
        'fullname' => $fullname,
        'solar_date' => "$day/$month/$year",
        'lunar_date' => $lunar['day'] . '/' . $lunar['month'] . '/' . $lunar['year'],
        'gender' => $gender ? $lang_module['male'] : $lang_module['female'],
        'cuc' => $horoscope->info['cuc_name'],
        'age' => $age_am,
        'sao' => $sao_han['sao'],
        'han' => $sao_han['han'],
        'current_year' => $current_year,
        'bad_luck' => implode(', ', $bad_luck)
    ],
    'cung' => $chart,
    'interpretations' => $interpretations
];

$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('INFO', $data['info']);

foreach ($data['cung'] as $cung) {
    // Add CSS class for grid positioning
    $ids = ['ty', 'suu', 'dan', 'mao', 'thin', 'ty_snake', 'ngo', 'mui', 'than', 'dau', 'tuat', 'hoi'];
    $cung['css_class'] = $ids[$cung['id']];

    // Add highlighting classes
    if ($cung['id'] == $dai_van_id) $cung['css_class'] .= ' daivan-highlight';
    if ($cung['id'] == $tieu_van_id) $cung['css_class'] .= ' tieuvan-highlight';

    // Assign stars
    if (!empty($cung['chinh_tinh'])) {
        foreach ($cung['chinh_tinh'] as $star) {
            $xtpl->assign('STAR_NAME', $star);
            $xtpl->parse('main.loop.chinh_tinh');
        }
    }
    if (!empty($cung['phu_tinh'])) {
        foreach ($cung['phu_tinh'] as $star) {
            $xtpl->assign('STAR_NAME', $star);
            $xtpl->parse('main.loop.phu_tinh');
        }
    }

    // Labels for Dai Van/Tieu Van
    if ($cung['id'] == $dai_van_id) {
        $xtpl->assign('LABEL_DAIVAN', 'Đại Vận');
        $xtpl->parse('main.loop.daivan_label');
    }
    if ($cung['id'] == $tieu_van_id) {
        $xtpl->assign('LABEL_TIEUVAN', 'Tiểu Vận');
        $xtpl->parse('main.loop.tieuvan_label');
    }

    $xtpl->assign('CUNG', $cung);
    $xtpl->parse('main.loop');
}

// Parse Interpretations
$year_detail = [];
$sao_han_detail = [];

if (!empty($data['interpretations'])) {
    foreach ($data['interpretations'] as $interp) {
        // Collect specific monthly details for the current Star
        if ($interp['star_key'] == $sao_han['sao'] && strpos($interp['palace_key'], 'Tháng') === 0) {
            $year_detail[] = $interp;
        }
        // Collect generic overview
        elseif ($interp['star_key'] == 'Bình Giải Năm' && $interp['palace_key'] == 'Tổng Quan') {
            // Prepend to year detail or separate? Let's add to year detail list as header
            array_unshift($year_detail, $interp);
        }
        elseif ($interp['star_key'] == 'Sao Chiếu Mệnh' || $interp['star_key'] == 'Hạn') {
            $sao_han_detail[] = $interp;
        } else {
            $xtpl->assign('INTERP', $interp);
            $xtpl->parse('main.interpretations.loop');
        }
    }
    $xtpl->parse('main.interpretations');
}

// Parse Sao Han
if (!empty($sao_han_detail)) {
    foreach ($sao_han_detail as $detail) {
        $xtpl->assign('DETAIL', $detail);
        $xtpl->parse('main.sao_han_detail.loop');
    }
    $xtpl->parse('main.sao_han_detail');
}

// Parse Year Detail
if (!empty($year_detail)) {
    foreach ($year_detail as $detail) {
        // Simple sort to keep months in order if array_unshift messed it up?
        // Database retrieval order usually preserves insertion order.
        $xtpl->assign('DETAIL', $detail);
        $xtpl->parse('main.year_detail.loop');
    }
    $xtpl->parse('main.year_detail');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
