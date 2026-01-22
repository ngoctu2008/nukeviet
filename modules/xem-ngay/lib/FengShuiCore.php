<?php

namespace NukeViet\Module\XemNgay\Lib;

/**
 * Class FengShuiCore
 * Core Feng Shui calculations for Auspicious Date Selection.
 */
class FengShuiCore
{
    // Five Elements (0: Kim, 1: Moc, 2: Thuy, 3: Hoa, 4: Tho)
    const KIM = 0;
    const MOC = 1;
    const THUY = 2;
    const HOA = 3;
    const THO = 4;

    public $nguHanhNames = ['Kim', 'Mộc', 'Thủy', 'Hỏa', 'Thổ'];

    // Can Ngu Hanh: Giap/At=Moc, Binh/Dinh=Hoa, Mau/Ky=Tho, Canh/Tan=Kim, Nham/Quy=Thuy
    public $canNguHanh = [1, 1, 3, 3, 4, 4, 0, 0, 2, 2];

    // Chi Ngu Hanh:
    // Ty=Thuy, Suu=Tho, Dan=Moc, Mao=Moc, Thin=Tho, Ty=Hoa, Ngo=Hoa, Mui=Tho, Than=Kim, Dau=Kim, Tuat=Tho, Hoi=Thuy
    public $chiNguHanh = [2, 4, 1, 1, 4, 3, 3, 4, 0, 0, 4, 2];

    // 12 Officers (Trực)
    public $trucNames = ['Kiến', 'Trừ', 'Mãn', 'Bình', 'Định', 'Chấp', 'Phá', 'Nguy', 'Thành', 'Thu', 'Khai', 'Bế'];

    // 28 Mansions (Sao)
    public $saoNames = [
        'Giác', 'Cang', 'Đê', 'Phòng', 'Tâm', 'Vĩ', 'Cơ', // East (Wood)
        'Đẩu', 'Ngưu', 'Nữ', 'Hư', 'Nguy', 'Thất', 'Bích', // North (Water)
        'Khuê', 'Lâu', 'Vị', 'Mão', 'Tất', '觜', 'Sâm', // West (Metal) (Note: 觜 = Chủy)
        'Tỉnh', 'Quỷ', 'Liễu', 'Tinh', 'Trương', 'Dực', 'Chẩn' // South (Fire)
    ];

    /**
     * Check Element Interaction
     * @param int $elem1
     * @param int $elem2
     * @return int 1: Sinh (Good), -1: Khac (Bad), 0: Binh Hoa (Neutral)
     */
    public function checkNguHanh($elem1, $elem2)
    {
        // Sinh: Kim(0)->Thuy(2)->Moc(1)->Hoa(3)->Tho(4)->Kim(0)
        $sinh = [
            0 => 2, 2 => 1, 1 => 3, 3 => 4, 4 => 0
        ];
        // Khac: Kim(0)->Moc(1)->Tho(4)->Thuy(2)->Hoa(3)->Kim(0)
        $khac = [
            0 => 1, 1 => 4, 4 => 2, 2 => 3, 3 => 0
        ];

        if ($sinh[$elem1] === $elem2) return 1; // 1 sinh 2
        if ($sinh[$elem2] === $elem1) return 1; // 2 sinh 1 (Still good interaction)

        if ($khac[$elem1] === $elem2) return -1; // 1 khac 2
        if ($khac[$elem2] === $elem1) return -1; // 2 khac 1

        return 0;
    }

