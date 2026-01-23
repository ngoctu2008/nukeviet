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

    /**
     * Lap La So Tu Vi
     * @param int $dd Day (Lunar)
     * @param int $mm Month (Lunar)
     * @param int $yyyy Year (Lunar)
     * @param int $hh Hour (Lunar - 0-11, where 0=Ty, 1=Suu...)
     * @param int $gender 1=Male, 0=Female (Not used for Main Stars but needed for Dai Han - Phase 2)
     * @param int $canYear Can of the Year (0=Giap...9=Quy)
     * @return array La So Data
     */
    public static function lapLaSo($dd, $mm, $yyyy, $hh, $gender, $canYear) {
        $chart = array();
        for ($i = 0; $i < 12; $i++) {
            $chart[$i] = array(
                'index' => $i,
                'name' => self::$DIA_CHI[$i],
                'palace_name' => '', // Menh, Phu Mau...
                'stars' => array()
            );
        }

        // --- STEP 1: An 12 Cung (Dia Ban) ---
        // Menh: Month - Hour + 1. (Start from Dan=2)
        // Adjust formula for 0-based index (Ty=0).
        // Standard: Start at Dan (2). Move CW Month steps? No.
        // Rule: "Khởi từ Dần, thuận đến tháng sinh, nghịch đến giờ sinh."
        // Pos = 2 + (Month - 1) - (Hour - 1). (Note: Hour in TuVi usually 1=Ty, but here input $hh is 0..11?)
        // Let's assume input $hh: 0=Ty, 1=Suu...
        // So: Pos = 2 + (Month - 1) - $hh.
        // Normalize to 0-11.
        $posMenh = (2 + ($mm - 1) - $hh) % 12;
        if ($posMenh < 0) $posMenh += 12;

        // Than: Month + Hour - 1?
        // Rule: "Khởi từ Dần, thuận đến tháng sinh, thuận đến giờ sinh."
        // Pos = 2 + (Month - 1) + $hh.
        $posThan = (2 + ($mm - 1) + $hh) % 12;

        // Place 12 Palaces (Menh, Phu, Phuc...)
        // User Instruction: "An 12 cung: Nghịch chiều kim đồng hồ."
        // List: Mệnh, Phụ Mẫu, Phúc Đức, Điền Trạch, Quan Lộc, Nô Bộc, Thiên Di, Tật Ách, Tài Bạch, Tử Tức, Phu Thê, Huynh Đệ.
        // (Note: Standard sequence for CCW placement)
        $palaceNames = array(
            'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
            'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
        );

        for ($i = 0; $i < 12; $i++) {
            // CCW placement: Mệnh at posMenh. Next is posMenh - 1.
            $pos = ($posMenh - $i) % 12;
            if ($pos < 0) $pos += 12;
            $chart[$pos]['palace_name'] = $palaceNames[$i];
            if ($pos == $posThan) {
                $chart[$pos]['palace_name'] .= ' (Thân)'; // Mark Than cư...
            }
        }

        // --- STEP 2: Tinh Cuc ---
        // 1. Determine Can of the "Mệnh" Palace.
        // Formula: CanYear -> Can of Dan (Month 1).
        // CanOfDan = (CanYear % 5 + 1) * 2. (0=Giap...9=Quy).
        // Count from Dan (2) to posMenh.
        // Distance from Dan: dist = posMenh - 2.
        $canDan = (($canYear % 5) + 1) * 2;
        if ($canDan >= 10) $canDan -= 10;

        // We need to count from Dan to posMenh.
        // PosMenh index (0..11). Dan is 2.
        // Steps = posMenh - 2.
        $steps = $posMenh - 2;
        if ($steps < 0) $steps += 12; // e.g. Ty(0) -> 10 steps from Dan.

        $canMenh = ($canDan + $steps) % 10;
        $chiMenh = $posMenh; // Mệnh is at this Earth Branch.

        // 2. Nap Am (CanMenh, ChiMenh) -> Cuc.
        // Using FengShuiUtils::getNguHanhNapAm ($can, $chi).
        // Returns 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc.
        $cucElement = FengShuiUtils::getNguHanhNapAm($canMenh, $chiMenh);

        // Map Element to Cuc Number
        // Thuy (1) -> Thuy Nhi Cuc (2)
        // Hoa (2) -> Hoa Luc Cuc (6)
        // Tho (3) -> Tho Ngu Cuc (5)
        // Kim (4) -> Kim Tu Cuc (4)
        // Moc (5) -> Moc Tam Cuc (3)
        $cucMap = array(
            1 => 2, // Thuy 2
            2 => 6, // Hoa 6
            3 => 5, // Tho 5
            4 => 4, // Kim 4
            5 => 3  // Moc 3
        );
        $cuc = $cucMap[$cucElement];

        // Debug info
        $chart['info'] = array(
            'cuc' => $cuc,
            'cuc_name' => FengShuiUtils::getNguHanhName($cucElement) . " " . $cuc . " Cục"
        );


        // --- STEP 3: An Tu Vi ---
        // Input: $cuc, $dd (Lunar Day).
        // Algorithm:
        // if d % c == 0: pos = d/c. Start Dan(2), move CW (pos-1).
        // if d % c != 0: r = d%c. x = c-r. pos = (d+x)/c. Start Dan, move CW (pos-1).
        //                Then move X steps: Odd->CCW, Even->CW.

        $posTuVi = 0;
        if ($dd % $cuc == 0) {
            $q = $dd / $cuc;
            $posTuVi = (2 + ($q - 1)) % 12; // 2 is Dan
        } else {
            $r = $dd % $cuc;
            $x = $cuc - $r;
            $q = ($dd + $x) / $cuc;
            $basePos = (2 + ($q - 1)) % 12;

            if ($x % 2 != 0) {
                // Odd -> CCW
                $posTuVi = ($basePos - $x) % 12;
            } else {
                // Even -> CW
                $posTuVi = ($basePos + $x) % 12;
            }
        }
        if ($posTuVi < 0) $posTuVi += 12;

        $chart[$posTuVi]['stars'][] = array('code' => 'tu_vi', 'name' => self::$STARS['tu_vi'], 'type' => 1);

        // --- STEP 4: An Thien Phu ---
        // Symmetric across Dan-Than (2-8).
        // Formula: PosTP = (4 - PosTV + 12) % 12.
        $posThienPhu = (4 - $posTuVi + 12) % 12;
        $chart[$posThienPhu]['stars'][] = array('code' => 'thien_phu', 'name' => self::$STARS['thien_phu'], 'type' => 1);


        // --- STEP 5: An 13 Chinh Tinh ---

        // 1. Vong Tu Vi (CCW)
        // Tu Vi (0)
        // Thien Co (1) - Next CCW
        // Thai Duong (3)
        // Vu Khuc (4)
        // Thien Dong (5)
        // Liem Trinh (8)

        $offsetsTuVi = array(
            'thien_co' => 1,
            'thai_duong' => 3,
            'vu_khuc' => 4,
            'thien_dong' => 5,
            'liem_trinh' => 8
        );

        foreach ($offsetsTuVi as $code => $offset) {
            $pos = ($posTuVi - $offset) % 12;
            if ($pos < 0) $pos += 12;
            $chart[$pos]['stars'][] = array('code' => $code, 'name' => self::$STARS[$code], 'type' => 1);
        }

        // 2. Vong Thien Phu (CW)
        // Thien Phu (0)
        // Thai Am (1)
        // Tham Lang (2)
        // Cu Mon (3)
        // Thien Tuong (4)
        // Thien Luong (5)
        // That Sat (6)
        // Pha Quan (10)

        $offsetsThienPhu = array(
            'thai_am' => 1,
            'tham_lang' => 2,
            'cu_mon' => 3,
            'thien_tuong' => 4,
            'thien_luong' => 5,
            'that_sat' => 6,
            'pha_quan' => 10
        );

        foreach ($offsetsThienPhu as $code => $offset) {
            $pos = ($posThienPhu + $offset) % 12;
            $chart[$pos]['stars'][] = array('code' => $code, 'name' => self::$STARS[$code], 'type' => 1);
        }

        return $chart;
    }
}
