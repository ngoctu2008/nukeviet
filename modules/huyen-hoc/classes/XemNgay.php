<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class XemNgay {

    // Gio Hoang Dao Map (Chi cua Ngay -> Array of Gio Hoang Dao Chi)
    // 0=Ty, 1=Suu, 2=Dan, 3=Mao, 4=Thin, 5=Ty, 6=Ngo, 7=Mui, 8=Than, 9=Dau, 10=Tuat, 11=Hoi
    public static $GIO_HOANG_DAO = array(
        0 => [0, 1, 3, 6, 8, 9],
        1 => [2, 3, 5, 8, 10, 11],
        2 => [0, 1, 4, 5, 7, 10],
        3 => [0, 2, 3, 6, 7, 9],
        4 => [2, 4, 5, 8, 9, 11],
        5 => [1, 4, 6, 7, 10, 11],
        6 => [0, 1, 3, 6, 8, 9],
        7 => [2, 3, 5, 8, 10, 11],
        8 => [0, 1, 4, 5, 7, 10],
        9 => [0, 2, 3, 6, 7, 9],
        10 => [2, 4, 5, 8, 9, 11],
        11 => [1, 4, 6, 7, 10, 11]
    );

    // 12 Truc (Kien, Tru, Man, Binh, Dinh, Chap, Pha, Nguy, Thanh, Thu, Khai, Be)
    public static $TRUC = ['Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế'];

    // Sát Chủ (Month 1-12 -> Bad Day Chi)
    public static $SAT_CHU = [
        1 => 5, 2 => 0, 3 => 7, 4 => 3, 5 => 8, 6 => 10,
        7 => 11, 8 => 1, 9 => 6, 10 => 9, 11 => 2, 12 => 4
    ];

    // Thọ Tử (Month 1-12 -> Bad Day Chi)
    public static $THO_TU = [
        1 => 10, 2 => 4, 3 => 11, 4 => 5, 5 => 0, 6 => 6,
        7 => 1, 8 => 7, 9 => 2, 10 => 8, 11 => 3, 12 => 9
    ];

    /**
     * Get Gio Hoang Dao for a given Day Chi
     */
    public static function getGioHoangDao($dayChi) {
        $dayChi = intval($dayChi) % 12;
        return isset(self::$GIO_HOANG_DAO[$dayChi]) ? self::$GIO_HOANG_DAO[$dayChi] : [];
    }

    /**
     * Get Truc of the day
     */
    public static function getTruc($month, $dayChi) {
        $startKien = ($month + 1) % 12;
        $diff = ($dayChi - $startKien + 12) % 12;
        return self::$TRUC[$diff];
    }

    /**
     * Check good/bad day (Generic)
     */
    public static function checkNgayTot($lunarDay, $lunarMonth, $lunarYear, $dayChi) {
        return self::checkNgayTotTheoMucDich($lunarDay, $lunarMonth, $lunarYear, $dayChi, 'generic');
    }

    /**
     * Check good/bad day based on Purpose
     * @param string $purpose 'cuoi_hoi', 'khai_truong', 'dong_tho', 'xuat_hanh', 'generic'
     * @param int $birthYear (Optional)
     */
    public static function checkNgayTotTheoMucDich($lunarDay, $lunarMonth, $lunarYear, $dayChi, $purpose, $birthYear = null) {
        $comments = [];
        $isBad = false;

        // 1. Tam Nuong (All purposes)
        if (in_array($lunarDay, [3, 7, 13, 18, 22, 27])) {
            $comments[] = "Ngày Tam Nương (Xấu mọi việc).";
            $isBad = true;
        }

        // 2. Nguyet Ky (All purposes, esp Travel/Wedding)
        if (in_array($lunarDay, [5, 14, 23])) {
            $comments[] = "Ngày Nguyệt Kỵ (Xấu đi xa, cưới hỏi).";
            if ($purpose != 'generic') $isBad = true;
        }

        // 3. Sat Chu
        if (isset(self::$SAT_CHU[$lunarMonth]) && self::$SAT_CHU[$lunarMonth] == $dayChi) {
            $comments[] = "Ngày Sát Chủ (Đại kỵ).";
            $isBad = true;
        }

        // 4. Tho Tu
        if (isset(self::$THO_TU[$lunarMonth]) && self::$THO_TU[$lunarMonth] == $dayChi) {
            $comments[] = "Ngày Thọ Tử (Xấu mọi việc).";
            $isBad = true;
        }

        // 5. Age-based checks (Kim Lau, Hoang Oc) for Dong Tho / Cuoi Hoi
        if ($birthYear && ($purpose == 'dong_tho' || $purpose == 'cuoi_hoi' || $purpose == 'lam_nha')) {
             $age = $lunarYear - $birthYear + 1;

             // Kim Lau (Dong Tho, Cuoi Hoi)
             if (self::isKimLau($age)) {
                 $comments[] = "Phạm Kim Lâu (Tuổi $age - Kỵ làm nhà/cưới hỏi).";
                 $isBad = true;
             }

             // Hoang Oc (Dong Tho / Lam Nha)
             if (($purpose == 'dong_tho' || $purpose == 'lam_nha') && self::isHoangOc($age)) {
                 $comments[] = "Phạm Hoang Ốc (Tuổi $age - Kỵ làm nhà).";
                 $isBad = true;
             }

             // Tam Tai (Optional check, requires full lookup, simplified here)
             // ...
        }

        // 6. Purpose Specifics
        if ($purpose == 'khai_truong') {
             // Prefer Truc: Man, Thanh, Khai
             // Avoid Truc: Pha, Nguy, Be
             $truc = self::getTruc($lunarMonth, $dayChi);
             if (in_array($truc, ['Phá', 'Nguy', 'Bế'])) {
                 $comments[] = "Trực $truc (Không tốt cho khai trương).";
                 // Not strictly bad, but warning.
             }
        }

        $truc = self::getTruc($lunarMonth, $dayChi);

        return array(
            'is_good' => !$isBad,
            'comment' => empty($comments) ? "Ngày tốt/bình thường." : implode(' ', $comments),
            'truc' => $truc,
            'sao' => 'Đang cập nhật',
            'details' => $comments
        );
    }

    public static function isKimLau($age) {
        $rem = $age % 9;
        return in_array($rem, [1, 3, 6, 8]); // 1: Than, 3: The, 6: Tu, 8: Luc Suc
    }

    public static function isHoangOc($age) {
        // Simple Hoang Oc Calculation
        // Start: 10(1), 20(2), 30(3), 40(4), 50(5), 60(6)
        // 1: Cat, 2: Nghi, 3: Dia Sat (Bad), 4: Tan Tai (Cat), 5: Tho Tu (Bad), 6: Hoang Oc (Bad)
        // Bad: 3, 5, 6. Good: 1, 2, 4.

        $tens = floor($age / 10);
        $units = $age % 10;

        if ($tens == 0) $tens = 1; // 1-9 starts at 1

        // Determine start node based on tens
        // 10 -> Node 1. 20 -> Node 2. 30 -> Node 3.
        // If age < 10? Start at 1.

        $startNode = $tens;
        if ($startNode > 6) $startNode = ($startNode - 1) % 6 + 1; // Wrap 7->1?
        // Actually: 10->1, 20->2, 30->3, 40->4, 50->5, 60->6, 70->1...

        // Count units
        $currentNode = $startNode;
        for ($i = 0; $i < $units; $i++) { // If 30 (units=0), loop 0 times? No.
             // If age 30: Start at 3. Count 0? Result 3.
             // If age 31: Start at 3. Count 1? Node 4.
             // Wait. Logic: 30 is Tam Dia Sat. 31 is Tu Tan Tai.
             // So if units > 0, we increment.
             // Loop logic:
             $currentNode++;
             if ($currentNode > 6) $currentNode = 1;
        }
        // Adjustment: Since we started at $startNode which accounts for the first year of the decade?
        // Example: Age 30. Tens=3. Start=3. Units=0. Loop doesn't run. Result 3 (Bad). Correct.
        // Example: Age 31. Tens=3. Start=3. Units=1. Loop runs once. Result 4 (Good). Correct.
        // Example: Age 33. Tens=3. Start=3. Units=3. Loop 3 times. 3->4->5->6. Result 6 (Bad). Correct.

        return in_array($currentNode, [3, 5, 6]);
    }
}
