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

    // 14 Chinh Tinh
    public static $STARS = array(
        'tu_vi' => 'Tử Vi',
        'thien_co' => 'Thiên Cơ',
        'thai_duong' => 'Thái Dương',
        'vu_khuc' => 'Vũ Khúc',
        'thien_dong' => 'Thiên Đồng',
        'liem_trinh' => 'Liêm Trinh',
        'thien_phu' => 'Thiên Phủ',
        'thai_am' => 'Thái Âm',
        'tham_lang' => 'Tham Lang',
        'cu_mon' => 'Cự Môn',
        'thien_tuong' => 'Thiên Tướng',
        'thien_luong' => 'Thiên Lương',
        'that_sat' => 'Thất Sát',
        'pha_quan' => 'Phá Quân'
    );

    // Ngu Hanh Sao (1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc)
    public static $STAR_ELEMENTS = array(
        'tu_vi' => 3,       // Tho
        'thien_co' => 5,    // Moc
        'thai_duong' => 2,  // Hoa
        'vu_khuc' => 4,     // Kim
        'thien_dong' => 1,  // Thuy
        'liem_trinh' => 2,  // Hoa
        'thien_phu' => 3,   // Tho
        'thai_am' => 1,     // Thuy
        'tham_lang' => 1,   // Thuy (also Moc)
        'cu_mon' => 1,      // Thuy
        'thien_tuong' => 1, // Thuy
        'thien_luong' => 5, // Moc (also Tho)
        'that_sat' => 4,    // Kim
        'pha_quan' => 1     // Thuy
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
        $elId = isset(self::$STAR_ELEMENTS[$code]) ? self::$STAR_ELEMENTS[$code] : 0;
        // Map ID to color code for template
        // 1=Thuy (black/blue), 2=Hoa (red), 3=Tho (yellow), 4=Kim (gray/white), 5=Moc (green)
        $colors = [1 => 'thuy', 2 => 'hoa', 3 => 'tho', 4 => 'kim', 5 => 'moc'];

        return [
            'name' => isset(self::$STARS[$code]) ? self::$STARS[$code] : $code,
            'element_id' => $elId,
            'color' => isset($colors[$elId]) ? $colors[$elId] : 'default'
        ];
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
                'phu_tinh_xau' => array(),
                'tuan' => false,
                'triet' => false,
                'dai_van' => 0,
                'tieu_van' => '',
                'vong_trang_sinh' => ''
            );
        }

        // --- STEP 1: An 12 Cung (Dia Ban) ---
        // Menh: 2 + (Month - 1) - Hour
        $posMenh = (2 + ($mm - 1) - $hh) % 12;
        if ($posMenh < 0) $posMenh += 12;

        // Than: 2 + (Month - 1) + Hour
        $posThan = (2 + ($mm - 1) + $hh) % 12;

        $palaceNames = array(
            'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
            'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
        );

        for ($i = 0; $i < 12; $i++) {
            // CCW placement
            $pos = ($posMenh - $i) % 12;
            if ($pos < 0) $pos += 12;

            $chart[$pos]['palace_name'] = $palaceNames[$i];
            if ($pos == $posThan) {
                // If Body (Than) is here
                $chart[$pos]['palace_name'] .= ' (Thân)';
                $chart[$pos]['is_than'] = true;
            } else {
                $chart[$pos]['is_than'] = false;
            }
        }

        // --- STEP 2: Cuc & Dai Van ---
        // Can Dan
        $canDan = (($canYear % 5) + 1) * 2;
        if ($canDan >= 10) $canDan -= 10;

        $steps = $posMenh - 2;
        if ($steps < 0) $steps += 12;
        $canMenh = ($canDan + $steps) % 10;
        $chiMenh = $posMenh;

        $cucElement = FengShuiUtils::getNguHanhNapAm($canMenh, $chiMenh);
        $cucMap = array(1 => 2, 2 => 6, 3 => 5, 4 => 4, 5 => 3); // Thuy=2, Hoa=6, Tho=5, Kim=4, Moc=3
        $cuc = $cucMap[$cucElement];
        $cucNameMap = [2=>'Thủy Nhị Cục', 3=>'Mộc Tam Cục', 4=>'Kim Tứ Cục', 5=>'Thổ Ngũ Cục', 6=>'Hỏa Lục Cục'];

        // Dai Van
        // Duong Nam (1,1), Am Nu (0,0) -> Thuan (CW)
        // Am Nam (0,1), Duong Nu (1,0) -> Nghich (CCW)
        // Gender: 1=Male, 0=Female.
        // CanYear: Even=Duong (0,2..), Odd=Am (1,3..). Wait.
        // Can: 0=Giap(D), 1=At(A), 2=Binh(D), 3=Dinh(A)...
        // So CanYear % 2 == 0 is Duong, != 0 is Am.
        $isDuong = ($canYear % 2 == 0);
        $direction = 1; // 1=CW, -1=CCW

        if (($isDuong && $gender == 1) || (!$isDuong && $gender == 0)) {
            $direction = 1; // Thuan
        } else {
            $direction = -1; // Nghich
        }

        // Start from Menh
        for ($k = 0; $k < 12; $k++) {
            $age = $cuc + ($k * 10);
            $idx = ($posMenh + ($k * $direction)) % 12;
            if ($idx < 0) $idx += 12;
            $chart[$idx]['dai_van'] = $age;
        }


        // --- STEP 3: Tieu Van ---
        // Triad logic
        // Dan(2), Ngo(6), Tuat(10) -> Start Thin(4) (corresponds to Tuat)
        // Than(8), Ty(0), Thin(4) -> Start Tuat(10) (corresponds to Thin)
        // Hoi(11), Mao(3), Mui(7) -> Start Suu(1) (corresponds to Mui)
        // Ty(5), Dau(9), Suu(1) -> Start Mui(7) (corresponds to Suu)

        $startTVPalace = 0;
        $startTVYear = 0; // The Chi of the year associated with start palace

        if (in_array($chiYear, [2, 6, 10])) { $startTVPalace = 4; $startTVYear = 10; } // Tuat
        elseif (in_array($chiYear, [8, 0, 4])) { $startTVPalace = 10; $startTVYear = 4; } // Thin
        elseif (in_array($chiYear, [11, 3, 7])) { $startTVPalace = 1; $startTVYear = 7; } // Mui
        elseif (in_array($chiYear, [5, 9, 1])) { $startTVPalace = 7; $startTVYear = 1; } // Suu

        // Direction: Nam Thuan, Nu Nghich (Different from Dai Van?)
        // Rule: "Trai thuan, Gai nghich". Regardless of Am/Duong Year.
        $tvDirection = ($gender == 1) ? 1 : -1;

        // Fill 12 palaces with Year Labels
        for ($k = 0; $k < 12; $k++) {
            // Palace Index
            $idx = ($startTVPalace + ($k * $tvDirection)) % 12;
            if ($idx < 0) $idx += 12;

            // Year Branch Index (Always CW? No, sequence of years is always CW: Ty, Suu, Dan...)
            // Wait. "Tieu Van nam Ty o cung X". "Tieu Van nam Suu o cung Y".
            // If Tieu Van moves Thuan, then next palace is next year.
            // If Tieu Van moves Nghich, then next palace (CCW) is next year.
            // But usually we label the palace with the Year Name.
            // "Nam Nay la nam Thin". User looks for "Thin" label.
            // So: At StartTVPalace, place StartTVYear label.
            // Next label (StartTVYear + 1) is at Next Palace (StartTVPalace + Direction).

            $labelChi = ($startTVYear + $k) % 12; // Next year
            $palaceIdx = ($startTVPalace + ($k * $tvDirection)) % 12;
            if ($palaceIdx < 0) $palaceIdx += 12;

            $chart[$palaceIdx]['tieu_van'] = self::$DIA_CHI[$labelChi];
        }


        // --- STEP 4: Tuan / Triet ---
        // Triet: Can Year
        // 0/5(Giap/Ky) -> Than(8), Dau(9)
        // 1/6(At/Canh) -> Ngo(6), Mui(7)
        // 2/7(Binh/Tan) -> Thin(4), Ty(5) // Ty_Nho = 5 (Snake)
        // 3/8(Dinh/Nham) -> Dan(2), Mao(3)
        // 4/9(Mau/Quy) -> Ty(0), Suu(1) // Ty_Rat = 0

        $trietMap = [
            0 => [8,9], 5 => [8,9],
            1 => [6,7], 6 => [6,7],
            2 => [4,5], 7 => [4,5],
            3 => [2,3], 8 => [2,3],
            4 => [0,1], 9 => [0,1]
        ];
        foreach ($trietMap[$canYear % 10] as $p) {
            $chart[$p]['triet'] = true;
        }

        // Tuan: Decade
        // Diff = Chi - Can.
        // DecadeStart = (Chi - Can). If < 0 += 12.
        // Tuan at (Start + 10) and (Start + 11).
        $diff = $chiYear - ($canYear % 10);
        if ($diff < 0) $diff += 12;
        $tuan1 = ($diff + 10) % 12;
        $tuan2 = ($diff + 11) % 12;
        $chart[$tuan1]['tuan'] = true;
        $chart[$tuan2]['tuan'] = true;


        // --- STEP 5: An Sao ---
        // Same logic as before for Main Stars

        $posTuVi = 0;
        if ($dd % $cuc == 0) {
            $q = $dd / $cuc;
            $posTuVi = (2 + ($q - 1)) % 12;
        } else {
            $r = $dd % $cuc;
            $x = $cuc - $r;
            $q = ($dd + $x) / $cuc;
            $basePos = (2 + ($q - 1)) % 12;
            if ($x % 2 != 0) $posTuVi = ($basePos - $x) % 12;
            else $posTuVi = ($basePos + $x) % 12;
        }
        if ($posTuVi < 0) $posTuVi += 12;

        $addStar = function($pIdx, $code) use (&$chart) {
            $info = self::getStarInfo($code);
            $bright = self::getBrightness($code, $pIdx);
            $chart[$pIdx]['chinh_tinh'][] = array(
                'name' => $info['name'],
                'color' => $info['color'],
                'dacs' => $bright
            );
        };

        // Tu Vi
        $addStar($posTuVi, 'tu_vi');

        // Thien Phu
        $posThienPhu = (4 - $posTuVi + 12) % 12;
        $addStar($posThienPhu, 'thien_phu');

        // Vong Tu Vi (CCW)
        $offsetsTuVi = ['thien_co' => 1, 'thai_duong' => 3, 'vu_khuc' => 4, 'thien_dong' => 5, 'liem_trinh' => 8];
        foreach ($offsetsTuVi as $code => $offset) {
            $pos = ($posTuVi - $offset) % 12;
            if ($pos < 0) $pos += 12;
            $addStar($pos, $code);
        }

        // Vong Thien Phu (CW)
        $offsetsThienPhu = ['thai_am' => 1, 'tham_lang' => 2, 'cu_mon' => 3, 'thien_tuong' => 4, 'thien_luong' => 5, 'that_sat' => 6, 'pha_quan' => 10];
        foreach ($offsetsThienPhu as $code => $offset) {
            $pos = ($posThienPhu + $offset) % 12;
            $addStar($pos, $code);
        }

        // --- STEP 6: Thien Ban Info ---
        $thienBan = array(
            'ho_ten' => $name,
            'nam_sinh' => FengShuiUtils::$CAN[$canYear] . ' ' . FengShuiUtils::$CHI[$chiYear],
            'menh_ngu_hanh' => '', // Need Nam Sinh Nap Am? Or Menh Palace Nap Am? Usually "Mệnh: Hải Trung Kim" (Birth Year Nap Am)
            'cuc' => $cucNameMap[$cuc],
            'chu_menh' => 'Tham Lang', // Placeholder
            'chu_than' => 'Hỏa Tinh', // Placeholder
            'am_duong' => ($isDuong ? 'Dương' : 'Âm') . ' ' . ($gender==1 ? 'Nam' : 'Nữ'),
            'menh_color' => 'hoa' // Placeholder
        );

        // Calculate Ban Menh (Birth Year Nap Am)
        $banMenhEl = FengShuiUtils::getNguHanhNapAm($canYear, $chiYear);
        // Map 1..5 to Names
        $nhNames = [1=>'Thủy', 2=>'Hỏa', 3=>'Thổ', 4=>'Kim', 5=>'Mộc']; // IDs from Utils
        $thienBan['menh_ngu_hanh'] = isset($nhNames[$banMenhEl]) ? $nhNames[$banMenhEl] : 'Unknown';
        $thienBan['menh_color'] = isset([1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl]) ? [1=>'thuy', 2=>'hoa', 3=>'tho', 4=>'kim', 5=>'moc'][$banMenhEl] : 'default';

        return [
            'thien_ban' => $thienBan,
            'dia_ban' => $chart
        ];
    }
}
