<?php

namespace NukeViet\Module\XemNgay\Lib\Events;

use NukeViet\Module\XemNgay\Lib\EventInterface;
use NukeViet\Module\XemNgay\Lib\LunarDate;
use NukeViet\Module\XemNgay\Lib\FengShuiCore;

class FuneralEvent implements EventInterface
{
    private $lunar;
    private $fengShui;

    public function __construct()
    {
        $this->lunar = new LunarDate();
        $this->fengShui = new FengShuiCore();
    }

    /**
     * Check Death Time for Trùng Tang/Thiên Di/Nhập Mộ
     * @param string $deathDateTime Y-m-d H:i:s
     * @param int $birthYear
     * @param int $gender 1: Male, 0: Female
     * @return array Status for Year, Month, Day, Hour
     */
    public function checkDeathTime($deathDateTime, $birthYear, $gender)
    {
        // 1. Calculate Age (Tuổi mụ)
        $ts = strtotime($deathDateTime);

        // Convert Death Date to Lunar
        $d = (int)date('d', $ts);
        $m = (int)date('m', $ts);
        $y = (int)date('Y', $ts);
        $h = (int)date('H', $ts); // Need Lunar Hour (Chi)

        $lunarDate = $this->lunar->convertSolarToLunar($d, $m, $y); // [d, m, y, leap, dayCan, dayChi...]

        // Lunar Age = DeathYearLunar - BirthYear + 1
        $age = isset($lunarDate[2]) ? $lunarDate[2] - $birthYear + 1 : 1;
        if ($age < 1) $age = 1; // Fallback

        // Lunar Hour Index (0=Ty... 11=Hoi)
        // 23-1: Ty, 1-3: Suu...
        // Logic: (h + 1) / 2
        $hourChi = floor(($h + 1) / 2) % 12;

        // 2. Perform Counting Logic
        // Points: 12 Chi (0:Ty, 1:Suu, ..., 11:Hoi)
        // Men: Start Dan (2), Clockwise (+1)
        // Women: Start Than (8), Counter-Clockwise (-1)

        $start = ($gender == 1) ? 2 : 8;
        $dir = ($gender == 1) ? 1 : -1;

        // Step 1: Count Age
        // Count by 10s: 10, 20...
        // Example Age 43.
        // 10 -> Start
        // 20 -> Start + 1*dir

        $tens = floor($age / 10);
        $units = $age % 10;

        $current = $start;

        if ($tens > 0) {
            $current = $this->move($start, ($tens - 1) * $dir);
            if ($units > 0) {
                $current = $this->move($current, $units * $dir);
            }
        } else {
             // Age < 10.
             $current = $this->move($start, ($units - 1) * $dir);
        }

        $pAge = $current;

        // Step 2: Count Month
        // Standard: "Tháng 1 ngay tại cung tuổi".
        // So offset = (month - 1).
        $monthVal = isset($lunarDate[1]) ? $lunarDate[1] : 1;
        $pMonth = $this->move($pAge, ($monthVal - 1) * $dir);

        // Step 3: Count Day
        // "Từ cung tháng, đếm ngày 1...".
        // Day 1 at P_Month.
        $dayVal = isset($lunarDate[0]) ? $lunarDate[0] : 1;
        $pDay = $this->move($pMonth, ($dayVal - 1) * $dir);

        // Step 4: Count Hour
        // "Từ cung ngày, đếm giờ Tý...".
        // Hour Ty (0) at P_Day.
        $pHour = $this->move($pDay, $hourChi * $dir);

        return [
            'age_status' => $this->getStatus($pAge),
            'month_status' => $this->getStatus($pMonth),
            'day_status' => $this->getStatus($pDay),
            'hour_status' => $this->getStatus($pHour),
            'details' => [
                'age_chi' => $this->lunar->getChiName($pAge),
                'month_chi' => $this->lunar->getChiName($pMonth),
                'day_chi' => $this->lunar->getChiName($pDay),
                'hour_chi' => $this->lunar->getChiName($pHour)
            ]
        ];
    }

    private function move($start, $steps)
    {
        $res = ($start + $steps) % 12;
        if ($res < 0) $res += 12;
        return $res;
    }

    private function getStatus($chi)
    {
        // Dần(2), Thân(8), Tỵ(5), Hợi(11) -> Trùng Tang
        if (in_array($chi, [2, 5, 8, 11])) return 'Trùng Tang';

        // Tý(0), Ngọ(6), Mão(3), Dậu(9) -> Thiên Di
        if (in_array($chi, [0, 3, 6, 9])) return 'Thiên Di';

        // Thìn(4), Tuất(10), Sửu(1), Mùi(7) -> Nhập Mộ
        return 'Nhập Mộ';
    }

