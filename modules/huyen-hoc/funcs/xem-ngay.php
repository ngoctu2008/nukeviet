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

// Include classes manually to prevent "Class not found" error
$classes = ['TuViConstants', 'LunarCalendar', 'XemNgay', 'TuViMuonTuoi', 'TuViXemNgay'];
foreach ($classes as $cls) {
    $file = NV_ROOTDIR . '/modules/' . $module_file . '/classes/' . $cls . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

use NukeViet\Module\HuyenHoc\XemNgay;
use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViMuonTuoi;
use NukeViet\Module\HuyenHoc\TuViXemNgay;

$page_title = $lang_module['xem_ngay'];

// Initialize XTemplate
$xtpl = new XTemplate('xem-ngay.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);

$tab = $nv_Request->get_string('tab', 'post,get', 'general');
$func = $nv_Request->get_string('func', 'post,get', '');

// Handle Specific Functions (AJAX or Form Submits)

// --- 1. TANG LE (TRUNG TANG) ---
if ($func == 'trung_tang' || $tab == 'tang_le') {
    $deceasedYear = $nv_Request->get_int('deceased_year', 'post', 0);
    $deceasedGender = $nv_Request->get_int('deceased_gender', 'post', 1);
    $deathTimeStr = $nv_Request->get_string('death_time', 'post', '');
    $headYear = $nv_Request->get_int('head_year', 'post', 0);
    $relativesStr = $nv_Request->get_string('relatives_list', 'post', '');

    $xtpl->assign('ACTIVE_TAB_TANG_LE', 'active');
    $xtpl->assign('INPUT_TT', [
        'deceased_year' => $deceasedYear,
        'death_time' => $deathTimeStr,
        'head_year' => $headYear,
        'relatives_list' => $relativesStr
    ]);

    if ($deceasedYear > 0 && !empty($deathTimeStr)) {
        // Parse Death Time
        $dt = strtotime($deathTimeStr);
        $d = date('j', $dt);
        $m = date('n', $dt);
        $y = date('Y', $dt);
        $h = date('G', $dt); // 0-23

        // Convert to Lunar
        $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
        $canChi = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], $h);

        // Deceased Info
        $age = $lunar['year'] - $deceasedYear + 1;
        $deceasedInfo = [
            'year' => $deceasedYear,
            'gender' => $deceasedGender,
            'age' => $age,
            'death_time' => [
                'd' => $lunar['day'],
                'm' => $lunar['month'],
                'y' => $lunar['year'],
                'h_chi' => $canChi['chiHour']
            ]
        ];

        // Relatives
        $relatives = [];
        if (!empty($relativesStr)) {
            $parts = explode(',', $relativesStr);
            foreach ($parts as $p) {
                $rYear = (int)trim($p);
                if ($rYear > 0) $relatives[] = $rYear;
            }
        }

        $app = new TuViXemNgay($deceasedYear, $deceasedGender, "$y-$m-$d");
        $result = $app->xemTrungTang($deceasedInfo, $headYear, $relatives);

        // Assign Result
        $xtpl->assign('TT_RESULT', $result);

        if (!empty($result['conflicts'])) {
            foreach ($result['conflicts'] as $c) {
                $xtpl->assign('CONFLICT', $c);
                $xtpl->parse('main.tang_le_result.conflict');
            }
        }

        // Suggestions for Kham liem, Di quan, Ha huyet
        $suggestions = $app->timNgayGioTangLe($deceasedYear, "$y-$m-$d");
        if (!empty($suggestions)) {
            foreach ($suggestions as $sugg) {
                $xtpl->assign('SUGG', $sugg);
                $xtpl->parse('main.tang_le_result.suggestion_list.item');
            }
            $xtpl->parse('main.tang_le_result.suggestion_list');
        }

        $xtpl->parse('main.tang_le_result');
    }
}

