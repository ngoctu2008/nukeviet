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

// Check Permissions
$module_table_name = str_replace('-', '_', $module_data);
$table_config = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_config";
$sql = "SELECT config_value FROM " . $table_config . " WHERE config_name = 'groups_view'";
$result = $db->query($sql);
$row = $result->fetch();

if (!empty($row)) {
    $allowed_groups = explode(',', $row['config_value']);
    if (!empty($allowed_groups) && !nv_user_in_groups($allowed_groups)) {
        $contents = "Bạn không có quyền xem nội dung này. Vui lòng đăng nhập hoặc liên hệ quản trị viên.";
        include NV_ROOTDIR . '/includes/header.php';
        echo nv_site_theme($contents);
        include NV_ROOTDIR . '/includes/footer.php';
        exit;
    }
}

$id = $nv_Request->get_int('id', 'get', 0);
if ($id <= 0) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Get Event Info
$table_events = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $module_table_name . "_events";
$sql = "SELECT * FROM " . $table_events . " WHERE id = " . $id;
$result = $db->query($sql);
$event_info = $result->fetch();

if (empty($event_info)) {
    die('Event not found');
}

$event_config = !empty($event_info['config']) ? unserialize($event_info['config']) : ['logic' => [], 'inputs' => []];

require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/LunarDate.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/FengShuiCore.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/EventInterface.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/Events/CustomEvent.php';

use NukeViet\Module\XemNgay\Lib\Events\CustomEvent;

$page_title = $event_info['title'];
$key_words = $module_info['keywords'];

// Load CSS
$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/css/xem-ngay.css">';

$xtpl = new XTemplate('custom.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$lang_current = NV_LANG_DATA;
$xtpl->assign('ACTION_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_current . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=custom&id=' . $id);
$xtpl->assign('EVENT', $event_info);

// Render Inputs
if (in_array('input_partner', $event_config['inputs'])) {
    $xtpl->parse('main.partner');
}

$data = [
    'birth_year' => '',
    'partner_year' => '',
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+30 days'))
];

if ($nv_Request->isset_request('submit', 'post')) {
    $data['birth_year'] = $nv_Request->get_int('birth_year', 'post', 0);
    $data['partner_year'] = $nv_Request->get_int('partner_year', 'post', 0);
    $data['start_date'] = $nv_Request->get_string('start_date', 'post', date('Y-m-d'));
    $data['end_date'] = $nv_Request->get_string('end_date', 'post', date('Y-m-d', strtotime('+30 days')));

    $xtpl->assign('DATA', $data);

    if ($data['birth_year'] > 0) {
        $customEvent = new CustomEvent($event_config);
        $year = (int)date('Y');

        $ageCheck = $customEvent->checkAge($data['birth_year'], $year, $data['partner_year']);

        if (!$ageCheck['is_good']) {
            if (!empty($ageCheck['warnings'])) {
                foreach ($ageCheck['warnings'] as $w) {
                    $xtpl->assign('WARNING', $w);
                    $xtpl->parse('main.result.warning.loop');
                }
                $xtpl->parse('main.result.warning');
            }
        } else {
            $xtpl->assign('SUCCESS_MSG', "Tuổi " . $ageCheck['age'] . " tốt cho công việc này.");
            $xtpl->parse('main.result.success');
        }

        $dates = $customEvent->findDates($data['birth_year'], $data['start_date'], $data['end_date']);

        foreach ($dates as $date) {
            $date['hoang_dao'] = $date['is_hoang_dao'] ? 'Có' : 'Không';
            $xtpl->assign('ROW', $date);
            $xtpl->parse('main.result.date_row');
        }

        $xtpl->parse('main.result');
    }
} else {
    $xtpl->assign('DATA', $data);
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
