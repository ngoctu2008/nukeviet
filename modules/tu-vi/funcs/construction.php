<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_IS_MOD_TU_VI')) {
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

require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/LunarDate.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/FengShuiCore.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/EventInterface.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/Events/ConstructionEvent.php';
require_once NV_ROOTDIR . '/modules/' . $module_file . '/includes/XemNgayHelper.php';

use NukeViet\Module\TuVi\Includes\Events\ConstructionEvent;
use NukeViet\Module\TuVi\Includes\XemNgayHelper;
use NukeViet\Module\TuVi\Includes\LunarDate;

$page_title = $lang_module['construction_title'];
$key_words = $module_info['keywords'];

// Load CSS
$my_head .= '<link rel="stylesheet" href="' . NV_BASE_SITEURL . 'themes/' . $module_info['template'] . '/css/tu-vi.css">';

$xtpl = new XTemplate('construction.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$lang_current = NV_LANG_DATA;
$xtpl->assign('ACTION_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_current . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=' . $op);

$data = [
    'birth_year' => '',
    'start_date' => date('Y-m-d'),
    'end_date' => date('Y-m-d', strtotime('+30 days'))
];

if ($nv_Request->isset_request('submit', 'post')) {
    $data['birth_year'] = $nv_Request->get_int('birth_year', 'post', 0);
    $data['start_date'] = $nv_Request->get_string('start_date', 'post', date('Y-m-d'));
    $data['end_date'] = $nv_Request->get_string('end_date', 'post', date('Y-m-d', strtotime('+30 days')));

    $xtpl->assign('DATA', $data);

    if ($data['birth_year'] > 0) {
        $construction = new ConstructionEvent();
        $helper = new XemNgayHelper();
        $lunarLib = new LunarDate();

        $year = (int)date('Y');

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

        // Find Dates using Enhanced Funnel
        $current = strtotime($data['start_date']);
        $end = strtotime($data['end_date']);

        while ($current <= $end) {
            $d = (int)date('d', $current);
            $m = (int)date('m', $current);
            $y = (int)date('Y', $current);

            // Convert to Lunar
            $lunar = $lunarLib->convertSolarToLunar($d, $m, $y);
            // Result: [day, month, year, leap, dayCan, dayChi, monthCan, monthChi, yearCan, yearChi]
            // Note: convertSolarToLunar returns indexed array mostly, but calculateCanChi merges keys.
            // Let's ensure structure.

            $analysis = $helper->calculate($lunar, $data['birth_year'], 'house_build');

            if ($analysis['score'] >= 50) { // Filter out bad days
                $dateRow = [
                    'date' => date('Y-m-d', $current),
                    'lunar_date' => $lunar[0] . '/' . $lunar[1],
                    'day_can_chi' => $lunarLib->getCanName($lunar['dayCan']) . ' ' . $lunarLib->getChiName($lunar['dayChi']),
                    'truc' => $analysis['truc'],
                    'hoang_dao' => ($analysis['score'] >= 80) ? 'Đại Cát' : 'Tiểu Cát', // Simple mapping
                    'score' => $analysis['score'],
                    'hours' => '' // Would calc hours here if needed
                ];

                // Add warnings/advice to view if score is average
                if (!empty($analysis['warnings'])) {
                    // Could append to notes
                    $dateRow['truc'] .= " (" . implode(', ', $analysis['warnings']) . ")";
                }

                $xtpl->assign('ROW', $dateRow);
                $xtpl->parse('main.result.date_row');
            }

            $current = strtotime('+1 day', $current);
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
