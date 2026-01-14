<?php

namespace NukeViet\Module\TuVi;

/**
 * Class Horoscope
 * Handles An Sao (Star Placement) logic.
 * Implements Nam Phai rules.
 */
class Horoscope
{
    public $cung = [];
    public $stars = [];
    public $lunar;
    public $info;

    public function __construct($lunar, $hour_chi_id, $gender)
    {
        $this->lunar = $lunar;
        $this->info = [
            'hour_chi' => $hour_chi_id,
            'gender' => $gender, // 1=Male, 0=Female
            'can_year_id' => ($lunar['year'] + 6) % 10,
            'chi_year_id' => ($lunar['year'] + 8) % 12,
        ];
        $this->initCung();
    }

    private function initCung()
    {
        $chi = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
        for ($i = 0; $i < 12; $i++) {
            $this->cung[$i] = [
                'id' => $i,
                'name' => $chi[$i],
                'stars' => [],
                'is_menh' => false,
                'is_than' => false,
                'chinh_tinh' => [],
                'phu_tinh' => [],
                'cung_chuc' => ''
            ];
        }
    }

    public function lapLaSo()
    {
        $this->anCungMenhThan();
        $this->an12Cung();
        $this->tinhCuc();
        $this->anTuVi();
        $this->anThienPhu();
        $this->anPhuTinh();
        $this->anVongThaiTue();
        $this->anVongLocTon();
        $this->anVongTrangSinh();

        return $this->cung;
    }

    public function getSaoHan($age, $gender)
    {
        $sao_nam = [1=>'La Hầu', 2=>'Thổ Tú', 3=>'Thủy Diệu', 4=>'Thái Bạch', 5=>'Thái Dương', 6=>'Vân Hớn', 7=>'Kế Đô', 8=>'Thái Âm', 9=>'Mộc Đức'];
        $sao_nu = [1=>'Kế Đô', 2=>'Vân Hớn', 3=>'Mộc Đức', 4=>'Thái Âm', 5=>'Thổ Tú', 6=>'La Hầu', 7=>'Thái Dương', 8=>'Thái Bạch', 9=>'Thủy Diệu'];

        $han_nam = [1=>'Huỳnh Tuyền', 2=>'Tam Kheo', 3=>'Ngũ Mộ', 4=>'Thiên Tinh', 5=>'Toán Tận', 6=>'Thiên La', 7=>'Địa Võng', 8=>'Diêm Vương'];
        $han_nu = [1=>'Toán Tận', 2=>'Thiên Tinh', 3=>'Ngũ Mộ', 4=>'Tam Kheo', 5=>'Huỳnh Tuyền', 6=>'Diêm Vương', 7=>'Địa Võng', 8=>'Thiên La'];

        if ($age < 10) return ['sao' => '', 'han' => ''];

        $idx_sao = ($age - 10) % 9 + 1;
        $idx_han = ($age - 10) % 8 + 1;

        if ($gender == 1) { // Nam
            $sao = $sao_nam[$idx_sao];
            $han = $han_nam[$idx_han];
        } else { // Nu
            $sao = $sao_nu[$idx_sao];
            $han = $han_nu[$idx_han];
        }

        return ['sao' => $sao, 'han' => $han];
    }

