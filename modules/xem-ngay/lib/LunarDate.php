<?php

namespace NukeViet\Module\XemNgay\Lib;

/**
 * Class LunarDate
 * Provides Solar-Lunar conversion and Calendar calculations (Can/Chi, Tiet Khi).
 * Based on the algorithm by Ho Ngoc Duc.
 */
class LunarDate
{
    // Arrays for lookup
    public $can = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    public $chi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

    // Solar terms (Tiết khí)
    public $tietKhi = [
        'Xuân Phân', 'Thanh Minh', 'Cốc Vũ', 'Lập Hạ', 'Tiểu Mãn', 'Mang Chủng',
        'Hạ Chí', 'Tiểu Thử', 'Đại Thử', 'Lập Thu', 'Xử Thử', 'Bạch Lộ',
        'Thu Phân', 'Hàn Lộ', 'Sương Giáng', 'Lập Đông', 'Tiểu Tuyết', 'Đại Tuyết',
        'Đông Chí', 'Tiểu Hàn', 'Đại Hàn', 'Lập Xuân', 'Vũ Thủy', 'Kinh Trập'
    ];

    /**
     * Convert Solar Date to Lunar Date
     * @param int $d Day
     * @param int $m Month
     * @param int $y Year
     * @param float $timeZone Timezone (default 7.0 for Vietnam)
     * @return array [day, month, year, leap, dayCan, dayChi, monthCan, monthChi, yearCan, yearChi]
     */
    public function convertSolarToLunar($d, $m, $y, $timeZone = 7.0)
    {
        $jdn = $this->jdn($d, $m, $y);
        return $this->jdnToLunar($jdn, $timeZone);
    }

    /**
     * Calculate Julian Day Number
     */
    public function jdn($d, $m, $y)
    {
        if ($m < 3) {
            $m += 12;
            $y -= 1;
        }
        $a = floor($y / 100);
        $b = 2 - $a + floor($a / 4);
        return floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $b - 1524.5;
    }

    /**
     * Convert JDN to Lunar
     */
    public function jdnToLunar($jdn, $timeZone = 7.0)
    {
        $k = floor(($jdn - 2415021.076998695) / 29.530588853);
        $m = $k;
        $n = $k;

        $lunar = $this->getLunarDateFull($jdn, $timeZone);

        // Calculate Can Chi
        $canChi = $this->calculateCanChi($lunar[0], $lunar[1], $lunar[2], $jdn);

        return array_merge($lunar, $canChi);
    }

    private function getLunarDateFull($jdn, $timeZone)
    {
        // Algorithm requires finding the new moon (k)
        $k = floor(($jdn - 2415021.076998695) / 29.530588853);
        $nm = $this->getNewMoonDay($k, $timeZone);

        if ($jdn < $nm) {
            $k--;
            $nm = $this->getNewMoonDay($k, $timeZone);
        }

        $lunarDay = (int)($jdn - $nm + 1);

        // Find lunar month and year
        // We need to find the major solar term (Trung Khi) to determine month
        // This part is computationally intensive.
        // Logic:
        // 1. Find the new moon of the 11th lunar month (containing Dong Chi/Winter Solstice)
        // 2. Count months from there.

        return $this->computeLunarDetails($jdn, $timeZone);
    }

    // Full implementation of Ho Ngoc Duc's JS logic ported to PHP
    private function computeLunarDetails($jdn, $timeZone)
    {
        $k = floor(($jdn - 2415021.076998695) / 29.530588853);
        $nm = $this->getNewMoonDay($k, $timeZone);
        if ($jdn < $nm) {
            $nm = $this->getNewMoonDay($k - 1, $timeZone);
        }
        $lunarDay = (int)($jdn - $nm + 1);

        $nm11 = $this->getLunarMonth11($jdn, $timeZone);
        $k11 = floor(($nm11 - 2415021.076998695) / 29.530588853);

        // Identify month
        $diff = round(($nm - $nm11) / 29.530588853);
        $lunarMonth = $diff + 11;
        $leapMonth = $this->getLeapMonthOffset($nm11, $timeZone);
        $isLeap = false;

        if ($diff >= $leapMonth) {
            $lunarMonth--;
            if ($diff == $leapMonth) {
                $isLeap = true;
            }
        }

        if ($lunarMonth > 12) {
            $lunarMonth -= 12;
        }

        // Get Solar Year from JDN to approx
        $z = floor($jdn + 0.5);
        $alpha = floor(($z - 1867216.25) / 36524.25);
        $A = $z + 1 + $alpha - floor($alpha / 4);
        $B = $A + 1524;
        $C = floor(($B - 122.1) / 365.25);
        $D = floor(365.25 * $C);
        $E = floor(($B - $D) / 30.6001);
        $solarYear = floor($C - 4715);
        if ($E > 13) $solarYear++; // Month is Jan/Feb

        // Lunar year usually follows solar year but can be off at start
        $lunarYear = $solarYear;
        if ($lunarMonth >= 11 && $E <= 2) {
             $lunarYear--;
        } elseif ($lunarMonth <= 2 && $E >= 11) {
            $lunarYear++;
        }

        // Correct logic: The 11th lunar month corresponds to the Winter Solstice of year Y.
        // So the lunar year is roughly Y.
        // Let's simpler:
        // Find year of nm11
        $d11 = $this->jdnToDate($nm11); // [d, m, y]
        $lunarYear = $d11[2];
        if ($lunarMonth < 11) {
            $lunarYear++;
        }

        return [$lunarDay, $lunarMonth, $lunarYear, $isLeap ? 1 : 0];
    }

