<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_XEMNGAY')) {
    die('Stop!!!');
}

require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/LunarDate.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/FengShuiCore.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/EventInterface.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/Events/ConstructionEvent.php';

use NukeViet\Module\XemNgay\Lib\Events\ConstructionEvent;
use NukeViet\Module\XemNgay\Lib\FengShuiCore; // Add this line

$page_title = $lang_module['construction_title'];
$key_words = $module_info['keywords'];

// Load CSS
$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/css/xem-ngay.css">';

$xtpl = new XTemplate('construction.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$lang_current = NV_LANG_DATA;
$xtpl->assign('ACTION_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_current . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);

$data = [
    'birth_year' => '',
    'gender' => 1,
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+30 days'))
];

if ($nv_Request->isset_request('submit', 'post')) {
    $data['birth_year'] = $nv_Request->get_int('birth_year', 'post', 0);
    $data['gender'] = $nv_Request->get_int('gender', 'post', 1);
    $data['start_date'] = $nv_Request->get_string('start_date', 'post', date('Y-m-d'));
    $data['end_date'] = $nv_Request->get_string('end_date', 'post', date('Y-m-d', strtotime('+30 days')));

    $xtpl->assign('DATA', $data);
    if ($data['gender'] == 1) $xtpl->assign('SELECTED_MALE', 'selected');
    else $xtpl->assign('SELECTED_FEMALE', 'selected');

    if ($data['birth_year'] > 0) {
        $construction = new ConstructionEvent();
        $fengShui = new FengShuiCore();
        $year = (int)date('Y');

        // Info: Cung Menh, Sao Han
        $cung = $fengShui->getCungMenh($data['birth_year'], $data['gender']);
        $elementNames = ['Kim', 'Mộc', 'Thủy', 'Hỏa', 'Thổ'];
        $age = $year - $data['birth_year'] + 1;
        $sao = $fengShui->getSaoHan($age, $data['gender']);

        $xtpl->assign('INFO', [
            'cung_menh' => $cung['name'],
            'cung_element' => isset($elementNames[$cung['element']]) ? $elementNames[$cung['element']] : '',
            'sao_han' => $sao
        ]);

        // Check Age
        $ageCheck = $construction->checkAge($data['birth_year'], $year);

        $warnings = [];
        if ($ageCheck['kim_lau']) $warnings[] = "Phạm Kim Lâu";
        if ($ageCheck['hoang_oc']) $warnings[] = "Phạm Hoang Ốc";
        if ($ageCheck['tam_tai']) $warnings[] = "Phạm Tam Tai";

        if (!empty($warnings)) {
            $xtpl->assign('WARNING_MSG', "Tuổi " . $ageCheck['age'] . " không đẹp để làm nhà năm nay: " . implode(', ', $warnings) . ". Nên mượn tuổi.");
            $xtpl->parse('main.result.warning');

            if (!empty($ageCheck['advice'])) {
                foreach ($ageCheck['advice'] as $adv) {
                    $xtpl->assign('ADVICE', $adv);
                    $xtpl->parse('main.result.advice.loop');
                }
                $xtpl->parse('main.result.advice');
            }
        } else {
            $xtpl->assign('SUCCESS_MSG', "Tuổi " . $ageCheck['age'] . " đẹp, có thể động thổ.");
            $xtpl->parse('main.result.success');
        }

        // Find Dates
        $dates = $construction->findDates($data['birth_year'], $data['start_date'], $data['end_date']);

        foreach ($dates as $date) {
            $date['hoang_dao'] = $date['is_hoang_dao'] ? 'Có' : 'Không';
            $xtpl->assign('ROW', $date);
            $xtpl->parse('main.result.date_row');
        }

        $xtpl->parse('main.result');
    }
} else {
    $xtpl->assign('DATA', $data);
    $xtpl->assign('SELECTED_MALE', 'selected');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
