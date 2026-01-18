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

use NukeViet\Module\TuVi\Lunisolar;
use NukeViet\Module\TuVi\Horoscope;

$page_title = "Lá Số Tử Vi";

// Get Input
$fullname = $nv_Request->get_string('fullname', 'post', 'Anonymous');
$d = $nv_Request->get_int('day', 'post', 1);
$m = $nv_Request->get_int('month', 'post', 1);
$y = $nv_Request->get_int('year', 'post', 2000);
$hour = $nv_Request->get_int('hour', 'post', 12); // Solar hour (0-23)
$gender = $nv_Request->get_int('gender', 'post', 1);

// Convert Solar to Lunar
// Note: Lunisolar::convertSolarToLunar returns [d, m, y, leap]
// For this Demo, we assume simplified conversion
$lunarData = Lunisolar::convertSolarToLunar($d, $m, $y);
$lunarD = $lunarData[0];
$lunarM = $lunarData[1];
$lunarY = $lunarData[2];

// Convert Hour (0-23) to Chi (0-11, Ty..Hoi)
// Ty: 23-1, Suu: 1-3...
// Formula: Floor((h+1)/2) % 12
$chiHour = floor(($hour + 1) / 2) % 12;

// Initialize Horoscope Engine
$horoscope = new Horoscope($lunarD, $lunarM, $lunarY, $chiHour, $gender);
$chartData = $horoscope->generateChart();

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
    // ... Add more info like Ban Menh, Cuc ...
];
$xtpl->assign('USER', $userInfo);

// Assign Chart Data
// The Grid logic in view.tpl will expect a loop or specific indices.
// To make it easy for the Template to render the Grid cell by cell in 12 positions:
// We pass the array.
foreach ($chartData as $palace) {
    $xtpl->assign('PALACE', $palace);

    // Parse Stars
    if (!empty($palace['stars'])) {
        foreach ($palace['stars'] as $star) {
            $star['css'] = 'star-' . $star['element']; // star-kim, star-moc
            $xtpl->assign('STAR', $star);
            $xtpl->parse('main.loop.stars');
        }
    }

    $xtpl->parse('main.loop');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