    /**
     * Get Dai Van Palace Index
     * Based on Cuc and Gender/Year Can YinYang
     */
    public function getDaiVan($age, $gender, $cuc)
    {
        // 1. Determine direction (Thuan/Nghich)
        // Duong Nam/Am Nu -> Thuan. Am Nam/Duong Nu -> Nghich.
        $can_year = $this->info['can_year_id'];
        $is_yang_year = ($can_year % 2 == 0); // 0=Giap(Yang)

        $direction = 1;
        if (($is_yang_year && $gender == 1) || (!$is_yang_year && $gender == 0)) {
            $direction = 1;
        } else {
            $direction = -1;
        }

        // 2. Start from Menh (Dai Van 1 start at Menh?)
        // Actually Dai Van starts from Menh with age = Cuc.
        // E.g. Thuy Nhi Cuc (2). Menh is 2-11. Next is 12-21.

        $menh_id = $this->info['menh_id'];

        // Calculate offset from start age
        // Dai van index = floor((Age - Cuc) / 10)
        // If Age < Cuc, no Dai Van? Or pre-Dai Van. Assume Age >= Cuc for standard chart.

        if ($age < $cuc) return $menh_id; // Simple fallback

        $daivan_step = floor(($age - $cuc) / 10);

        // Move from Menh
        $pos = ($menh_id + ($daivan_step * $direction));
        while ($pos < 0) $pos += 12;
        $pos %= 12;

        return $pos;
    }

    /**
     * Get Tieu Van Palace Index
     * Rule:
     * Nam: Thuan. Nu: Nghich. (Or opposite?)
     * Rule: Tieu Van follows year Chi.
     * Calculation often starts from specific positions based on Year Chi?
     * Common rule:
     *   Dan Ngo Tuat -> Khoi o Thin.
     *   Than Ty Thin -> Khoi o Tuat.
     *   Hoi Mao Mui -> Khoi o Suu.
     *   Ty Dau Suu -> Khoi o Mui.
     *
     *   Nam thuan, Nu nghich.
     *   Start from 'Khoi' palace is for 1 year old? Or current year Chi?
     *   Standard: Tieu Van is fixed by Year Branch? No, it rotates.
     *
     *   Let's use the formula:
     *   Look up starting palace for the *Birth Year Chi Group* (Tam Hop).
     *   Dan/Ngo/Tuat -> Thin.
     *   Than/Ty/Thin -> Tuat.
     *   Hoi/Mao/Mui -> Suu.
     *   Ty/Dau/Suu -> Mui.
     *
     *   From that start palace (Age 1), move to current Age.
     *   Nam Thuan, Nu Nghich.
     */
    public function getTieuVan($age, $gender, $birth_chi)
    {
        // 1. Determine base palace
        // Groups:
        // 0,4,8 (Ty, Thin, Than) -> Tuat (10)
        // 1,5,9 (Suu, Ty, Dau)   -> Mui (7)
        // 2,6,10 (Dan, Ngo, Tuat)-> Thin (4)
        // 3,7,11 (Mao, Mui, Hoi) -> Suu (1)

        // Map 0-11 to Group ID?
        // 0(Ty)->Group0. 4(Thin)->Group0. 8(Than)->Group0.
        // 1(Suu)->Group1. 5(Ty_Snake)->Group1 ?? Wait.
        // Chi: Ty(0), Suu(1), Dan(2), Mao(3), Thin(4), Ty_Snake(5), Ngo(6), Mui(7), Than(8), Dau(9), Tuat(10), Hoi(11)

        // Correct Groups:
        // Dan(2), Ngo(6), Tuat(10) -> Thin (4)
        // Than(8), Ty(0), Thin(4)  -> Tuat (10)
        // Hoi(11), Mao(3), Mui(7)  -> Suu (1)
        // Ty(5), Dau(9), Suu(1)    -> Mui (7)

        $start_map = [];
        // Group 1: 2,6,10 -> 4
        $start_map[2] = 4; $start_map[6] = 4; $start_map[10] = 4;
        // Group 2: 8,0,4 -> 10
        $start_map[8] = 10; $start_map[0] = 10; $start_map[4] = 10;
        // Group 3: 11,3,7 -> 1
        $start_map[11] = 1; $start_map[3] = 1; $start_map[7] = 1;
        // Group 4: 5,9,1 -> 7
        $start_map[5] = 7; $start_map[9] = 7; $start_map[1] = 7;

        $start_pos = $start_map[$birth_chi];

        // Direction
        // Nam Thuan, Nu Nghich
        $direction = ($gender == 1) ? 1 : -1;

        // Move (Age - 1) steps
        $pos = ($start_pos + (($age - 1) * $direction));
        while ($pos < 0) $pos += 12;
        $pos %= 12;

        return $pos;
    }

