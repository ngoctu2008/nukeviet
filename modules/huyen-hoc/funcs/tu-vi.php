<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_HUYEN_HOC')) {
    die('Stop!!!');
}

use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViLapSo;

$page_title = $lang_module['tu_vi'];

$result = array();

$data_input = array(
    'name' => $nv_Request->get_string('name', 'post', ''),
    'd' => $nv_Request->get_int('day', 'post', date('d')),
    'm' => $nv_Request->get_int('month', 'post', date('m')),
    'y' => $nv_Request->get_int('year', 'post', date('Y')),
    'h' => $nv_Request->get_int('hour', 'post', 0),
    'g' => $nv_Request->get_int('gender', 'post', 1)
);

if ($nv_Request->isset_request('submit', 'post')) {
    $day = $data_input['d'];
    $month = $data_input['m'];
    $year = $data_input['y'];
    $hour = $data_input['h'];
    $gender = $data_input['g'];
    $name = $data_input['name'];

    // Convert Solar to Lunar
    $lunar = LunarCalendar::convertSolar2Lunar($day, $month, $year);

    // Get Can Chi
    $canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], $hour);

    // Lap La So
    $laSoData = TuViLapSo::lapLaSo(
        $lunar['day'],
        $lunar['month'],
        $lunar['year'],
        $hour,
        $gender,
        $canChi['canYear'],
        $canChi['chiYear'],
        $name
    );

    // Re-key dia_ban for template access (ty, suu, dan...)
    $diaBanKeyed = array();
    foreach ($laSoData['dia_ban'] as $palace) {
        $diaBanKeyed[$palace['key']] = $palace;
    }
    $laSoData['dia_ban'] = $diaBanKeyed;

    $result = array(
        'input' => $data_input,
        'lunar' => $lunar,
        'canchi' => $canChi,
        'laso' => $laSoData
    );
}

// Pass data to theme function
// Note: We need to define nv_theme_huyen_hoc_tu_vi in theme.php or generic
// Assuming theme.php handles 'tu_vi' template.
$contents = nv_theme_huyen_hoc_tu_vi($result, $data_input);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
