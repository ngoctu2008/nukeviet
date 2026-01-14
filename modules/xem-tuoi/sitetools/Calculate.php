<?php

/**
 * @Project NUKEVIET 4.x
 * @Author NukeViet Developer (contact@nukeviet.vn)
 * @Copyright (C) 2024 NukeViet. All rights reserved
 * @Createdate Sat, 25 May 2024 10:00:00 GMT
 */

namespace NukeViet\Module\XemTuoi\SiteTools;

class Calculate
{
    // Dữ liệu Can
    private static $can = [
        0 => 'Canh', 1 => 'Tân', 2 => 'Nhâm', 3 => 'Quý', 4 => 'Giáp',
        5 => 'Ất', 6 => 'Bính', 7 => 'Đinh', 8 => 'Mậu', 9 => 'Kỷ'
    ];

    // Dữ liệu Chi
    private static $chi = [
        0 => 'Thân', 1 => 'Dậu', 2 => 'Tuất', 3 => 'Hợi', 4 => 'Tý', 5 => 'Sửu',
        6 => 'Dần', 7 => 'Mão', 8 => 'Thìn', 9 => 'Tỵ', 10 => 'Ngọ', 11 => 'Mùi'
    ];

    // Ngũ hành: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
    // (Quy ước đơn giản hóa để tính toán, cần map chính xác theo Lục Thập Hoa Giáp)

    // Bảng tra Ngũ Hành nạp âm theo Can Chi (Lục thập hoa giáp)
    // Key: Tên Can Chi (hoặc mã). Value: Mệnh (string)
    // Để tối ưu, ta dùng thuật toán tính Mệnh:
    // Can: Giáp/Ất=1, Bính/Đinh=2, Mậu/Kỷ=3, Canh/Tân=4, Nhâm/Quý=5
    // Chi: Tý/Sửu/Ngọ/Mùi=0, Dần/Mão/Thân/Dậu=1, Thìn/Tỵ/Tuất/Hợi=2
    // Tổng = Can + Chi. Nếu > 5 thì trừ 5.
    // Kết quả: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc.

    public static function getCan($year)
    {
        return self::$can[$year % 10];
    }

    public static function getChi($year)
    {
        return self::$chi[$year % 12];
    }

    public static function getCanChi($year)
    {
        return self::getCan($year) . ' ' . self::getChi($year);
    }

    /**
     * Tính Mệnh (Ngũ Hành)
     * Quy ước giá trị trả về: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
     */
    public static function getNguHanh($year)
    {
        $can_val = 0;
        $can_check = $year % 10;
        // Giáp, Ất = 1
        if ($can_check == 4 || $can_check == 5) $can_val = 1;
        // Bính, Đinh = 2
        elseif ($can_check == 6 || $can_check == 7) $can_val = 2;
        // Mậu, Kỷ = 3
        elseif ($can_check == 8 || $can_check == 9) $can_val = 3;
        // Canh, Tân = 4
        elseif ($can_check == 0 || $can_check == 1) $can_val = 4;
        // Nhâm, Quý = 5
        elseif ($can_check == 2 || $can_check == 3) $can_val = 5;

        $chi_val = 0;
        $chi_check = $year % 12;
        // Tý, Sửu, Ngọ, Mùi = 0
        if (in_array($chi_check, [4, 5, 10, 11])) $chi_val = 0;
        // Dần, Mão, Thân, Dậu = 1
        elseif (in_array($chi_check, [6, 7, 0, 1])) $chi_val = 1;
        // Thìn, Tỵ, Tuất, Hợi = 2
        elseif (in_array($chi_check, [8, 9, 2, 3])) $chi_val = 2;

        $sum = $can_val + $chi_val;
        if ($sum > 5) $sum -= 5;

        return $sum;
    }

    public static function getNguHanhText($val)
    {
        $map = [1 => 'Kim', 2 => 'Thủy', 3 => 'Hỏa', 4 => 'Thổ', 5 => 'Mộc'];
        return isset($map[$val]) ? $map[$val] : 'Không xác định';
    }

    /**
     * Tính Cung Phi (Thiên Mệnh)
     * Công thức:
     * Cộng các số của năm sinh lại (đến khi còn 1 chữ số).
     * Nam: Lấy 11 trừ đi số đó. (Nếu >9 lại cộng tiếp)
     * Nữ: Lấy 4 cộng với số đó. (Nếu >9 lại cộng tiếp)
     * Hoặc dùng bảng tra Cung Phi Bát Trạch.
     * Quy ước trả về text: Khảm, Ly, Chấn, Tốn, Càn, Đoài, Cấn, Khôn.
     */
    public static function getCungPhi($year, $gender)
    {
        // 1=Nam, 0=Nữ
        $sum = array_sum(str_split((string)$year));
        while ($sum > 9) {
            $sum = array_sum(str_split((string)$sum));
        }

        if ($gender == 1) { // Nam
            $val = 11 - $sum;
        } else { // Nữ
            $val = 4 + $sum;
        }

        while ($val > 9) {
             $val = array_sum(str_split((string)$val));
        }

        // Bảng Cung Phi theo số dư (Cửu tinh)
        // 1: Khảm, 2: Khôn, 3: Chấn, 4: Tốn, 5: Trung cung (Nam Khôn, Nữ Cấn), 6: Càn, 7: Đoài, 8: Cấn, 9: Ly
        // Lưu ý: Số 5, Nam quy về 2 (Khôn), Nữ quy về 8 (Cấn).

        if ($val == 5) {
            if ($gender == 1) $val = 2;
            else $val = 8;
        }

        $cung = [
            1 => 'Khảm', 2 => 'Khôn', 3 => 'Chấn', 4 => 'Tốn',
            5 => 'Trung', 6 => 'Càn', 7 => 'Đoài', 8 => 'Cấn', 9 => 'Ly'
        ];

        return isset($cung[$val]) ? $cung[$val] : '';
    }