    /**
     * Check Chi Interaction (Tam Hop, Tu Hanh Xung, Luc Hai, Tuong Hinh)
     * @param int $chi1
     * @param int $chi2
     * @return array List of interactions found (e.g., ['Tam Hợp', 'Lục Hại'])
     */
    public function checkXungKhacChi($chi1, $chi2)
    {
        $results = [];

        // Tam Hop (Difference is 4)
        if (abs($chi1 - $chi2) % 12 == 4 || abs($chi1 - $chi2) % 12 == 8) {
            $results[] = 'Tam Hợp';
        }

        // Nhi Hop (Luc Hop)
        $lucHop = [0=>1, 1=>0, 2=>11, 11=>2, 3=>10, 10=>3, 4=>9, 9=>4, 5=>8, 8=>5, 6=>7, 7=>6];
        if ($lucHop[$chi1] === $chi2) {
            $results[] = 'Nhị Hợp';
        }

        // Tu Hanh Xung (Difference is 6 - Direct Clash, or within group 3)
        // Groups: (Dan, Than, Ty, Hoi), (Ty, Ngo, Mao, Dau), (Thin, Tuat, Suu, Mui)
        if (abs($chi1 - $chi2) == 6) {
            $results[] = 'Lục Xung'; // Direct clash
        }

        // Luc Hai
        $lucHai = [0=>7, 7=>0, 1=>6, 6=>1, 2=>5, 5=>2, 3=>4, 4=>3, 8=>11, 11=>8, 9=>10, 10=>9];
        if ($lucHai[$chi1] === $chi2) {
            $results[] = 'Lục Hại';
        }

        // Tuong Hinh
        // Ty(0) - Mao(3)
        if (($chi1 == 0 && $chi2 == 3) || ($chi1 == 3 && $chi2 == 0)) $results[] = 'Tương Hình (Vô Lễ)';
        // Dan(2) - Ty(5) - Than(8)
        $hinh3 = [2, 5, 8];
        if (in_array($chi1, $hinh3) && in_array($chi2, $hinh3) && $chi1 != $chi2) $results[] = 'Tương Hình (Vô Ân/Trì Thế)';
        // Suu(1) - Tuat(10) - Mui(7)
        $hinh3b = [1, 10, 7];
        if (in_array($chi1, $hinh3b) && in_array($chi2, $hinh3b) && $chi1 != $chi2) $results[] = 'Tương Hình (Vô Ân/Trì Thế)';
        // Thin(4), Ngo(6), Dau(9), Hoi(11) - Self Penalty
        if ($chi1 == $chi2 && in_array($chi1, [4, 6, 9, 11])) $results[] = 'Tự Hình';

        return $results;
    }

    /**
     * Get Truc (12 Officers)
     */
    public function getTruc($month, $dayChi)
    {
        $start = ($month + 1) % 12; // Index of Kien day
        $diff = $dayChi - $start;
        if ($diff < 0) $diff += 12;

        return [
            'id' => $diff,
            'name' => $this->trucNames[$diff]
        ];
    }

    /**
     * Get Sao (28 Mansions)
     */
    public function getSao($jdn)
    {
        // Reference: JDN 2451161 is Giac (0).
        $offset = floor($jdn - 2451161);
        $idx = $offset % 28;
        if ($idx < 0) $idx += 28;

        return [
            'id' => $idx,
            'name' => $this->saoNames[$idx]
        ];
    }

    /**
     * Check if Day is Hoang Dao (Zodiac Day)
     * @param int $month Lunar Month
     * @param int $dayChi Day Chi Index
     * @return boolean
     */
    public function isHoangDao($month, $dayChi)
    {
        $map = [
            1 => [0, 1, 5, 7], // Ty, Suu, Ty, Mui
            2 => [2, 3, 7, 9], // Dan, Mao, Mui, Dau
            3 => [4, 5, 9, 11], // Thin, Ty, Dau, Hoi
            4 => [6, 7, 11, 1], // Ngo, Mui, Hoi, Suu
            5 => [8, 9, 1, 3], // Than, Dau, Suu, Mao
            6 => [10, 11, 3, 5], // Tuat, Hoi, Mao, Ty
            7 => [0, 1, 5, 7], // Repeat 1
            8 => [2, 3, 7, 9], // Repeat 2
            9 => [4, 5, 9, 11], // Repeat 3
            10 => [6, 7, 11, 1], // Repeat 4
            11 => [8, 9, 1, 3], // Repeat 5
            12 => [10, 11, 3, 5] // Repeat 6
        ];

        // Check map
        if (isset($map[$month])) {
            return in_array($dayChi, $map[$month]);
        }
        return false;
    }

