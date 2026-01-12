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

$page_title = $module_info['custom_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=view');

// Render days/months/years options
for ($i = 1; $i <= 31; $i++) {
    $xtpl->assign('DAY', ['value' => $i, 'title' => $i]);
    $xtpl->parse('main.day');
}
for ($i = 1; $i <= 12; $i++) {
    $xtpl->assign('MONTH', ['value' => $i, 'title' => $i]);
    $xtpl->parse('main.month');
}
$curYear = date('Y');
for ($i = 1900; $i <= $curYear + 1; $i++) {
    $sel = ($i == 1990) ? 'selected="selected"' : '';
    $xtpl->assign('YEAR', ['value' => $i, 'title' => $i, 'selected' => $sel]);
    $xtpl->parse('main.year');
}
// Hours (0-23)
for ($i = 0; $i < 24; $i++) {
    $xtpl->assign('HOUR', ['value' => $i, 'title' => $i . ':00 - ' . $i . ':59']);
    $xtpl->parse('main.hour');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