// --- 2. LAM NHA (MUON TUOI & XEM NGAY) ---
elseif ($tab == 'lam_nha' || $func == 'muon_tuoi') {
    $targetYear = $nv_Request->get_int('target_year', 'get', date('Y'));
    $targetMonth = $nv_Request->get_int('target_month', 'get', date('m'));
    $ownerYear = $nv_Request->get_int('owner_year', 'get', 0);
    $gender = 1; // Default Male for House Owner

    $xtpl->assign('ACTIVE_TAB_LAM_NHA', 'active');
    $xtpl->assign('TARGET_YEAR', $targetYear);
    $xtpl->assign('TARGET_MONTH', $targetMonth);
    $xtpl->assign('OWNER_YEAR', $ownerYear);

    if ($ownerYear > 0) {
        // A. Check Owner
        $muonTuoiTool = new TuViMuonTuoi($targetYear);
        $ownerAnalysis = $muonTuoiTool->analyzeCandidate($ownerYear, $ownerYear);

        $ownerStatus = [
            'is_good' => $ownerAnalysis['is_eligible'],
            'score' => $ownerAnalysis['score'],
            'details' => $ownerAnalysis['bad_factors'], // Kim Lau, Hoang Oc...
            'comment' => $ownerAnalysis['comment']
        ];

        $xtpl->assign('OWNER_STATUS', $ownerStatus);

        // Render Owner Analysis details
        $badList = [];
        if ($ownerStatus['details']['kim_lau']) $badList[] = "Phạm Kim Lâu";
        if ($ownerStatus['details']['hoang_oc']) $badList[] = "Phạm Hoang Ốc";
        if ($ownerStatus['details']['tam_tai']) $badList[] = "Phạm Tam Tai";
        if ($ownerStatus['details']['thai_tue']) $badList[] = "Phạm Thái Tuế";

        $badStr = implode(', ', $badList);
        $age = $ownerAnalysis['age'];

        if (empty($badList)) {
            $msg = "Tuổi $age đẹp, không phạm hạn lớn. Có thể tự đứng tên động thổ.";
            $alert = "success";
        } else {
            $msg = "Tuổi $age không đẹp để làm nhà năm nay: $badStr. Nên mượn tuổi.";
            $alert = "warning";
        }

        $xtpl->assign('OWNER_MSG', $msg);
        $xtpl->assign('OWNER_ALERT', $alert);
        $xtpl->parse('main.lam_nha_result.owner_check');

        // B. Find Candidates (If Bad)
        if (!$ownerAnalysis['is_eligible']) {
            $candidates = $muonTuoiTool->timNguoiMuonTuoi($ownerYear);
            foreach ($candidates as $cand) {
                $candComment = implode(', ', $cand['comment']);
                $xtpl->assign('CANDIDATE', array_merge($cand, ['comment_str' => $candComment]));
                $xtpl->parse('main.lam_nha_result.borrow_list.candidate');
            }
            $xtpl->parse('main.lam_nha_result.borrow_list');
        }

        // C. Find Good Days (Based on Owner OR Best Candidate?)
        // Usually based on Owner for Tam Hop, but avoid Owner's Xung.
        // If borrowing, technically should use Borrower's age.
        // For simplicity, we list general good days for House Building in the month.

        $app = new TuViXemNgay($ownerYear, $gender, "$targetYear-$targetMonth-01");
        $goodDays = $app->goiYNgayTotTrongThang($targetMonth, $targetYear, 'LAM_NHA');

        foreach ($goodDays as $gd) {
            $xtpl->assign('DAY', $gd);
            $xtpl->parse('main.lam_nha_result.good_days.day');
        }
        $xtpl->parse('main.lam_nha_result.good_days');

        $xtpl->parse('main.lam_nha_result');
    }
}

