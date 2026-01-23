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

if ($nv_Request->isset_request('submit', 'post')) {
    $day = $nv_Request->get_int('day', 'post', 1);
    $month = $nv_Request->get_int('month', 'post', 1);
    $year = $nv_Request->get_int('year', 'post', 1990);
    $hour = $nv_Request->get_int('hour', 'post', 0); // 0-11
    $gender = $nv_Request->get_int('gender', 'post', 1);

    // Convert Solar to Lunar (if input is Solar - assumed for now it's Solar input)
    // But Tu Vi usually asks for Solar or Lunar. Let's assume user inputs Solar.
    $lunar = LunarCalendar::convertSolar2Lunar($day, $month, $year);

    // Get Can Chi
    $canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], $hour);

    // Lap La So
    // Note: TuViLapSo expects Lunar Year, Month, Day, Hour (0-11).
    // Our LunarCalendar::convertSolar2Lunar returns simple mapping for now.
    $laSo = TuViLapSo::lapLaSo($lunar['day'], $lunar['month'], $lunar['year'], $hour, $gender, $canChi['canYear']);

    $result = array(
        'input' => array('d' => $day, 'm' => $month, 'y' => $year, 'h' => $hour, 'g' => $gender),
        'lunar' => $lunar,
        'canchi' => $canChi,
        'chart' => $laSo
    );
}

$contents = nv_theme_huyen_hoc_tu_vi($result);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
