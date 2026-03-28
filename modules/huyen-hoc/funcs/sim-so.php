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

use NukeViet\Module\HuyenHoc\SimPhongThuy;

$page_title = $lang_module['sim_so'];
$phone = $nv_Request->get_string('phone', 'post,get', '');

$result = array();
if (!empty($phone)) {
    $result = SimPhongThuy::analyze($phone);
}

$xtpl = new XTemplate('sim-so.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('PHONE', $phone);

if (!empty($result)) {
    if (isset($result['error'])) {
        $xtpl->assign('ERROR', $result['error']);
        $xtpl->parse('main.error');
    } else {
        $xtpl->assign('RESULT', $result);
        $xtpl->parse('main.result');
    }
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