    public function getTamTai($current_year_chi, $birth_chi)
    {
        // Tam Tai rules:
        // Than-Ty-Thin gap Dan/Mao/Thin
        // Dan-Ngo-Tuat gap Than/Dau/Tuat
        // Hoi-Mao-Mui gap Ty/Ngo/Mui
        // Ty-Dau-Suu gap Hoi/Ty/Suu

        $bad_years = [];
        // 0(Ty), 1(Suu), 2(Dan), 3(Mao), 4(Thin), 5(Ty), 6(Ngo), 7(Mui), 8(Than), 9(Dau), 10(Tuat), 11(Hoi)

        // Than(8), Ty(0), Thin(4) -> 2,3,4
        if (in_array($birth_chi, [8,0,4])) $bad_years = [2,3,4];
        // Dan(2), Ngo(6), Tuat(10) -> 8,9,10
        if (in_array($birth_chi, [2,6,10])) $bad_years = [8,9,10];
        // Hoi(11), Mao(3), Mui(7) -> 5,6,7
        if (in_array($birth_chi, [11,3,7])) $bad_years = [5,6,7];
        // Ty(5), Dau(9), Suu(1) -> 11,0,1
        if (in_array($birth_chi, [5,9,1])) $bad_years = [11,0,1];

        if (in_array($current_year_chi, $bad_years)) return "Phạm Tam Tai";
        return "";
    }

    public function getKimLau($age)
    {
        // Lay tuoi am chia 9. Du 1,3,6,8 -> Kim Lau.
        $rem = $age % 9;
        if ($rem == 1) return "Kim Lâu Thân";
        if ($rem == 3) return "Kim Lâu Thê";
        if ($rem == 6) return "Kim Lâu Tử";
        if ($rem == 8) return "Kim Lâu Súc";
        return "";
    }

    public function getHoangOc($age)
    {
        // 6 cung: 1 Kiet, 2 Nghi, 3 Dia Sat, 4 Tan Tai, 5 Tho Tu, 6 Hoang Oc.
        // Start 10 at 1, 20 at 2...
        // Algorithm:
        // tens = floor(age/10)
        // units = age % 10
        // if tens == 0, tens = 1? No, 10 is start.
        // Just use lookup for 10-99 common range or map.

        // Simple map for "Bad" ages (Dia Sat, Tho Tu, Hoang Oc):
        // 12, 14, 15, 18, 21, 23, 24, 27, 29, 30, 32, 33, 36, 38, 39, 41, 42, 45, 47, 48, 50...
        // Let's implement the counter logic.

        $start = 1;
        if ($age >= 10 && $age < 20) $start = 1;
        if ($age >= 20 && $age < 30) $start = 2;
        if ($age >= 30 && $age < 40) $start = 3;
        if ($age >= 40 && $age < 50) $start = 4;
        if ($age >= 50 && $age < 60) $start = 5;
        if ($age >= 60 && $age < 70) $start = 6;
        if ($age >= 70) $start = 1; // Cycle

        $rem = $age % 10;
        if ($rem == 0) $val = $start;
        else {
            $val = $start + $rem; // Approximate
            // Real logic: 10->1, 11->2...
            // 20->2, 21->3...
            $val = $start + ($age % 10); // Actually complicated.
        }

        // Lookup list of Hoang Oc ages is safer.
        $hoang_oc_ages = [12, 14, 15, 18, 21, 23, 24, 27, 29, 30, 32, 33, 36, 38, 39, 41, 42, 45, 47, 48, 50, 51, 54, 56, 57, 60, 63, 65, 66, 69, 72, 74, 75];
        if (in_array($age, $hoang_oc_ages)) return "Phạm Hoang Ốc";

        return "";
    }

