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

    /**
     * Get Sao Han for a specific age
     * @param int $age (Tuoi am)
     * @param int $gender (1=Male, 0=Female)
     * @return array
     */
    public function getSaoHan($age, $gender)
    {
        // 9 Sao: La Hau, Tho Tu, Thuy Dieu, Thai Bach, Thai Duong, Van Hon, Ke Do, Thai Am, Moc Duc
        // Mapping based on remainder of age
        // But standard tables are easier.
        // Male: 10 La Hau, 11 Tho Tu...
        // Let's use array map for 10-99 or mod 9 logic?
        // Mod 9 logic is complex because it shifts.
        // Array map for 9 stars cycle:
        // Nam: La Hau (1), Tho Tu (2), Thuy Dieu (3), Thai Bach (4), Thai Duong (5), Van Hon (6), Ke Do (7), Thai Am (8), Moc Duc (9)
        // Age: 10 -> La Hau (1). 11 -> Tho Tu (2). 10 % 9 = 1. So (Age - 10) % 9 + 1 ?
        // 19 -> La Hau. (19-10)%9 = 0 -> +1 = 1. Correct.
        // 18 -> Moc Duc (9). (18-10)%9 = 8 -> +1 = 9. Correct.

        // Nu: Ke Do (1), Van Hon (2), Moc Duc (3), Thai Am (4), Tho Tu (5), La Hau (6), Thai Duong (7), Thai Bach (8), Thuy Dieu (9)
        // Age 10: Ke Do.

        $sao_nam = [1=>'La Hầu', 2=>'Thổ Tú', 3=>'Thủy Diệu', 4=>'Thái Bạch', 5=>'Thái Dương', 6=>'Vân Hớn', 7=>'Kế Đô', 8=>'Thái Âm', 9=>'Mộc Đức'];
        $sao_nu = [1=>'Kế Đô', 2=>'Vân Hớn', 3=>'Mộc Đức', 4=>'Thái Âm', 5=>'Thổ Tú', 6=>'La Hầu', 7=>'Thái Dương', 8=>'Thái Bạch', 9=>'Thủy Diệu'];

        // Han: 8 Han.
        // Huynh Tuyen, Tam Kheo, Ngu Mo, Thien Tinh, Toan Tan, Thien La, Dia Vong, Diem Vuong.
        // Nam: 10 Huynh Tuyen, 11 Tam Kheo...
        // Nu: 10 Toan Tan...
        // Cycle 8.

        $han_nam = [1=>'Huỳnh Tuyền', 2=>'Tam Kheo', 3=>'Ngũ Mộ', 4=>'Thiên Tinh', 5=>'Toán Tận', 6=>'Thiên La', 7=>'Địa Võng', 8=>'Diêm Vương'];
        $han_nu = [1=>'Toán Tận', 2=>'Thiên Tinh', 3=>'Ngũ Mộ', 4=>'Tam Kheo', 5=>'Huỳnh Tuyền', 6=>'Diêm Vương', 7=>'Địa Võng', 8=>'Thiên La']; // Check Nu order?
        // Nu 10: Toan Tan. 11 Thien Tinh.

        // Calculate index
        // Start from age 10. If age < 10?
        // Under 10 usually no Sao Han calculated same way.
        // Let's assume age >= 10. If < 10, maybe map to 10? Or return empty.

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
