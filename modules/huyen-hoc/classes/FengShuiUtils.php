<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class FengShuiUtils {

    // Can: 0=Giáp, 1=Ất, ... 9=Quý
    public static $CAN = array('Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý');

    // Chi: 0=Tý, 1=Sửu, ... 11=Hợi
    public static $CHI = array('Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi');

    // Ngu Hanh: 1=Thủy, 2=Hỏa, 3=Thổ, 4=Kim, 5=Mộc (Order requested by user)
    public static $NGU_HANH = array(1 => 'Thủy', 2 => 'Hỏa', 3 => 'Thổ', 4 => 'Kim', 5 => 'Mộc');

    /**
     * Calculate Ngu Hanh Nap Am based on Can and Chi
     * Logic requested: 1-Thủy, 2-Hỏa, 3-Thổ, 4-Kim, 5-Mộc
     *
     * Formula (common Nap Am calculation):
     * Can value: Giap/At=1, Binh/Dinh=2, Mau/Ky=3, Canh/Tan=4, Nham/Quy=5
     * Chi value: Ty/Suu/Ngo/Mui=0, Dan/Mao/Than/Dau=1, Thin/Ty/Tuat/Hoi=2
     * Sum = Can + Chi. If Sum > 5, Sum -= 5.
     * Result map: 1=Kim, 2=Thuy, 3=Hoa, 4=Tho, 5=Moc.
     *
     * BUT User requested specific mapping ID: 1-Thủy, 2-Hỏa, 3-Thổ, 4-Kim, 5-Mộc.
     * So I need to map the standard result to the user's IDs.
     *
     * Standard Result -> User ID:
     * Kim (1) -> 4
     * Thuy (2) -> 1
     * Hoa (3) -> 2
     * Tho (4) -> 3
     * Moc (5) -> 5
     */
    public static function getNguHanhNapAm($can, $chi) {
        // Normalize inputs
        $can = intval($can) % 10;
        $chi = intval($chi) % 12;

        // Can values for calculation
        // Giap(0), At(1) -> 1
        // Binh(2), Dinh(3) -> 2
        // Mau(4), Ky(5) -> 3
        // Canh(6), Tan(7) -> 4
        // Nham(8), Quy(9) -> 5
        $canVal = floor($can / 2) + 1;

        // Chi values for calculation
        // Ty(0), Suu(1), Ngo(6), Mui(7) -> 0
        // Dan(2), Mao(3), Than(8), Dau(9) -> 1
        // Thin(4), Ty(5), Tuat(10), Hoi(11) -> 2
        $chiVal = 0;
        if (in_array($chi, [0, 1, 6, 7])) $chiVal = 0;
        elseif (in_array($chi, [2, 3, 8, 9])) $chiVal = 1;
        elseif (in_array($chi, [4, 5, 10, 11])) $chiVal = 2;

        $sum = $canVal + $chiVal;
        if ($sum > 5) $sum -= 5;

        // Standard Result: 1=Kim, 2=Thuy, 3=Hoa, 4=Tho, 5=Moc
        // User Requirement: 1-Thủy, 2-Hỏa, 3-Thổ, 4-Kim, 5-Mộc

        switch ($sum) {
            case 1: return 4; // Kim
            case 2: return 1; // Thuy
            case 3: return 2; // Hoa
            case 4: return 3; // Tho
            case 5: return 5; // Moc
            default: return 0;
        }
    }

    /**
     * Helper to get Element Name
     */
    public static function getNguHanhName($id) {
        return isset(self::$NGU_HANH[$id]) ? self::$NGU_HANH[$id] : 'Unknown';
    }
}