// --- 3. CUOI HOI ---
elseif ($tab == 'cuoi_hoi') {
    $groomYear = $nv_Request->get_int('groom_year', 'get', 0);
    $brideYear = $nv_Request->get_int('bride_year', 'get', 0);
    $targetYear = $nv_Request->get_int('target_year', 'get', date('Y'));
    $targetMonth = $nv_Request->get_int('target_month', 'get', date('m'));

    $xtpl->assign('ACTIVE_TAB_CUOI_HOI', 'active');
    $xtpl->assign('GROOM_YEAR', $groomYear);
    $xtpl->assign('BRIDE_YEAR', $brideYear);
    $xtpl->assign('TARGET_YEAR', $targetYear);
    $xtpl->assign('TARGET_MONTH', $targetMonth);

    if ($brideYear > 0) {
        // Check Bride Age (Kim Lau)
        $age = $targetYear - $brideYear + 1; // Lunar Age approx
        $rem = $age % 9;
        $kimLau = in_array($rem, [1, 3, 6, 8]);

        $xtpl->assign('BRIDE_AGE', $age);

        if ($kimLau) {
            $msg = "Tuổi cô dâu ($age) phạm Kim Lâu. Không nên cưới năm nay.";
            $alert = "danger";
        } else {
            $msg = "Tuổi cô dâu đẹp ($age tuổi), không phạm Kim Lâu.";
            $alert = "success";
        }

        $xtpl->assign('BRIDE_MSG', $msg);
        $xtpl->assign('BRIDE_ALERT', $alert);
        $xtpl->parse('main.cuoi_hoi_result.bride_check');

        // Good Days
        // Use Bride's age for filtering (usually Bride is main factor for wedding date)
        $app = new TuViXemNgay($brideYear, 0, "$targetYear-$targetMonth-01");
        $goodDays = $app->goiYNgayTotTrongThang($targetMonth, $targetYear, 'CUOI_HOI');

        foreach ($goodDays as $gd) {
            $xtpl->assign('DAY', $gd);
            $xtpl->parse('main.cuoi_hoi_result.good_days.day');
        }
        $xtpl->parse('main.cuoi_hoi_result.good_days');

        $xtpl->parse('main.cuoi_hoi_result');
    }
}

// --- 4. KHAI TRUONG ---
elseif ($tab == 'khai_truong') {
    $birthYear = $nv_Request->get_int('birth_year', 'get', 0);
    $m = $nv_Request->get_int('m', 'get', date('m'));
    $y = $nv_Request->get_int('y', 'get', date('Y'));

    $xtpl->assign('ACTIVE_TAB_KHAI_TRUONG', 'active');
    $xtpl->assign('INPUT', ['birth_year'=>$birthYear, 'm'=>$m, 'y'=>$y]);

    if ($birthYear > 0) {
        $app = new TuViXemNgay($birthYear, 1, "$y-$m-01");
        $goodDays = $app->goiYNgayTotTrongThang($m, $y, 'KHAI_TRUONG');

        foreach ($goodDays as $gd) {
            $xtpl->assign('DAY', $gd);
            $xtpl->parse('main.khai_truong_result.day');
        }
        $xtpl->parse('main.khai_truong_result');
    }
}

// --- 5. GENERAL (DEFAULT) ---
else { // tab = general
    $d = $nv_Request->get_int('d', 'get', date('d'));
    $m = $nv_Request->get_int('m', 'get', date('m'));
    $y = $nv_Request->get_int('y', 'get', date('Y'));

    $xtpl->assign('ACTIVE_TAB_GENERAL', 'active');
    $xtpl->assign('INPUT', ['d'=>$d, 'm'=>$m, 'y'=>$y]);

    // Calculate Info
    $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
    $lunar['leap_msg'] = isset($lunar['leap']) && $lunar['leap'] ? '(Nhuận)' : '';

    $ccLunar = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], 1, 0);
    $ccSolar = LunarCalendar::getCanChi($y, $m, $d, 0);
    $canList = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    $chiList = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
    $canChiText = [
        'year' => $canList[$ccLunar['canYear']] . ' ' . $chiList[$ccLunar['chiYear']],
        'month' => $canList[$ccLunar['canMonth']] . ' ' . $chiList[$ccLunar['chiMonth']],
        'day' => $canList[$ccSolar['canDay']] . ' ' . $chiList[$ccSolar['chiDay']]
    ];

    $app = new TuViXemNgay(0, 1, "$y-$m-$d");
    $info = $app->phanTichNgay('GENERIC');
    $info['alert_type'] = ($info['diem_so'] >= 0) ? 'success' : 'danger';

    $xtpl->assign('LUNAR', $lunar);
    $xtpl->assign('CANCHI_TEXT', $canChiText);
    $xtpl->assign('INFO', $info);

    if (!empty($info['binh_giai'])) {
        foreach ($info['binh_giai'] as $bg) {
            $xtpl->assign('DETAIL', $bg);
            $xtpl->parse('main.general_result.detail');
        }
    }
    $xtpl->parse('main.general_result');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