    private function jdnToDate($jdn)
    {
        $z = floor($jdn + 0.5);
        $w = floor(($z - 1867216.25) / 36524.25);
        $x = floor($w / 4);
        $a = $z + 1 + $w - $x;
        $b = $a + 1524;
        $c = floor(($b - 122.1) / 365.25);
        $d = floor(365.25 * $c);
        $e = floor(($b - $d) / 30.6001);
        $day = $b - $d - floor(30.6001 * $e);
        $month = ($e < 14) ? $e - 1 : $e - 13;
        $year = ($month > 2) ? $c - 4716 : $c - 4715;
        return [(int)$day, (int)$month, (int)$year];
    }

    private function getNewMoonDay($k, $timeZone)
    {
        $T = $k / 1236.85;
        $dr = pi() / 180;
        $Jd1 = 2415020.75933 + 29.53058868 * $k + 0.0001178 * $T * $T - 0.000000155 * $T * $T * $T;
        $Jd1 += 0.00033 * sin((166.56 + 132.87 * $T - 0.009173 * $T * $T) * $dr);
        $M = 359.2242 + 29.10535608 * $k - 0.0000333 * $T * $T - 0.00000347 * $T * $T * $T;
        $Mprime = 306.0253 + 385.81691806 * $k + 0.0107306 * $T * $T + 0.00001236 * $T * $T * $T;
        $F = 21.2964 + 390.67050646 * $k - 0.0016528 * $T * $T - 0.00000239 * $T * $T * $T;

        $correction = (0.1734 - 0.000393 * $T) * sin($M * $dr) + 0.0021 * sin(2 * $M * $dr);
        $correction -= 0.4068 * sin($Mprime * $dr) + 0.0161 * sin(2 * $Mprime * $dr);
        $correction -= 0.0004 * sin(3 * $Mprime * $dr);
        $correction += 0.0104 * sin(2 * $F * $dr) - 0.0051 * sin($M * $dr + 2 * $F * $dr);
        $correction -= 0.0074 * sin($Mprime * $dr - 2 * $F * $dr) + 0.0004 * sin(2 * $F * $dr + 2 * $M * $dr);
        $correction -= 0.0004 * sin(2 * $F * $dr - 2 * $M * $dr) - 0.0006 * sin(2 * $F * $dr + $Mprime * $dr);
        $correction += 0.0010 * sin(2 * $F * $dr - $Mprime * $dr) + 0.0005 * sin($M * $dr + 2 * $Mprime * $dr);

        $JdNew = $Jd1 + $correction;
        return $JdNew + $timeZone / 24.0;
    }

    private function getSunLongitude($jdn, $timeZone)
    {
        $T = ($jdn - 2451545.0 - $timeZone / 24.0) / 36525.0;
        $dr = pi() / 180;
        $L0 = 280.46645 + 36000.76983 * $T + 0.0003032 * $T * $T;
        $M = 357.52910 + 35999.05030 * $T - 0.0001559 * $T * $T - 0.00000048 * $T * $T * $T;
        $C = (1.914600 - 0.004817 * $T - 0.000014 * $T * $T) * sin($M * $dr);
        $C += (0.019993 - 0.000101 * $T) * sin(2 * $M * $dr) + 0.000290 * sin(3 * $M * $dr);
        $theta = $L0 + $C;
        return $theta - 360 * floor($theta / 360);
    }

