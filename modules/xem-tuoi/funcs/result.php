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

$year_1 = $nv_Request->get_int('year_1', 'post', 0);
$gender_1 = $nv_Request->get_int('gender_1', 'post', 1);
$year_2 = $nv_Request->get_int('year_2', 'post', 0);
$gender_2 = $nv_Request->get_int('gender_2', 'post', 0);
$purpose = $nv_Request->get_string('purpose', 'post', 'business');

if ($year_1 == 0 || $year_2 == 0) {
    // Redirect back if invalid input
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    die();
}

require_once NV_ROOTDIR . '/modules/' . $module_file . '/sitetools/Calculate.php';

use NukeViet\Module\XemTuoi\SiteTools\Calculate;

$result = Calculate::calculateCompatibility($year_1, $gender_1, $year_2, $gender_2);

// Save log
$sql = "INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_logs
    (year_1, gender_1, year_2, gender_2, type, result_score, add_time, ip)
    VALUES (:year_1, :gender_1, :year_2, :gender_2, :type, :result_score, :add_time, :ip)";

$data_insert = [
    ':year_1' => $year_1,
    ':gender_1' => $gender_1,
    ':year_2' => $year_2,
    ':gender_2' => $gender_2,
    ':type' => $purpose,
    ':result_score' => $result['total'],
    ':add_time' => NV_CURRENTTIME,
    ':ip' => $client_info['ip']
];

$db->prepare($sql)->execute($data_insert);

// Get Advice
$advice = '';
if ($result['total'] >= 8) {
    $advice = $module_config[$module_name]['advice_high'] ?? $lang_module['advice_high'];
} elseif ($result['total'] >= 5) {
    $advice = $module_config[$module_name]['advice_medium'] ?? $lang_module['advice_medium'];
} else {
    $advice = $module_config[$module_name]['advice_low'] ?? $lang_module['advice_low'];
}

$page_title = $lang_module['result_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('result.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('RESULT', $result);
$xtpl->assign('ADVICE', $advice);
$xtpl->assign('BACK_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);

$xtpl->assign('Y1', $year_1);
$xtpl->assign('Y2', $year_2);
$xtpl->assign('G1', ($gender_1 == 1) ? $lang_module['male'] : $lang_module['female']);
$xtpl->assign('G2', ($gender_2 == 1) ? $lang_module['male'] : $lang_module['female']);

// Pass detail scores
$xtpl->assign('SCORE_CAN', $result['scoreCan']);
$xtpl->assign('SCORE_CHI', $result['scoreChi']);
$xtpl->assign('SCORE_NGUHANH', $result['scoreNguHanh']);
$xtpl->assign('SCORE_CUNGPHI', $result['scoreCungPhi']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