    /**
     * Check if Hour is Hoang Dao
     */
    public function isGioHoangDao($dayChi, $hourChi)
    {
        // Groups:
        // 1. Ty, Ngo
        // 2. Suu, Mui
        // 3. Dan, Than
        // 4. Mao, Dau
        // 5. Thin, Tuat
        // 6. Ty, Hoi

        $groups = [
            0 => 1, 6 => 1, // Ty, Ngo -> Group 1
            1 => 2, 7 => 2, // Suu, Mui -> Group 2
            2 => 3, 8 => 3, // Dan, Than -> Group 3
            3 => 4, 9 => 4, // Mao, Dau -> Group 4
            4 => 5, 10 => 5, // Thin, Tuat -> Group 5
            5 => 6, 11 => 6 // Ty, Hoi -> Group 6
        ];

        $goodHours = [
            1 => [0, 1, 3, 6, 8, 9], // Ty, Suu, Mao, Ngo, Than, Dau
            2 => [2, 3, 5, 8, 10, 11], // Dan, Mao, Ty, Than, Tuat, Hoi
            3 => [0, 1, 4, 6, 8, 10], // Ty, Suu, Thin, Ngo, Mui, Tuat
            4 => [0, 2, 3, 6, 7, 9], // Ty, Dan, Mao, Ngo, Mui, Dau
            5 => [2, 4, 5, 8, 9, 11], // Dan, Thin, Ty, Than, Dau, Hoi
            6 => [1, 4, 6, 7, 10, 11] // Suu, Thin, Ngo, Mui, Tuat, Hoi
        ];

        $g = $groups[$dayChi];
        return in_array($hourChi, $goodHours[$g]);
    }

    /**
     * Check Kim Lau
     * @param int $age
     * @return boolean True if Kim Lau
     */
    public function checkKimLau($age)
    {
        $remainder = $age % 9;
        // 1: Than, 3: The, 6: Tu, 8: Luc Suc
        return in_array($remainder, [1, 3, 6, 8]);
    }

    /**
     * Check Hoang Oc
     * @param int $age
     * @return boolean True if Hoang Oc
     */
    public function checkHoangOc($age)
    {
        // Cycles: 10->1, 20->2, 30->3, 40->4, 50->5, 60->6, 70->1
        $tens = floor($age / 10);
        $units = $age % 10;

        if ($tens == 0) {
             $start = 1; // Age < 10 starts at 1
        } else {
             $start = ($tens % 6);
             if ($start == 0) $start = 6;
        }

        $current = $start + ($units - 1); // If units=0 (e.g. 20), start at 2. units=1 -> 3? No.
        // Rule: 20 is at 2. 21 is at 3.
        // So units=0 -> current = start.
        // units=1 -> current = start + 1.
        // Formula: start + units. Wait.
        // 20 -> start=2. units=0. res=2. Correct.
        // 21 -> start=2. units=1. res=3. Correct.
        // 29 -> start=2. units=9. res=11 -> 5. Correct?
        // Let's verify manually: 20(2), 21(3), 22(4), 23(5), 24(6), 25(1), 26(2), 27(3), 28(4), 29(5).
        // Formula: ($start + $units - 1) % 6 + 1.

        $current = $start + $units;
        $res = ($current - 1) % 6 + 1;

        // Bad: 3 (Dia Sat), 4 (Tan Tai), 5 (Tho Tu), 6 (Hoang Oc)
        return in_array($res, [3, 4, 5, 6]);
    }

    /**
     * Check Tam Tai
     * @param int $birthChi
     * @param int $currentYearChi
     * @return boolean True if Tam Tai
     */
    public function checkTamTai($birthChi, $currentYearChi)
    {
        // Than(8)-Ty(0)-Thin(4) -> Dan(2), Mao(3), Thin(4)
        // Hoi(11)-Mao(3)-Mui(7) -> Ty(5), Ngo(6), Mui(7)
        // Dan(2)-Ngo(6)-Tuat(10) -> Than(8), Dau(9), Tuat(10)
        // Ty(5)-Dau(9)-Suu(1) -> Hoi(11), Ty(0), Suu(1)

        $groups = [
            0 => [2, 3, 4], 4 => [2, 3, 4], 8 => [2, 3, 4], // Than Ty Thin -> Dan Mao Thin
            3 => [5, 6, 7], 7 => [5, 6, 7], 11 => [5, 6, 7], // Hoi Mao Mui -> Ty Ngo Mui
            2 => [8, 9, 10], 6 => [8, 9, 10], 10 => [8, 9, 10], // Dan Ngo Tuat -> Than Dau Tuat
            1 => [11, 0, 1], 5 => [11, 0, 1], 9 => [11, 0, 1] // Ty Dau Suu -> Hoi Ty Suu
        ];

        if (isset($groups[$birthChi])) {
            return in_array($currentYearChi, $groups[$birthChi]);
        }
        return false;
    }
}
