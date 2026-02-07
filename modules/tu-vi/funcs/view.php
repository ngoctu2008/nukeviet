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

require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/Lunisolar.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/Horoscope.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/TuViLuanGiai.php';

use NukeViet\Module\TuVi\Lunisolar;
use NukeViet\Module\TuVi\Horoscope;
use NukeViet\Module\TuVi\Includes\TuViLuanGiai;

$page_title = "Lá Số Tử Vi";

// Get Input
$fullname = $nv_Request->get_string('fullname', 'post', 'Anonymous');
$d = $nv_Request->get_int('day', 'post', 1);
$m = $nv_Request->get_int('month', 'post', 1);
$y = $nv_Request->get_int('year', 'post', 2000);
$hour = $nv_Request->get_int('hour', 'post', 12); // Solar hour (0-23)
$gender = $nv_Request->get_int('gender', 'post', 1);

// Convert Solar to Lunar
$lunarData = Lunisolar::convertSolarToLunar($d, $m, $y);
$lunarD = $lunarData[0];
$lunarM = $lunarData[1];
$lunarY = $lunarData[2];

// Convert Hour (0-23) to Chi (0-11, Ty..Hoi)
$chiHour = floor(($hour + 1) / 2) % 12;

// Initialize Horoscope Engine
$horoscope = new Horoscope($lunarD, $lunarM, $lunarY, $chiHour, $gender);
$chartData = $horoscope->generateChart();

// Initialize Advanced Interpretation Engine
$luanGiai = new TuViLuanGiai();

// Prepare View
$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('TEMPLATE', $module_info['template']);

// Assign User Info to Center Block (Thien Ban)
$canChiYear = Lunisolar::getCanChiYear($lunarY);
$userInfo = [
    'fullname' => $fullname,
    'birth_solar' => "$d/$m/$y $hour:00",
    'birth_lunar' => "$lunarD/$lunarM/$lunarY ($canChiYear)",
    'gender_txt' => ($gender == 1) ? 'Nam' : 'Nữ',
    'year_view' => date('Y')
];
$xtpl->assign('USER', $userInfo);

// Interpretation Output Buffer
$interpretation_html = '';

// Add General Interpretation (Tong Quan)
$chartMeta = $horoscope->getMeta();
$tongQuan = $luanGiai->luanGiaiTongQuan($chartMeta['can_year'], $chartMeta['chi_year'], $chartMeta['gender'], $chartMeta['cuc']);
$interpretation_html .= "<div class='panel panel-success'>
    <div class='panel-heading'><strong>Tổng Quan Lá Số</strong></div>
    <div class='panel-body'>" . $tongQuan . "</div>
</div>";

// Assign Chart Data
foreach ($chartData as $palace) {
    $xtpl->assign('PALACE', $palace);

    // Determine Tuan/Triet for this palace (Horoscope class needs to support this or we deduce it)
    // For now, assume Tuan/Triet are not fully implemented in the basic Horoscope stub provided earlier.
    // If they were, they would be in $palace['stars'] or properties.
    // I will check $palace['stars'] for 'Tuần', 'Triệt'.

    $has_tuan = false;
    $has_triet = false;

    // Parse Stars to check Tuan/Triet
    if (!empty($palace['stars'])) {
        foreach ($palace['stars'] as $star) {
            $star['css'] = 'star-' . $star['element'];
            $xtpl->assign('STAR', $star);
            $xtpl->parse('main.loop.stars');

            if ($star['name'] == 'Tuần') $has_tuan = true;
            if ($star['name'] == 'Triệt') $has_triet = true;
        }
    }

    // Generate Interpretation for this Palace
    $palaceKey = str_replace('-', '_', strtolower(change_alias($palace['name']))); // menh, phu_mau...

    // Call Advanced Engine
    $content = $luanGiai->luanGiaiCung($palace['stars'], $palaceKey, $has_tuan, $has_triet);

    if (!empty($content)) {
        // Format as Panel
        $interpretation_html .= "<div class='panel panel-primary'>
            <div class='panel-heading'><strong>Cung " . $palace['name'] . "</strong> (" . $palace['zodiac'] . ")</div>
            <div class='panel-body'>
                " . $content . "
            </div>
        </div>";
    }

    $xtpl->parse('main.loop');
}

// Assign Interpretation Block
$xtpl->assign('INTERPRETATION', $interpretation_html);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
