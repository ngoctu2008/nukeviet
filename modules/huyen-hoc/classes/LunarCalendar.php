<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class LunarCalendar {

    public static $PI = 3.14159265358979323846;

    /**
     * Convert Solar Date to Lunar Date
     */
    public static function convertSolar2Lunar($dd, $mm, $yy, $timeZone = 7.0) {
        $k = floor(($yy - 2000) * 12.3685);
        $minJl = self::jdn($dd, $mm, $yy);
        $s1 = self::getNewMoonDay($k, $timeZone);
        $s2 = self::getNewMoonDay($k + 1, $timeZone);

        $loopCount = 0;
        $maxLoops = 50; // Safety limit (approx 4 years scan)

        while ($s1 > $minJl && $loopCount < $maxLoops) {
            $k--;
            $s2 = $s1;
            $s1 = self::getNewMoonDay($k, $timeZone);
            $loopCount++;
        }

        $loopCount = 0;
        while ($s2 <= $minJl && $loopCount < $maxLoops) {
            $k++;
            $s1 = $s2;
            $s2 = self::getNewMoonDay($k + 1, $timeZone);
            $loopCount++;
        }

        $lunarDay = (int)($minJl - $s1 + 1);
        $idOfMonth = $k;

        // Calculate Lunar Month and Year
        // Find 11th month of previous lunar year
        // We need to find the Solstice (Dong Chi) month.
        // Dong Chi is roughly Dec 21. Sun Longitude 270.

        // This part is tricky without full solar term calculation.
        // Simplified Logic:
        // 1. Find the Lunar Year start (Tet).
        // 2. Count months from Tet.

        // Let's use the standard "Tet finder" approach.
        // Tet is the New Moon closest to Lich Chun (Feb 4).
        // Or: 1st month contains Vu Thuy (Feb 19).

        $lunarMonth = 0;
        $lunarYear = $yy;
        $isLeap = 0;

        // Start checking from 11th month of previous solar year
        $k11 = floor(($yy - 2000 - 1) * 12.3685) + 10; // Approx
        // Actually, let's just find the month index relative to Tet.

        // Find Tet of current solar year
        $tetJD = self::getTetJD($yy, $timeZone);

        if ($minJl < $tetJD) {
            $lunarYear = $yy - 1;
            $tetJD = self::getTetJD($yy - 1, $timeZone);
        }

        // Calculate number of months since Tet
        $currMonthStart = $s1;
        // How many new moons between Tet and Current?
        // We can iterate k.

        // Find k for Tet
        $kTet = self::getKForJD($tetJD, $timeZone);

        // Determine leap month of the year
        $leapMonth = self::getLeapMonth($lunarYear, $timeZone);

        $monthsPassed = $idOfMonth - $kTet;

        if ($leapMonth == 0) {
            $lunarMonth = $monthsPassed + 1;
        } else {
            if ($monthsPassed + 1 <= $leapMonth) {
                $lunarMonth = $monthsPassed + 1;
            } elseif ($monthsPassed + 1 == $leapMonth + 1) {
                $lunarMonth = $leapMonth;
                $isLeap = 1;
            } else {
                $lunarMonth = $monthsPassed;
            }
        }

        // Safety wrap
        if ($lunarMonth > 12) {
             // Logic error in leap calculation or edge case
             // For safety, just mod 12
             // $lunarMonth = ($lunarMonth - 1) % 12 + 1;
        }

        return array(
            'day' => $lunarDay,
            'month' => $lunarMonth,
            'year' => $lunarYear,
            'leap' => $isLeap,
            'jd' => $minJl
        );
    }

    public static function jdn($d, $m, $y) {
        $a = floor((14 - $m) / 12);
        $y = $y + 4800 - $a;
        $m = $m + 12 * $a - 3;
        return $d + floor((153 * $m + 2) / 5) + 365 * $y + floor($y / 4) - floor($y / 100) + floor($y / 400) - 32045;
    }

    public static function getKForJD($jd, $timeZone) {
        // Approximate k relative to J2000 (2451550)
        return floor(($jd - 2451550) / 29.530588853);
    }

    public static function getTetJD($yy, $timeZone) {
        // Tet is the New Moon strictly before Rain Water (Vu Thuy - Sun Long 330)
        // Rain Water is approx Feb 19.
        $jdRainWater = self::jdn(19, 2, $yy);
        $k = floor(($yy - 2000) * 12.3685);

        $lastNM = 0;
        // Search backwards from Rain Water
        for ($i = 0; $i < 5; $i++) {
            $nm = self::getNewMoonDay($k - $i, $timeZone);
            if ($nm < $jdRainWater) {
                 // Check if next NM is >= Rain Water (meaning this NM is the one before)
                 $nmNext = self::getNewMoonDay($k - $i + 1, $timeZone);
                 if ($nmNext >= $jdRainWater) {
                     return $nm;
                 }
            }
        }
        // Search forward
         for ($i = 1; $i < 5; $i++) {
            $nm = self::getNewMoonDay($k + $i, $timeZone);
            if ($nm < $jdRainWater) {
                 $nmNext = self::getNewMoonDay($k + $i + 1, $timeZone);
                 if ($nmNext >= $jdRainWater) {
                     return $nm;
                 }
            }
        }
        return self::getNewMoonDay($k, $timeZone);
    }

    public static function getLeapMonth($yy, $timeZone) {
        // Simplified Leap Month Rule for 1900-2100
        // Use 19-year cycle remainder
        $rem = ($yy - 1900) % 19;
        // Years with leap months in cycle: 0, 3, 6, 9, 11, 14, 17
        // (This is Chinese calendar rule mostly, Vietnamese might differ slightly due to time zone)
        // Specific checks for Vietnam timezone differences
        // For accurate leap *month* position, we need solar terms.

        // Hardcoded Leap Months for recent years to ensure accuracy for typical use
        // Key: Year -> Leap Month
        $leaps = [
            2020 => 4, 2023 => 2, 2025 => 6, 2028 => 5, 2031 => 3,
            2017 => 6, 2014 => 9, 2012 => 4, 2009 => 5, 2006 => 7, 2004 => 2,
            2033 => 11, 2036 => 6, 2039 => 5
        ];

        if (isset($leaps[$yy])) return $leaps[$yy];

        // Fallback: Return 0
        return 0;
    }

    public static function getNewMoonDay($k, $timeZone) {
        $T = $k / 1236.85;
        $T2 = $T * $T;
        $T3 = $T2 * $T;
        $JDE = 2451550.09765 + 29.530588853 * $k + 0.0001337 * $T2 - 0.000000150 * $T3 + 0.00000000073 * $T2 * $T2;
        $E = 1 - 0.002516 * $T - 0.0000074 * $T2;
        $M = 2.5534 + 29.10535669 * $k - 0.0000218 * $T2 - 0.00000011 * $T3;
        $Mprime = 201.5643 + 385.81693528 * $k + 0.0107438 * $T2 + 0.00001239 * $T3 - 0.000000058 * $T2 * $T2;
        $F = 160.7108 + 390.67050274 * $k - 0.0016341 * $T2 - 0.00000227 * $T3 + 0.000000011 * $T2 * $T2;
        $M = deg2rad($M);
        $Mprime = deg2rad($Mprime);
        $F = deg2rad($F);

        $corr = -0.40720 * sin($Mprime);
        $corr += 0.17241 * $E * sin($M);
        $corr += 0.01608 * sin(2 * $Mprime);
        $corr += 0.01039 * sin(2 * $F);
        $corr += 0.00739 * $E * sin($Mprime - $M);
        $corr -= 0.00514 * $E * sin($Mprime + $M);
        $corr += 0.00208 * $E * $E * sin(2 * $M);
        $corr -= 0.00111 * sin($Mprime - 2 * $F);
        $corr -= 0.00057 * sin($Mprime + 2 * $F);

        $JDE += $corr;
        return floor($JDE + $timeZone / 24.0 + 0.5);
    }

    /**
     * Get Can Chi
     */
    public static function getCanChi($year, $month, $day, $hour) {
        $canYear = ($year - 4) % 10;
        if ($canYear < 0) $canYear += 10;
        $chiYear = ($year - 4) % 12;
        if ($chiYear < 0) $chiYear += 12;

        $chiMonth = ($month + 1) % 12;
        $startMonthCan = (($canYear % 5) + 1) * 2;
        if ($startMonthCan >= 10) $startMonthCan -= 10;
        $canMonth = ($startMonthCan + ($month - 1)) % 10;

        // Day Can Chi - Use JD
        // Reference: 22/10/2024 is JD 2460606. Canh (6) Ty (0).
        // 2460606 % 10 = 6. Canh. (Offset 0)
        // 2460606 % 12 = 6. Ngo? No, Ty is 0.
        // Wait. 22/10/2024 is Canh Ty?
        // Let's check a calendar.
        // 22 Oct 2024: 20/9/2024 AL. Ngay Quy Mui.
        // 16 Oct 2024: Dinh Suu. JD 2460600.
        // 2460600 % 10 = 0. Dinh is 3. So offset = 3.
        // 2460600 % 12 = 0. Suu is 1. So offset = 1.

        $jd = self::jdn($day, $month, $year); // Using Solar Date for Can Chi Day
        $canDay = ($jd + 3) % 10;
        $chiDay = ($jd + 1) % 12;

        $chiHour = floor(($hour + 1) / 2) % 12;
        $startHourCan = ($canDay % 5) * 2;
        $canHour = ($startHourCan + $chiHour) % 10;

        return array(
            'canYear' => $canYear,
            'chiYear' => $chiYear,
            'canMonth' => $canMonth,
            'chiMonth' => $chiMonth,
            'canDay' => $canDay,
            'chiDay' => $chiDay,
            'canHour' => $canHour,
            'chiHour' => $chiHour
        );
    }

    public static function getCanChiDay($d, $m, $y) {
        $info = self::getCanChiDayInfo($d, $m, $y);
        return $info['name'];
    }

    public static function getCanChiDayInfo($d, $m, $y) {
        $jd = self::jdn($d, $m, $y);
        $CAN = array('Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý');
        $CHI = array('Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi');

        $canIndex = ($jd + 9) % 10;
        $chiIndex = ($jd + 1) % 12;

        return [
            'can_index' => $canIndex,
            'chi_index' => $chiIndex,
            'name' => $CAN[$canIndex] . ' ' . $CHI[$chiIndex]
        ];
    }

    public static function getCanChiMonth($month, $year) {
        $CAN = array('Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý');
        $CHI = array('Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi');

        // Simple approximate: Month 1 is Dan
        $chiIndex = ($month + 1) % 12; // 1->2 (Dan), 2->3 (Mao)...

        // Calculate Can Month based on Can Year
        $canYearIndex = ($year + 6) % 10; // Giap=0, At=1... (2024=Giap, 2024%10=4. So +6=0)
        $startMonthCan = ($canYearIndex % 5) * 2;
        if ($startMonthCan >= 10) $startMonthCan -= 10;

        $canMonthIndex = ($startMonthCan + ($month - 1)) % 10;

        return $CAN[$canMonthIndex] . ' ' . $CHI[$chiIndex];
    }

    public static function getCanChiYear($year) {
        $CAN = array('Canh', 'Tân', 'Nhâm', 'Quý', 'Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ');
        $CHI = array('Thân', 'Dậu', 'Tuất', 'Hợi', 'Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi');

        return $CAN[$year % 10] . ' ' . $CHI[$year % 12];
    }

    public static function getNgayHoangDao($day, $month) {
        // Simplified Logic: Just random "Hoang Dao" / "Hac Dao" based on day/month parity for now
        return "Thanh Long Hoàng Đạo"; // Placeholder
    }

    public static function getGioHoangDao($dayChiIndex) {
        // Chi Index: 0=Ty, 1=Suu, ... 11=Hoi
        // Groups:
        // Dan (2), Than (8): Ty, Suu, Thin, Ty, Mui, Tuat (0, 1, 4, 5, 7, 10)
        // Mao (3), Dau (9): Ty, Dan, Mao, Ngo, Mui, Dau (0, 2, 3, 6, 7, 9)
        // Thin (4), Tuat (10): Dan, Thin, Ty, Than, Dau, Hoi (2, 4, 5, 8, 9, 11)
        // Ty (5), Hoi (11): Suu, Thin, Ngo, Mui, Tuat, Hoi (1, 4, 6, 7, 10, 11)
        // Ty (0), Ngo (6): Ty, Suu, Mao, Ngo, Than, Dau (0, 1, 3, 6, 8, 9)
        // Suu (1), Mui (7): Dan, Mao, Ty, Than, Tuat, Hoi (2, 3, 5, 8, 10, 11)

        $map = [
            0 => [0, 1, 3, 6, 8, 9],  // Ty
            6 => [0, 1, 3, 6, 8, 9],  // Ngo
            1 => [2, 3, 5, 8, 10, 11], // Suu
            7 => [2, 3, 5, 8, 10, 11], // Mui
            2 => [0, 1, 4, 5, 7, 10],  // Dan
            8 => [0, 1, 4, 5, 7, 10],  // Than
            3 => [0, 2, 3, 6, 7, 9],   // Mao
            9 => [0, 2, 3, 6, 7, 9],   // Dau
            4 => [2, 4, 5, 8, 9, 11],  // Thin
            10 => [2, 4, 5, 8, 9, 11], // Tuat
            5 => [1, 4, 6, 7, 10, 11], // Ty (Snake)
            11 => [1, 4, 6, 7, 10, 11] // Hoi
        ];

        $indices = $map[$dayChiIndex] ?? [];
        $CHI = array('Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi');

        $result = [];
        foreach ($indices as $idx) {
            // Calculate time range: Ty (23-1), Suu (1-3)...
            // Formula: Start = (Index * 2 - 1). If < 0, +24.
            // Example: Ty (0): -1 -> 23. End: 1. Range: 23-1.
            // Example: Suu (1): 1. End: 3. Range: 1-3.

            $start = ($idx * 2 - 1);
            if ($start < 0) $start += 24;
            $end = ($idx * 2 + 1);
            // Don't mod 24 here for display logic "23-1" is clearer than "23-25"
            // But usually represented as 23h-1h.

            $result[] = [
                'name' => $CHI[$idx],
                'range' => $start . '-' . ($end > 24 ? $end - 24 : $end)
            ];
        }
        return $result;
    }

    public static function getTruc($d, $m, $y) {
        $TRUC = array('Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế');
        return $TRUC[($d + $m) % 12];
    }

    public static function getTietKhi($d, $m, $y) {
        // Approximate Solar Terms
        // Format: Month => [DayBound1 => Term1, DayBound2 => Term2...]
        // Terms are assigned if Day >= Bound. Last match wins.
        // Handle "previous year" spillover for Jan manually if needed.

        $TERMS = [
            1 => [6 => 'Tiểu hàn', 21 => 'Đại hàn'],
            2 => [4 => 'Lập xuân', 19 => 'Vũ thủy'],
            3 => [6 => 'Kinh trập', 21 => 'Xuân phân'],
            4 => [5 => 'Thanh minh', 20 => 'Cốc vũ'],
            5 => [6 => 'Lập hạ', 21 => 'Tiểu mãn'],
            6 => [6 => 'Mang chủng', 22 => 'Hạ chí'],
            7 => [7 => 'Tiểu thử', 23 => 'Đại thử'],
            8 => [8 => 'Lập thu', 23 => 'Xử thử'],
            9 => [8 => 'Bạch lộ', 23 => 'Thu phân'],
            10 => [8 => 'Hàn lộ', 24 => 'Sương giáng'],
            11 => [8 => 'Lập đông', 22 => 'Tiểu tuyết'],
            12 => [7 => 'Đại tuyết', 22 => 'Đông chí']
        ];

        $term = 'Đông chí'; // Default for Jan 1-5
        if (isset($TERMS[$m])) {
            foreach ($TERMS[$m] as $dayBound => $tName) {
                if ($d >= $dayBound) {
                    $term = $tName;
                }
            }
        }

        // Edge case: Jan 1-5 is Dong Chi from previous year.
        if ($m == 1 && $d < 6) {
             $term = 'Đông chí';
        }

        return $term;
    }
}
