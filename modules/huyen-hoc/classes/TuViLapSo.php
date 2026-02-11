<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
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
        0 => 'ty_chuot', 1 => 'suu', 2 => 'dan', 3 => 'mao',
        4 => 'thin', 5 => 'ty_ran', 6 => 'ngo', 7 => 'mui',
        8 => 'than', 9 => 'dau', 10 => 'tuat', 11 => 'hoi'
    );

    // Full List of Stars (Updated 110+)
    // Format: 'code' => ['Name', ElementID, Type]
    // Elements: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc
    // Type: 1=ChinhTinh, 2=PhuTinhTot, 3=PhuTinhXau (Sat/Bai), 4=VongThaiTue, 5=VongLocTon, 6=VongTrangSinh
    public static $STARS = array(
        // 14 Chinh Tinh
        'tu_vi' => ['Tử Vi', 3, 1], 'thien_co' => ['Thiên Cơ', 5, 1], 'thai_duong' => ['Thái Dương', 2, 1], 'vu_khuc' => ['Vũ Khúc', 4, 1], 'thien_dong' => ['Thiên Đồng', 1, 1], 'liem_trinh' => ['Liêm Trinh', 2, 1],
        'thien_phu' => ['Thiên Phủ', 3, 1], 'thai_am' => ['Thái Âm', 1, 1], 'tham_lang' => ['Tham Lang', 1, 1], 'cu_mon' => ['Cự Môn', 1, 1], 'thien_tuong' => ['Thiên Tướng', 1, 1], 'thien_luong' => ['Thiên Lương', 5, 1], 'that_sat' => ['Thất Sát', 4, 1], 'pha_quan' => ['Phá Quân', 1, 1],

        // Vong Thai Tue
        'thai_tue' => ['Thái Tuế', 2, 4], 'thieu_duong' => ['Thiếu Dương', 2, 4], 'tang_mon' => ['Tang Môn', 5, 4], 'thieu_am' => ['Thiếu Âm', 1, 4], 'quan_phu_3' => ['Quan Phù', 2, 4], 'tu_phu' => ['Tử Phù', 4, 4], 'tue_pha' => ['Tuế Phá', 2, 4], 'long_duc' => ['Long Đức', 1, 4], 'bach_ho' => ['Bạch Hổ', 4, 4], 'phuc_duc' => ['Phúc Đức', 3, 4], 'dieu_khach' => ['Điếu Khách', 2, 4], 'truc_phu' => ['Trực Phù', 4, 4],

        // Vong Loc Ton
        'loc_ton' => ['Lộc Tồn', 3, 5], 'luc_si' => ['Lực Sĩ', 2, 5], 'thanh_long' => ['Thanh Long', 1, 5], 'tieu_hao' => ['Tiểu Hao', 2, 5], 'tuong_quan' => ['Tướng Quân', 5, 5], 'tau_thu' => ['Tấu Thư', 4, 5], 'phi_liem' => ['Phi Liêm', 2, 5], 'hy_than' => ['Hỷ Thần', 2, 5], 'benh_phu' => ['Bệnh Phù', 3, 5], 'dai_hao' => ['Đại Hao', 2, 5], 'phuc_binh' => ['Phục Binh', 2, 5], 'quan_phu_2' => ['Quan Phủ', 2, 5],

        // Vong Trang Sinh
        'trang_sinh' => ['Tràng Sinh', 1, 6], 'moc_duc' => ['Mộc Dục', 1, 6], 'quan_doi' => ['Quan Đới', 4, 6], 'lam_quan' => ['Lâm Quan', 4, 6], 'de_vuong' => ['Đế Vượng', 4, 6], 'suy' => ['Suy', 1, 6], 'benh' => ['Bệnh', 2, 6], 'tu' => ['Tử', 3, 6], 'mo' => ['Mộ', 3, 6], 'tuyet' => ['Tuyệt', 3, 6], 'thai' => ['Thai', 3, 6], 'duong' => ['Dưỡng', 5, 6],

        // Luc Sat (Kinh Da Khong Kiep Hoa Linh)
        'kinh_duong' => ['Kình Dương', 4, 3], 'da_la' => ['Đà La', 4, 3], 'dia_khong' => ['Địa Không', 2, 3], 'dia_kiep' => ['Địa Kiếp', 2, 3], 'hoa_tinh' => ['Hỏa Tinh', 2, 3], 'linh_tinh' => ['Linh Tinh', 2, 3],

        // Tu Hoa
        'hoa_loc' => ['Hóa Lộc', 5, 2], 'hoa_quyen' => ['Hóa Quyền', 5, 2], 'hoa_khoa' => ['Hóa Khoa', 1, 2], 'hoa_ky' => ['Hóa Kỵ', 1, 3],

        // Other Important Stars (Bo Tinh)
        'van_xuong' => ['Văn Xương', 4, 2], 'van_khuc' => ['Văn Khúc', 1, 2],
        'ta_phu' => ['Tả Phù', 3, 2], 'huu_bat' => ['Hữu Bật', 1, 2],
        'thien_khoi' => ['Thiên Khôi', 2, 2], 'thien_viet' => ['Thiên Việt', 2, 2],
        'thien_khong' => ['Thiên Không', 2, 3],
        'dao_hoa' => ['Đào Hoa', 5, 2], 'hong_loan' => ['Hồng Loan', 1, 2], 'thien_hy' => ['Thiên Hỷ', 1, 2],
        'thien_hinh' => ['Thiên Hình', 4, 3], 'thien_rieu' => ['Thiên Riêu', 1, 3],
        'co_than' => ['Cô Thần', 3, 3], 'qua_tu' => ['Quả Tú', 3, 3],
        'an_quang' => ['Ân Quang', 5, 2], 'thien_quy' => ['Thiên Quý', 3, 2],
        'tam_thai' => ['Tam Thai', 1, 2], 'bat_toa' => ['Bát Tọa', 3, 2],
        'long_tri' => ['Long Trì', 1, 2], 'phuong_cac' => ['Phượng Các', 4, 2],
        'thien_duc' => ['Thiên Đức', 2, 2], 'nguyet_duc' => ['Nguyệt Đức', 2, 2],
        'thien_giai' => ['Thiên Giải', 2, 2], 'dia_giai' => ['Địa Giải', 3, 2], 'giai_than' => ['Giải Thần', 5, 2],
        'thien_y' => ['Thiên Y', 1, 2], 'thien_quan' => ['Thiên Quan', 2, 2], 'thien_phuc' => ['Thiên Phúc', 2, 2],
        'luu_ha' => ['Lưu Hà', 1, 3], 'kiet_sat' => ['Kiếp Sát', 2, 3],
        'pha_toai' => ['Phá Toái', 2, 3], 'thien_hu' => ['Thiên Hư', 1, 3], 'thien_khoc' => ['Thiên Khốc', 1, 3],
        'thien_tai' => ['Thiên Tài', 3, 2], 'thien_tho' => ['Thiên Thọ', 3, 2],
        'thien_thuong' => ['Thiên Thương', 3, 3], 'thien_su' => ['Thiên Sứ', 1, 3],
        'hoa_cai' => ['Hoa Cái', 4, 2], 'thien_ma' => ['Thiên Mã', 2, 2], 'thien_la' => ['Thiên La', 3, 3], 'dia_vong' => ['Địa Võng', 3, 3],
        'dau_quan' => ['Đẩu Quân', 2, 2]
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
        'pha_quan'   => ['M','V','H','H','M','H','M','V','H','H','M','H'],
        // Aux
        'van_xuong'  => ['D','D','H','H','M','M','H','D','D','H','H','M'],
        'van_khuc'   => ['D','D','H','H','M','M','H','D','D','H','H','M'],
        'kinh_duong' => ['H','D','H','H','D','H','H','D','H','H','D','H'], // Dac tai Thin Tuat Suu Mui
        'da_la'      => ['H','D','H','H','D','H','H','D','H','H','D','H'],
        'hoa_tinh'   => ['H','H','M','H','D','H','H','H','M','H','D','H'],
        'linh_tinh'  => ['H','H','M','H','D','H','H','H','M','H','D','H'],
        'dia_khong'  => ['H','H','D','H','H','D','H','H','D','H','H','D'], // Dac tai Dan Than Ty Hoi
        'dia_kiep'   => ['H','H','D','H','H','D','H','H','D','H','H','D'],
        'thien_ma'   => ['','','D','','','H','','','D','','','H'], // Dac tai Dan Than, Ham tai Ty Hoi
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
            'color' => isset($colors[$elId]) ? $colors[$elId] : 'default',
            'type_id' => isset($info[2]) ? $info[2] : 2
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
        $isPalaceYang = ($menhPalaceBranch % 2 == 0);

        if ($isYearYang == $isPalaceYang) {
            return "Âm Dương Thuận Lý";
        } else {
            return "Âm Dương Nghịch Lý";
        }
    }

    public static function calculateElementRelation($menhElement, $cucElement) {
        // Elements: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc
        if ($menhElement == $cucElement) return "Cục Mệnh Bình Hòa";

        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4];
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4];

        if (isset($sinh[$cucElement]) && $sinh[$cucElement] == $menhElement) return "Cục Sinh Mệnh (Tốt)";
        if (isset($sinh[$menhElement]) && $sinh[$menhElement] == $cucElement) return "Mệnh Sinh Cục (Hao)"; // Sinh xuat

        if (isset($khac[$cucElement]) && $khac[$cucElement] == $menhElement) return "Cục Khắc Mệnh (Xấu)";
        if (isset($khac[$menhElement]) && $khac[$menhElement] == $cucElement) return "Mệnh Khắc Cục (Khắc chế được hoàn cảnh)";

        return "Không xác định";
    }

    /**
     * Finds Tu Vi Position based on Cuc and Lunar Day
     * Implements specific user algorithm
     */
    public static function getTuViPosition($cuc, $lunarDay) {
        // $cuc: 2 (Thuy), 3 (Moc), 4 (Kim), 5 (Tho), 6 (Hoa)
        // $lunarDay: 1-30

        $remainder = $lunarDay % $cuc;
        $adjustment = 0;

        if ($remainder == 0) {
            $quotient = $lunarDay / $cuc;
            $adjustment = 0;
        } else {
            // Find complement to divide evenly
            $add = $cuc - $remainder;
            $quotient = ($lunarDay + $add) / $cuc;

            // Rule: Odd add -> subtract, Even add -> add
            if ($add % 2 != 0) {
                $adjustment = -$add;
            } else {
                $adjustment = $add;
            }
        }

        // Start from Dan (index 2 in 0-11 system, but usually called Palace 3 in 1-12 system)
        // User pseudo: Base = 3 + Quotient - 1. (3 is Dan)
        // Our 0-11 system: Dan is 2.
        // Base = 2 + Quotient - 1 = 1 + Quotient.

        $basePos = 2 + $quotient - 1;
        $finalPos = $basePos + $adjustment;

        // Normalize to 0-11
        while ($finalPos > 11) $finalPos -= 12;
        while ($finalPos < 0) $finalPos += 12;

        return $finalPos; // Returns 0=Ty, 1=Suu...
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
        $addStar = function($pIdx, $code, $typeOverride = null) use (&$chart) {
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

            $type = $typeOverride ? $typeOverride : $info['type_id'];

            if ($type == 1) { // Chinh Tinh
                $chart[$pIdx]['chinh_tinh'][] = $starData;
            } elseif ($type == 3) { // Sat Tinh / Xau
                $chart[$pIdx]['phu_tinh_xau'][] = $starData;
            } else { // Tot / Trung
                 $chart[$pIdx]['phu_tinh_tot'][] = $starData;
            }
        };

        // --- STEP 1: An 12 Cung (Dia Ban) ---
        // Menh: From Dan(2) + Month - 1 - Hour
        $posMenh = (2 + ($mm - 1) - $hh) % 12;
        if ($posMenh < 0) $posMenh += 12;
        // Than: From Dan(2) + Month - 1 + Hour
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
        $startTVPalace = 0; $startTVYear = 0;
        if (in_array($chiYear, [2, 6, 10])) { $startTVPalace = 4; $startTVYear = 10; } // Dan Ngo Tuat -> Thin
        elseif (in_array($chiYear, [8, 0, 4])) { $startTVPalace = 10; $startTVYear = 4; } // Than Ty Thin -> Tuat
        elseif (in_array($chiYear, [11, 3, 7])) { $startTVPalace = 1; $startTVYear = 7; } // Hoi Mao Mui -> Suu
        elseif (in_array($chiYear, [5, 9, 1])) { $startTVPalace = 7; $startTVYear = 1; } // Ty Dau Suu -> Mui

        $tvDirection = ($gender == 1) ? 1 : -1;
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

        // --- STEP 4: An Sao Chinh Tinh (Algorithm Update) ---
        $posTuVi = self::getTuViPosition($cuc, $dd);
        $addStar($posTuVi, 'tu_vi');

        // An Thien Phu: Opposite Tu Vi across Dan-Than axis
        // Formula: PosThienPhu = (2 + 8 - PosTuVi) % 12 ? No.
        // Dan=2, Than=8. Axis sum = 10? No.
        // Rule: Dan(2) <-> Dan(2). Mao(3) <-> Suu(1).
        // Sum of indices = 4 (or 16). Ex: 2+2=4. 3+1=4. 0+4=4.
        // So PosTP = (4 - PosTV) % 12.
        $posThienPhu = (4 - $posTuVi + 12) % 12;
        $addStar($posThienPhu, 'thien_phu');

        $offsetsTuVi = ['thien_co' => 1, 'thai_duong' => 3, 'vu_khuc' => 4, 'thien_dong' => 5, 'liem_trinh' => 8];
        foreach ($offsetsTuVi as $code => $offset) $addStar($posTuVi - $offset, $code);

        $offsetsThienPhu = ['thai_am' => 1, 'tham_lang' => 2, 'cu_mon' => 3, 'thien_tuong' => 4, 'thien_luong' => 5, 'that_sat' => 6, 'pha_quan' => 10];
        foreach ($offsetsThienPhu as $code => $offset) $addStar($posThienPhu + $offset, $code);

        // --- STEP 5: An Cac Sao Khac ---

        // 1. Vong Thai Tue (Chi Nam)
        $starsThaiTue = ['thai_tue', 'thieu_duong', 'tang_mon', 'thieu_am', 'quan_phu_3', 'tu_phu', 'tue_pha', 'long_duc', 'bach_ho', 'phuc_duc', 'dieu_khach', 'truc_phu'];
        foreach ($starsThaiTue as $idx => $code) {
            $p = ($chiYear + $idx) % 12;
            $addStar($p, $code);
        }

        // 2. Vong Loc Ton (Can Nam)
        $locTonMap = [0=>2, 1=>3, 2=>5, 3=>6, 4=>5, 5=>6, 6=>8, 7=>9, 8=>11, 9=>0];
        $posLocTon = $locTonMap[$canYear % 10];

        $starsLocTon = ['loc_ton', 'luc_si', 'thanh_long', 'tieu_hao', 'tuong_quan', 'tau_thu', 'phi_liem', 'hy_than', 'benh_phu', 'dai_hao', 'phuc_binh', 'quan_phu_2'];
        $ltDir = (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) ? 1 : -1;

        foreach ($starsLocTon as $idx => $code) {
            $p = ($posLocTon + ($idx * $ltDir)) % 12;
            if ($p < 0) $p += 12;
            $addStar($p, $code);
        }

        $posKinhDuong = ($posLocTon + 1) % 12;
        $posDaLa = ($posLocTon - 1 + 12) % 12;
        $addStar($posKinhDuong, 'kinh_duong');
        $addStar($posDaLa, 'da_la');

        // 3. Vong Trang Sinh (Cuc)
        $tsMap = [2 => 8, 3 => 11, 4 => 5, 5 => 8, 6 => 2];
        $posTrangSinh = $tsMap[$cuc];
        $tsDir = $direction;

        $starsTrangSinh = ['trang_sinh', 'moc_duc', 'quan_doi', 'lam_quan', 'de_vuong', 'suy', 'benh', 'tu', 'mo', 'tuyet', 'thai', 'duong'];
        foreach ($starsTrangSinh as $idx => $code) {
            $p = ($posTrangSinh + ($idx * $tsDir)) % 12;
            if ($p < 0) $p += 12;
            $chart[$p]['vong_trang_sinh'] = self::getStarInfo($code)['name'];
            $addStar($p, $code);
        }

        // 4. Luc Sat & Other
        $posDiaKiep = (11 + $hh) % 12;
        $posDiaKhong = (11 - $hh + 12) % 12;
        $addStar($posDiaKiep, 'dia_kiep');
        $addStar($posDiaKhong, 'dia_khong');

        $khoiHoa = 0; $khoiLinh = 0; $fireDir = (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) ? 1 : -1;
        if (in_array($chiYear, [2, 6, 10])) { $khoiHoa=1; $khoiLinh=3; }
        elseif (in_array($chiYear, [8, 0, 4])) { $khoiHoa=2; $khoiLinh=10; }
        elseif (in_array($chiYear, [5, 9, 1])) { $khoiHoa=9; $khoiLinh=10; }
        else { $khoiHoa=9; $khoiLinh=10; }

        $posHoaTinh = ($khoiHoa + ($hh * $fireDir)) % 12;
        $posLinhTinh = ($khoiLinh + ($hh * -$fireDir)) % 12;
        if ($posHoaTinh < 0) $posHoaTinh += 12;
        if ($posLinhTinh < 0) $posLinhTinh += 12;

        $addStar($posHoaTinh, 'hoa_tinh');
        $addStar($posLinhTinh, 'linh_tinh');

        // 5. Tu Hoa
        $tuHoaTable = [
            0 => ['liem_trinh', 'pha_quan', 'vu_khuc', 'thai_duong'],
            1 => ['thien_co', 'thien_luong', 'tu_vi', 'thai_am'],
            2 => ['thien_dong', 'thien_co', 'van_xuong', 'liem_trinh'],
            3 => ['thai_am', 'thien_dong', 'thien_co', 'cu_mon'],
            4 => ['tham_lang', 'thai_am', 'huu_bat', 'thien_co'],
            5 => ['vu_khuc', 'tham_lang', 'thien_luong', 'van_khuc'],
            6 => ['thai_duong', 'vu_khuc', 'thai_am', 'thien_dong'],
            7 => ['cu_mon', 'thai_duong', 'van_khuc', 'van_xuong'],
            8 => ['thien_luong', 'tu_vi', 'ta_phu', 'vu_khuc'],
            9 => ['pha_quan', 'cu_mon', 'thai_am', 'tham_lang']
        ];

        $thCodes = ['hoa_loc', 'hoa_quyen', 'hoa_khoa', 'hoa_ky'];
        $thStars = $tuHoaTable[$canYear % 10];

        // 6. Bo Tinh (Aux)
        $posVanXuong = (10 - $hh + 12) % 12;
        $posVanKhuc = (4 + $hh) % 12;
        $addStar($posVanXuong, 'van_xuong');
        $addStar($posVanKhuc, 'van_khuc');

        $posTaPhu = (4 + ($mm - 1)) % 12;
        $posHuuBat = (10 - ($mm - 1) + 12) % 12;
        $addStar($posTaPhu, 'ta_phu');
        $addStar($posHuuBat, 'huu_bat');

        $khoiVietMap = [0 => [1,7], 4 => [1,7], 6 => [1,7], 1 => [0,8], 5 => [0,8], 2 => [11,9], 3 => [11,9], 8 => [3,5], 9 => [3,5], 7 => [6,2]];
        $kv = $khoiVietMap[$canYear % 10];
        $addStar($kv[0], 'thien_khoi');
        $addStar($kv[1], 'thien_viet');

        $maPos = 0;
        if (in_array($chiYear, [2,6,10])) $maPos = 8;
        elseif (in_array($chiYear, [8,0,4])) $maPos = 2;
        elseif (in_array($chiYear, [5,9,1])) $maPos = 11;
        else $maPos = 5;
        $addStar($maPos, 'thien_ma');

        $posKhoc = (6 - $chiYear + 12) % 12;
        $posHu = (6 + $chiYear) % 12;
        $addStar($posKhoc, 'thien_khoc');
        $addStar($posHu, 'thien_hu');

        $daoPos = 0;
        if (in_array($chiYear, [2,6,10])) $daoPos = 3;
        elseif (in_array($chiYear, [8,0,4])) $daoPos = 9;
        elseif (in_array($chiYear, [5,9,1])) $daoPos = 6;
        else $daoPos = 0;
        $addStar($daoPos, 'dao_hoa');

        $posHongLoan = (3 - $chiYear + 12) % 12;
        $posThienHy = ($posHongLoan + 6) % 12;
        $addStar($posHongLoan, 'hong_loan');
        $addStar($posThienHy, 'thien_hy');

        $posHinh = (9 + ($mm - 1)) % 12;
        $posRieu = (1 + ($mm - 1)) % 12;
        $addStar($posHinh, 'thien_hinh');
        $addStar($posRieu, 'thien_rieu');
        $addStar($posRieu, 'thien_y');

        $coQuaMap = [];
        if (in_array($chiYear, [11,0,1])) { $co=2; $qua=10; }
        elseif (in_array($chiYear, [2,3,4])) { $co=5; $qua=1; }
        elseif (in_array($chiYear, [5,6,7])) { $co=8; $qua=4; }
        else { $co=11; $qua=7; }
        $addStar($co, 'co_than');
        $addStar($qua, 'qua_tu');

        $posQuang = ($posVanXuong + ($dd - 1)) % 12;
        $posQuy = ($posVanKhuc - ($dd - 1));
        while($posQuy < 0) $posQuy += 12; $posQuy %= 12;
        $addStar($posQuang, 'an_quang');
        $addStar($posQuy, 'thien_quy');

        // Re-loop for Tu Hoa on Aux stars
        foreach ($thStars as $idx => $starCode) {
            for ($p = 0; $p < 12; $p++) {
                $allStars = array_merge($chart[$p]['chinh_tinh'], $chart[$p]['phu_tinh_tot'], $chart[$p]['phu_tinh_xau']);
                foreach ($allStars as $s) {
                    if ($s['code'] == $starCode) {
                         $addStar($p, $thCodes[$idx]);
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
            'am_duong' => ($isDuong ? 'Dương' : 'Âm') . ' ' . ($gender==1 ? 'Nam' : 'Nữ'),
            'menh_color' => isset([1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl]) ? [1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl] : 'default',
            'am_duong_ly' => self::calculateYinYangBalance($canYear, $chiYear, $posMenh, $gender),
            'cuc_menh_ly' => self::calculateElementRelation($banMenhEl, $cucElement)
        );

        return [
            'thien_ban' => $thienBan,
            'dia_ban' => $chart,
            'meta' => [
                'birth_year' => $yyyy,
                'canYear' => $canYear, // 0..9
                'chiYear' => $chiYear, // 0..11
                'gender' => $gender, // 1/0
                'menh_idx' => $posMenh,
                'than_idx' => $posThan,
                'menh_element_id' => $banMenhEl, // 1..5
                'cuc_element_id' => $cucMap[$cucElement], // 2..6 (Wait, cucMap values are 2,6,5,4,3. These are Cuc ID, not element ID?)
                // cucMap was: array(1 => 2, 2 => 6, 3 => 5, 4 => 4, 5 => 3);
                // 1=Thuy -> 2 (Thuy Nhi Cuc). Element 1 map to Cuc 2.
                // 2=Hoa -> 6 (Hoa Luc Cuc). Element 2 map to Cuc 6.
                // So $cucMap[$cucElement] IS the Cuc ID (2..6).
                'cuc_id' => $cuc // 2..6
            ]
        ];
    }

    public static function getLimitInfoForYear($chiYear, $gender, $targetYear, $birthYear) {
        if ($targetYear < $birthYear) return null;
        $age = $targetYear - $birthYear + 1; // Tuoi Am (Lunar Age)

        // --- 1. Tieu Van Palace (Tieu Han) Logic Update ---
        // Rules:
        // Dan-Ngo-Tuat (2,6,10) -> Start Thin (4)
        // Than-Ty-Thin (8,0,4) -> Start Tuat (10)
        // Ty-Dau-Suu (5,9,1) -> Start Mui (7)
        // Hoi-Mao-Mui (11,3,7) -> Start Suu (1)
        // Direction: Male Clockwise (1), Female Counter-Clockwise (-1)

        $startTVPalace = 0;
        if (in_array($chiYear, [8, 0, 4])) { $startTVPalace = 10; } // Than Ty Thin -> Tuat
        elseif (in_array($chiYear, [2, 6, 10])) { $startTVPalace = 4; } // Dan Ngo Tuat -> Thin
        elseif (in_array($chiYear, [5, 9, 1])) { $startTVPalace = 7; } // Ty Dau Suu -> Mui
        elseif (in_array($chiYear, [11, 3, 7])) { $startTVPalace = 1; } // Hoi Mao Mui -> Suu

        $tvDirection = ($gender == 1) ? 1 : -1;

        // Formula: Pos = Start + (Age - 1) * Direction
        // Note: Tieu Van shifts by Age. 1 year old at Start.
        $tieuVanIdx = ($startTVPalace + (($age - 1) * $tvDirection)) % 12;
        if ($tieuVanIdx < 0) $tieuVanIdx += 12;

        // --- 2. Luu Thai Tue ---
        // Always at the Palace of the current year's Earthly Branch
        $targetChi = ($targetYear - 4) % 12;
        if ($targetChi < 0) $targetChi += 12;
        $luuThaiTueIdx = $targetChi;

        // Pham Thai Tue Check
        $phamThaiTue = ($chiYear == $targetChi);

        // --- 3. Cuu Dieu Tinh Quan (9 Stars) ---
        $stars9 = [
            1 => ['code' => 'la_hau', 'name' => 'La Hầu', 'type' => 'xau'],
            2 => ['code' => 'tho_tu', 'name' => 'Thổ Tú', 'type' => 'trung'],
            3 => ['code' => 'thuy_dieu', 'name' => 'Thủy Diệu', 'type' => 'trung'],
            4 => ['code' => 'thai_bach', 'name' => 'Thái Bạch', 'type' => 'xau'],
            5 => ['code' => 'thai_duong_han', 'name' => 'Thái Dương', 'type' => 'tot'],
            6 => ['code' => 'van_hon', 'name' => 'Vân Hớn', 'type' => 'trung'],
            7 => ['code' => 'ke_do', 'name' => 'Kế Đô', 'type' => 'xau'],
            8 => ['code' => 'thai_am_han', 'name' => 'Thái Âm', 'type' => 'tot'],
            0 => ['code' => 'moc_duc', 'name' => 'Mộc Đức', 'type' => 'tot']
        ];

        $stars9Nu = [
            1 => ['code' => 'ke_do', 'name' => 'Kế Đô', 'type' => 'xau'],
            2 => ['code' => 'van_hon', 'name' => 'Vân Hớn', 'type' => 'trung'],
            3 => ['code' => 'moc_duc', 'name' => 'Mộc Đức', 'type' => 'tot'],
            4 => ['code' => 'thai_am_han', 'name' => 'Thái Âm', 'type' => 'tot'],
            5 => ['code' => 'tho_tu', 'name' => 'Thổ Tú', 'type' => 'trung'],
            6 => ['code' => 'la_hau', 'name' => 'La Hầu', 'type' => 'xau'],
            7 => ['code' => 'thai_duong_han', 'name' => 'Thái Dương', 'type' => 'tot'],
            8 => ['code' => 'thai_bach', 'name' => 'Thái Bạch', 'type' => 'xau'],
            0 => ['code' => 'thuy_dieu', 'name' => 'Thủy Diệu', 'type' => 'trung']
        ];

        $star9Info = ($gender == 1) ? $stars9[$age % 9] : $stars9Nu[$age % 9];

        // --- 3b. Cac Sao Luu (Dynamic Stars) ---
        $luuStars = [];

        // Luu Thai Tue (Already calculated as $targetChi)
        $luuStars['luu_thai_tue'] = $targetChi;

        // Luu Tang Mon (Thai Tue + 2)
        $luuStars['luu_tang_mon'] = ($targetChi + 2) % 12;

        // Luu Bach Ho (Opposite Tang Mon)
        $luuStars['luu_bach_ho'] = ($targetChi + 8) % 12; // (Target + 2 + 6)

        // Luu Thien Khoc / Luu Thien Hu
        // Khoc: Ngo (6) - YearChi. Hu: Ngo (6) + YearChi.
        $luuKhoc = (6 - $targetChi + 12) % 12;
        $luuHu = (6 + $targetChi) % 12;
        $luuStars['luu_thien_khoc'] = $luuKhoc;
        $luuStars['luu_thien_hu'] = $luuHu;

        // Luu Thien Ma
        // Dan/Ngo/Tuat (2,6,10) -> Than (8)
        // Than/Ty/Thin (8,0,4) -> Dan (2)
        // Ty/Dau/Suu (5,9,1) -> Hoi (11)
        // Hoi/Mao/Mui (11,3,7) -> Ty (5)
        $luuMa = 0;
        if (in_array($targetChi, [2, 6, 10])) $luuMa = 8;
        elseif (in_array($targetChi, [8, 0, 4])) $luuMa = 2;
        elseif (in_array($targetChi, [5, 9, 1])) $luuMa = 11;
        elseif (in_array($targetChi, [11, 3, 7])) $luuMa = 5;
        $luuStars['luu_thien_ma'] = $luuMa;

        // Luu Loc Ton (Based on Year Can)
        // Can of Viewing Year.
        $targetCan = ($targetYear - 4) % 10;
        if ($targetCan < 0) $targetCan += 10;

        $locTonMap = [0=>2, 1=>3, 2=>5, 3=>6, 4=>5, 5=>6, 6=>8, 7=>9, 8=>11, 9=>0];
        $luuLoc = isset($locTonMap[$targetCan]) ? $locTonMap[$targetCan] : 0;
        $luuStars['luu_loc_ton'] = $luuLoc;

        // Luu Kinh Duong / Da La
        $luuStars['luu_kinh_duong'] = ($luuLoc + 1) % 12;
        $luuStars['luu_da_la'] = ($luuLoc - 1 + 12) % 12;

        // Luu Dao Hoa / Hong Loan
        $luuDao = 0;
        if (in_array($targetChi, [2, 6, 10])) $luuDao = 3;
        elseif (in_array($targetChi, [8, 0, 4])) $luuDao = 9;
        elseif (in_array($targetChi, [5, 9, 1])) $luuDao = 6;
        elseif (in_array($targetChi, [11, 3, 7])) $luuDao = 0;
        $luuStars['luu_dao_hoa'] = $luuDao;

        // Hong Loan: Opposite Dao Hoa? No. Hong Loan is fixed rule: Mao (3) count backwards to Year Chi.
        // Position = (3 - YearChi + 12) % 12.
        $luuHong = (3 - $targetChi + 12) % 12;
        $luuStars['luu_hong_loan'] = $luuHong;

        // --- 4. Bat Han (8 Limits) ---
        $hans = [
            1 => ['code' => 'huynh_tuyen', 'name' => 'Huỳnh Tuyền'],
            2 => ['code' => 'tam_kheo', 'name' => 'Tam Kheo'],
            3 => ['code' => 'ngu_mo', 'name' => 'Ngũ Mộ'],
            4 => ['code' => 'thien_tinh', 'name' => 'Thiên Tinh'],
            5 => ['code' => 'toan_tan', 'name' => 'Toán Tận'],
            6 => ['code' => 'thien_la', 'name' => 'Thiên La'],
            7 => ['code' => 'dia_vong', 'name' => 'Địa Võng'],
            0 => ['code' => 'diem_vuong', 'name' => 'Diêm Vương'] // Remainder 0 or 8
        ];

        $hanInfo = $hans[$age % 8]; // Using % 8 for 8 limits.

        $tamTai = false;
        $tamTaiGroup = [];
        if (in_array($chiYear, [8, 0, 4])) $tamTaiGroup = [2, 3, 4]; // Than Ty Thin -> Dan Mao Thin
        elseif (in_array($chiYear, [5, 9, 1])) $tamTaiGroup = [11, 0, 1]; // Ty Dau Suu -> Hoi Ty Suu
        elseif (in_array($chiYear, [2, 6, 10])) $tamTaiGroup = [8, 9, 10]; // Dan Ngo Tuat -> Than Dau Tuat
        elseif (in_array($chiYear, [11, 3, 7])) $tamTaiGroup = [5, 6, 7]; // Hoi Mao Mui -> Ty Ngo Mui

        if (in_array($targetChi, $tamTaiGroup)) {
            $tamTai = true;
        }

        return [
            'age_am' => $age,
            'tieu_van_idx' => $tieuVanIdx,
            'luu_thai_tue_idx' => $luuThaiTueIdx,
            'target_chi' => self::$DIA_CHI[$targetChi],
            'sao_han' => $star9Info,
            'han' => $hanInfo,
            'tam_tai' => $tamTai,
            'pham_thai_tue' => $phamThaiTue,
            'luu_stars' => $luuStars
        ];
    }

    public static function getThanInfo($hh) {
        $map = [0=>'Mệnh', 6=>'Mệnh', 1=>'Phúc Đức', 7=>'Phúc Đức', 2=>'Quan Lộc', 8=>'Quan Lộc', 3=>'Thiên Di', 9=>'Thiên Di', 4=>'Tài Bạch', 10=>'Tài Bạch', 5=>'Phu Thê', 11=>'Phu Thê'];
        $palace = isset($map[$hh]) ? $map[$hh] : 'Mệnh';
        return ['palace' => $palace];
    }

    /**
     * Calculate Nguyet Han (Monthly Limit)
     * Rule: Start from Tieu Van Palace (Month 1), follow Tieu Van direction (Nam Thuan, Nu Nghich)
     */
    public static function getNguyetHan($tieuVanIdx, $month, $gender) {
        $direction = ($gender == 1) ? 1 : -1;
        // Month 1 starts at Tieu Van Index
        $idx = ($tieuVanIdx + (($month - 1) * $direction)) % 12;
        if ($idx < 0) $idx += 12;
        return $idx;
    }

    /**
     * Calculate Nhat Han (Daily Limit)
     * Rule: Start from Nguyet Han Palace (Day 1), follow Tieu Van direction
     */
    public static function getNhatHan($nguyetHanIdx, $day, $gender) {
        $direction = ($gender == 1) ? 1 : -1;
        // Day 1 starts at Nguyet Han Index
        $idx = ($nguyetHanIdx + (($day - 1) * $direction)) % 12;
        if ($idx < 0) $idx += 12;
        return $idx;
    }

    /**
     * Calculate Thoi Han (Hourly Limit)
     * Rule: Start from Nhat Han Palace (Hour Ty - 0), follow Tieu Van direction
     */
    public static function getThoiHan($nhatHanIdx, $hourIdx, $gender) {
        $direction = ($gender == 1) ? 1 : -1;
        // Hour 0 (Ty) starts at Nhat Han Index
        $idx = ($nhatHanIdx + ($hourIdx * $direction)) % 12;
        if ($idx < 0) $idx += 12;
        return $idx;
    }

    /**
     * Get Detailed Luu Stars for Month/Day (Optional expansion)
     */
    public static function getLuuStarsDetailed($canYear, $chiYear, $month, $day) {
        // Implement basics for Luu Stars dependent on time
        // This can be expanded.
        return [];
    }
}
