<?php

namespace NukeViet\Module\XemNgay\Lib\Events;

use NukeViet\Module\XemNgay\Lib\EventInterface;
use NukeViet\Module\XemNgay\Lib\LunarDate;
use NukeViet\Module\XemNgay\Lib\FengShuiCore;

class GrandOpeningEvent implements EventInterface
{
    private $lunar;
    private $fengShui;

    public function __construct()
    {
        $this->lunar = new LunarDate();
        $this->fengShui = new FengShuiCore();
    }

    /**
     * Check Age for Grand Opening (Optional, but usually avoiding Tam Tai/Hoang Oc)
     * @param int $birthYear
     * @param int $currentYear
     * @return array
     */
    public function checkAge($birthYear, $currentYear)
    {
        $age = $currentYear - $birthYear + 1;
        // Check basic bad luck
        $tamTai = $this->fengShui->checkTamTai(($birthYear + 8) % 12, ($currentYear + 8) % 12);

        return [
            'age' => $age,
            'tam_tai' => $tamTai
        ];
    }

    /**
     * Find Dates for Grand Opening
     * Focus on Tai Vuong, Loc Ma (Good Stars) and avoid bad days.
     * @param int $ownerYear
     * @param string $startDate
     * @param string $endDate
     */
    public function findDates($ownerYear, $startDate, $endDate)
    {
        $results = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        $ownerCanChi = $this->getYearCanChi($ownerYear);

        while ($current <= $end) {
            $d = (int)date('d', $current);
            $m = (int)date('m', $current);
            $y = (int)date('Y', $current);

            $lunar = $this->lunar->convertSolarToLunar($d, $m, $y);

            $dayChi = isset($lunar['dayChi']) ? $lunar['dayChi'] : 0;
            $dayCan = isset($lunar['dayCan']) ? $lunar['dayCan'] : 0;
            $month = isset($lunar[1]) ? $lunar[1] : 1;

            $dateStr = date('Y-m-d', $current);

            // 1. Avoid Clash with Owner
            $clash = $this->fengShui->checkXungKhacChi($dayChi, $ownerCanChi['chi']);
            if (in_array('Lục Xung', $clash)) {
                 $current = strtotime('+1 day', $current);
                 continue;
            }

            // 2. Prefer Hoang Dao
            $isHoangDao = $this->fengShui->isHoangDao($month, $dayChi);
            $truc = $this->fengShui->getTruc($month, $dayChi);

            $score = 0;
            if ($isHoangDao) $score += 2;

            // Truc: Thanh, Man, Khai -> Good for opening
            if (in_array($truc['id'], [2, 8, 10])) $score += 1;

            // Loc Ma (Simplified: Avoid bad stars)
            // Just basic score for now.

            // Get Good Hours
            $goodHours = $this->fengShui->getGioHoangDaoList($dayChi);

            $results[] = [
                'date' => $dateStr,
                'lunar_date' => (isset($lunar[0]) ? $lunar[0] : 1) . "/$month",
                'day_can_chi' => $this->lunar->getCanName($dayCan) . ' ' . $this->lunar->getChiName($dayChi),
                'truc' => $truc['name'],
                'is_hoang_dao' => $isHoangDao,
                'score' => $score,
                'hours' => implode(', ', $goodHours)
            ];

            $current = strtotime('+1 day', $current);
        }

        usort($results, function($a, $b) {
            return $b['score'] - $a['score'];
        });

        return $results;
    }

    private function getYearCanChi($year)
    {
        $can = ($year + 6) % 10;
        $chi = ($year + 8) % 12;
        return ['can' => $can, 'chi' => $chi];
    }
}
