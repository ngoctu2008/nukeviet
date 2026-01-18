<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_IS_MOD_TU_VI')) {
    exit('Stop!!!');
}

$fullname = $nv_Request->get_title('fullname', 'post', '');
$day = $nv_Request->get_int('day', 'post', 1);
$month = $nv_Request->get_int('month', 'post', 1);
$year = $nv_Request->get_int('year', 'post', 1990);
$hour = $nv_Request->get_int('hour', 'post', 0); // 0-23
$gender = $nv_Request->get_int('gender', 'post', 1);

// Convert Solar to Lunar
$lunar = NukeViet\Module\TuVi\Lunisolar::convertSolar2Lunar($day, $month, $year, 7);

// Convert Hour to Chi ID
$hour_chi_id = floor(($hour + 1) / 2) % 12;

// Calculate Horoscope
$horoscope = new NukeViet\Module\TuVi\Horoscope($lunar, $hour_chi_id, $gender);
$chart = $horoscope->lapLaSo();

// Calculate Age and Sao/Han first to use in query
$current_year = date('Y');
$birth_year = $lunar['year'];
$age_am = $current_year - $birth_year + 1;
if ($age_am < 1) $age_am = 1;

$sao_han = $horoscope->getSaoHan($age_am, $gender);

// Fetch Interpretations from Database
$interpretations = [];
$lookups = [];
$star_definitions = []; // Store tooltip info

// Palace Definitions for Tooltips
$palace_definitions = [
    'Mệnh' => 'Cung Mệnh là cung quan trọng nhất, biểu thị bản mệnh, tính cách, ngoại hình và vận mệnh tổng quát của đời người.',
    'Phụ Mẫu' => 'Cung Phụ Mẫu biểu thị về cha mẹ, mối quan hệ với cha mẹ, và sự giúp đỡ hay khắc hãm từ cha mẹ.',
    'Phúc Đức' => 'Cung Phúc Đức biểu thị về phúc phần dòng họ, may mắn, sự hưởng thụ tinh thần và tuổi thọ.',
    'Điền Trạch' => 'Cung Điền Trạch biểu thị về đất đai, nhà cửa, gia sản thừa kế hoặc tự tạo.',
    'Quan Lộc' => 'Cung Quan Lộc biểu thị về công danh, sự nghiệp, chức vụ, học hành và địa vị xã hội.',
    'Nô Bộc' => 'Cung Nô Bộc biểu thị về bạn bè, đồng nghiệp, cấp dưới, người giúp việc và các mối quan hệ xã hội.',
    'Thiên Di' => 'Cung Thiên Di biểu thị về việc đi lại, xuất ngoại, giao tiếp bên ngoài và môi trường xã hội.',
    'Tật Ách' => 'Cung Tật Ách biểu thị về sức khỏe, bệnh tật, tai nạn và những kiếp nạn trong đời.',
    'Tài Bạch' => 'Cung Tài Bạch biểu thị về tiền bạc, khả năng kiếm tiền, cách quản lý tài chính và sự giàu nghèo.',
    'Tử Tức' => 'Cung Tử Tức biểu thị về con cái, số lượng con, giới tính và sự hiếu thuận của con cái.',
    'Phu Thê' => 'Cung Phu Thê biểu thị về hôn nhân, người phối ngẫu, hạnh phúc gia đình và duyên nợ vợ chồng.',
    'Huynh Đệ' => 'Cung Huynh Đệ biểu thị về anh chị em ruột, mối quan hệ anh em và sự giúp đỡ lẫn nhau.'
];

// 1. Fetch Star Definitions (Generic info for tooltips)
// We assume there are not too many "star_info" entries, so we fetch all.
// In a large system, we would filter by the stars actually present in the chart, but usually all stars are in the chart somewhere.
$sql_def = "SELECT star_key, content FROM " . NV_PRE_TUVI . "_interpretations WHERE topic = 'star_info'";
$result_def = $db->query($sql_def);
while ($row = $result_def->fetch()) {
    $star_definitions[$row['star_key']] = $row['content'];
}

