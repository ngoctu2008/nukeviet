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

use NukeViet\Module\HuyenHoc\XemTuoi;

$page_title = $lang_module['xem_tuoi'];

$y1 = $nv_Request->get_int('y1', 'post,get', 1990);
$y2 = $nv_Request->get_int('y2', 'post,get', 1995);

$result = array();
if ($nv_Request->isset_request('submit', 'post,get')) {
    $result = XemTuoi::checkHopKhac($y1, $y2);
}

$xtpl = new XTemplate('xem-tuoi.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('INPUT', array('y1' => $y1, 'y2' => $y2));

if (!empty($result)) {
    $xtpl->assign('RESULT', $result);
    foreach($result['details'] as $detail) {
        $xtpl->assign('DETAIL', $detail);
        $xtpl->parse('main.result.detail');
    }
    $xtpl->parse('main.result');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