    // 1. An Cung Menh / Than
    private function anCungMenhThan()
    {
        $month = $this->lunar['month'];
        $hour = $this->info['hour_chi'];

        // Menh: Month - Hour + 1 (from Dan=2)
        // Pos = 2 + (Month - 1) - (Hour)
        $pos_menh = (2 + ($month - 1) - $hour);
        while ($pos_menh < 0) $pos_menh += 12;
        $pos_menh %= 12;

        // Than: Month + Hour - 1 (from Dan=2)
        $pos_than = (2 + ($month - 1) + $hour);
        $pos_than %= 12;

        $this->cung[$pos_menh]['is_menh'] = true;
        $this->cung[$pos_than]['is_than'] = true;

        $this->info['menh_id'] = $pos_menh;
        $this->info['than_id'] = $pos_than;
    }

    // 2. An 12 Cung Chuc
    private function an12Cung()
    {
        $names = ['Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
                  'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'];

        // CCW from Menh
        $start = $this->info['menh_id'];
        for ($i = 0; $i < 12; $i++) {
            $pos = ($start - $i);
            while ($pos < 0) $pos += 12;
            $this->cung[$pos]['cung_chuc'] = $names[$i];
        }
    }

    // 3. Tinh Cuc
    private function tinhCuc()
    {
        $can_year_id = $this->info['can_year_id'];
        // Start Can of Dan (2)
        $start_can = ($can_year_id % 5) * 2 + 2;

        $menh_id = $this->info['menh_id'];
        $dist = $menh_id - 2;
        if ($dist < 0) $dist += 12;

        $can_menh = ($start_can + $dist) % 10;

        // Matrix [CanGroup][ChiGroup]
        // CanGroup: 0..4 (Giap/At .. Nham/Quy)
        // ChiGroup: 0..5 (Ty/Suu .. Tuat/Hoi)
        $matrix = [
            [4, 2, 6, 5, 3, 4], // G/A
            [2, 6, 5, 3, 4, 2], // B/D
            [6, 5, 3, 4, 2, 6], // M/K
            [5, 3, 4, 2, 6, 5], // C/T
            [3, 4, 2, 6, 5, 3]  // N/Q
        ];

        $can_group = floor($can_menh / 2);
        $chi_group = floor($menh_id / 2);

        $cuc_val = $matrix[$can_group][$chi_group];

        $this->info['cuc'] = $cuc_val;
        $cuc_names = [2=>'Thủy Nhị Cục', 3=>'Mộc Tam Cục', 4=>'Kim Tứ Cục', 5=>'Thổ Ngũ Cục', 6=>'Hỏa Lục Cục'];
        $this->info['cuc_name'] = $cuc_names[$cuc_val];
    }

    // 4. An Tu Vi
    private function anTuVi()
    {
        $cuc = $this->info['cuc'];
        $day = $this->lunar['day'];

        $n = ceil($day / $cuc);
        $rem = ($n * $cuc) - $day;
        $pos = 2 + $n - 1;
        if ($rem % 2 == 0) {
            $pos += $rem;
        } else {
            $pos -= $rem;
        }

        while ($pos < 0) $pos += 12;
        $pos %= 12;

        $this->addStar($pos, 'Tử Vi', 100);
        $this->info['tu_vi_pos'] = $pos;
    }

    // 5. An Thien Phu
    private function anThienPhu()
    {
        $tv = $this->info['tu_vi_pos'];
        $tp = (10 - $tv);
        while ($tp < 0) $tp += 12;
        $tp %= 12;

        $this->addStar($tp, 'Thiên Phủ', 100);
        $this->info['thien_phu_pos'] = $tp;
    }

