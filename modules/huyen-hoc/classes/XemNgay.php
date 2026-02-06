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
        0 => array(0, 1, 3, 5, 7, 9), // Dan, Thin, Ty, Mui, Tuat, Ty (Wait, let's use standard table)
        // Ty (Ngay): Ty, Suu, Mao, Ngo, Than, Dau
        // Suu (Ngay): Dan, Mao, Ty, Than, Tuat, Hoi
        // Standard Reference:
        // Dan/Than: Ty, Suu, Thin, Ty, Mui, Tuat (0, 1, 4, 5, 7, 10)
        // Mao/Dau: Ty, Ngo, Mui, Dau (Note: Hoang Dao hours are 6)
        // Let's use a standard algorithmic approach or full map.

        // Map: Day Chi -> List of Good Hour Chis
        // Dan (2), Than (8): Ty (0), Suu (1), Thin (4), Ty (5), Mui (7), Tuat (10)
        2 => [0, 1, 4, 5, 7, 10],
        8 => [0, 1, 4, 5, 7, 10],

        // Mao (3), Dau (9): Ty (0), Dần (2), Mão (3), Ngọ (6), Mùi (7), Dậu (9)
        3 => [0, 2, 3, 6, 7, 9],
        9 => [0, 2, 3, 6, 7, 9],

        // Thin (4), Tuat (10): Dần (2), Thìn (4), Tỵ (5), Thân (8), Dậu (9), Hợi (11)
        4 => [2, 4, 5, 8, 9, 11],
        10 => [2, 4, 5, 8, 9, 11],

        // Ty (5), Hoi (11): Sửu (1), Thìn (4), Ngọ (6), Mùi (7), Tuất (10), Hợi (11)
        5 => [1, 4, 6, 7, 10, 11],
        11 => [1, 4, 6, 7, 10, 11],

        // Ty (0), Ngo (6): Tý (0), Sửu (1), Mão (3), Ngọ (6), Thân (8), Dậu (9)
        0 => [0, 1, 3, 6, 8, 9],
        6 => [0, 1, 3, 6, 8, 9],

        // Suu (1), Mui (7): Dần (2), Mão (3), Tỵ (5), Thân (8), Tuất (10), Hợi (11)
        1 => [2, 3, 5, 8, 10, 11],
        7 => [2, 3, 5, 8, 10, 11]
    );

    // 12 Truc (Kien, Tru, Man, Binh, Dinh, Chap, Pha, Nguy, Thanh, Thu, Khai, Be)
    public static $TRUC = ['Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế'];

    /**
     * Get Gio Hoang Dao for a given Day Chi
     */
    public static function getGioHoangDao($dayChi) {
        $dayChi = intval($dayChi) % 12;
        return isset(self::$GIO_HOANG_DAO[$dayChi]) ? self::$GIO_HOANG_DAO[$dayChi] : [];
    }

    /**
     * Check good/bad day
     */
    public static function checkNgayTot($lunarDay, $lunarMonth, $lunarYear, $dayChi) {
        $comments = [];
        $isBad = false;

        // 1. Nguyet Ky (5, 14, 23)
        if (in_array($lunarDay, [5, 14, 23])) {
            $comments[] = "Ngày Nguyệt Kỵ (Xấu, kỵ đi xa, khởi công).";
            $isBad = true;
        }

        // 2. Tam Nuong (3, 7, 13, 18, 22, 27)
        if (in_array($lunarDay, [3, 7, 13, 18, 22, 27])) {
            $comments[] = "Ngày Tam Nương (Xấu, kỵ cưới hỏi, khai trương).";
            $isBad = true;
        }

        // 3. Truc (12 Officers)
        // Rule: Month 1 -> Start Kien at Dan(2). Month 2 -> Kien at Mao(3).
        // Index Kien = (Month + 1) % 12.
        // Truc of Day = (DayChi - StartKienIndex + 12) % 12.
        $startKien = ($lunarMonth + 1) % 12; // Index of Day Chi where Kien starts
        $diff = ($dayChi - $startKien + 12) % 12;
        $trucName = self::$TRUC[$diff];

        // 4. Hoang Dao / Hac Dao (Day)
        // Rule depends on Month.
        // Month 1, 7: Ty (Hac), Suu (Hoang)...
        // Detailed map required. For skeleton, use simplified check or random.
        // Let's implement full map for accuracy.
        // Key: Month 1-12. Value: List of Hoang Dao Day Chi.
        // Thang 1, 7: Ty, Suu, Thin, Ty, Mui, Tuat. (Same as Gio Hoang Dao pattern?)
        // Actually often related.
        // Let's assume generic Hoang Dao for now or "Binh Thuong" if not strict.

        return array(
            'is_good' => !$isBad,
            'comment' => empty($comments) ? "Ngày bình thường." : implode(' ', $comments),
            'truc' => $trucName,
            'sao' => 'Đang cập nhật' // 28 Tu requires JD
        );
    }
}
