<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

if (!defined('NV_IS_MOD_HUYEN_HOC')) {
    die('Stop!!!');
}

use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViLapSo;
use NukeViet\Module\HuyenHoc\TuViLuanGiai;
use NukeViet\Module\HuyenHoc\TuViVanHan;
use NukeViet\Module\HuyenHoc\TuViSaoHan;

$page_title = $lang_module['tu_vi'];

// AJAX: Xem Vận Hạn
if ($nv_Request->isset_request('ajax_get_han', 'post')) {
    $birthDay = $nv_Request->get_int('d', 'post', 1);
    $birthMonth = $nv_Request->get_int('m', 'post', 1);
    $birthYear = $nv_Request->get_int('y', 'post', 1990);
    $birthHour = $nv_Request->get_int('h', 'post', 0);
    $gender = $nv_Request->get_int('g', 'post', 1);
    $viewYear = $nv_Request->get_int('view_year', 'post', date('Y'));
    $name = $nv_Request->get_string('name', 'post', 'Đương số');

    // 1. Convert Lunar for Birth
    $lunar = LunarCalendar::convertSolar2Lunar($birthDay, $birthMonth, $birthYear);
    $canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], $birthHour);

    // 2. Lap La So Goc
    $laSoData = TuViLapSo::lapLaSo(
        $lunar['day'],
        $lunar['month'],
        $lunar['year'],
        $birthHour,
        $gender,
        $canChi['canYear'],
        $canChi['chiYear'],
        $name
    );

    // Load Star Meanings (fix for undefined variable)
    $starMeanings = [];
    $jsonPath = NV_ROOTDIR . '/modules/' . $module_file . '/data/star_meanings.json';
    if (file_exists($jsonPath)) {
        $jsonContent = file_get_contents($jsonPath);
        $starMeanings = json_decode($jsonContent, true);
    }

    // 3. Tinh Sao Han (Cuu Dieu, Tam Tai...)
    $saoHanApp = new TuViSaoHan($birthYear, $gender, $viewYear);
    $htmlSaoHan = $saoHanApp->execute();

    // 4. Tinh Van Han (Tu Vi Chart Limits)
    $vanHanApp = new TuViVanHan($laSoData, $viewYear);
    $htmlVanHan = $vanHanApp->luanGiaiChiTiet();

    // Combine
    $html = '<div class="row">';
    $html .= '<div class="col-md-6">' . $htmlSaoHan . '</div>';
    $html .= '<div class="col-md-6">' . $htmlVanHan . '</div>';
    $html .= '</div>';

    echo $html;
    die();
}

$result = array();

$data_input = array(
    'name' => $nv_Request->get_string('name', 'post', ''),
    'd' => $nv_Request->get_int('day', 'post', date('d')),
    'm' => $nv_Request->get_int('month', 'post', date('m')),
    'y' => $nv_Request->get_int('year', 'post', date('Y')),
    'h' => $nv_Request->get_int('hour', 'post', 0),
    'g' => $nv_Request->get_int('gender', 'post', 1)
);