// 2. Standard Interpretations (Star in Palace)
foreach ($chart as $cung) {
    foreach ($cung['stars'] as $star) {
        $lookups[] = "(star_key = " . $db->quote($star['name']) . " AND palace_key = " . $db->quote($cung['name']) . ")";
        if ($cung['is_menh']) {
             $lookups[] = "(star_key = " . $db->quote($star['name']) . " AND palace_key = 'Mệnh')";
        }
    }
}

// Fetch general interpretations
$lookups[] = "(star_key = 'Tổng Quan' AND palace_key = 'Mệnh')";
$lookups[] = "(star_key = 'Vận Hạn' AND palace_key = 'Tiểu Vận')";

// Fetch Yearly Detail Interpretations
// Fetch generic "Bình Giải Năm" (Overview only)
$lookups[] = "(star_key = 'Bình Giải Năm' AND palace_key = 'Tổng Quan')";

// Fetch Specific Monthly Interpretations based on Sao Hạn
if (!empty($sao_han['sao'])) {
    $lookups[] = "(star_key = 'Sao Chiếu Mệnh' AND palace_key = " . $db->quote($sao_han['sao']) . ")";
    // Fetch monthly details for this Star
    // We look for star_key = [StarName] AND palace_key LIKE 'Tháng %'
    // Note: In data_vi.php we inserted: ('La Hầu', 'Tháng 1', ...)
    $lookups[] = "(star_key = " . $db->quote($sao_han['sao']) . " AND palace_key LIKE 'Tháng %')";
}

if (!empty($sao_han['han'])) {
    $lookups[] = "(star_key = 'Hạn' AND palace_key = " . $db->quote($sao_han['han']) . ")";
}

if (!empty($lookups)) {
    $sql_where = implode(' OR ', $lookups);
    $sql = "SELECT * FROM " . NV_PRE_TUVI . "_interpretations WHERE " . $sql_where;
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        $interpretations[] = $row;
    }
}

// New Calculations: Dai Van, Tieu Van, Bad Luck
$cuc = $horoscope->info['cuc'];
$dai_van_id = $horoscope->getDaiVan($age_am, $gender, $cuc);

$current_year_chi = ($current_year - 1900) % 12;
$birth_chi = $horoscope->info['chi_year_id'];

$tieu_van_id = $horoscope->getTieuVan($age_am, $gender, $birth_chi);

$bad_luck = [];
$tam_tai = $horoscope->getTamTai($current_year_chi, $birth_chi);
if ($tam_tai) $bad_luck[] = $tam_tai;

$kim_lau = $horoscope->getKimLau($age_am);
if ($kim_lau) $bad_luck[] = $kim_lau;

$hoang_oc = $horoscope->getHoangOc($age_am);
if ($hoang_oc) $bad_luck[] = $hoang_oc;

$data = [
    'info' => [
        'fullname' => $fullname,
        'solar_date' => "$day/$month/$year",
        'lunar_date' => $lunar['day'] . '/' . $lunar['month'] . '/' . $lunar['year'],
        'gender' => $gender ? $lang_module['male'] : $lang_module['female'],
        'cuc' => $horoscope->info['cuc_name'],
        'age' => $age_am,
        'sao' => $sao_han['sao'],
        'han' => $sao_han['han'],
        'current_year' => $current_year,
        'bad_luck' => implode(', ', $bad_luck)
    ],
    'cung' => $chart,
    'interpretations' => $interpretations
];

