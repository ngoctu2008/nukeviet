<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class TenPhongThuy {

    // --- CẤU HÌNH DỮ LIỆU ---

    // 1. Thiên Can (Dùng tính Nạp Âm)
    // Val: Giáp/Ất=1, Bính/Đinh=2, Mậu/Kỷ=3, Canh/Tân=4, Nhâm/Quý=5
    const THIEN_CAN = [
        0 => ['name' => 'Canh', 'val' => 4],
        1 => ['name' => 'Tân',  'val' => 4],
        2 => ['name' => 'Nhâm', 'val' => 5],
        3 => ['name' => 'Quý',  'val' => 5],
        4 => ['name' => 'Giáp', 'val' => 1],
        5 => ['name' => 'Ất',   'val' => 1],
        6 => ['name' => 'Bính', 'val' => 2],
        7 => ['name' => 'Đinh', 'val' => 2],
        8 => ['name' => 'Mậu',  'val' => 3],
        9 => ['name' => 'Kỷ',   'val' => 3],
    ];

    // 2. Địa Chi
    // Val: Tý/Sửu/Ngọ/Mùi=0, Dần/Mão/Thân/Dậu=1, Thìn/Tỵ/Tuất/Hợi=2
    const DIA_CHI = [
        0 =>  ['name' => 'Thân', 'val' => 1],
        1 =>  ['name' => 'Dậu',  'val' => 1],
        2 =>  ['name' => 'Tuất', 'val' => 2],
        3 =>  ['name' => 'Hợi',  'val' => 2],
        4 =>  ['name' => 'Tý',   'val' => 0],
        5 =>  ['name' => 'Sửu',  'val' => 0],
        6 =>  ['name' => 'Dần',  'val' => 1],
        7 =>  ['name' => 'Mão',  'val' => 1],
        8 =>  ['name' => 'Thìn', 'val' => 2],
        9 =>  ['name' => 'Tỵ',   'val' => 2],
        10 => ['name' => 'Ngọ',  'val' => 0],
        11 => ['name' => 'Mùi',  'val' => 0],
    ];

    // 3. Hệ thống Ngũ Hành (ID chuẩn)
    const NGU_HANH = [
        1 => ['name' => 'Kim',  'color' => '#f1c40f'], // Vàng
        2 => ['name' => 'Thủy', 'color' => '#3498db'], // Xanh dương
        3 => ['name' => 'Hỏa',  'color' => '#e74c3c'], // Đỏ
        4 => ['name' => 'Thổ',  'color' => '#8e44ad'], // Tím
        5 => ['name' => 'Mộc',  'color' => '#2ecc71']  // Xanh lá
    ];

    // 4. Các số nét ĐẠI KỴ với Nữ Mệnh (Số Cô Độc/Thủ Lĩnh)
    // Phụ nữ dùng các số này tài giỏi nhưng tình duyên lận đận, khắc phu.
    const SO_KY_NU = [21, 23, 29, 33, 39];

    public function __construct() {}

    // --- PHẦN 1: TÍNH MỆNH NGƯỜI (NẠP ÂM) ---

    /**
     * Tính ngũ hành nạp âm dựa trên năm sinh.
     * CT: (Can + Chi) > 5 ? -5 : Giữ nguyên.
     */
    public function getMenhNguoi($year) {
        $canIdx = $year % 10;
        $chiIdx = $year % 12;

        $canData = self::THIEN_CAN[$canIdx];
        $chiData = self::DIA_CHI[$chiIdx];

        $sum = $canData['val'] + $chiData['val'];
        if ($sum > 5) $sum -= 5;

        return [
            'year' => $year,
            'can_chi' => $canData['name'] . ' ' . $chiData['name'],
            'hanh_id' => $sum,
            'hanh_text' => self::NGU_HANH[$sum]['name'],
            'color' => self::NGU_HANH[$sum]['color']
        ];
    }

    // --- PHẦN 2: TÍNH CUNG PHI (BÁT TRẠCH) ---

    /**
     * Tính Cung Phi dựa trên Năm sinh và Giới tính.
     * Nam: 11 - Tổng số; Nữ: 4 + Tổng số.
     */
    public function getCungPhi($year, $gender) {
        // Cộng dồn các số của năm sinh đến khi còn 1 chữ số
        // 1999 -> 1+9+9+9=28 -> 2+8=10 -> 1+0=1
        $sum = array_sum(str_split($year));
        while ($sum > 9) {
            $sum = array_sum(str_split($sum));
        }

        // Tính quái số (1=Nam, 0=Nữ)
        if ($gender == 1) {
            $val = 11 - $sum;
            // 11 - 1 = 10 -> 1+0=1 ? No, standard formula: Keep reducing or mod 9?
            // Standard Bat Trach: 11 - (sum reduced to 1-9). Result if >9 reduce again? Or just subtract?
            // E.g. 1999 sum=1. 11-1=10 -> 1+0=1 (Kham). Correct.
            // But if result is 0 (e.g. 2000 sum=2, 11-2=9. 2009 sum=2? 2+0+0+9=11->2. 11-2=9 Ly. Correct).
            // Wait, if result > 9? 1994 -> 23 -> 5. 11-5=6 (Can).
            // Let's implement reduction for result too.
            while ($val > 9) {
                 $val = array_sum(str_split($val));
            }
            if ($val == 0) $val = 9; // Should not happen with 11-X where X in [1..9] except X=2 (11-2=9). 11-11? No X is single digit sum.
            // Wait, sum is single digit 1..9.
            // 11 - 1 = 10 -> 1.
            // 11 - 9 = 2.

            // Correction for 2000+ ? No, formula for 1900-1999 is different from 2000-2099 usually?
            // Popular formula:
            // 19xx: Nam (10 - sum), Nu (5 + sum) ?
            // Let's stick to the code provided by user but ensure it works.
            // User code: if ($val > 9) $val -= 9;
            // 11 - 1 = 10 -> 10-9 = 1. Correct.
            if ($val > 9) $val -= 9;
        } else {
            // Nu: 4 + sum
            // 1999 sum=1. 4+1=5 (Can). Correct.
            $val = 4 + $sum;
            if ($val > 9) $val -= 9;
        }

        // Xử lý Trung Cung (5): Nam quy về Khôn (2), Nữ quy về Cấn (8)
        if ($val == 5) $val = ($gender == 1) ? 2 : 8;

        // Map Quái Số sang Ngũ Hành
        // 1=Khảm(Thủy), 2=Khôn(Thổ), 3=Chấn(Mộc), 4=Tốn(Mộc), 6=Càn(Kim), 7=Đoài(Kim), 8=Cấn(Thổ), 9=Ly(Hỏa)
        // ID Ngũ hành Class: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
        $mapHanh = [
            1 => 2, // Thủy
            2 => 4, // Thổ
            3 => 5, // Mộc
            4 => 5, // Mộc
            6 => 1, // Kim
            7 => 1, // Kim
            8 => 4, // Thổ
            9 => 3  // Hỏa
        ];

        $tenCung = [
            1=>'Khảm', 2=>'Khôn', 3=>'Chấn', 4=>'Tốn',
            6=>'Càn', 7=>'Đoài', 8=>'Cấn', 9=>'Ly'
        ];

        // Safety check if val not in map (e.g. 5 resolved)
        $hanhID = isset($mapHanh[$val]) ? $mapHanh[$val] : 4; // Default Tho

        return [
            'quai_so' => $val,
            'ten_cung' => isset($tenCung[$val]) ? $tenCung[$val] : '',
            'hanh_id' => $hanhID,
            'hanh_text' => self::NGU_HANH[$hanhID]['name'],
            'color' => self::NGU_HANH[$hanhID]['color']
        ];
    }

    // --- PHẦN 3: TÍNH HÀNH CỦA TÊN (SỐ NÉT) ---

    /**
     * Tính ngũ hành tên theo số đuôi (Số Lý).
     * 1-2: Mộc, 3-4: Hỏa, 5-6: Thổ, 7-8: Kim, 9-0: Thủy.
     */
    public function getHanhTen($soNet) {
        $lastDigit = $soNet % 10;

        // Map số đuôi sang ID Ngũ Hành Class
        $map = [
            1 => 5, 2 => 5, // Mộc
            3 => 3, 4 => 3, // Hỏa
            5 => 4, 6 => 4, // Thổ
            7 => 1, 8 => 1, // Kim
            9 => 2, 0 => 2  // Thủy
        ];

        $hanhID = $map[$lastDigit];

        return [
            'strokes' => $soNet,
            'hanh_id' => $hanhID,
            'hanh_text' => self::NGU_HANH[$hanhID]['name'],
            'color' => self::NGU_HANH[$hanhID]['color']
        ];
    }

    // --- PHẦN 4: TỔNG HỢP & PHÂN TÍCH ---

    /**
     * Hàm chính: Phân tích Tên Toàn Diện
     * @param int $year Năm sinh
     * @param int $soNetTen Tổng số nét (Chữ Hán)
     * @param int $gender 1=Nam, 0=Nữ
     */
    public function phanTichTen($year, $soNetTen, $gender) {
        $menh = $this->getMenhNguoi($year);
        $cung = $this->getCungPhi($year, $gender);
        $ten = $this->getHanhTen($soNetTen);

        // 1. So sánh Tên vs Mệnh (Nạp âm) - Trọng số 40%
        $relMenh = $this->soSanhNguHanh($ten['hanh_id'], $menh['hanh_id']);

        // 2. So sánh Tên vs Cung Phi (Bát trạch) - Trọng số 40%
        $relCung = $this->soSanhNguHanh($ten['hanh_id'], $cung['hanh_id']);

        // 3. Kiểm tra Hạn Giới Tính (Nữ kỵ số cường) - Trọng số 20%
        $genderCheck = ['score' => 0, 'msg' => ''];
        if ($gender == 0 && in_array($soNetTen, self::SO_KY_NU)) {
            $genderCheck = [
                'score' => -2,
                'msg' => "<span class='text-danger bold'>Đại Kỵ: Tổng cách $soNetTen là số Cô Độc/Thủ Lĩnh. Nữ mệnh dùng số này tài giỏi nhưng dễ lấn quyền chồng, tình duyên trắc trở.</span>"
            ];
        }

        // Tính tổng điểm
        $totalScore = $relMenh['score'] + $relCung['score'] + $genderCheck['score'];

        return [
            'user' => [
                'nam_sinh' => $year,
                'gioi_tinh' => ($gender==1) ? 'Nam' : 'Nữ',
                'can_chi' => $menh['can_chi'],
                'menh_text' => $menh['hanh_text'],
                'cung_text' => $cung['ten_cung'] . " (" . $cung['hanh_text'] . ")"
            ],
            'ten' => [
                'so_net' => $ten['strokes'],
                'hanh_ten' => $ten['hanh_text'],
                'color' => $ten['color']
            ],
            'chi_tiet' => [
                'vs_menh' => $relMenh,
                'vs_cung' => $relCung,
                'canh_bao_nu' => $genderCheck
            ],
            'tong_diem' => $totalScore,
            'ket_luan' => $this->getKetLuan($totalScore)
        ];
    }

    /**
     * Logic so sánh Sinh/Khắc
     */
    private function soSanhNguHanh($idTen, $idGoc) {
        // ID: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1]; // Kim sinh Thuy, Thuy sinh Moc...
        $khac = [1=>5, 5=>4, 4=>2, 2=>3, 3=>1]; // Kim khac Moc, Moc khac Tho...

        // $sinh maps [From => To].
        // If Ten generates Goc (Sinh Nhap - Good): $sinh[idTen] == idGoc.
        // If Goc generates Ten (Sinh Xuat - Bad/Weak): $sinh[idGoc] == idTen.

        // Wait, "Sinh Nhap" is "Duoc Sinh" or "Sinh ra"?
        // Usually, Object (Ten) sinh Subject (Nguoi) is Sinh Nhap (Good).
        // Subject (Nguoi) sinh Object (Ten) is Sinh Xuat (Bad).

        if ($idTen == $idGoc) return ['msg' => 'Tương Hòa (Tốt)', 'score' => 1];

        // Ten sinh Goc (Sinh Nhap)
        if (isset($sinh[$idTen]) && $sinh[$idTen] == $idGoc) return ['msg' => 'Tương Sinh (Rất Tốt - Sinh nhập)', 'score' => 2];

        // Goc sinh Ten (Sinh Xuat)
        if (isset($sinh[$idGoc]) && $sinh[$idGoc] == $idTen) return ['msg' => 'Sinh Xuất (Hao tổn)', 'score' => -0.5];

        // Ten khac Goc (Khac Nhap - Ten controls Nguoi - Bad)
        if (isset($khac[$idTen]) && $khac[$idTen] == $idGoc) return ['msg' => 'Tương Khắc (Xấu - Khắc nhập)', 'score' => -2];

        // Goc khac Ten (Khac Xuat - Nguoi controls Ten - Neutral/Ok)
        if (isset($khac[$idGoc]) && $khac[$idGoc] == $idTen) return ['msg' => 'Khắc Xuất (Trung bình)', 'score' => 0];

        return ['msg' => 'Không xác định', 'score' => 0];
    }

    private function getKetLuan($score) {
        if ($score >= 3.5) return "ĐẠI CÁT";
        if ($score >= 1.5) return "CÁT";
        if ($score >= 0) return "BÌNH HÒA";
        if ($score >= -2) return "HUNG";
        return "ĐẠI HUNG";
    }
}
