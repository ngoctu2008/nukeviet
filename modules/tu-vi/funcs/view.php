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
$lunarData = Lunisolar::convertSolarToLunar($d, $m, $y);
$lunarD = $lunarData[0];
$lunarM = $lunarData[1];
$lunarY = $lunarData[2];

// Convert Hour (0-23) to Chi (0-11, Ty..Hoi)
$chiHour = floor(($hour + 1) / 2) % 12;

// Initialize Horoscope Engine
$horoscope = new Horoscope($lunarD, $lunarM, $lunarY, $chiHour, $gender);
$chartData = $horoscope->generateChart();

// Prepare DB Query for Interpretations
// Table Name construction (Standardized)
$table_interpretations = $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . str_replace('-', '_', $module_data) . '_interpretations';

// Fetch all interpretations (Optimization: Fetch all might be heavy if DB is huge, but for this demo/scope it's fine)
// A better way: Collect keys.
$sql = "SELECT * FROM " . $table_interpretations;
$result = $db->query($sql);
$interpretations_db = [];
while ($row = $result->fetch()) {
    // Key by star_key + palace_key (e.g. 'tu_vi|ngo')
    // Or just group by star_key
    $key = strtolower($row['star_key'] . '|' . $row['palace_key']);
    $interpretations_db[$key] = $row['content'];
}

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

// Assign Chart Data
foreach ($chartData as $palace) {
    $xtpl->assign('PALACE', $palace);

    // Check for Palace Interpretation (e.g. Menh at Ngo)
    // Key format: 'menh|ngo' or similar? Depends on how data is entered.
    // We normalize keys: lowercase, underscores.
    $palaceKey = str_replace('-', '_', strtolower(change_alias($palace['name']))); // menh, phu_mau...
    $zodiacKey = str_replace('-', '_', strtolower(change_alias($palace['zodiac']))); // ty, suu...

    // Look up Palace Interpretation
    $dbKey = $palaceKey . '|' . $zodiacKey;
    if (isset($interpretations_db[$dbKey])) {
        $interpretation_html .= "<div class='panel panel-default'><div class='panel-heading'>" . $palace['name'] . " tại " . $palace['zodiac'] . "</div><div class='panel-body'>" . $interpretations_db[$dbKey] . "</div></div>";
    }

    // Parse Stars
    if (!empty($palace['stars'])) {
        foreach ($palace['stars'] as $star) {
            $star['css'] = 'star-' . $star['element']; // star-kim, star-moc
            $xtpl->assign('STAR', $star);
            $xtpl->parse('main.loop.stars');

            // Look up Star Interpretation
            // Key: 'tu_vi|ngo'
            $starKey = str_replace('-', '_', strtolower(change_alias($star['name']))); // tu_vi
            $dbKey = $starKey . '|' . $zodiacKey;

            if (isset($interpretations_db[$dbKey])) {
                 $interpretation_html .= "<div class='panel panel-info'><div class='panel-heading'>Sao " . $star['name'] . " tại " . $palace['zodiac'] . " (" . $palace['name'] . ")</div><div class='panel-body'>" . $interpretations_db[$dbKey] . "</div></div>";
            }
        }
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