$xtpl = new XTemplate('view.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_info['module_theme']);
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('GLANG', $lang_global);
$xtpl->assign('INFO', $data['info']);

foreach ($data['cung'] as $cung) {
    // Add CSS class for grid positioning
    $ids = ['ty', 'suu', 'dan', 'mao', 'thin', 'ty_snake', 'ngo', 'mui', 'than', 'dau', 'tuat', 'hoi'];
    $kanji = ['子', '丑', '寅', '卯', '辰', '巳', '午', '未', '申', '酉', '戌', '亥'];
    $cung['css_class'] = $ids[$cung['id']];
    $cung['kanji'] = $kanji[$cung['id']]; // Assign Kanji for watermark

    // Assign Palace Tooltip
    if (isset($palace_definitions[$cung['cung_chuc']])) {
        $cung['cung_desc'] = $palace_definitions[$cung['cung_chuc']];
    } else {
        $cung['cung_desc'] = '';
    }

    // Add highlighting classes
    if ($cung['id'] == $dai_van_id) $cung['css_class'] .= ' daivan-highlight';
    if ($cung['id'] == $tieu_van_id) $cung['css_class'] .= ' tieuvan-highlight';

    // Assign stars
    if (!empty($cung['chinh_tinh'])) {
        foreach ($cung['chinh_tinh'] as $star) {
            $xtpl->assign('STAR_NAME', $star);
            // Check for definition
            $star_info = isset($star_definitions[$star]) ? $star_definitions[$star] : $star;
            // Strip tags for title attribute to avoid breaking HTML, unless using data-html="true" which we are.
            // But standard title attribute doesn't support HTML. Bootstrap tooltip with data-html="true" does.
            // However, to be safe against quotes breaking the attribute:
            $star_info_safe = htmlspecialchars($star_info, ENT_QUOTES, 'UTF-8');

            $xtpl->assign('STAR_INFO', $star_info_safe);
            $xtpl->parse('main.loop.chinh_tinh');
        }
    }
    if (!empty($cung['phu_tinh'])) {
        foreach ($cung['phu_tinh'] as $star) {
            $xtpl->assign('STAR_NAME', $star);
            // Check for definition
            $star_info = isset($star_definitions[$star]) ? $star_definitions[$star] : $star;
            $star_info_safe = htmlspecialchars($star_info, ENT_QUOTES, 'UTF-8');

            $xtpl->assign('STAR_INFO', $star_info_safe);
            $xtpl->parse('main.loop.phu_tinh');
        }
    }

    // Labels for Dai Van/Tieu Van
    if ($cung['id'] == $dai_van_id) {
        $xtpl->assign('LABEL_DAIVAN', 'Đại Vận');
        $xtpl->parse('main.loop.daivan_label');
    }
    if ($cung['id'] == $tieu_van_id) {
        $xtpl->assign('LABEL_TIEUVAN', 'Tiểu Vận');
        $xtpl->parse('main.loop.tieuvan_label');
    }

    $xtpl->assign('CUNG', $cung);
    $xtpl->parse('main.loop');
}

// Parse Interpretations
$year_detail = [];
$sao_han_detail = [];

if (!empty($data['interpretations'])) {
    foreach ($data['interpretations'] as $interp) {
        // Collect specific monthly details for the current Star
        if ($interp['star_key'] == $sao_han['sao'] && strpos($interp['palace_key'], 'Tháng') === 0) {
            $year_detail[] = $interp;
        }
        // Collect generic overview
        elseif ($interp['star_key'] == 'Bình Giải Năm' && $interp['palace_key'] == 'Tổng Quan') {
            // Prepend to year detail or separate? Let's add to year detail list as header
            array_unshift($year_detail, $interp);
        }
        elseif ($interp['star_key'] == 'Sao Chiếu Mệnh' || $interp['star_key'] == 'Hạn') {
            $sao_han_detail[] = $interp;
        } else {
            $xtpl->assign('INTERP', $interp);
            $xtpl->parse('main.interpretations.loop');
        }
    }
    $xtpl->parse('main.interpretations');
}

// Parse Sao Han
if (!empty($sao_han_detail)) {
    foreach ($sao_han_detail as $detail) {
        $xtpl->assign('DETAIL', $detail);
        $xtpl->parse('main.sao_han_detail.loop');
    }
    $xtpl->parse('main.sao_han_detail');
}

// Parse Year Detail
if (!empty($year_detail)) {
    foreach ($year_detail as $detail) {
        // Remove repeated star name pattern like "(La Hầu):" or "Tháng X (La Hầu):"
        // Pattern: (Any Text):
        // We want to remove the "(StarName):" part specifically if it repeats.
        // Based on user request: "Bỏ hiển thị tên sao trong phần chi tiết... (La Hầu):"
        // Content example: "Tháng Giêng (La Hầu): Đầu năm..."
        // Regex to remove "(Text):"
        $detail['content'] = preg_replace('/\([^)]+\):/', '', $detail['content']);

        $xtpl->assign('DETAIL', $detail);
        $xtpl->parse('main.year_detail.loop');
    }
    $xtpl->parse('main.year_detail');
}

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