if ($nv_Request->isset_request('submit', 'post')) {
    $day = $data_input['d'];
    $month = $data_input['m'];
    $year = $data_input['y'];
    $hour = $data_input['h'];
    $gender = $data_input['g'];
    $name = $data_input['name'];
    if (empty($name)) $name = 'Đương số';

    // Convert Solar to Lunar
    $lunar = LunarCalendar::convertSolar2Lunar($day, $month, $year);

    // Get Can Chi
    $canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], $hour);

    // Lap La So
    $laSoData = TuViLapSo::lapLaSo(
        $lunar['day'],
        $lunar['month'],
        $lunar['year'],
        $hour,
        $gender,
        $canChi['canYear'],
        $canChi['chiYear'],
        $name
    );

    // Load Star Meanings from JSON
    $starMeanings = [];
    $jsonPath = NV_ROOTDIR . '/modules/' . $module_file . '/data/star_meanings.json';
    if (file_exists($jsonPath)) {
        $jsonContent = file_get_contents($jsonPath);
        $starMeanings = json_decode($jsonContent, true);
    }

    // Enrich Star Data in La So
    foreach ($laSoData['dia_ban'] as &$palace) {
        $lists = ['chinh_tinh', 'phu_tinh_tot', 'phu_tinh_xau'];
        foreach ($lists as $listName) {
            if (!empty($palace[$listName])) {
                foreach ($palace[$listName] as &$star) {
                    if (isset($starMeanings[$star['code']])) {
                        $info = $starMeanings[$star['code']];
                        // Merge info fields
                        $star['tooltip_name'] = $info['name'];
                        $star['tooltip_hanh'] = $info['hanh'];
                        $star['tooltip_loai'] = isset($info['loai']) ? $info['loai'] : '';
                        $star['tooltip_tinh_chat'] = isset($info['dac_tinh']) ? $info['dac_tinh'] : '';
                        $star['tooltip_dac_ham'] = isset($info['dac_ham']) ? $info['dac_ham'] : '';
                        $star['tooltip_y_nghia'] = isset($info['y_nghia']) ? $info['y_nghia'] : [];
                        $star['tooltip_luu_sao'] = isset($info['luu_sao']) ? $info['luu_sao'] : '';
                        $star['tooltip_cach_cuc'] = isset($info['cach_cuc']) ? $info['cach_cuc'] : '';
                    }
                }
            }
        }
    }
    unset($palace);

    // Luan Giai
    try {
        // Pass Loaded Star Meanings to Interpreter
        $interpreter = new TuViLuanGiai($starMeanings);
        $interpretation = $interpreter->luanGiai($laSoData);
        $structuredReport = $interpreter->generateStructuredReport($laSoData);

        // Merge into dia_ban
        foreach ($laSoData['dia_ban'] as $i => &$palace) {
             if (isset($interpretation[$i])) {
                 $palace['luan_giai'] = $interpretation[$i];

                 // Map readings to Chinh Tinh
                 if (!empty($palace['chinh_tinh']) && !empty($interpretation[$i]['chinh_tinh'])) {
                     foreach ($palace['chinh_tinh'] as &$star) {
                         foreach ($interpretation[$i]['chinh_tinh'] as $reading) {
                             if (isset($reading['star_code']) && $reading['star_code'] == $star['code']) {
                                 $star['content'] = $reading['content'];
                             }
                         }
                     }
                 }

                 // Map readings to Phu Tinh Tot
                 if (!empty($palace['phu_tinh_tot']) && !empty($interpretation[$i]['phu_tinh'])) {
                     foreach ($palace['phu_tinh_tot'] as &$star) {
                         foreach ($interpretation[$i]['phu_tinh'] as $reading) {
                             if (isset($reading['star_code']) && $reading['star_code'] == $star['code']) {
                                 $star['content'] = $reading['content'];
                             }
                         }
                     }
                 }

                 // Map readings to Phu Tinh Xau
                 if (!empty($palace['phu_tinh_xau']) && !empty($interpretation[$i]['phu_tinh'])) {
                     foreach ($palace['phu_tinh_xau'] as &$star) {
                         foreach ($interpretation[$i]['phu_tinh'] as $reading) {
                             if (isset($reading['star_code']) && $reading['star_code'] == $star['code']) {
                                 $star['content'] = $reading['content'];
                             }
                         }
                     }
                 }
             }
        }
        unset($palace);

        // Add Tong Quan to laSoData
        $laSoData['luan_giai_tong_quan'] = array(
            'menh' => isset($interpretation['tong_quan_menh']) ? $interpretation['tong_quan_menh'] : [],
            'than' => isset($interpretation['tong_quan_than']) ? $interpretation['tong_quan_than'] : [],
            'overview' => isset($interpretation['overview']) ? $interpretation['overview'] : [],
            'limit' => isset($interpretation['limit']) ? $interpretation['limit'] : []
        );

        $laSoData['structured_report'] = $structuredReport;

    } catch (\Exception $e) {
        // Ignore error if DB not ready
    }

    // Re-key dia_ban for template access (ty, suu, dan...)
    $diaBanKeyed = array();
    foreach ($laSoData['dia_ban'] as $palace) {
        $diaBanKeyed[$palace['key']] = $palace;
    }
    $laSoData['dia_ban'] = $diaBanKeyed;

    $result = array(
        'input' => $data_input,
        'lunar' => $lunar,
        'canchi' => $canChi,
        'laso' => $laSoData
    );
}

// Pass data to theme function
// Note: We need to define nv_theme_huyen_hoc_tu_vi in theme.php or generic
// Assuming theme.php handles 'tu_vi' template.
$contents = nv_theme_huyen_hoc_tu_vi($result, $data_input);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
