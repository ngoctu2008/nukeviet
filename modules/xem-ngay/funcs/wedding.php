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

require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/LunarDate.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/FengShuiCore.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/EventInterface.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/lib/Events/WeddingEvent.php';

use NukeViet\Module\XemNgay\Lib\Events\WeddingEvent;

$page_title = $lang_module['wedding_title'];
$key_words = $module_info['keywords'];

// Load CSS
$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/css/xem-ngay.css">';

$xtpl = new XTemplate('wedding.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$lang_current = NV_LANG_DATA;
$xtpl->assign('ACTION_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_current . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);

$data = [
    'groom_year' => '',
    'bride_year' => '',
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+30 days'))
];

if ($nv_Request->isset_request('submit', 'post')) {
    $data['groom_year'] = $nv_Request->get_int('groom_year', 'post', 0);
    $data['bride_year'] = $nv_Request->get_int('bride_year', 'post', 0);
    $data['start_date'] = $nv_Request->get_string('start_date', 'post', date('Y-m-d'));
    $data['end_date'] = $nv_Request->get_string('end_date', 'post', date('Y-m-d', strtotime('+30 days')));

    $xtpl->assign('DATA', $data);

    if ($data['groom_year'] > 0 && $data['bride_year'] > 0) {
        $wedding = new WeddingEvent();
        $year = (int)date('Y');

        // Check Age
        $ageCheck = $wedding->checkAge($data['groom_year'], $data['bride_year'], $year);

        // Kim Lau Warning
        if ($ageCheck['kim_lau_bride']) {
            $xtpl->assign('WARNING_MSG', "Cảnh báo: Cô dâu phạm Kim Lâu (Tuổi " . $ageCheck['bride_age'] . ").");
            $xtpl->parse('main.result.warning');

            if (!empty($ageCheck['advice'])) {
                foreach ($ageCheck['advice'] as $adv) {
                    $xtpl->assign('ADVICE', $adv);
                    $xtpl->parse('main.result.advice.loop');
                }
                $xtpl->parse('main.result.advice');
            }
        } else {
             $xtpl->assign('SUCCESS_MSG', "Tuổi cô dâu đẹp, không phạm Kim Lâu.");
             $xtpl->parse('main.result.success');
        }

        // Find Dates
        $dates = $wedding->findDates($data['groom_year'], $data['bride_year'], $data['start_date'], $data['end_date']);

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
