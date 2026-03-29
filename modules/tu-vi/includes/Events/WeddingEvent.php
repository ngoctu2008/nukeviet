<?php

namespace NukeViet\Module\TuVi\Includes\Events;

use NukeViet\Module\TuVi\Includes\EventInterface;
use NukeViet\Module\TuVi\Includes\LunarDate;
use NukeViet\Module\TuVi\Includes\FengShuiCore;

class WeddingEvent implements EventInterface
{
    private $lunar;
    private $fengShui;

    public function __construct()
    {
        $this->lunar = new LunarDate();
        $this->fengShui = new FengShuiCore();
    }

    /**
     * Check Age Compatibility for Wedding
     * @param int $groomYear
     * @param int $brideYear
     * @param int $year Current Year (Solar)
     * @return array
     */
    public function checkAge($groomYear, $brideYear, $year)
    {
        // 1. Calculate Ages
        // Age = CurrentYear - BirthYear + 1
        // Usually calculate based on Lunar Year, but approximately Solar Year works for age calc.

        $groomAge = $year - $groomYear + 1;
        $brideAge = $year - $brideYear + 1;

        // 2. Check Kim Lau (Bride)
        // Wedding primarily checks Bride's Kim Lau.
        $isKimLauBride = $this->fengShui->checkKimLau($brideAge);

        // 3. Check Tam Tai (Optional for wedding but good to know)
        // Need Chi of Birth Year and Current Year.
        // Assuming user provides valid years.

        // Helper to get Chi from Year
        $groomChi = ($groomYear + 8) % 12;
        $brideChi = ($brideYear + 8) % 12;
        $currentChi = ($year + 8) % 12;

        $isTamTaiGroom = $this->fengShui->checkTamTai($groomChi, $currentChi);
        $isTamTaiBride = $this->fengShui->checkTamTai($brideChi, $currentChi);

        $advice = [];
        if ($isKimLauBride) {
            $advice[] = "Nên xin dâu hai lần để hóa giải.";
            $advice[] = "Hoặc chờ qua ngày Đông Chí để tính sang tuổi mới.";
        }

        return [
            'groom_age' => $groomAge,
            'bride_age' => $brideAge,
            'kim_lau_bride' => $isKimLauBride,
            'tam_tai_groom' => $isTamTaiGroom,
            'tam_tai_bride' => $isTamTaiBride,
            'advice' => $advice
        ];
    }

    /**
     * Find Auspicious Dates for Wedding
     * @param int $groomYear
     * @param int $brideYear
     * @param string $startDate
     * @param string $endDate
     */
    public function findDates($groomYear, $brideYear, $startDate, $endDate)
    {
        $results = [];
        $current = strtotime($startDate);
        $end = strtotime($endDate);

        $groomCanChi = $this->getYearCanChi($groomYear);
        $brideCanChi = $this->getYearCanChi($brideYear);

        while ($current <= $end) {
            $d = (int)date('d', $current);
            $m = (int)date('m', $current);
            $y = (int)date('Y', $current);

            $lunar = $this->lunar->convertSolarToLunar($d, $m, $y);
            // $lunar: [day, month, year, leap, dayCan, dayChi...]

            // Fix: Use associative keys instead of numeric indices for Can/Chi
            $dayChi = isset($lunar['dayChi']) ? $lunar['dayChi'] : 0;
            $dayCan = isset($lunar['dayCan']) ? $lunar['dayCan'] : 0;
            $month = isset($lunar[1]) ? $lunar[1] : 1;

            $dateStr = date('Y-m-d', $current);

            // 1. Check Bat Tuong (Best for wedding)
            // Bat Tuong logic is complex (combines Can/Chi/Month).
            // Simplified: Avoid Sat Chu, Tho Tu, Tam Nuong, Nguyet Ky.

            // 2. Avoid Tam Nuong (Lunar 3, 7, 13, 18, 22, 27)
            $lunarDay = isset($lunar[0]) ? $lunar[0] : 1;
            if (in_array($lunarDay, [3, 7, 13, 18, 22, 27])) {
                $current = strtotime('+1 day', $current);
                continue; // Skip
            }

            // 3. Avoid Nguyet Ky (5, 14, 23)
            if (in_array($lunarDay, [5, 14, 23])) {
                $current = strtotime('+1 day', $current);
                continue;
            }

            // 4. Avoid Clash with Bride/Groom (Luc Xung)
            $clashGroom = $this->fengShui->checkXungKhacChi($dayChi, $groomCanChi['chi']);
            $clashBride = $this->fengShui->checkXungKhacChi($dayChi, $brideCanChi['chi']);

            if (in_array('Lục Xung', $clashGroom) || in_array('Lục Xung', $clashBride)) {
                 $current = strtotime('+1 day', $current);
                 continue;
            }

            // 5. Check Hoang Dao
            $isHoangDao = $this->fengShui->isHoangDao($month, $dayChi);

            // Score
            $score = 0;
            if ($isHoangDao) $score += 2;

            // Check Nhi Hop / Tam Hop with Bride/Groom (Bonus)
            if (in_array('Tam Hợp', $clashBride) || in_array('Nhị Hợp', $clashBride)) $score += 1;
            if (in_array('Tam Hợp', $clashGroom) || in_array('Nhị Hợp', $clashGroom)) $score += 1;

            // Get Good Hours
            $goodHours = $this->fengShui->getGioHoangDaoList($dayChi);

            $results[] = [
                'date' => $dateStr,
                'lunar_date' => "$lunarDay/$month",
                'day_can_chi' => $this->lunar->getCanName($dayCan) . ' ' . $this->lunar->getChiName($dayChi),
                'is_hoang_dao' => $isHoangDao,
                'score' => $score,
                'hours' => implode(', ', $goodHours)
            ];

            $current = strtotime('+1 day', $current);
        }

        // Sort by score desc
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
