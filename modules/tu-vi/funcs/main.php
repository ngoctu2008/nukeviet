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

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=view');

// Generate Hours dropdown
for ($i = 0; $i < 24; $i++) {
    $xtpl->assign('HOUR', ['val' => $i, 'title' => $i . 'h']);
    $xtpl->parse('main.hour');
}

// Generate Days
for ($i = 1; $i <= 31; $i++) {
    $xtpl->assign('DAY', ['val' => $i, 'title' => $i]);
    $xtpl->parse('main.day');
}

// Generate Months
for ($i = 1; $i <= 12; $i++) {
    $xtpl->assign('MONTH', ['val' => $i, 'title' => $i]);
    $xtpl->parse('main.month');
}

// Generate Years (1920-2030)
$curYear = date('Y');
for ($i = 1950; $i <= $curYear + 1; $i++) {
    $sel = ($i == 2000) ? 'selected' : '';
    $xtpl->assign('YEAR', ['val' => $i, 'title' => $i, 'selected' => $sel]);
    $xtpl->parse('main.year');
}

$xtpl->assign('CURRENT_YEAR', date('Y'));

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
