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

    // Dong Cong (Month 1-12)
    // 0=Ty... 11=Hoi. Values: 1=Tot, -1=Xau, 0=BinhThuong (or omitted)
    public static $DONG_CONG = [
        1 => [0=>-1, 1=>1, 2=>0, 3=>1, 4=>1, 5=>1, 6=>-1, 7=>1, 8=>0, 9=>0, 10=>0, 11=>-1], // Thang 1
        2 => [0=>0, 1=>-1, 2=>1, 3=>1, 4=>-1, 5=>0, 6=>0, 7=>1, 8=>1, 9=>0, 10=>1, 11=>1],
        3 => [0=>1, 1=>1, 2=>0, 3=>-1, 4=>1, 5=>1, 6=>1, 7=>-1, 8=>1, 9=>1, 10=>0, 11=>0],
        4 => [0=>0, 1=>1, 2=>1, 3=>1, 4=>-1, 5=>-1, 6=>0, 7=>0, 8=>0, 9=>1, 10=>1, 11=>0],
        5 => [0=>-1, 1=>-1, 2=>1, 3=>1, 4=>1, 5=>-1, 6=>0, 7=>1, 8=>0, 9=>1, 10=>0, 11=>0],
        6 => [0=>0, 1=>0, 2=>0, 3=>1, 4=>1, 5=>0, 6=>-1, 7=>-1, 8=>1, 9=>0, 10=>1, 11=>1],
        7 => [0=>1, 1=>1, 2=>0, 3=>0, 4=>0, 5=>1, 6=>0, 7=>-1, 8=>-1, 9=>1, 10=>0, 11=>0],
        8 => [0=>1, 1=>0, 2=>0, 3=>0, 4=>1, 5=>1, 6=>0, 7=>0, 8=>-1, 9=>-1, 10=>1, 11=>1],
        9 => [0=>0, 1=>1, 2=>1, 3=>0, 4=>0, 5=>0, 6=>1, 7=>0, 8=>1, 9=>0, 10=>-1, 11=>-1],
        10 => [0=>-1, 1=>-1, 2=>0, 3=>1, 4=>0, 5=>1, 6=>1, 7=>0, 8=>0, 9=>0, 10=>0, 11=>0],
        11 => [0=>0, 1=>1, 2=>1, 3=>-1, 4=>-1, 5=>0, 6=>0, 7=>0, 8=>0, 9=>1, 10=>0, 11=>0],
        12 => [0=>0, 1=>0, 2=>0, 3=>0, 4=>1, 5=>-1, 6=>-1, 7=>0, 8=>1, 9=>1, 10=>1, 11=>0],
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

        // 5. Age-based checks (Kim Lau, Hoang Oc, Tam Tai) for specific purposes
        $ageAnalysis = [];
        if ($birthYear) {
             $age = $lunarYear - $birthYear + 1;

             // Kim Lau (Dong Tho, Cuoi Hoi, Lam Nha)
             if ($purpose == 'dong_tho' || $purpose == 'cuoi_hoi' || $purpose == 'lam_nha') {
                 if (self::isKimLau($age)) {
                     $msg = "Phạm Kim Lâu (Tuổi $age - Kỵ làm nhà/cưới hỏi).";
                     $comments[] = $msg;
                     $ageAnalysis[] = ['type' => 'kim_lau', 'msg' => $msg, 'bad' => true];
                     $isBad = true;
                 } else {
                     $ageAnalysis[] = ['type' => 'kim_lau', 'msg' => "Không phạm Kim Lâu.", 'bad' => false];
                 }
             }

             // Hoang Oc (Dong Tho / Lam Nha)
             if ($purpose == 'dong_tho' || $purpose == 'lam_nha') {
                 if (self::isHoangOc($age)) {
                     $msg = "Phạm Hoang Ốc (Tuổi $age - Kỵ làm nhà).";
                     $comments[] = $msg;
                     $ageAnalysis[] = ['type' => 'hoang_oc', 'msg' => $msg, 'bad' => true];
                     $isBad = true;
                 } else {
                     $ageAnalysis[] = ['type' => 'hoang_oc', 'msg' => "Không phạm Hoang Ốc.", 'bad' => false];
                 }
             }

             // Tam Tai (All purposes generally, but strictly for major events)
             if ($purpose != 'generic') {
                 if (self::isTamTai($age, $lunarYear)) {
                     $msg = "Phạm Tam Tai (Năm nay xấu với tuổi).";
                     $comments[] = $msg;
                     $ageAnalysis[] = ['type' => 'tam_tai', 'msg' => $msg, 'bad' => true];
                     // Tam Tai is not always a blocker for all days, but adds negative weight.
                     // Marking as bad for simplicity in this strict mode.
                     $isBad = true;
                 } else {
                     $ageAnalysis[] = ['type' => 'tam_tai', 'msg' => "Không phạm Tam Tai.", 'bad' => false];
                 }
             }
        }

        // 6. Dong Cong
        $dcCheck = self::checkDongCong($lunarMonth, $dayChi);
        if ($dcCheck == 1) $comments[] = "Đổng Công: Rất Tốt (Đại Cát).";
        elseif ($dcCheck == -1) {
            $comments[] = "Đổng Công: Xấu (Hung).";
            if ($purpose != 'generic') $isBad = true;
        }

        // 7. Ngoc Hap Thong Thu (Star check)
        $stars = self::checkNgocHap($lunarMonth, $dayChi);
        $goodStars = $stars['good'];
        $badStars = $stars['bad'];

        if (!empty($goodStars)) $comments[] = "Sao Tốt: " . implode(', ', $goodStars) . ".";
        if (!empty($badStars)) {
             $comments[] = "Sao Xấu: " . implode(', ', $badStars) . ".";
             // Basic heuristic: if strictly bad stars present for purpose
             // For simplicity, just listing them.
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
            'sao' => implode(', ', $goodStars),
            'details' => $comments,
            'age_analysis' => $ageAnalysis
        );
    }

    public static function checkDongCong($month, $dayChi) {
        if (isset(self::$DONG_CONG[$month][$dayChi])) {
            return self::$DONG_CONG[$month][$dayChi];
        }
        return 0;
    }

    public static function checkNgocHap($month, $dayChi) {
        // Simplified Logic for Demo
        // Map Month -> Good Day Chi (Thien Duc, Nguyet Duc)
        $good = [];
        $bad = [];

        // Thien Duc (Month -> Chi)
        // 1-Din, 2-Than, 3-Ty(Snake), 4-Than, 5-Hoi, 6-Giap... (Depends on Can too? Usually Chi)
        // Standard:
        // 1: Dinh (Can?), 2: Than (Monkey), 3: Nham (Can?), 4: Tan (Can?), 5: Hoi (Pig)...
        // Actually Thien Duc often maps to CAN or CHI depending on source.
        // Let's use a simpler known set: Thien Duc Hop, Nguyet Duc.

        // Nguyet Duc (Month -> Can). We need Day Can. Function doesn't receive Day Can.
        // Assuming user passed Day Chi only.
        // Let's rely on Month-Day Chi relations for some stars.

        // Example: Thien H u / Thien Khoc (Bad)
        // 1: Ngo, 2: Ty...
        $khocHu = [1=>6, 2=>5, 3=>4, 4=>3, 5=>2, 6=>1, 7=>0, 8=>11, 9=>10, 10=>9, 11=>8, 12=>7]; // Roughly
        if (isset($khocHu[$month]) && $khocHu[$month] == $dayChi) {
            $bad[] = "Thiên Khốc/Hư";
        }

        // Example: Thien Hy (Good for wedding)
        // Spring: Tuat?
        // 1: Tuat, 2: Hoi, 3: Ty, 4: Suu...
        $thienHy = [1=>10, 2=>11, 3=>0, 4=>1, 5=>2, 6=>3, 7=>4, 8=>5, 9=>6, 10=>7, 11=>8, 12=>9];
        if (isset($thienHy[$month]) && $thienHy[$month] == $dayChi) {
            $good[] = "Thiên Hỷ";
        }

        return ['good' => $good, 'bad' => $bad];
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
        $startNode = $tens;
        if ($startNode > 6) $startNode = ($startNode - 1) % 6 + 1;

        // Count units
        $currentNode = $startNode;
        for ($i = 0; $i < $units; $i++) {
             $currentNode++;
             if ($currentNode > 6) $currentNode = 1;
        }

        return in_array($currentNode, [3, 5, 6]);
    }

    public static function isTamTai($age, $currentYear) {
        $birthYear = $currentYear - $age + 1;
        $birthChi = ($birthYear - 4) % 12;
        if ($birthChi < 0) $birthChi += 12;

        $currentChi = ($currentYear - 4) % 12;
        if ($currentChi < 0) $currentChi += 12;

        // Group 1: Than (8), Ty (0), Thin (4) -> Tam Tai: Dan (2), Mao (3), Thin (4)
        if (in_array($birthChi, [8, 0, 4])) {
            return in_array($currentChi, [2, 3, 4]);
        }
        // Group 2: Ty (5), Dau (9), Suu (1) -> Tam Tai: Hoi (11), Ty (0), Suu (1)
        if (in_array($birthChi, [5, 9, 1])) {
            return in_array($currentChi, [11, 0, 1]);
        }
        // Group 3: Dan (2), Ngo (6), Tuat (10) -> Tam Tai: Than (8), Dau (9), Tuat (10)
        if (in_array($birthChi, [2, 6, 10])) {
            return in_array($currentChi, [8, 9, 10]);
        }
        // Group 4: Hoi (11), Mao (3), Mui (7) -> Tam Tai: Ty (5), Ngo (6), Mui (7)
        if (in_array($birthChi, [11, 3, 7])) {
            return in_array($currentChi, [5, 6, 7]);
        }

        return false;
    }
}
