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
$lookups[] = "(star_key = 'Bình Giải Năm')";

if (!empty($lookups)) {
    $sql_where = implode(' OR ', $lookups);
    $sql = "SELECT * FROM " . NV_PRE_TUVI . "_interpretations WHERE " . $sql_where;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $interpretations[] = $row;
    }
}

$data = [
    'info' => [
        'fullname' => $fullname,
        'solar_date' => "$day/$month/$year",
        'lunar_date' => $lunar['day'] . '/' . $lunar['month'] . '/' . $lunar['year'],
        'gender' => $gender ? $lang_module['male'] : $lang_module['female'],
        'cuc' => $horoscope->info['cuc_name']
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

    $xtpl->assign('CUNG', $cung);
    $xtpl->parse('main.loop');
}

// Parse Interpretations
$year_detail = [];
if (!empty($data['interpretations'])) {
    foreach ($data['interpretations'] as $interp) {
        if ($interp['star_key'] == 'Bình Giải Năm') {
            $year_detail[] = $interp;
        } else {
            $xtpl->assign('INTERP', $interp);
            $xtpl->parse('main.interpretations.loop');
        }
    }
    $xtpl->parse('main.interpretations');
}

// Parse Year Detail
if (!empty($year_detail)) {
    // Sort by Month (custom sort needed or rely on DB order? DB order is insert order, which is 1-12)
    foreach ($year_detail as $detail) {
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