    private function getLunarMonth11($jdn, $timeZone)
    {
        $off = $jdn - 2415021.076998695;
        $k = floor($off / 29.530588853);
        $nm = $this->getNewMoonDay($k, $timeZone);

        // Efficient way:
        // 1. Estimate k for Winter Solstice
        // 2. Adjust
        $k_approx = floor(($jdn - 2415021.076998695) / 29.53);

        // Check backwards
        for ($i = 0; $i < 15; $i++) {
            $k_test = $k_approx - $i;
            $nm_test = $this->getNewMoonDay($k_test, $timeZone);
            $sunLong = $this->getSunLongitude($nm_test, $timeZone); // Longitude at start of month
            // We want the month that CONTAINS 270 degree point.

            // Actually, simply: 11th month must contain Winter Solstice.
            // So we check if the Winter Solstice falls within [nm_test, nm_test_next)
            $nm_next = $this->getNewMoonDay($k_test + 1, $timeZone);

            // Find when Sun hits 270 exactly?
            // Instead, just check if integer(SunLong / 30) changes to 9 (270/30) inside the month.
            // Sun Longitude at nm_test
            $sl1 = $this->getSunLongitude($nm_test, $timeZone);
            $sl2 = $this->getSunLongitude($nm_next, $timeZone);

            // Wrap around 360
            if ($sl2 < $sl1) $sl2 += 360;

            // Check if 270 is in [sl1, sl2]
            if ($sl1 <= 270 && $sl2 >= 270) {
                 return $nm_test; // This is the start of 11th month
            }
            if ($sl1 <= 630 && $sl2 >= 630) { // 270 + 360
                 return $nm_test;
            }
        }
        return $nm; // Fallback
    }

    private function getLeapMonthOffset($nm11, $timeZone)
    {
        // Find next 11th month
        // We can just step forward k
        $k11 = floor(($nm11 - 2415021.076998695) / 29.530588853);
        $last_nm = $nm11;

        $k = $k11;
        $count = 0;
        $months = [];

        for ($i=1; $i<=15; $i++) {
            $nm = $this->getNewMoonDay($k + $i, $timeZone);
            $nm_next = $this->getNewMoonDay($k + $i + 1, $timeZone);

            // Check if this month contains Winter Solstice (270 or 630...)
            $sl1 = $this->getSunLongitude($nm, $timeZone);
            $sl2 = $this->getSunLongitude($nm_next, $timeZone);
            if ($sl2 < $sl1) $sl2 += 360;

            $hasMajor = false;
            for ($d = 0; $d <= 330; $d += 30) {
                 // Check if angle $d is in ($sl1, $sl2]
                 // Adjust for wrap
                 $check = $d;
                 if ($check < $sl1) $check += 360;
                 if ($check > $sl1 && $check <= $sl2) {
                     $hasMajor = true;
                     break;
                 }
            }

            // More precise:
            // Calculate integer parts of (SL / 30).
            // if floor(sl1/30) != floor(sl2/30), we crossed a term boundary.

            $idx1 = floor($sl1 / 30);
            $idx2 = floor($sl2 / 30);
            if ($idx1 != $idx2) {
                $hasMajor = true;
            }

            $months[$i] = $hasMajor;

            // Check if this month contains WS (270 deg)
            // 270 / 30 = 9.
            if ($idx1 == 8 && $idx2 == 9) { // Crossed into 270?
                if ($i == 13) {
                     // Find the first month without Major Term
                     for ($j=1; $j<=13; $j++) {
                         if (!$months[$j]) {
                             return $j; // 1-based index from start
                         }
                     }
                }
                return 0; // No leap
            }
        }
        return 0;
    }

    /**
     * Calculate Can Chi for Date
     */
    public function calculateCanChi($d, $m, $y, $jdn)
    {
        // Can/Chi Year
        $canY = ($y + 6) % 10;
        $chiY = ($y + 8) % 12;

        // Can/Chi Month
        $canYvals = [2, 4, 6, 8, 0]; // Binh, Mau, Canh, Nham, Giap (indices in can array: 2, 4, 6, 8, 0)
        $startCanMonth = $canYvals[$canY % 5];

        $chiM = ($m + 1) % 12; // Month 1 is Dan (index 2).
        $canM = ($startCanMonth + ($m - 1)) % 10;

        // Can/Chi Day
        $canD = ($jdn + 9) % 10;
        $chiD = ($jdn + 1) % 12;

        return [
            'dayCan' => $canD,
            'dayChi' => $chiD,
            'monthCan' => $canM,
            'monthChi' => $chiM,
            'yearCan' => $canY,
            'yearChi' => $chiY
        ];
    }

    // Helpers to get text
    public function getCanName($idx) { return $this->can[$idx % 10]; }
    public function getChiName($idx) { return $this->chi[$idx % 12]; }

    /**
     * Get Solar Term (Tiet Khi)
     */
    public function getTietKhi($jdn, $timeZone)
    {
         $sl = $this->getSunLongitude($jdn, $timeZone);
         // Each term is 15 degrees.
         $idx = floor($sl / 15);
         return $this->tietKhi[$idx % 24];
    }
}
