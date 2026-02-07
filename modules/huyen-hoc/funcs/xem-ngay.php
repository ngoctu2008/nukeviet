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

use NukeViet\Module\HuyenHoc\XemNgay;
use NukeViet\Module\HuyenHoc\LunarCalendar;

$page_title = $lang_module['xem_ngay'];

$d = $nv_Request->get_int('d', 'post,get', date('d'));
$m = $nv_Request->get_int('m', 'post,get', date('m'));
$y = $nv_Request->get_int('y', 'post,get', date('Y'));

// Convert to Lunar
$lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);

// Calculate Can Chi for Day first
$canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], 0);

// Check Good Day (Now requires Day Chi)
$info = XemNgay::checkNgayTot($lunar['day'], $lunar['month'], $lunar['year'], $canChi['chiDay']);

// Get Gio Hoang Dao
$gioHoangDao = XemNgay::getGioHoangDao($canChi['chiDay']);

$xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('INPUT', array('d' => $d, 'm' => $m, 'y' => $y));
$xtpl->assign('LUNAR', $lunar);
$xtpl->assign('INFO', $info);

foreach($gioHoangDao as $g) {
    $xtpl->assign('GIO', array('name' => $g)); // Should map to name (Ty, Suu...)
    $xtpl->parse('main.gio');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
