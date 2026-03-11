<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

if (!defined('NV_IS_MOD_HUYEN_HOC')) die('Stop!!!');

$page_title = $module_info['custom_title'] . ' - Xem Tuổi Hợp Khắc';
$key_words = $module_info['keywords'];

$xtpl = new XTemplate('xem-tuoi.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
$xtpl->assign('TEMPLATE', $module_info['template']);
$xtpl->assign('MODULE_FILE', $module_file);
$xtpl->assign('MODULE_NAME', $module_name);

// Inputs
$year1 = $nv_Request->get_int('year1', 'post,get', 0);
$gender1 = $nv_Request->get_int('gender1', 'post,get', 1);
$year2 = $nv_Request->get_int('year2', 'post,get', 0);
$gender2 = $nv_Request->get_int('gender2', 'post,get', 0); // Default female for partner

// Assign inputs back to form
$xtpl->assign('YEAR1', ($year1 > 0 ? $year1 : ''));
$xtpl->assign('YEAR2', ($year2 > 0 ? $year2 : ''));
if ($gender1 == 1) $xtpl->assign('G1_M_CHECKED', 'checked'); else $xtpl->assign('G1_F_CHECKED', 'checked');
if ($gender2 == 1) $xtpl->assign('G2_M_CHECKED', 'checked'); else $xtpl->assign('G2_F_CHECKED', 'checked');

if ($year1 > 0 && $year2 > 0) {
    $app = new \NukeViet\Module\HuyenHoc\XemTuoi();
    $result = $app->soSanhTuoi($year1, $year2, $gender1, $gender2);

    // Nguoi 1
    $xtpl->assign('N1_YEAR', $result['nguoi_1']['year']);
    $xtpl->assign('N1_NAME', $result['nguoi_1']['can_name'] . ' ' . $result['nguoi_1']['chi_name']);
    $xtpl->assign('N1_MENH', $result['nguoi_1']['hanh_name']);
    $xtpl->assign('N1_CUNG', $result['nguoi_1']['cung_phi_name']);

    // Nguoi 2
    $xtpl->assign('N2_YEAR', $result['nguoi_2']['year']);
    $xtpl->assign('N2_NAME', $result['nguoi_2']['can_name'] . ' ' . $result['nguoi_2']['chi_name']);
    $xtpl->assign('N2_MENH', $result['nguoi_2']['hanh_name']);
    $xtpl->assign('N2_CUNG', $result['nguoi_2']['cung_phi_name']);

    // Analysis
    // Can
    $xtpl->assign('CAN_SCORE', $result['phan_tich']['thien_can']['score']);
    $xtpl->assign('CAN_MSG', $result['phan_tich']['thien_can']['msg']);
    $xtpl->assign('CAN_DETAIL', $result['phan_tich']['thien_can']['detail']);

    // Chi
    $xtpl->assign('CHI_SCORE', $result['phan_tich']['dia_chi']['score']);
    $xtpl->assign('CHI_MSG', $result['phan_tich']['dia_chi']['msg']);
    $xtpl->assign('CHI_DETAIL', $result['phan_tich']['dia_chi']['detail']);

    // Hanh
    $xtpl->assign('HANH_SCORE', $result['phan_tich']['ngu_hanh']['score']);
    $xtpl->assign('HANH_MSG', $result['phan_tich']['ngu_hanh']['msg']);
    $xtpl->assign('HANH_DETAIL', $result['phan_tich']['ngu_hanh']['detail']);

    // Cung
    $xtpl->assign('CUNG_SCORE', $result['phan_tich']['cung_phi']['score']);
    $xtpl->assign('CUNG_MSG', $result['phan_tich']['cung_phi']['msg']);
    $xtpl->assign('CUNG_DETAIL', $result['phan_tich']['cung_phi']['detail']);

    // Final
    $xtpl->assign('TOTAL_SCORE', $result['tong_diem']);
    $xtpl->assign('CONCLUSION', $result['ket_luan']);

    $xtpl->parse('main.result');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
