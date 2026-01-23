<?php

namespace NukeViet\Module\XemNgay\Lib\Events;

use NukeViet\Module\XemNgay\Lib\EventInterface;
use NukeViet\Module\XemNgay\Lib\LunarDate;
use NukeViet\Module\XemNgay\Lib\FengShuiCore;

class CustomEvent implements EventInterface
{
    private $lunar;
    private $fengShui;
    private $config;

    public function __construct($config)
    {
        $this->lunar = new LunarDate();
        $this->fengShui = new FengShuiCore();
        $this->config = $config;
    }

    /**
     * Check Age Compatibility
     * @param int $birthYear
     * @param int $currentYear
     * @param int|null $partnerYear
     * @return array
     */
    public function checkAge($birthYear, $currentYear, $partnerYear = null)
    {
        $age = $currentYear - $birthYear + 1;
        $logic = isset($this->config['logic']) ? $this->config['logic'] : [];
        $result = ['age' => $age, 'is_good' => true, 'warnings' => []];

        $birthChi = ($birthYear + 8) % 12;
        $currentChi = ($currentYear + 8) % 12;

        // Check Kim Lau
        if (in_array('check_kim_lau', $logic)) {
            if ($this->fengShui->checkKimLau($age)) {
                $result['warnings'][] = "Tuổi $age phạm Kim Lâu.";
                $result['is_good'] = false;
            }
        }

        // Check Hoang Oc
        if (in_array('check_hoang_oc', $logic)) {
            if ($this->fengShui->checkHoangOc($age)) {
                $result['warnings'][] = "Tuổi $age phạm Hoang Ốc.";
                $result['is_good'] = false;
            }
        }

        // Check Tam Tai
        if (in_array('check_tam_tai', $logic)) {
            if ($this->fengShui->checkTamTai($birthChi, $currentChi)) {
                $result['warnings'][] = "Tuổi $age phạm Tam Tai.";
                $result['is_good'] = false;
            }
        }

        // Check Partner Clash
        if ($partnerYear && in_array('input_partner', $this->config['inputs'])) {
            $partnerChi = ($partnerYear + 8) % 12;
            $clash = $this->fengShui->checkXungKhacChi($birthChi, $partnerChi);
            if (in_array('Lục Xung', $clash)) {
                $result['warnings'][] = "Tuổi gia chủ xung khắc với đối tác ($partnerYear).";
                // Don't necessarily set is_good to false, just warn
            }
        }

        return $result;
    }

    /**
     * Find Auspicious Dates
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

            // 1. Avoid Clash with Owner (Always check this)
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