    /**
     * Tính điểm Thiên Can (2 điểm)
     * Hợp: 2, Bình: 1, Phá/Xung: 0
     */
    public static function getScoreCan($year1, $year2)
    {
        $can1 = $year1 % 10;
        $can2 = $year2 % 10;

        // Hợp (Hóa): Giáp-Kỷ, Ất-Canh, Bính-Tân, Đinh-Nhâm, Mậu-Quý
        // Hiệu số (mod 10) là 5 hoặc -5
        if (abs($can1 - $can2) == 5) return 2;

        // Phá (Xung): Giáp-Canh, Ất-Tân, Bính-Nhâm, Đinh-Quý
        // Hiệu số là 4 hoặc 6 (đối xung qua vòng tròn 10) - Kiểm tra kỹ lại logic Can Phá.
        // Quy tắc xung: 1-7 (Giáp - Canh), 2-8 (Ất - Tân)... cách nhau 6 vị trí (tương tự địa chi xung)
        // Canh (0) - Giáp (4) : 4
        // Tân (1) - Ất (5) : 4
        // ...
        // Thông thường Can xung là Dương khắc Dương, Âm khắc Âm.
        // Giáp (Mộc+) khắc Mậu (Thổ+), Canh (Kim+) khắc Giáp (Mộc+)...
        // Logic đơn giản cho module:
        // Cặp xung chính: Giáp-Canh, Ất-Tân, Bính-Nhâm, Đinh-Quý (Cách nhau 4 hoặc 6 đơn vị mod 10 ?)
        // Check bảng:
        // 0:Canh, 1:Tân, 2:Nhâm, 3:Quý, 4:Giáp, 5:Ất, 6:Bính, 7:Đinh, 8:Mậu, 9:Kỷ
        // Canh(0) - Giáp(4): 4. Tân(1)-Ất(5):4. Nhâm(2)-Bính(6):4. Quý(3)-Đinh(7):4.
        // Giáp(4)-Mậu(8):4. Ất(5)-Kỷ(9):4. Bính(6)-Canh(0): 6.
        // Tóm lại nếu abs(diff) == 4 hoặc 6 => Xung/Khắc.

        $diff = abs($can1 - $can2);
        if ($diff == 4 || $diff == 6) return 0;

        return 1; // Bình hòa
    }

    /**
     * Tính điểm Địa Chi (2 điểm)
     * Tam hợp/Lục hợp: 2, Bình: 1, Tứ hành xung/Lục hại: 0
     */
    public static function getScoreChi($year1, $year2)
    {
        $chi1 = $year1 % 12;
        $chi2 = $year2 % 12;

        // Tam hợp: (Thân-Tý-Thìn), (Dần-Ngọ-Tuất), (Tỵ-Dậu-Sửu), (Hợi-Mão-Mùi)
        // Cách nhau 4 hoặc 8
        $diff = abs($chi1 - $chi2);
        if ($diff == 4 || $diff == 8) return 2;

        // Lục hợp: Tý-Sửu, Dần-Hợi, Mão-Tuất, Thìn-Dậu, Tỵ-Thân, Ngọ-Mùi
        // Tổng cặp = số cố định hoặc check switch case
        // 0:Thân, 1:Dậu, 2:Tuất, 3:Hợi, 4:Tý, 5:Sửu, 6:Dần, 7:Mão, 8:Thìn, 9:Tỵ, 10:Ngọ, 11:Mùi
        // Tý(4)-Sửu(5), Dần(6)-Hợi(3), Mão(7)-Tuất(2), Thìn(8)-Dậu(1), Tỵ(9)-Thân(0), Ngọ(10)-Mùi(11)
        // Logic mapping pairs
        $luc_hop = [
            4=>5, 5=>4,
            6=>3, 3=>6,
            7=>2, 2=>7,
            8=>1, 1=>8,
            9=>0, 0=>9,
            10=>11, 11=>10
        ];
        if (isset($luc_hop[$chi1]) && $luc_hop[$chi1] == $chi2) return 2;

        // Tứ hành xung (Lấy chính xung): Tý-Ngọ, Mão-Dậu, Thìn-Tuất, Sửu-Mùi, Dần-Thân, Tỵ-Hợi
        // Cách nhau 6
        if ($diff == 6) return 0;

        // Tứ hành xung (Bộ 4 con): Dần-Thân-Tỵ-Hợi... (Nếu cần chi tiết hơn thì check)
        // Ở đây tạm tính chính xung (đối nhau) là nặng nhất.

        // Lục hại: Tý-Mùi, Sửu-Ngọ, Dần-Tỵ, Mão-Thìn, Thân-Hợi, Dậu-Tuất
        // Tý(4)-Mùi(11), Sửu(5)-Ngọ(10), Dần(6)-Tỵ(9), Mão(7)-Thìn(8), Thân(0)-Hợi(3), Dậu(1)-Tuất(2)
        $luc_hai = [
            4=>11, 11=>4,
            5=>10, 10=>5,
            6=>9, 9=>6,
            7=>8, 8=>7,
            0=>3, 3=>0,
            1=>2, 2=>1
        ];
        if (isset($luc_hai[$chi1]) && $luc_hai[$chi1] == $chi2) return 0;

        return 1; // Bình hòa
    }