    /**
     * Find Auspicious Dates for Funeral
     */
    public function findDates($deceasedInfo, $chiefMourner, $relatives, $startDate, $endDate, $badDates = [])
    {
        $results = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        $deceasedCanChi = $this->getYearCanChi($deceasedInfo['birth_year']);
        $chiefCanChi = $this->getYearCanChi($chiefMourner['birth_year']);

        while ($current <= $end) {
            $d = (int)date('d', $current);
            $m = (int)date('m', $current);
            $y = (int)date('Y', $current);

            $lunar = $this->lunar->convertSolarToLunar($d, $m, $y);
            // $lunar: [day, month, year, leap, dayCan, dayChi...]

            // Fix: Use associative keys instead of numeric indices for Can/Chi
            $dayChi = isset($lunar['dayChi']) ? $lunar['dayChi'] : 0;
            $month = isset($lunar[1]) ? $lunar[1] : 1;
            $dayCan = isset($lunar['dayCan']) ? $lunar['dayCan'] : 0;

            $dateStr = date('Y-m-d', $current);

            // --- FILTER 1: HARD EXCLUSIONS ---

            // 1. Check Bad Dates (Tho Tu, Sat Chu...)
            if ($this->isBadDayGeneric($month, $dayChi)) {
                $current = strtotime('+1 day', $current);
                continue;
            }

            // 2. Check Clash with Deceased (Truc Xung)
            $clashDeceased = $this->fengShui->checkXungKhacChi($dayChi, $deceasedCanChi['chi']);
            if (in_array('Lục Xung', $clashDeceased)) {
                 $current = strtotime('+1 day', $current);
                 continue;
            }

            // --- FILTER 2: CHIEF MOURNER ---

            $clashChief = $this->fengShui->checkXungKhacChi($dayChi, $chiefCanChi['chi']);

            $chiefStatus = 'OK';
            if (in_array('Lục Xung', $clashChief)) {
                 $chiefStatus = 'Bad';
            }

            // --- FILTER 3: RELATIVES ---

            $warnings = [];
            foreach ($relatives as $rel) {
                $relCanChi = $this->getYearCanChi($rel['birth_year']);
                $clashRel = $this->fengShui->checkXungKhacChi($dayChi, $relCanChi['chi']);
                if (in_array('Lục Xung', $clashRel)) {
                    $warnings[] = "Xung tuổi " . $rel['birth_year'];
                }
            }

            // Good Stars check
            $isHoangDao = $this->fengShui->isHoangDao($month, $dayChi);
            $truc = $this->fengShui->getTruc($month, $dayChi);
            $sao = $this->fengShui->getSao($this->lunar->jdn($d, $m, $y));

            $score = 0;
            if ($isHoangDao) $score += 2;
            // Evaluate Truc (simple logic)
            if (in_array($truc['id'], [0, 8, 9, 10])) $score += 1; // Kien, Thanh, Thu, Khai

            if ($chiefStatus !== 'Bad') {
                $results[] = [
                    'date' => $dateStr,
                    'lunar_date' => (isset($lunar[0]) ? $lunar[0] : '-') . '/' . (isset($lunar[1]) ? $lunar[1] : '-'),
                    'day_can_chi' => $this->lunar->getCanName($dayCan) . ' ' . $this->lunar->getChiName($dayChi),
                    'is_hoang_dao' => $isHoangDao,
                    'truc' => $truc['name'],
                    'sao' => $sao['name'],
                    'warnings' => $warnings,
                    'score' => $score
                ];
            }

            $current = strtotime('+1 day', $current);
        }

        return $results;
    }

    private function getYearCanChi($year)
    {
        $can = ($year + 6) % 10;
        $chi = ($year + 8) % 12;
        return ['can' => $can, 'chi' => $chi];
    }

    private function isBadDayGeneric($month, $dayChi)
    {
        // Simple hardcoded checks for common bad days
        // Sat Chu (Lunar Month -> Bad Chi)
        $satChu = [
            1 => 5, // Ty
            2 => 0, // Ty (Rat)
            3 => 7, // Mui
            4 => 3, // Mao
            5 => 8, // Than
            6 => 10, // Tuat
            7 => 11, // Hoi
            8 => 1, // Suu
            9 => 6, // Ngo
            10 => 1, // Suu
            11 => 0, // Ty (Rat)
            12 => 4 // Thin
        ];
        if (isset($satChu[$month]) && $satChu[$month] == $dayChi) return true;

        // Tho Tu
        $thoTu = [
            1 => 10, // Tuat
            2 => 4, // Thin
            3 => 11, // Hoi
            4 => 5, // Ty (Snake)
            5 => 0, // Ty (Rat)
            6 => 6, // Ngo
            7 => 1, // Suu
            8 => 7, // Mui
            9 => 2, // Dan
            10 => 8, // Than
            11 => 3, // Mao
            12 => 9 // Dau
        ];
        if (isset($thoTu[$month]) && $thoTu[$month] == $dayChi) return true;

        return false;
    }
}
