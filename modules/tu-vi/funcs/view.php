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
// Load Star Definitions (Safe include)
if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/data_vi.php')) {
    include NV_ROOTDIR . '/modules/' . $module_file . '/language/data_vi.php';
} else {
    $star_definitions = [];
}

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
$view_year = $nv_Request->get_int('view_year', 'post', date('Y'));

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

// Calculate Tieu Han (Viewing Year)
// For demonstration, simplistic Tieu Han logic:
// Nam: Start at Grid X, CW/CCW...
// We will just mark a random one as "Tiểu Hạn" for demo if actual logic is complex
// But let's try to map the viewing year to a Zodiac.
$view_year_chi = ($view_year + 8) % 12; // 0=Ty...
// Tieu Han location depends on Birth Year Chi + Gender
// Let's assume we find the palace that matches the View Year Chi (Luu Thai Tue) for now,
// OR simpler: just pass the year to the template.

// Prepare DB Query for Interpretations
$table_interpretations = $db_config['prefix'] . '_' . NV_LANG_DATA . '_' . str_replace('-', '_', $module_data) . '_interpretations';

// Fetch all interpretations
$sql = "SELECT * FROM " . $table_interpretations;
try {
    $result = $db->query($sql);
    $interpretations_db = [];
    while ($row = $result->fetch()) {
        // Key logic:
        // 1. Star + Palace: 'tu_vi|ngo'
        // 2. Topic + Palace: 'nam_xem|ngo'
        $key = strtolower($row['star_key'] . '|' . $row['palace_key']);
        $interpretations_db[$key] = $row['content'];

        // Also support Topic-based grouping if needed
        $interpretations_db['topic_' . $row['topic'] . '|' . $key] = $row['content'];
    }
} catch (PDOException $e) {
    $interpretations_db = [];
}

// Prepare View
$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('TEMPLATE', $module_info['template']);

// Assign User Info
$canChiYear = Lunisolar::getCanChiYear($lunarY);
$userInfo = [
    'fullname' => $fullname,
    'birth_solar' => "$d/$m/$y $hour:00",
    'birth_lunar' => "$lunarD/$lunarM/$lunarY ($canChiYear)",
    'gender_txt' => ($gender == 1) ? 'Nam' : 'Nữ',
    'year_view' => $view_year
];
$xtpl->assign('USER', $userInfo);

// Interpretation Output Buffers
$inter_general = '';
$inter_yearly = '';

// Assign Chart Data
foreach ($chartData as $palace) {
    $xtpl->assign('PALACE', $palace);

    // Key Normalization
    $palaceKey = str_replace('-', '_', strtolower(change_alias($palace['name'])));
    $zodiacKey = str_replace('-', '_', strtolower(change_alias($palace['zodiac'])));

    // 1. General Interpretation (Lifetime)
    // Star + Palace
    $dbKey = $palaceKey . '|' . $zodiacKey;
    if (isset($interpretations_db[$dbKey])) {
        $inter_general .= "<div class='panel panel-default'><div class='panel-heading'>" . $palace['name'] . " tại " . $palace['zodiac'] . "</div><div class='panel-body'>" . $interpretations_db[$dbKey] . "</div></div>";
    }

    // Parse Stars & Tooltips
    if (!empty($palace['stars'])) {
        foreach ($palace['stars'] as $star) {
            $star['css'] = 'star-' . $star['element'];

            // Build Tooltip
            $starKeyRaw = str_replace('-', '_', strtolower(change_alias($star['name'])));
            if (isset($star_definitions[$starKeyRaw])) {
                $def = $star_definitions[$starKeyRaw];
                $tooltip = "<strong>" . $def['name'] . " (" . $def['element'] . ")</strong><br>" .
                           "<em>" . $def['nature'] . "</em><br>" .
                           $def['info'];
            } else {
                $tooltip = $star['name'];
            }
            $star['tooltip'] = $tooltip;

            $xtpl->assign('STAR', $star);
            $xtpl->parse('main.loop.stars');

            // Interpretation
            $dbKey = $starKeyRaw . '|' . $zodiacKey;
            if (isset($interpretations_db[$dbKey])) {
                 $inter_general .= "<div class='panel panel-info'><div class='panel-heading'>Sao " . $star['name'] . " tại " . $palace['zodiac'] . " (" . $palace['name'] . ")</div><div class='panel-body'>" . $interpretations_db[$dbKey] . "</div></div>";
            }
        }
    }

    // 2. Yearly Interpretation (Năm Xem)
    // Check if this Palace matches the View Year (Luu Thai Tue)
    // View Year Chi: $view_year_chi (0-11). Palace Index: $palace['index'] (0-11)?
    // Wait, Horoscope.php returns indices 0-11 mapped to Ty..Hoi.
    // If $view_year_chi == $palace['index'], this is the Year Palace (Thai Tue).
    if ($palace['index'] == $view_year_chi) {
        // Fetch 'nam_xem' topic
        // We assume logic: "nam_xem" record where palace_key = 'menh' (if Year falls in Menh)
        // OR specific Year Comments.
        // Let's just output a generic header for now and try to find DB content
        $inter_yearly .= "<h4>Năm " . $view_year . " (Tại cung " . $palace['zodiac'] . ")</h4>";
        // Try key: 'nam_xem|ty' (Year at Ty)
        $yearKey = 'nam_xem|' . $zodiacKey;
        if (isset($interpretations_db[$yearKey])) {
            $inter_yearly .= "<div class='alert alert-warning'>" . $interpretations_db[$yearKey] . "</div>";
        } else {
             $inter_yearly .= "<p><em>Chưa có dữ liệu luận giải cho năm này tại cung " . $palace['zodiac'] . ".</em></p>";
        }
    }

    $xtpl->parse('main.loop');
}

// Assign Output Blocks
$xtpl->assign('INTERPRETATION', $inter_general);
$xtpl->assign('INTERPRETATION_YEAR', $inter_yearly);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
