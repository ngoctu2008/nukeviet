<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

if (!defined('NV_IS_MOD_TU_VI')) {
    die('Stop!!!');
}

$year_1 = $nv_Request->get_int('year_1', 'post', 0);
$gender_1 = $nv_Request->get_int('gender_1', 'post', 1);
$year_2 = $nv_Request->get_int('year_2', 'post', 0);
$gender_2 = $nv_Request->get_int('gender_2', 'post', 0);
$purpose = $nv_Request->get_string('purpose', 'post', 'business');

if ($year_1 == 0 || $year_2 == 0) {
    Header('Location: ' . NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=compatibility');
    die();
}

require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/XemTuoiHelper.php';

use NukeViet\Module\TuVi\Includes\XemTuoiHelper;

$helper = new XemTuoiHelper();
$result = $helper->tuvan($year_1, $gender_1, $year_2, $gender_2, $purpose);

// Save log
$module_data_safe = str_replace('-', '_', $module_data);
$table_name = NV_PREFIXLANG . "_" . $module_data_safe . "_compatibility_logs";

$sql = "INSERT INTO " . $table_name . "
    (year_1, gender_1, year_2, gender_2, type, result_score, add_time, ip)
    VALUES (:year_1, :gender_1, :year_2, :gender_2, :type, :result_score, :add_time, :ip)";

$data_insert = [
    ':year_1' => $year_1,
    ':gender_1' => $gender_1,
    ':year_2' => $year_2,
    ':gender_2' => $gender_2,
    ':type' => $purpose,
    ':result_score' => $result['total_score'],
    ':add_time' => NV_CURRENTTIME,
    ':ip' => $client_info['ip']
];

$db->prepare($sql)->execute($data_insert);

$page_title = $lang_module['result_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('compatibility_result.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('RESULT', $result);
$xtpl->assign('BACK_LINK', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=compatibility');

$xtpl->assign('Y1', $year_1);
$xtpl->assign('Y2', $year_2);
$xtpl->assign('G1', ($gender_1 == 1) ? $lang_module['male'] : $lang_module['female']);
$xtpl->assign('G2', ($gender_2 == 1) ? $lang_module['male'] : $lang_module['female']);

// Pass detail scores
$xtpl->assign('SCORE_CAN', $result['details']['can']['score']);
$xtpl->assign('SCORE_CHI', $result['details']['chi']['score']);
$xtpl->assign('SCORE_NGUHANH', $result['details']['menh']['score']);
$xtpl->assign('SCORE_CUNGPHI', $result['details']['cung']['score']);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
