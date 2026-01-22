<?php

namespace NukeViet\Module\XemNgay\Lib\Events;

use NukeViet\Module\XemNgay\Lib\EventInterface;
use NukeViet\Module\XemNgay\Lib\LunarDate;
use NukeViet\Module\XemNgay\Lib\FengShuiCore;

class ConstructionEvent implements EventInterface
{
    private $lunar;
    private $fengShui;

    public function __construct()
    {
        $this->lunar = new LunarDate();
        $this->fengShui = new FengShuiCore();
    }

    /**
     * Check Age for Construction (Kim Lau, Hoang Oc, Tam Tai)
     * @param int $birthYear
     * @param int $currentYear
     * @return array
     */
    public function checkAge($birthYear, $currentYear)
    {
        $age = $currentYear - $birthYear + 1;

        // Kim Lau
        $kimLau = $this->fengShui->checkKimLau($age);

        // Hoang Oc
        $hoangOc = $this->fengShui->checkHoangOc($age);

        // Tam Tai
        $birthChi = ($birthYear + 8) % 12;
        $currentChi = ($currentYear + 8) % 12;
        $tamTai = $this->fengShui->checkTamTai($birthChi, $currentChi);

        $advice = [];
        if ($kimLau || $hoangOc || $tamTai) {
            $advice[] = "Gia chủ phạm hạn, nên mượn tuổi người khác để động thổ.";
            $advice[] = "Nên chọn người tuổi Tam Hợp hoặc Nhị Hợp, tránh người tuổi Lục Xung, Kim Lâu, Hoang Ốc.";
        }

        return [
            'age' => $age,
            'kim_lau' => $kimLau,
            'hoang_oc' => $hoangOc,
            'tam_tai' => $tamTai,
            'is_good' => (!$kimLau && !$hoangOc && !$tamTai),
            'advice' => $advice
        ];
    }

    /**
     * Find Dates for Construction
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

            // Fix: Use associative keys instead of numeric indices for Can/Chi
            $dayChi = isset($lunar['dayChi']) ? $lunar['dayChi'] : 0;
            $dayCan = isset($lunar['dayCan']) ? $lunar['dayCan'] : 0;
            $month = isset($lunar[1]) ? $lunar[1] : 1;

            $dateStr = date('Y-m-d', $current);

            // 1. Avoid Tho Cam, Duong Cong Ky (Generic Bad Days)
            // Simplified: Avoid Tam Nuong, Nguyet Ky as well.
            $lunarDay = isset($lunar[0]) ? $lunar[0] : 1;
            if (in_array($lunarDay, [5, 14, 23])) { // Nguyet Ky
                $current = strtotime('+1 day', $current);
                continue;
            }

            // 2. Avoid Clash with Owner
            $clash = $this->fengShui->checkXungKhacChi($dayChi, $ownerCanChi['chi']);
            if (in_array('Lục Xung', $clash)) {
                 $current = strtotime('+1 day', $current);
                 continue;
            }

            // 3. Prefer Hoang Dao, Dai An, Toc Hy (Truc)
            // Simplified: Hoang Dao + Truc Kien/Khai/Thanh
            $isHoangDao = $this->fengShui->isHoangDao($month, $dayChi);
            $truc = $this->fengShui->getTruc($month, $dayChi);

            $score = 0;
            if ($isHoangDao) $score += 2;

            // Truc: Kien(0), Man(2), Binh(3), Dinh(4), Thanh(8), Khai(10) -> Good for construction?
            // Kien: Good for starting.
            // Khai: Open.
            // Thanh: Success.
            if (in_array($truc['id'], [0, 8, 10])) $score += 1;

            // Get Good Hours
            $goodHours = $this->fengShui->getGioHoangDaoList($dayChi);

            $results[] = [
                'date' => $dateStr,
                'lunar_date' => "$lunarDay/$month",
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
