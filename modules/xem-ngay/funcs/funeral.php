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
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/Events/FuneralEvent.php';

use NukeViet\Module\XemNgay\Lib\Events\FuneralEvent;

$page_title = $lang_module['funeral_title'];
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('funeral.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ACTION_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);

// Default Data
$data = [
    'deceased_year' => '',
    'gender' => 1,
    'death_time' => date('Y-m-d\TH:i'),
    'chief_year' => '',
    'relatives_str' => ''
];

if ($nv_Request->isset_request('submit', 'post')) {
    $data['deceased_year'] = $nv_Request->get_int('deceased_year', 'post', 0);
    $data['gender'] = $nv_Request->get_int('gender', 'post', 1);
    $data['death_time'] = $nv_Request->get_string('death_time', 'post', '');
    $data['chief_year'] = $nv_Request->get_int('chief_year', 'post', 0);
    $data['relatives_str'] = $nv_Request->get_string('relatives', 'post', '');

    $xtpl->assign('DATA', $data);
    if ($data['gender'] == 1) $xtpl->assign('SELECTED_MALE', 'selected');
    else $xtpl->assign('SELECTED_FEMALE', 'selected');

    if ($data['deceased_year'] > 0 && !empty($data['death_time'])) {
        $funeral = new FuneralEvent();

        // 1. Check Death Time (Trùng Tang)
        // death_time format from input type=datetime-local is Y-m-dTH:i
        $deathTimeStr = str_replace('T', ' ', $data['death_time']) . ':00';

        $status = $funeral->checkDeathTime($deathTimeStr, $data['deceased_year'], $data['gender']);

        // Parse Result
        $statusMap = [
            'Trùng Tang' => ['class' => 'status-bad', 'text' => 'Trùng Tang (Xấu)'],
            'Thiên Di' => ['class' => 'neutral-status', 'text' => 'Thiên Di (Bình thường)'],
            'Nhập Mộ' => ['class' => 'status-good', 'text' => 'Nhập Mộ (Tốt)']
        ];

        foreach (['age', 'month', 'day', 'hour'] as $key) {
            $st = $status[$key . '_status'];
            $info = isset($statusMap[$st]) ? $statusMap[$st] : ['class' => '', 'text' => $st];

            $xtpl->assign(strtoupper($key) . '_STATUS', $info['text']);
            $xtpl->assign(strtoupper($key) . '_CLASS', $info['class']);
            $xtpl->assign(strtoupper($key) . '_CHI', $status['details'][$key . '_chi']);
        }
        $xtpl->parse('main.result.trung_tang');

        // 2. Find Dates (Next 7 days)
        $startDate = date('Y-m-d', strtotime($deathTimeStr . ' +1 day'));
        $endDate = date('Y-m-d', strtotime($deathTimeStr . ' +7 days'));

        $relativesArr = [];
        if (!empty($data['relatives_str'])) {
            $parts = explode(',', $data['relatives_str']);
            foreach ($parts as $p) {
                $y = (int)trim($p);
                if ($y > 0) $relativesArr[] = ['birth_year' => $y];
            }
        }

        $dates = $funeral->findDates(
            ['birth_year' => $data['deceased_year'], 'gender' => $data['gender'], 'death_date' => $deathTimeStr],
            ['birth_year' => $data['chief_year']],
            $relativesArr,
            $startDate,
            $endDate
        );

        foreach ($dates as $date) {
            $date['hoang_dao'] = $date['is_hoang_dao'] ? 'Hoàng Đạo' : '-';
            $date['score_class'] = ($date['score'] >= 2) ? 'high' : (($date['score'] >= 1) ? 'medium' : 'low');

            $xtpl->assign('ROW', $date);

            if (!empty($date['warnings'])) {
                foreach ($date['warnings'] as $w) {
                    $xtpl->assign('WARNING', $w);
                    $xtpl->parse('main.result.date_row.warning');
                }
            }

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