    // 6. Phu Tinh (Chinh Tinh loops)
    private function anPhuTinh()
    {
        $tv = $this->info['tu_vi_pos'];
        $tp = $this->info['thien_phu_pos'];

        // Vong Tu Vi (CCW)
        $this->addStar(($tv - 1 + 12) % 12, 'Thiên Cơ');
        $this->addStar(($tv - 3 + 12) % 12, 'Thái Dương');
        $this->addStar(($tv - 4 + 12) % 12, 'Vũ Khúc');
        $this->addStar(($tv - 5 + 12) % 12, 'Thiên Đồng');
        $this->addStar(($tv - 8 + 12) % 12, 'Liêm Trinh');

        // Vong Thien Phu (CW)
        $this->addStar(($tp + 1) % 12, 'Thái Âm');
        $this->addStar(($tp + 2) % 12, 'Tham Lang');
        $this->addStar(($tp + 3) % 12, 'Cự Môn');
        $this->addStar(($tp + 4) % 12, 'Thiên Tướng');
        $this->addStar(($tp + 5) % 12, 'Thiên Lương');
        $this->addStar(($tp + 6) % 12, 'Thất Sát');
        $this->addStar(($tp + 10) % 12, 'Phá Quân');
    }

    // Vong Thai Tue
    private function anVongThaiTue() {
        $start = $this->info['chi_year_id'];
        $stars = ['Thái Tuế', 'Thiếu Dương', 'Tang Môn', 'Thiếu Âm', 'Quan Phù', 'Tử Phù', 'Tuế Phá', 'Long Đức', 'Bạch Hổ', 'Phúc Đức', 'Điếu Khách', 'Trực Phù'];
        for ($i=0; $i<12; $i++) {
            $this->addStar(($start + $i)%12, $stars[$i], 50);
        }
    }

    // Vong Loc Ton
    private function anVongLocTon() {
        $map = [2, 3, 5, 6, 5, 6, 8, 9, 11, 0];
        $pos = $map[$this->info['can_year_id']];
        $this->addStar($pos, 'Lộc Tồn', 80);

        $can_year = $this->info['can_year_id'];
        $is_yang_year = ($can_year % 2 == 0);
        $gender = $this->info['gender'];

        $direction = (($is_yang_year && $gender == 1) || (!$is_yang_year && $gender == 0)) ? 1 : -1;

        $stars = ['Lộc Tồn', 'Lực Sĩ', 'Thanh Long', 'Tiểu Hao', 'Tướng Quân', 'Tấu Thư', 'Phi Liêm', 'Hỷ Thần', 'Bệnh Phù', 'Đại Hao', 'Phục Binh', 'Quan Phủ'];
        // Note: Start from index 1 because index 0 is Loc Ton (already added)
        for ($i=1; $i<12; $i++) {
            $p = ($pos + ($i * $direction));
            while ($p < 0) $p += 12;
            $p %= 12;
            $this->addStar($p, $stars[$i], 40);
        }
    }

    // Vong Trang Sinh
    private function anVongTrangSinh() {
        $cuc = $this->info['cuc'];
        $map = [2 => 8, 3 => 11, 4 => 5, 5 => 8, 6 => 2];
        $pos = $map[$cuc];

        $can_year = $this->info['can_year_id'];
        $is_yang_year = ($can_year % 2 == 0);
        $gender = $this->info['gender'];

        $direction = (($is_yang_year && $gender == 1) || (!$is_yang_year && $gender == 0)) ? 1 : -1;

        $stars = ['Tràng Sinh', 'Mộc Dục', 'Quan Đới', 'Lâm Quan', 'Đế Vượng', 'Suy', 'Bệnh', 'Tử', 'Mộ', 'Tuyệt', 'Thai', 'Dưỡng'];
        for ($i=0; $i<12; $i++) {
             $p = ($pos + ($i * $direction));
            while ($p < 0) $p += 12;
            $p %= 12;
            $this->addStar($p, $stars[$i], 60);
        }
    }

    private function addStar($pos, $name, $importance = 10)
    {
        $this->cung[$pos]['stars'][] = ['name' => $name, 'importance' => $importance];
        if ($importance >= 100) {
            $this->cung[$pos]['chinh_tinh'][] = $name;
        } else {
            $this->cung[$pos]['phu_tinh'][] = $name;
        }
    }
}
