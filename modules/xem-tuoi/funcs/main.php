<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_IS_MOD_XEM_TUOI')) {
    die('Stop!!!');
}

$page_title = $lang_module['main_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=result');

// Loop years for select
$current_year = date('Y');
for ($i = $current_year; $i >= 1900; $i--) {
    $xtpl->assign('YEAR', $i);
    $xtpl->parse('main.loop_year1');
    $xtpl->parse('main.loop_year2');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
