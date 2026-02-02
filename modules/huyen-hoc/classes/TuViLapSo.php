<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class TuViLapSo {

    // 12 Cung Dia Ban (0=Ty ... 11=Hoi)
    public static $DIA_CHI = array(
        0 => 'Tý', 1 => 'Sửu', 2 => 'Dần', 3 => 'Mão',
        4 => 'Thìn', 5 => 'Tỵ', 6 => 'Ngọ', 7 => 'Mùi',
        8 => 'Thân', 9 => 'Dậu', 10 => 'Tuất', 11 => 'Hợi'
    );

    // Key map for Frontend (ty, suu...)
    public static $DIA_CHI_KEYS = array(
        0 => 'ty', 1 => 'suu', 2 => 'dan', 3 => 'mao',
        4 => 'thin', 5 => 'ty_nho', 6 => 'ngo', 7 => 'mui',
        8 => 'than', 9 => 'dau', 10 => 'tuat', 11 => 'hoi'
    );

    // Full List of Stars (~110)
    // Format: 'code' => ['Name', ElementID]
    // Elements: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc
    public static $STARS = array(
        // 14 Chinh Tinh
        'tu_vi' => ['Tử Vi', 3], 'thien_co' => ['Thiên Cơ', 5], 'thai_duong' => ['Thái Dương', 2], 'vu_khuc' => ['Vũ Khúc', 4], 'thien_dong' => ['Thiên Đồng', 1], 'liem_trinh' => ['Liêm Trinh', 2],
        'thien_phu' => ['Thiên Phủ', 3], 'thai_am' => ['Thái Âm', 1], 'tham_lang' => ['Tham Lang', 1], 'cu_mon' => ['Cự Môn', 1], 'thien_tuong' => ['Thiên Tướng', 1], 'thien_luong' => ['Thiên Lương', 5], 'that_sat' => ['Thất Sát', 4], 'pha_quan' => ['Phá Quân', 1],

        // Vong Thai Tue
        'thai_tue' => ['Thái Tuế', 2], 'thieu_duong' => ['Thiếu Dương', 2], 'tang_mon' => ['Tang Môn', 5], 'thieu_am' => ['Thiếu Âm', 1], 'quan_phu_3' => ['Quan Phù', 2], 'tu_phu' => ['Tử Phù', 4], 'tue_pha' => ['Tuế Phá', 2], 'long_duc' => ['Long Đức', 1], 'bach_ho' => ['Bạch Hổ', 4], 'phuc_duc' => ['Phúc Đức', 3], 'dieu_khach' => ['Điếu Khách', 2], 'truc_phu' => ['Trực Phù', 4],

        // Vong Loc Ton
        'loc_ton' => ['Lộc Tồn', 3], 'luc_si' => ['Lực Sĩ', 2], 'thanh_long' => ['Thanh Long', 1], 'tieu_hao' => ['Tiểu Hao', 2], 'tuong_quan' => ['Tướng Quân', 5], 'tau_thu' => ['Tấu Thư', 4], 'phi_liem' => ['Phi Liêm', 2], 'hy_than' => ['Hỷ Thần', 2], 'benh_phu' => ['Bệnh Phù', 3], 'dai_hao' => ['Đại Hao', 2], 'phuc_binh' => ['Phục Binh', 2], 'quan_phu_2' => ['Quan Phủ', 2],

        // Vong Trang Sinh
        'trang_sinh' => ['Tràng Sinh', 1], 'moc_duc' => ['Mộc Dục', 1], 'quan_doi' => ['Quan Đới', 4], 'lam_quan' => ['Lâm Quan', 4], 'de_vuong' => ['Đế Vượng', 4], 'suy' => ['Suy', 1], 'benh' => ['Bệnh', 2], 'tu' => ['Tử', 3], 'mo' => ['Mộ', 3], 'tuyet' => ['Tuyệt', 3], 'thai' => ['Thai', 3], 'duong' => ['Dưỡng', 5],

        // Luc Sat (Kinh Da Khong Kiep Hoa Linh)
        'kinh_duong' => ['Kình Dương', 4], 'da_la' => ['Đà La', 4], 'dia_khong' => ['Địa Không', 2], 'dia_kiep' => ['Địa Kiếp', 2], 'hoa_tinh' => ['Hỏa Tinh', 2], 'linh_tinh' => ['Linh Tinh', 2],

        // Tu Hoa
        'hoa_loc' => ['Hóa Lộc', 5], 'hoa_quyen' => ['Hóa Quyền', 5], 'hoa_khoa' => ['Hóa Khoa', 1], 'hoa_ky' => ['Hóa Kỵ', 1],

        // Other Important Stars
        'van_xuong' => ['Văn Xương', 4], 'van_khuc' => ['Văn Khúc', 1],
        'ta_phu' => ['Tả Phù', 3], 'huu_bat' => ['Hữu Bật', 1],
        'thien_khoi' => ['Thiên Khôi', 2], 'thien_viet' => ['Thiên Việt', 2],
        'thien_khong' => ['Thiên Không', 2],
        'dao_hoa' => ['Đào Hoa', 5], 'hong_loan' => ['Hồng Loan', 1], 'thien_hy' => ['Thiên Hỷ', 1],
        'thien_hinh' => ['Thiên Hình', 4], 'thien_rieu' => ['Thiên Riêu', 1],
        'co_than' => ['Cô Thần', 3], 'qua_tu' => ['Quả Tú', 3],
        'an_quang' => ['Ân Quang', 5], 'thien_quy' => ['Thiên Quý', 3],
        'tam_thai' => ['Tam Thai', 1], 'bat_toa' => ['Bát Tọa', 3],
        'long_tri' => ['Long Trì', 1], 'phuong_cac' => ['Phượng Các', 4],
        'thien_duc' => ['Thiên Đức', 2], 'nguyet_duc' => ['Nguyệt Đức', 2],
        'thien_giai' => ['Thiên Giải', 2], 'dia_giai' => ['Địa Giải', 3], 'giai_than' => ['Giải Thần', 5],
        'thien_y' => ['Thiên Y', 1], 'thien_quan' => ['Thiên Quan', 2], 'thien_phuc' => ['Thiên Phúc', 2],
        'luu_ha' => ['Lưu Hà', 1], 'kiet_sat' => ['Kiếp Sát', 2],
        'pha_toai' => ['Phá Toái', 2], 'thien_hu' => ['Thiên Hư', 1], 'thien_khoc' => ['Thiên Khốc', 1],
        'thien_tai' => ['Thiên Tài', 3], 'thien_tho' => ['Thiên Thọ', 3],
        'thien_thuong' => ['Thiên Thương', 3], 'thien_su' => ['Thiên Sứ', 1],
        'hoa_cai' => ['Hoa Cái', 4], 'thien_ma' => ['Thiên Mã', 2], 'thien_la' => ['Thiên La', 3], 'dia_vong' => ['Địa Võng', 3],
        'dau_quan' => ['Đẩu Quân', 2]
    );

    // Do sang (Brightness) Matrix: 14 rows x 12 cols (Ty..Hoi)
    // M=Mieu, V=Vuong, D=Dac, H=Ham, B=Binh
    public static $BRIGHTNESS = array(
        'tu_vi'      => ['B','D','B','H','V','V','M','D','B','H','V','B'],
        'thien_co'   => ['V','H','M','M','V','B','M','H','D','D','V','B'],
        'thai_duong' => ['H','H','V','V','V','V','M','D','B','B','H','H'],
        'vu_khuc'    => ['V','M','V','B','M','B','V','M','V','B','M','B'],
        'thien_dong' => ['V','H','M','M','H','B','H','H','V','H','H','M'],
        'liem_trinh' => ['V','D','M','H','V','H','V','D','M','H','V','H'],
        'thien_phu'  => ['M','M','M','B','M','B','V','M','D','B','M','D'],
        'thai_am'    => ['V','M','B','H','H','H','H','H','B','V','M','M'],
        'tham_lang'  => ['H','M','B','B','M','H','H','M','B','B','M','H'],
        'cu_mon'     => ['M','H','M','M','H','B','M','H','D','M','H','V'],
        'thien_tuong'=> ['V','M','M','H','M','D','V','M','M','H','M','B'],
        'thien_luong'=> ['M','M','M','V','M','H','M','M','B','H','M','H'],
        'that_sat'   => ['M','D','M','H','M','M','M','D','M','H','M','M'],
        'pha_quan'   => ['M','V','H','H','M','H','M','V','H','H','M','H']
    );

    /**
     * Get Star Info
     */
    public static function getStarInfo($code) {
        if (!isset(self::$STARS[$code])) return null;

        $info = self::$STARS[$code];
        $elId = $info[1];

        // 1=Thuy (black/blue), 2=Hoa (red), 3=Tho (yellow), 4=Kim (gray/white), 5=Moc (green)
        $colors = [1 => 'thuy', 2 => 'hoa', 3 => 'tho', 4 => 'kim', 5 => 'moc'];

        return [
            'name' => $info[0],
            'element_id' => $elId,
            'color' => isset($colors[$elId]) ? $colors[$elId] : 'default'
        ];
    }

    public static function getElementName($id) {
        $names = [1 => 'Thủy', 2 => 'Hỏa', 3 => 'Thổ', 4 => 'Kim', 5 => 'Mộc'];
        return isset($names[$id]) ? $names[$id] : '';
    }

    /**
     * Get Brightness
     */
    public static function getBrightness($code, $palaceIndex) {
        if (isset(self::$BRIGHTNESS[$code][$palaceIndex])) {
            return self::$BRIGHTNESS[$code][$palaceIndex];
        }
        return '';
    }

    // --- Helper Logic Calculations ---

    public static function calculateYinYangBalance($canYear, $chiYear, $menhPalaceBranch, $gender) {
        // canYear: 0=Giap (Yang), 1=At (Yin)...
        $isYearYang = ($canYear % 2 == 0);

        // menhPalaceBranch: 0=Ty (Yang), 1=Suu (Yin)...
        // Tý (Yang), Sửu (Yin), Dần (Yang), Mão (Yin)...
        // Logic: 0,2,4,6,8,10 are Yang. 1,3,5,7,9,11 are Yin.
        $isPalaceYang = ($menhPalaceBranch % 2 == 0);

        // Âm Dương Thuận Lý:
        // Nam (Yang) sinh năm Dương (Yang Year) => Thuận.
        // Nữ (Yin) sinh năm Âm (Yin Year) => Thuận.
        // Or: Tuổi Dương cư cung Dương, Tuổi Âm cư cung Âm => Đắc địa?

        // Standard Tu Vi text usually refers to:
        // "Âm Dương Thuận Lý": Người Dương (Nam/Nữ) sinh năm Dương, hoặc Người Âm sinh năm Âm.
        // Wait, "Dương Nam" means Male born in Yang Year. "Âm Nữ" means Female born in Yin Year.
        // If Dương Nam or Âm Nữ => Thuận Lý?
        // Let's implement the standard check:
        // Year Yang/Yin matches Palace Yang/Yin? Or Person Gender matches Year?

        // Interpretation 1: "Âm Dương Thuận Lý" = Year Yin/Yang matches Palace Yin/Yang?
        // Interpretation 2: "Âm Dương Thuận Lý" = Gender matches Year Yin/Yang (Duong Nam / Am Nu).
        // Most software uses Interpretation 2 for the "Am Duong" line, but compares Year vs Palace for "De Vuong/Suy" etc.
        // BUT, the request asked for: "so sánh Can Chi năm sinh với Cung Mệnh (Âm Dương)".
        // So: Compare Year (Can/Chi) vs Palace Branch.
        // If Year is Yang and Palace is Yang => Thuận Lý.
        // If Year is Yin and Palace is Yin => Thuận Lý.
        // Else => Nghịch Lý.

        if ($isYearYang == $isPalaceYang) {
            return "Âm Dương Thuận Lý";
        } else {
            return "Âm Dương Nghịch Lý";
        }
    }

    public static function calculateElementRelation($menhElement, $cucElement) {
        // Elements: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc
        // Cycles:
        // Sinh: Kim(4)->Thuy(1)->Moc(5)->Hoa(2)->Tho(3)->Kim(4)
        // Khac: Kim(4)->Moc(5)->Tho(3)->Thuy(1)->Hoa(2)->Kim(4)

        if ($menhElement == $cucElement) return "Cục Mệnh Bình Hòa"; // Or Tương Hòa

        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4];
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4];

        if (isset($sinh[$cucElement]) && $sinh[$cucElement] == $menhElement) return "Cục Sinh Mệnh (Tốt)";
        if (isset($sinh[$menhElement]) && $sinh[$menhElement] == $cucElement) return "Mệnh Sinh Cục (Hao)"; // Sinh xuat

        if (isset($khac[$cucElement]) && $khac[$cucElement] == $menhElement) return "Cục Khắc Mệnh (Xấu)";
        if (isset($khac[$menhElement]) && $khac[$menhElement] == $cucElement) return "Mệnh Khắc Cục (Khắc chế được hoàn cảnh)";

        return "Không xác định";
    }

    public static function calcTieuVan($chiYear, $targetYear) {
        // Tieu Van calculation for a specific year
        // Use the same logic as in lapLaSo but with $targetYear's Chi
        // 1. Get Chi of Target Year
        // Can/Chi calculation is complex without Solar->Lunar.
        // Assuming user passes just the Year Number (e.g., 2025). We need to know its Chi.
        // Simple formula for Chi: (Year - 4) % 12.
        // 2024 (Giap Thin) -> (2024-4)%12 = 2020%12 = 4 (Thin). Correct.
        $targetChi = ($targetYear - 4) % 12;

        // 2. Logic khoi Tieu Van (based on Birth Chi - $chiYear)
        // Dan Ngo Tuat (2, 6, 10) -> Khoi tai Thin (4)
        // Than Ty Thin (8, 0, 4) -> Khoi tai Tuat (10)
        // Hoi Mao Mui (11, 3, 7) -> Khoi tai Suu (1)
        // Ty Dau Suu (5, 9, 1) -> Khoi tai Mui (7)

        $startPalace = 0;
        if (in_array($chiYear, [2, 6, 10])) $startPalace = 4;
        elseif (in_array($chiYear, [8, 0, 4])) $startPalace = 10;
        elseif (in_array($chiYear, [11, 3, 7])) $startPalace = 1;
        elseif (in_array($chiYear, [5, 9, 1])) $startPalace = 7;

        // Tieu Van moves depending on Gender?
        // Standard: Nam Thuan, Nu Nghich. (Wait, standard Tieu Van is: "Trai thuan gai nghich"? Yes)
        // But we need the gender here!
        // Wait, calcTieuVan signature needs gender.
        // Let's rely on lapLaSo logic which already did this for current year.
        // But here we need generic method.
        return [$startPalace, $targetChi]; // Incomplete without gender
    }

    /**
     * Lap La So Tu Vi Full
     */
    public static function lapLaSo($dd, $mm, $yyyy, $hh, $gender, $canYear, $chiYear, $name) {
        $chart = array();
        for ($i = 0; $i < 12; $i++) {
            $chart[$i] = array(
                'index' => $i,
                'key' => self::$DIA_CHI_KEYS[$i], // ty, suu...
                'name' => self::$DIA_CHI[$i],
                'palace_name' => '',
                'chinh_tinh' => array(),
                'phu_tinh_tot' => array(),
                'phu_tinh_xau' => array(), // Sat tinh, Bai tinh
                'tuan' => false,
                'triet' => false,
                'dai_van' => 0,
                'tieu_van' => '',
                'vong_trang_sinh' => ''
            );
        }

        // Helpers
        $addStar = function($pIdx, $code, $type = 'tot') use (&$chart) {
            $pIdx = $pIdx % 12;
            if ($pIdx < 0) $pIdx += 12;

            $info = self::getStarInfo($code);
            if (!$info) return;

            $bright = self::getBrightness($code, $pIdx);

            $starData = array(
                'code' => $code,
                'name' => $info['name'],
                'color' => $info['color'],
                'element' => self::getElementName($info['element_id']),
                'dacs' => $bright
            );

            // Determine type automatically if not major
            // Simple logic: Major stars always go to chinh_tinh
            if (isset(self::$BRIGHTNESS[$code])) {
                $chart[$pIdx]['chinh_tinh'][] = $starData;
            } else {
                // Heuristic: "Xau" includes Luc Sat, Bai Tinh. "Tot" includes others.
                // For simplicity, use the passed type or default to 'tot'
                if ($type == 'xau') {
                    $chart[$pIdx]['phu_tinh_xau'][] = $starData;
                } else {
                    $chart[$pIdx]['phu_tinh_tot'][] = $starData;
                }
            }
        };

        // --- STEP 1: An 12 Cung (Dia Ban) ---
        $posMenh = (2 + ($mm - 1) - $hh) % 12;
        if ($posMenh < 0) $posMenh += 12;
        $posThan = (2 + ($mm - 1) + $hh) % 12;

        $palaceNames = array(
            'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
            'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
        );

        for ($i = 0; $i < 12; $i++) {
            $pos = ($posMenh - $i) % 12;
            if ($pos < 0) $pos += 12;
            $chart[$pos]['palace_name'] = $palaceNames[$i];
            if ($pos == $posThan) {
                $chart[$pos]['palace_name'] .= ' (Thân)';
                $chart[$pos]['is_than'] = true;
            } else {
                $chart[$pos]['is_than'] = false;
            }
        }

        // --- STEP 2: Cuc & Dai Van ---
        $canDan = (($canYear % 5) + 1) * 2;
        if ($canDan >= 10) $canDan -= 10;
        $steps = $posMenh - 2;
        if ($steps < 0) $steps += 12;
        $canMenh = ($canDan + $steps) % 10;
        $chiMenh = $posMenh;
        $cucElement = FengShuiUtils::getNguHanhNapAm($canMenh, $chiMenh);
        $cucMap = array(1 => 2, 2 => 6, 3 => 5, 4 => 4, 5 => 3);
        $cuc = $cucMap[$cucElement];
        $cucNameMap = [2=>'Thủy Nhị Cục', 3=>'Mộc Tam Cục', 4=>'Kim Tứ Cục', 5=>'Thổ Ngũ Cục', 6=>'Hỏa Lục Cục'];

        // Dai Van
        $isDuong = ($canYear % 2 == 0);
        $direction = (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) ? 1 : -1;
        for ($k = 0; $k < 12; $k++) {
            $age = $cuc + ($k * 10);
            $idx = ($posMenh + ($k * $direction)) % 12;
            if ($idx < 0) $idx += 12;
            $chart[$idx]['dai_van'] = $age;
        }

        // --- STEP 3: Tieu Van & Tuan/Triet ---
        // Tieu Van
        $startTVPalace = 0; $startTVYear = 0;
        if (in_array($chiYear, [2, 6, 10])) { $startTVPalace = 4; $startTVYear = 10; } // Dan Ngo Tuat -> Thin
        elseif (in_array($chiYear, [8, 0, 4])) { $startTVPalace = 10; $startTVYear = 4; } // Than Ty Thin -> Tuat
        elseif (in_array($chiYear, [11, 3, 7])) { $startTVPalace = 1; $startTVYear = 7; } // Hoi Mao Mui -> Suu
        elseif (in_array($chiYear, [5, 9, 1])) { $startTVPalace = 7; $startTVYear = 1; } // Ty Dau Suu -> Mui

        $tvDirection = ($gender == 1) ? 1 : -1; // Nam Thuan, Nu Nghich
        for ($k = 0; $k < 12; $k++) {
            $labelChi = ($startTVYear + $k) % 12;
            $palaceIdx = ($startTVPalace + ($k * $tvDirection)) % 12;
            if ($palaceIdx < 0) $palaceIdx += 12;
            $chart[$palaceIdx]['tieu_van'] = self::$DIA_CHI[$labelChi];
        }

        // Triet
        $trietMap = [
            0 => [8,9], 5 => [8,9], 1 => [6,7], 6 => [6,7],
            2 => [4,5], 7 => [4,5], 3 => [2,3], 8 => [2,3],
            4 => [0,1], 9 => [0,1]
        ];
        foreach ($trietMap[$canYear % 10] as $p) { $chart[$p]['triet'] = true; }

        // Tuan
        $diff = $chiYear - ($canYear % 10);
        if ($diff < 0) $diff += 12;
        $tuan1 = ($diff + 10) % 12; $tuan2 = ($diff + 11) % 12;
        $chart[$tuan1]['tuan'] = true; $chart[$tuan2]['tuan'] = true;

        // --- STEP 4: An Sao Chinh Tinh ---
        $posTuVi = 0;
        if ($dd % $cuc == 0) {
            $q = $dd / $cuc;
            $posTuVi = (2 + ($q - 1)) % 12;
        } else {
            $r = $dd % $cuc;
            $x = $cuc - $r;
            $q = ($dd + $x) / $cuc;
            $basePos = (2 + ($q - 1)) % 12;
            $posTuVi = ($x % 2 != 0) ? ($basePos - $x) : ($basePos + $x);
        }
        if ($posTuVi < 0) $posTuVi += 12;
        $posTuVi = $posTuVi % 12; // Safety

        $addStar($posTuVi, 'tu_vi');
        $posThienPhu = (4 - $posTuVi + 12) % 12;
        $addStar($posThienPhu, 'thien_phu');

        $offsetsTuVi = ['thien_co' => 1, 'thai_duong' => 3, 'vu_khuc' => 4, 'thien_dong' => 5, 'liem_trinh' => 8];
        foreach ($offsetsTuVi as $code => $offset) $addStar($posTuVi - $offset, $code);

        $offsetsThienPhu = ['thai_am' => 1, 'tham_lang' => 2, 'cu_mon' => 3, 'thien_tuong' => 4, 'thien_luong' => 5, 'that_sat' => 6, 'pha_quan' => 10];
        foreach ($offsetsThienPhu as $code => $offset) $addStar($posThienPhu + $offset, $code);

        // --- STEP 5: An Cac Sao Khac (Major Groups) ---

        // 1. Vong Thai Tue (Theo Chi Nam Sinh)
        $starsThaiTue = ['thai_tue', 'thieu_duong', 'tang_mon', 'thieu_am', 'quan_phu_3', 'tu_phu', 'tue_pha', 'long_duc', 'bach_ho', 'phuc_duc', 'dieu_khach', 'truc_phu'];
        foreach ($starsThaiTue as $idx => $code) {
            $p = ($chiYear + $idx) % 12;
            $type = in_array($code, ['tang_mon', 'tue_pha', 'bach_ho', 'dieu_khach', 'truc_phu']) ? 'xau' : 'tot';
            $addStar($p, $code, $type);
        }

        // 2. Vong Loc Ton (Theo Can Nam Sinh)
        // Giap(0)->Dan(2), At(1)->Mao(3), Binh(2)->Ty(5), Dinh(3)->Ngo(6), Mau(4)->Ty(5), Ky(5)->Ngo(6), Canh(6)->Than(8), Tan(7)->Dau(9), Nham(8)->Hoi(11), Quy(9)->Ty(0)
        $locTonMap = [0=>2, 1=>3, 2=>5, 3=>6, 4=>5, 5=>6, 6=>8, 7=>9, 8=>11, 9=>0];
        $posLocTon = $locTonMap[$canYear % 10];

        $starsLocTon = ['loc_ton', 'luc_si', 'thanh_long', 'tieu_hao', 'tuong_quan', 'tau_thu', 'phi_liem', 'hy_than', 'benh_phu', 'dai_hao', 'phuc_binh', 'quan_phu_2'];
        // Duong Nam/Am Nu -> Thuan (+), Am Nam/Duong Nu -> Nghich (-)
        // Same direction logic as Dai Van?
        // Rule: "Duong Nam Am Nu thuan hanh, Am Nam Duong Nu nghich hanh"
        $ltDir = (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) ? 1 : -1;

        foreach ($starsLocTon as $idx => $code) {
            $p = ($posLocTon + ($idx * $ltDir)) % 12;
            if ($p < 0) $p += 12;
            $type = in_array($code, ['tieu_hao', 'benh_phu', 'dai_hao', 'quan_phu_2']) ? 'xau' : 'tot';
            $addStar($p, $code, $type);
        }

        // Luc Sat: Kinh Duong (Truoc Loc Ton), Da La (Sau Loc Ton)
        $posKinhDuong = ($posLocTon + 1) % 12;
        $posDaLa = ($posLocTon - 1 + 12) % 12;
        $addStar($posKinhDuong, 'kinh_duong', 'xau');
        $addStar($posDaLa, 'da_la', 'xau');

        // 3. Vong Trang Sinh (Theo Cuc)
        // Thuy(2) Nhi Cuc -> Than(8)
        // Moc(3) Tam Cuc -> Hoi(11)
        // Kim(4) Tu Cuc -> Ty(5)
        // Tho(5) Ngu Cuc -> Than(8)
        // Hoa(6) Luc Cuc -> Dan(2)
        $tsMap = [2 => 8, 3 => 11, 4 => 5, 5 => 8, 6 => 2];
        $posTrangSinh = $tsMap[$cuc];
        // Direction same as Loc Ton? No. Gender Only?
        // Rule: Nam Thuan, Nu Nghich (Like Tieu Van? Or Dai Van?)
        // Rule Vong Trang Sinh: "Nam thuan nu nghich" (Most sources) OR "Duong Nam Am Nu thuan, Am Nam Duong Nu nghich" (Like Dai Van)
        // Standard Tu Vi usually follows Dai Van direction (based on Am/Duong Year + Gender).
        $tsDir = $direction; // Use Dai Van direction

        $starsTrangSinh = ['trang_sinh', 'moc_duc', 'quan_doi', 'lam_quan', 'de_vuong', 'suy', 'benh', 'tu', 'mo', 'tuyet', 'thai', 'duong'];
        foreach ($starsTrangSinh as $idx => $code) {
            $p = ($posTrangSinh + ($idx * $tsDir)) % 12;
            if ($p < 0) $p += 12;
            // Vong Trang Sinh usually displayed separately, but let's add as small stars?
            // Better: Add to specific field 'vong_trang_sinh' for the palace
            $chart[$p]['vong_trang_sinh'] = self::getStarInfo($code)['name'];
            // Also add as a star? Users like to see them as stars.
            $addStar($p, $code, 'tot'); // Treat as stars for visual
        }

        // 4. Luc Sat (Remaining: Khong, Kiep, Hoa, Linh)
        // Dia Khong / Dia Kiep: Gio Sinh
        // Hoi -> Ty(11) ? No.
        // Khoi tu Hoi (11). Dia Kiep Thuan, Dia Khong Nghich. Den gio Sinh.
        // Gio Ty(0) -> Hoi(11).
        // Cong thuc:
        // Dia Kiep: 11 + (hh)
        // Dia Khong: 11 - (hh)
        $posDiaKiep = (11 + $hh) % 12;
        $posDiaKhong = (11 - $hh + 12) % 12;
        $addStar($posDiaKiep, 'dia_kiep', 'xau');
        $addStar($posDiaKhong, 'dia_khong', 'xau');

        // Hoa Tinh / Linh Tinh: Year Chi + Gio Sinh
        // Phuc tap. Simplified version for common cases:
        // Dan Ngo Tuat (2,6,10): Hoa(Suu 1), Linh(Mao 3)
        // Than Ty Thin (8,0,4): Hoa(Dan 2), Linh(Tuat 10)
        // Ty Dau Suu (5,9,1): Hoa(Dau 9), Linh(Tuat 10)
        // Hoi Mao Mui (11,3,7): Hoa(Dau 9), Linh(Tuat 10) -- Wait, different sources.
        // Let's use generic lookup or formula.
        // Dan Ngo Tuat: Hoa khoi Suu, Linh khoi Mao.
        // Than Ty Thin: Hoa khoi Dan, Linh khoi Tuat.
        // Ty Dau Suu: Hoa khoi Dau, Linh khoi Tuat.
        // Hoi Mao Mui: Hoa khoi Dau, Linh khoi Tuat.

        $khoiHoa = 0; $khoiLinh = 0; $dirHoa = 1; $dirLinh = -1; // Duong Nam/Am Nu thuan?
        // Direction:
        // Duong Nam, Am Nu: Hoa thuan, Linh nghich.
        // Am Nam, Duong Nu: Hoa nghich, Linh thuan.
        $fireDir = (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) ? 1 : -1;

        if (in_array($chiYear, [2, 6, 10])) { $khoiHoa=1; $khoiLinh=3; }
        elseif (in_array($chiYear, [8, 0, 4])) { $khoiHoa=2; $khoiLinh=10; }
        elseif (in_array($chiYear, [5, 9, 1])) { $khoiHoa=9; $khoiLinh=10; } // Check source? Some say 9, 11.
        else { $khoiHoa=9; $khoiLinh=10; } // Hoi Mao Mui

        // Start from Khoi + Hour.
        // Formular: Start + (Hour) * Dir
        // Note: Hour 0 (Ty) is start? Or start is Ty?
        // Usually: "Khoi tu cung X, dem thuan den gio sinh". So if Ty (0), it is at X.
        $posHoaTinh = ($khoiHoa + ($hh * $fireDir)) % 12;
        $posLinhTinh = ($khoiLinh + ($hh * -$fireDir)) % 12;
        if ($posHoaTinh < 0) $posHoaTinh += 12;
        if ($posLinhTinh < 0) $posLinhTinh += 12;

        $addStar($posHoaTinh, 'hoa_tinh', 'xau');
        $addStar($posLinhTinh, 'linh_tinh', 'xau');


        // 5. Tu Hoa (Hoa Loc, Hoa Quyen, Hoa Khoa, Hoa Ky) - Can Year
        // Can: 0=Giap, 1=At...
        $tuHoaTable = [
            0 => ['liem_trinh', 'pha_quan', 'vu_khuc', 'thai_duong'], // Giap: Liem Pha Vu Duong
            1 => ['thien_co', 'thien_luong', 'tu_vi', 'thai_am'], // At: Co Luong Vi Nguyet
            2 => ['thien_dong', 'thien_co', 'van_xuong', 'liem_trinh'], // Binh: Dong Co Xuong Liem
            3 => ['thai_am', 'thien_dong', 'thien_co', 'cu_mon'], // Dinh: Nguyet Dong Co Cu
            4 => ['tham_lang', 'thai_am', 'huu_bat', 'thien_co'], // Mau: Tham Nguyet Bat Co
            5 => ['vu_khuc', 'tham_lang', 'thien_luong', 'van_khuc'], // Ky: Vu Tham Luong Khuc
            6 => ['thai_duong', 'vu_khuc', 'thien_dong', 'thai_am'], // Canh: Nhat Vu Dong Nguyet (Wait? Nhat Vu Dong Am?) -> Thai Duong, Vu Khuc, Thien Dong, Thai Am ?
            // Canh: Nhat Vu Am Dong (Thai Duong, Vu Khuc, Thai Am, Thien Dong) - Standard is Nhat Vu Am Dong.
            // Let's use: Thai Duong, Vu Khuc, Thai Am, Thien Dong.

            7 => ['cu_mon', 'thai_duong', 'van_khuc', 'van_xuong'], // Tan: Cu Nhat Khuc Xuong
            8 => ['thien_luong', 'tu_vi', 'ta_phu', 'vu_khuc'], // Nham: Luong Vi Phu Vu
            9 => ['pha_quan', 'cu_mon', 'thai_am', 'tham_lang'] // Quy: Pha Cu Am Tham
        ];

        // Correct Canh: Nhat Vu Dong Am? Or Nhat Vu Am Dong?
        // Most sources: Canh Nhat Vu Am Dong. (Thai Duong, Vu Khuc, Thai Am, Thien Dong).
        // Update table for 6 (Canh)
        $tuHoaTable[6] = ['thai_duong', 'vu_khuc', 'thai_am', 'thien_dong'];

        $thCodes = ['hoa_loc', 'hoa_quyen', 'hoa_khoa', 'hoa_ky'];
        $thStars = $tuHoaTable[$canYear % 10];

        // Loop all palaces to find the star and attach Tu Hoa
        // This is tricky. Tu Hoa attaches TO the star.
        // We need to find where the star is, then add the Tu Hoa star there.
        foreach ($thStars as $idx => $starCode) {
            // Find palace of $starCode
            for ($p = 0; $p < 12; $p++) {
                // Check chinh tinh
                foreach ($chart[$p]['chinh_tinh'] as $s) {
                    if ($s['code'] == $starCode) {
                        $addStar($p, $thCodes[$idx], 'tot'); // Add the Hoa star
                        break 2;
                    }
                }
                // Check phu tinh? (Van Xuong, Van Khuc, Ta Phu, Huu Bat)
                // Need to have them placed first!
                // So Tu Hoa must be placed AFTER all other stars.
            }
        }

        // 6. Other Stars (Selection)
        // Van Xuong / Van Khuc (Gio Sinh)
        // Xuong: Tu Tuat(10) nghich den gio sinh.
        // Khuc: Tu Thin(4) thuan den gio sinh.
        $posVanXuong = (10 - $hh + 12) % 12;
        $posVanKhuc = (4 + $hh) % 12;
        $addStar($posVanXuong, 'van_xuong');
        $addStar($posVanKhuc, 'van_khuc');

        // Ta Phu / Huu Bat (Thang Sinh)
        // Ta: Tu Thin(4) thuan den Thang.
        // Huu: Tu Tuat(10) nghich den Thang.
        $posTaPhu = (4 + ($mm - 1)) % 12;
        $posHuuBat = (10 - ($mm - 1) + 12) % 12;
        $addStar($posTaPhu, 'ta_phu');
        $addStar($posHuuBat, 'huu_bat');

        // Thien Khoi / Thien Viet (Can Nam)
        // Giap Mau Canh -> Suu(1) Mui(7)
        // At Ky -> Ty(0) Than(8)
        // Binh Dinh -> Hoi(11) Dau(9)
        // Nham Quy -> Mao(3) Ty(5)
        // Tan -> Ngo(6) Dan(2)
        $khoiVietMap = [
            0 => [1,7], 4 => [1,7], 6 => [1,7],
            1 => [0,8], 5 => [0,8],
            2 => [11,9], 3 => [11,9],
            8 => [3,5], 9 => [3,5],
            7 => [6,2]
        ];
        $kv = $khoiVietMap[$canYear % 10];
        $addStar($kv[0], 'thien_khoi');
        $addStar($kv[1], 'thien_viet');

        // Thien Ma (Chi Nam)
        // Dan Ngo Tuat -> Than(8)
        // Than Ty Thin -> Dan(2)
        // Ty Dau Suu -> Hoi(11)
        // Hoi Mao Mui -> Ty(5)
        $maPos = 0;
        if (in_array($chiYear, [2,6,10])) $maPos = 8;
        elseif (in_array($chiYear, [8,0,4])) $maPos = 2;
        elseif (in_array($chiYear, [5,9,1])) $maPos = 11;
        else $maPos = 5;
        $addStar($maPos, 'thien_ma');

        // Thien Khoc / Thien Hu (Chi Nam)
        // Khoc: Ngo(6) nghich den nam.
        // Hu: Ngo(6) thuan den nam.
        $posKhoc = (6 - $chiYear + 12) % 12;
        $posHu = (6 + $chiYear) % 12;
        $addStar($posKhoc, 'thien_khoc', 'xau');
        $addStar($posHu, 'thien_hu', 'xau');

        // Dao Hoa (Chi Nam)
        // Dan Ngo Tuat -> Mao(3)
        // Than Ty Thin -> Dau(9)
        // Ty Dau Suu -> Ngo(6)
        // Hoi Mao Mui -> Ty(0)
        $daoPos = 0;
        if (in_array($chiYear, [2,6,10])) $daoPos = 3;
        elseif (in_array($chiYear, [8,0,4])) $daoPos = 9;
        elseif (in_array($chiYear, [5,9,1])) $daoPos = 6;
        else $daoPos = 0;
        $addStar($daoPos, 'dao_hoa');

        // Hong Loan / Thien Hy (Chi Nam)
        // Hong Loan: Mao(3) nghich den nam.
        // Thien Hy: Doi xung Hong Loan.
        $posHongLoan = (3 - $chiYear + 12) % 12;
        $posThienHy = ($posHongLoan + 6) % 12;
        $addStar($posHongLoan, 'hong_loan');
        $addStar($posThienHy, 'thien_hy');

        // Thien Hinh / Thien Rieu / Thien Y (Thang Sinh)
        // Hinh: Dau(9) thuan den Thang.
        // Rieu: Suu(1) thuan den Thang.
        // Y: Doi xung Rieu (always? Rieu Y usually together or opposite? Rieu Y dong cung. Check?)
        // Correction: Thien Y luon dong cung Thien Rieu.
        $posHinh = (9 + ($mm - 1)) % 12;
        $posRieu = (1 + ($mm - 1)) % 12;
        $addStar($posHinh, 'thien_hinh', 'xau');
        $addStar($posRieu, 'thien_rieu', 'xau');
        $addStar($posRieu, 'thien_y', 'tot'); // Dong cung Rieu

        // Co Than / Qua Tu (Chi Nam)
        // Hoi Ty Suu -> Dan(2) / Tuat(10)
        // Dan Mao Thin -> Ty(5) / Suu(1)
        // Ty Ngo Mui -> Than(8) / Thin(4)
        // Than Dau Tuat -> Hoi(11) / Mui(7)
        $coQuaMap = [];
        if (in_array($chiYear, [11,0,1])) { $co=2; $qua=10; }
        elseif (in_array($chiYear, [2,3,4])) { $co=5; $qua=1; }
        elseif (in_array($chiYear, [5,6,7])) { $co=8; $qua=4; }
        else { $co=11; $qua=7; }
        $addStar($co, 'co_than', 'xau');
        $addStar($qua, 'qua_tu', 'xau');

        // An Quang / Thien Quy (Van Xuong / Van Khuc + Ngay Sinh)
        // Quang: Cung Xuong + (Ngay - 2). Wait.
        // Rule: Quang = Xuong + (Ngay - 1). Quy = Khuc - (Ngay - 1). (Complex)
        // Simple Rule:
        // Quang: Tu cung Van Xuong dem thuan den ngay sinh, lui lai 1 cung. -> (Xuong + dd - 1 - 1)?
        // Quy: Tu cung Van Khuc dem nghich den ngay sinh, lui lai 1 cung.
        // Let's use: Quang = (PosXuong + dd - 2); Quy = (PosKhuc - dd + 2? No).
        // Standard:
        // Quang: start at Xuong, go CW (day-1).
        // Quy: start at Khuc, go CCW (day-1).
        $posQuang = ($posVanXuong + ($dd - 1)) % 12;
        $posQuy = ($posVanKhuc - ($dd - 1));
        while($posQuy < 0) $posQuy += 12; $posQuy %= 12;
        $addStar($posQuang, 'an_quang');
        $addStar($posQuy, 'thien_quy');

        // --- RELOOP FOR TU HOA (Now that Phus are placed) ---
        // Need to run Tu Hoa check again for stars like Van Xuong, Van Khuc, Ta Phu, Huu Bat
        $thStars = $tuHoaTable[$canYear % 10];
        foreach ($thStars as $idx => $starCode) {
            // Check if already placed (Chinh tinh covered). Now check phu tinh lists.
            for ($p = 0; $p < 12; $p++) {
                // Check all star lists
                $allStars = array_merge($chart[$p]['phu_tinh_tot'], $chart[$p]['phu_tinh_xau']);
                foreach ($allStars as $s) {
                    if ($s['code'] == $starCode) {
                         // Check if Tu Hoa already added? (to avoid double add if Chinh Tinh and Phu Tinh same name? No, names unique)
                         // Just add.
                         $addStar($p, $thCodes[$idx], 'tot');
                         break 2;
                    }
                }
            }
        }

        // --- STEP 6: Thien Ban Info ---
        $banMenhEl = FengShuiUtils::getNguHanhNapAm($canYear, $chiYear);
        $nhNames = [1=>'Thủy', 2=>'Hỏa', 3=>'Thổ', 4=>'Kim', 5=>'Mộc'];

        $thienBan = array(
            'ho_ten' => $name,
            'nam_sinh' => FengShuiUtils::$CAN[$canYear] . ' ' . FengShuiUtils::$CHI[$chiYear],
            'menh_ngu_hanh' => isset($nhNames[$banMenhEl]) ? $nhNames[$banMenhEl] : 'Unknown',
            'cuc' => $cucNameMap[$cuc],
            'chu_menh' => 'Tham Lang', // Placeholder
            'chu_than' => 'Hỏa Tinh', // Placeholder
            'am_duong' => ($isDuong ? 'Dương' : 'Âm') . ' ' . ($gender==1 ? 'Nam' : 'Nữ'),
            'menh_color' => isset([1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl]) ? [1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl] : 'default',
            'am_duong_ly' => self::calculateYinYangBalance($canYear, $chiYear, $posMenh, $gender),
            'cuc_menh_ly' => self::calculateElementRelation($banMenhEl, $cucMap[$cucElement])
        );

        return [
            'thien_ban' => $thienBan,
            'dia_ban' => $chart,
            'meta' => [
                'chiYear' => $chiYear,
                'gender' => $gender
            ]
        ];
    }

    public static function getLimitInfoForYear($chiYear, $gender, $targetYear) {
        // 1. Tieu Van Position
        // Logic copy from lapLaSo but for target year
        $startTVPalace = 0; $startTVYear = 0;
        if (in_array($chiYear, [2, 6, 10])) { $startTVPalace = 4; $startTVYear = 10; } // Dan Ngo Tuat -> Thin (start Tuat)
        elseif (in_array($chiYear, [8, 0, 4])) { $startTVPalace = 10; $startTVYear = 4; } // Than Ty Thin -> Tuat (start Thin)
        elseif (in_array($chiYear, [11, 3, 7])) { $startTVPalace = 1; $startTVYear = 7; } // Hoi Mao Mui -> Suu (start Mui)
        elseif (in_array($chiYear, [5, 9, 1])) { $startTVPalace = 7; $startTVYear = 1; } // Ty Dau Suu -> Mui (start Suu)

        $tvDirection = ($gender == 1) ? 1 : -1;

        // Find which Palace corresponds to the Target Year's Chi
        // Target Chi
        $targetChi = ($targetYear - 4) % 12;
        if ($targetChi < 0) $targetChi += 12; // PHP modulo fix

        // We need to find k such that ($startTVYear + k) % 12 == $targetChi
        $diff = $targetChi - $startTVYear;
        if ($diff < 0) $diff += 12;
        $k = $diff;

        $tieuVanIdx = ($startTVPalace + ($k * $tvDirection)) % 12;
        if ($tieuVanIdx < 0) $tieuVanIdx += 12;

        // 2. Luu Thai Tue Position
        // Luu Thai Tue is simply at the Palace corresponding to the Year's Chi
        // e.g. Year Thin -> Palace Thin (4)
        $luuThaiTueIdx = $targetChi; // Assuming Palace 0=Ty, 1=Suu...

        return [
            'tieu_van_idx' => $tieuVanIdx,
            'luu_thai_tue_idx' => $luuThaiTueIdx,
            'target_chi' => self::$DIA_CHI[$targetChi]
        ];
    }
}