    /**
     * Tính điểm Ngũ Hành (3 điểm)
     * Tương sinh: 3, Bình (Tương hỗ): 2, Sinh xuất/Khắc xuất: 1, Tương khắc: 0
     * Ở đây làm đơn giản: Sinh (3), Hòa (1.5 - 2), Khắc (0)
     */
    public static function getScoreNguHanh($year1, $year2)
    {
        $nh1 = self::getNguHanh($year1);
        $nh2 = self::getNguHanh($year2);

        // 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
        // Tương sinh: Kim(1)->Thủy(2)->Mộc(5)->Hỏa(3)->Thổ(4)->Kim(1)
        $sinh = [
            1 => 2,
            2 => 5,
            5 => 3,
            3 => 4,
            4 => 1
        ];

        // Tương khắc: Kim(1)->Mộc(5)->Thổ(4)->Thủy(2)->Hỏa(3)->Kim(1)
        $khac = [
            1 => 5,
            5 => 4,
            4 => 2,
            2 => 3,
            3 => 1
        ];

        if ($nh1 == $nh2) return 2; // Bình hòa
        if (isset($sinh[$nh1]) && $sinh[$nh1] == $nh2) return 3; // Tương sinh (Thuận)
        if (isset($sinh[$nh2]) && $sinh[$nh2] == $nh1) return 3; // Tương sinh (Nghịch - cũng tốt)

        if (isset($khac[$nh1]) && $khac[$nh1] == $nh2) return 0; // Tương khắc
        if (isset($khac[$nh2]) && $khac[$nh2] == $nh1) return 0; // Tương khắc

        return 1;
    }

    /**
     * Tính điểm Cung Phi (3 điểm)
     * Sinh Khí, Thiên Y, Diên Niên, Phục Vị: Tốt
     * Tuyệt Mệnh, Ngũ Quỷ, Lục Sát, Họa Hại: Xấu
     */
    public static function getScoreCungPhi($year1, $gen1, $year2, $gen2)
    {
        $cung1 = self::getCungPhi($year1, $gen1);
        $cung2 = self::getCungPhi($year2, $gen2);

        // Nhóm Đông Tứ Trạch: Khảm(1), Chấn(3), Tốn(4), Ly(9)
        // Nhóm Tây Tứ Trạch: Khôn(2), Càn(6), Đoài(7), Cấn(8)

        $dong = ['Khảm', 'Chấn', 'Tốn', 'Ly'];
        $tay = ['Khôn', 'Càn', 'Đoài', 'Cấn'];

        $isDong1 = in_array($cung1, $dong);
        $isDong2 = in_array($cung2, $dong);

        // Cùng nhóm là tốt
        if ($isDong1 == $isDong2) return 3;

        // Khác nhóm là xấu
        return 0;
    }

    public static function calculateCompatibility($y1, $g1, $y2, $g2)
    {
        $scoreCan = self::getScoreCan($y1, $y2);
        $scoreChi = self::getScoreChi($y1, $y2);
        $scoreNguHanh = self::getScoreNguHanh($y1, $y2);
        $scoreCungPhi = self::getScoreCungPhi($y1, $g1, $y2, $g2);

        $total = $scoreCan + $scoreChi + $scoreNguHanh + $scoreCungPhi;

        return [
            'scoreCan' => $scoreCan,
            'scoreChi' => $scoreChi,
            'scoreNguHanh' => $scoreNguHanh,
            'scoreCungPhi' => $scoreCungPhi,
            'total' => $total,
            'info1' => [
                'can_chi' => self::getCanChi($y1),
                'ngu_hanh' => self::getNguHanhText(self::getNguHanh($y1)),
                'cung_phi' => self::getCungPhi($y1, $g1)
            ],
            'info2' => [
                'can_chi' => self::getCanChi($y2),
                'ngu_hanh' => self::getNguHanhText(self::getNguHanh($y2)),
                'cung_phi' => self::getCungPhi($y2, $g2)
            ]
        ];
    }
}
