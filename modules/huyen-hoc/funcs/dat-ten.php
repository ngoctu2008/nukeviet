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

use NukeViet\Module\HuyenHoc\NameAnalysis;

$page_title = $lang_module['dat_ten'];

$ho = $nv_Request->get_string('ho', 'post,get', '');
$ten = $nv_Request->get_string('ten', 'post,get', '');
$year = $nv_Request->get_int('year', 'post,get', date('Y'));

$result = array();
if (!empty($ho) && !empty($ten)) {
    // Split 'ten' into 'tenDem' and 'tenChinh'
    $parts = explode(' ', trim($ten));
    $tenChinh = array_pop($parts);
    $tenDem = implode(' ', $parts);

    $result = NameAnalysis::analyze($ho, $tenDem, $tenChinh, $year);
}

$xtpl = new XTemplate('dat-ten.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('INPUT', array('ho' => $ho, 'ten' => $ten, 'year' => $year));

if (!empty($result)) {
    $xtpl->assign('RESULT', $result);
    $xtpl->assign('CACH', $result['ngu_cach']);

    // Pass Han-Viet Breakdown
    $breakdown = array_merge($result['parts']['ho'], $result['parts']['dem'], $result['parts']['ten']);
    foreach ($breakdown as $part) {
        $xtpl->assign('PART', $part);
        $xtpl->parse('main.result.part');
    }

    $xtpl->parse('main.result');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
