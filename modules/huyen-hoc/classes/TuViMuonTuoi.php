<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

// FIX: Do not require_once using NV_ROOTDIR here if class might be autoloaded or used in test script with different path structure.
// Instead, check if class exists or assume autoloader/test script handles inclusion.
// For TuViMuonTuoi, we need TuViConstants.

namespace NukeViet\Module\HuyenHoc;

use NukeViet\Module\HuyenHoc\TuViConstants;

class TuViMuonTuoi {
    protected $currentYear;
    protected $currentLunarYearCan;
    protected $currentLunarYearChi;
    protected $currentElement;

    public function __construct($year = 2026) {
        $this->currentYear = $year;
        $this->currentLunarYearCan = ($year - 4) % 10;
        $this->currentLunarYearChi = ($year - 4) % 12;

        // Ensure TuViConstants is available
        if (!class_exists('\NukeViet\Module\HuyenHoc\TuViConstants')) {
             // Fallback for standalone test if not required yet (should be required by caller)
        }

        $napAm = TuViConstants::getNapAm($this->currentLunarYearCan, $this->currentLunarYearChi);
        $this->currentElement = $napAm['id'];
    }

    public function timNguoiMuonTuoi($ownerBirthYear) {
        $candidates = [];
        $minYear = $this->currentYear - 75;
        $maxYear = $this->currentYear - 20;

        for ($y = $maxYear; $y >= $minYear; $y--) {
            if ($y == $ownerBirthYear) continue;
            $analysis = $this->analyzeCandidate($y, $ownerBirthYear);
            if ($analysis['is_eligible']) {
                $candidates[] = $analysis;
            }
        }

        usort($candidates, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return array_slice($candidates, 0, 10);
    }

    public function analyzeCandidate($birthYear, $ownerBirthYear) {
        $age = $this->currentYear - $birthYear + 1;
        $canChi = $this->getCanChi($birthYear);

        $kimLau = $this->checkKimLau($age);
        $hoangOc = $this->checkHoangOc($age);
        $tamTai = $this->checkTamTai($canChi['chi_id']);
        $thaiTue = $this->checkThaiTue($canChi['chi_id']);

        $isEligible = (!$kimLau && !$hoangOc['is_bad'] && !$tamTai && !$thaiTue);

        $score = 0;
        $details = [];

        if ($isEligible) {
            $menhNguoiMuon = TuViConstants::getNapAm($canChi['can_id'], $canChi['chi_id']);
            $checkHanhNam = $this->checkSinhKhac($menhNguoiMuon['id'], $this->currentElement);
            $score += $checkHanhNam['score'];
            $details[] = "Ngũ hành với năm " . $this->currentYear . ": " . $checkHanhNam['msg'];

            $canChiGiaChu = $this->getCanChi($ownerBirthYear);
            $menhGiaChu = TuViConstants::getNapAm($canChiGiaChu['can_id'], $canChiGiaChu['chi_id']);
            $checkHanhChu = $this->checkSinhKhac($menhNguoiMuon['id'], $menhGiaChu['id']);
            $score += $checkHanhChu['score'];
            $details[] = "Ngũ hành với gia chủ: " . $checkHanhChu['msg'];

            $chi = $canChi['chi_id'];
            $namChi = $this->currentLunarYearChi;

            $tamHop = [
                0 => [4, 8], 4 => [0, 8], 8 => [0, 4],
                2 => [6, 10], 6 => [2, 10], 10 => [2, 6],
                11 => [3, 7], 3 => [11, 7], 7 => [11, 3],
                5 => [9, 1], 9 => [5, 1], 1 => [5, 9]
            ];

            $lucHop = [0=>1, 1=>0, 2=>11, 11=>2, 3=>10, 10=>3, 4=>9, 9=>4, 5=>8, 8=>5, 6=>7, 7=>6];

            if (isset($tamHop[$namChi]) && in_array($chi, $tamHop[$namChi])) {
                $score += 2;
                $details[] = "Tam Hợp với năm (Rất tốt)";
            } elseif (isset($lucHop[$namChi]) && $lucHop[$namChi] == $chi) {
                $score += 2;
                $details[] = "Lục Hợp với năm (Rất tốt)";
            }
        }

        return [
            'birth_year' => $birthYear,
            'age' => $age,
            'can_chi' => $canChi['name'],
            'menh' => TuViConstants::getNapAm($canChi['can_id'], $canChi['chi_id'])['ten'],
            'is_eligible' => $isEligible,
            'score' => $score,
            'bad_factors' => [
                'kim_lau' => $kimLau,
                'hoang_oc' => $hoangOc['is_bad'] ? $hoangOc['name'] : false,
                'tam_tai' => $tamTai,
                'thai_tue' => $thaiTue
            ],
            'comment' => $details
        ];
    }

    private function checkKimLau($age) {
        $remainder = $age % 9;
        if (in_array($remainder, [1, 3, 6, 8])) {
            return true;
        }
        return false;
    }

    private function checkHoangOc($age) {
        $tens = floor($age / 10);
        $units = $age % 10;

        if ($tens == 0) $start = 1;
        else {
            $start = $tens;
            while ($start > 6) $start -= 6;
        }

        $pos = ($start + $units - 1) % 6;
        if ($pos <= 0) $pos += 6;

        $current = $pos;

        $map = [
            1 => ['name' => 'Nhất Cát', 'is_bad' => false],
            2 => ['name' => 'Nhị Nghi', 'is_bad' => false],
            3 => ['name' => 'Tam Địa Sát', 'is_bad' => true],
            4 => ['name' => 'Tứ Tấn Tài', 'is_bad' => false],
            5 => ['name' => 'Ngũ Thọ Tử', 'is_bad' => true],
            6 => ['name' => 'Lục Hoang Ốc', 'is_bad' => true],
        ];
        return $map[$current];
    }

    private function checkTamTai($chiId) {
        $yearChi = $this->currentLunarYearChi;

        $groups = [
            'than_ty_thin' => ['ids' => [8,0,4], 'bad' => [2,3,4]],
            'dan_ngo_tuat' => ['ids' => [2,6,10], 'bad' => [8,9,10]],
            'hoi_mao_mui'  => ['ids' => [11,3,7], 'bad' => [5,6,7]],
            'ty_dau_suu'   => ['ids' => [5,9,1], 'bad' => [11,0,1]]
        ];

        foreach ($groups as $g) {
            if (in_array($chiId, $g['ids'])) {
                if (in_array($yearChi, $g['bad'])) return true;
            }
        }
        return false;
    }

    private function checkThaiTue($chiId) {
        $yearChi = $this->currentLunarYearChi;
        if ($chiId == $yearChi) return true;
        $xung = ($yearChi + 6) % 12;
        if ($chiId == $xung) return true;
        return false;
    }

    private function checkSinhKhac($hanhA, $hanhB) {
        if ($hanhA == $hanhB) return ['score' => 1, 'msg' => 'Tương Hòa'];
        return ['score' => 0, 'msg' => 'Bình thường'];
    }

    private function getCanChi($year) {
        $can = ($year - 4) % 10; if ($can < 0) $can += 10;
        $chi = ($year - 4) % 12; if ($chi < 0) $chi += 12;
        return [
            'can_id' => $can,
            'chi_id' => $chi,
            'name' => TuViConstants::CAN[$can] . ' ' . TuViConstants::CHI[$chi]
        ];
    }
}
