<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class FengShuiUtils {

    // Utility class for Feng Shui calculations (Can, Chi, Ngu Hanh, Bat Trach)

    // Can: 0=Giáp, 1=Ất, ... 9=Quý
    public static $CAN = array('Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý');

    // Chi: 0=Tý, 1=Sửu, ... 11=Hợi
    public static $CHI = array('Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi');

    // Ngu Hanh: 1=Thủy, 2=Hỏa, 3=Thổ, 4=Kim, 5=Mộc (Order requested by user)
    public static $NGU_HANH = array(1 => 'Thủy', 2 => 'Hỏa', 3 => 'Thổ', 4 => 'Kim', 5 => 'Mộc');

    // Cung Phi: 1=Khảm, 2=Khôn, 3=Chấn, 4=Tốn, 5=TrungCung, 6=Càn, 7=Đoài, 8=Cấn, 9=Ly
    public static $CUNG_PHI = array(
        1 => 'Khảm', 2 => 'Khôn', 3 => 'Chấn', 4 => 'Tốn',
        5 => 'Trung Cung', // Usually mapped to Khon(Male)/Can(Female)
        6 => 'Càn', 7 => 'Đoài', 8 => 'Cấn', 9 => 'Ly'
    );

    /**
     * Calculate Ngu Hanh Nap Am based on Can and Chi
     */
    public static function getNguHanhNapAm($can, $chi) {
        $can = intval($can) % 10;
        $chi = intval($chi) % 12;

        // Can val: Giap/At=1, Binh/Dinh=2, Mau/Ky=3, Canh/Tan=4, Nham/Quy=5
        $canVal = floor($can / 2) + 1;

        // Chi val: Ty/Suu/Ngo/Mui=0, Dan/Mao/Than/Dau=1, Thin/Ty/Tuat/Hoi=2
        $chiVal = 0;
        if (in_array($chi, [0, 1, 6, 7])) $chiVal = 0;
        elseif (in_array($chi, [2, 3, 8, 9])) $chiVal = 1;
        elseif (in_array($chi, [4, 5, 10, 11])) $chiVal = 2;

        $sum = $canVal + $chiVal;
        if ($sum > 5) $sum -= 5;

        // Result: 1=Kim, 2=Thuy, 3=Hoa, 4=Tho, 5=Moc
        // Map to User ID: 1-Thủy, 2-Hỏa, 3-Thổ, 4-Kim, 5-Mộc
        switch ($sum) {
            case 1: return 4; // Kim -> 4
            case 2: return 1; // Thuy -> 1
            case 3: return 2; // Hoa -> 2
            case 4: return 3; // Tho -> 3
            case 5: return 5; // Moc -> 5
            default: return 0;
        }
    }

    public static function getNguHanhName($id) {
        return isset(self::$NGU_HANH[$id]) ? self::$NGU_HANH[$id] : 'Unknown';
    }

    /**
     * Calculate Cung Phi (Bat Trach)
     * @param int $year (Solar Year e.g. 1990)
     * @param int $gender (1=Male, 0=Female)
     * @return int Cung ID (1-9)
     */
    public static function getCungPhi($year, $gender) {
        $sum = 0;
        $y = $year;
        while ($y > 0) {
            $sum += $y % 10;
            $y = floor($y / 10);
        }
        // Reduce to single digit
        while ($sum > 9) {
            $s = 0;
            $temp = $sum;
            while ($temp > 0) { $s += $temp % 10; $temp = floor($temp / 10); }
            $sum = $s;
        }

        $cung = 0;
        // Formula depends on Century?
        // Standard simplified formula for 1900-2099:
        // Or simply:
        // Sum digits of year.
        // 19xx: Male = 10 - Sum. Female = 5 + Sum.
        // 20xx: Male = 9 - Sum. Female = 6 + Sum.

        $century = floor($year / 100);
        if ($century == 19) {
            if ($gender == 1) $cung = 10 - $sum;
            else $cung = 5 + $sum;
        } else { // 20xx
            if ($gender == 1) $cung = 9 - $sum;
            else $cung = 6 + $sum;
        }

        if ($cung > 9) $cung -= 9;
        if ($cung <= 0) $cung += 9; // Should not happen with modulo logic usually

        // Special Case 5: Male -> 2 (Khon), Female -> 8 (Can)
        if ($cung == 5) {
            return ($gender == 1) ? 2 : 8;
        }

        return $cung;
    }

    public static function getCungName($id) {
        return isset(self::$CUNG_PHI[$id]) ? self::$CUNG_PHI[$id] : '';
    }

    /**
     * Get Bat Trach Relation
     * Groups: Dong Tu Menh (1,3,4,9), Tay Tu Menh (2,6,7,8)
     */
    public static function getBatTrachRelation($cung1, $cung2) {
        // Table of 8 Stars (4 Good, 4 Bad)
        // 1=Sinh Khi, 2=Thien Y, 3=Dien Nien, 4=Phuc Vi
        // 5=Tuyet Menh, 6=Ngu Quy, 7=Luc Sat, 8=Hoa Hai

        // Matrix (Row Cung1, Col Cung2)
        // 1:Kham, 2:Khon, 3:Chan, 4:Ton, 6:Can, 7:Doai, 8:Can, 9:Ly
        $matrix = [
            1 => [1=>4, 2=>5, 3=>2, 4=>1, 6=>7, 7=>8, 8=>6, 9=>3], // Kham vs ...
            2 => [1=>5, 2=>4, 3=>8, 4=>6, 6=>3, 7=>2, 8=>1, 9=>7], // Khon vs ...
            3 => [1=>2, 2=>8, 3=>4, 4=>3, 6=>6, 7=>5, 8=>7, 9=>1], // Chan vs ...
            4 => [1=>1, 2=>6, 3=>3, 4=>4, 6=>8, 7=>7, 8=>5, 9=>2], // Ton vs ...
            6 => [1=>7, 2=>3, 3=>6, 4=>8, 6=>4, 7=>1, 8=>2, 9=>5], // Can vs ...
            7 => [1=>8, 2=>2, 3=>5, 4=>7, 6=>1, 7=>4, 8=>3, 9=>6], // Doai vs ...
            8 => [1=>6, 2=>1, 3=>7, 4=>5, 6=>2, 7=>3, 8=>4, 9=>8], // Can vs ...
            9 => [1=>3, 2=>7, 3=>1, 4=>2, 6=>5, 7=>6, 8=>8, 9=>4], // Ly vs ...
        ];

        $resId = isset($matrix[$cung1][$cung2]) ? $matrix[$cung1][$cung2] : 0;

        $names = [
            1 => ['name' => 'Sinh Khí', 'type' => 'good', 'score' => 10],
            2 => ['name' => 'Thiên Y', 'type' => 'good', 'score' => 8],
            3 => ['name' => 'Diên Niên (Phúc Đức)', 'type' => 'good', 'score' => 7],
            4 => ['name' => 'Phục Vị', 'type' => 'good', 'score' => 6],
            5 => ['name' => 'Tuyệt Mệnh', 'type' => 'bad', 'score' => 0],
            6 => ['name' => 'Ngũ Quỷ', 'type' => 'bad', 'score' => 2],
            7 => ['name' => 'Lục Sát', 'type' => 'bad', 'score' => 3],
            8 => ['name' => 'Họa Hại', 'type' => 'bad', 'score' => 4],
        ];

        return isset($names[$resId]) ? $names[$resId] : ['name' => 'Không xác định', 'score' => 5];
    }
}
