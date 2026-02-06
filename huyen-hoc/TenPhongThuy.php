<?php
/**
 * Class TenPhongThuy
 * Chức năng:
 * 1. Tự động tính Ngũ Hành Nạp Âm của người (theo năm sinh).
 * 2. Tính Ngũ Hành của Tên (theo số nét chữ Hán/Phồn thể).
 * 3. Luận giải sự tương hợp giữa Tên và Mệnh.
 */

class TenPhongThuy {

    // --- CẤU HÌNH DỮ LIỆU ---

    // 1. Thiên Can (Dùng tính Nạp Âm)
    // Giá trị 'val' theo công thức cổ: Giáp/Ất=1, Bính/Đinh=2, Mậu/Kỷ=3, Canh/Tân=4, Nhâm/Quý=5
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
    // Giá trị 'val': Tý/Sửu/Ngọ/Mùi=0, Dần/Mão/Thân/Dậu=1, Thìn/Tỵ/Tuất/Hợi=2
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

    // 3. Hệ thống Ngũ Hành (ID chuẩn dùng cho cả class)
    // 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
    const NGU_HANH = [
        1 => ['name' => 'Kim',  'color' => '#f1c40f'],
        2 => ['name' => 'Thủy', 'color' => '#3498db'],
        3 => ['name' => 'Hỏa',  'color' => '#e74c3c'],
        4 => ['name' => 'Thổ',  'color' => '#8e44ad'],
        5 => ['name' => 'Mộc',  'color' => '#2ecc71']
    ];

    public function __construct() {
        // Class này tính toán trực tiếp, không cần khởi tạo dữ liệu lớn
    }

    // --- PHẦN 1: TÍNH MỆNH NGƯỜI (NẠP ÂM) ---

    /**
     * Tính ngũ hành bản mệnh dựa trên năm sinh dương lịch.
     * Sử dụng thuật toán Lục Thập Hoa Giáp: (Can + Chi) > 5 ? -5 : Giữ nguyên.
     */
    public function getMenhNguoi($year) {
        $canIdx = $year % 10;
        $chiIdx = $year % 12;

        $canVal = self::THIEN_CAN[$canIdx]['val'];
        $chiVal = self::DIA_CHI[$chiIdx]['val'];

        $sum = $canVal + $chiVal;
        if ($sum > 5) $sum -= 5;
        // Kết quả $sum chính là ID hành (1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc)

        return [
            'year' => $year,
            'can_chi' => self::THIEN_CAN[$canIdx]['name'] . ' ' . self::DIA_CHI[$chiIdx]['name'],
            'hanh_id' => $sum,
            'hanh_text' => self::NGU_HANH[$sum]['name'],
            'color' => self::NGU_HANH[$sum]['color']
        ];
    }

    // --- PHẦN 2: TÍNH HÀNH CỦA TÊN (SỐ NÉT) ---

    /**
     * Tính ngũ hành của tên dựa trên tổng số nét (Hán tự).
     * Quy tắc Số Lý (phổ biến nhất trong đặt tên):
     * 1-2: Mộc, 3-4: Hỏa, 5-6: Thổ, 7-8: Kim, 9-0: Thủy.
     */
    public function getHanhTen($soNet) {
        $lastDigit = $soNet % 10;
        
        // Mapping số đuôi sang ID Ngũ Hành của Class
        // ID Class: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
        $map = [
            1 => 5, 2 => 5, // 1,2 -> Mộc (ID 5)
            3 => 3, 4 => 3, // 3,4 -> Hỏa (ID 3)
            5 => 4, 6 => 4, // 5,6 -> Thổ (ID 4)
            7 => 1, 8 => 1, // 7,8 -> Kim (ID 1)
            9 => 2, 0 => 2  // 9,0 -> Thủy (ID 2)
        ];

        $hanhID = $map[$lastDigit];

        return [
            'strokes' => $soNet,
            'last_digit' => $lastDigit,
            'hanh_id' => $hanhID,
            'hanh_text' => self::NGU_HANH[$hanhID]['name'],
            'color' => self::NGU_HANH[$hanhID]['color']
        ];
    }

    // --- PHẦN 3: PHÂN TÍCH TƯƠNG HỢP ---

    /**
     * So sánh Tên (Chủ thể) và Mệnh (Khách thể)
     * Ưu tiên: Tên sinh Mệnh (Tốt nhất) hoặc Tương Hòa.
     */
    public function phanTichTen($year, $soNetTen) {
        $menh = $this->getMenhNguoi($year);
        $ten = $this->getHanhTen($soNetTen);

        $relation = $this->soSanhNguHanh($ten['hanh_id'], $menh['hanh_id']);

        return [
            'nguoi' => $menh,
            'ten' => $ten,
            'ket_luan' => $relation['msg'],
            'diem' => $relation['score'],
            'chi_tiet' => "Hành của Tên là {$ten['hanh_text']} tác động vào Bản mệnh {$menh['hanh_text']} => {$relation['msg']}."
        ];
    }

    /**
     * Logic so sánh Sinh/Khắc
     * Input ID: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
     */
    private function soSanhNguHanh($idTen, $idMenh) {
        // Quy luật Tương Sinh: Kim(1)->Thủy(2)->Mộc(5)->Hỏa(3)->Thổ(4)->Kim(1)
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1];
        
        // Quy luật Tương Khắc: Kim(1)->Mộc(5)->Thổ(4)->Thủy(2)->Hỏa(3)->Kim(1)
        $khac = [1=>5, 5=>4, 4=>2, 2=>3, 3=>1];

        if ($idTen == $idMenh) {
            return ['msg' => 'Tương Hòa (Bình - Tốt)', 'score' => 1];
        }
        
        // Tên sinh Mệnh (Sinh Nhập - Tốt nhất)
        // Ví dụ: Tên Kim (1), Mệnh Thủy (2). Kim sinh Thủy.
        if ($sinh[$idTen] == $idMenh) {
            return ['msg' => 'Tương Sinh (Đại Cát)', 'score' => 2];
        }

        // Mệnh sinh Tên (Sinh Xuất - Hao tổn)
        // Ví dụ: Tên Thủy (2), Mệnh Kim (1). Kim sinh Thủy -> Mệnh bị tiết khí.
        if ($sinh[$idMenh] == $idTen) {
            return ['msg' => 'Sinh Xuất (Hao tổn)', 'score' => -0.5];
        }

        // Tên khắc Mệnh (Khắc Nhập - Đại Hung)
        // Ví dụ: Tên Hỏa (3), Mệnh Kim (1). Hỏa khắc Kim -> Tên làm hại Mệnh.
        if ($khac[$idTen] == $idMenh) {
            return ['msg' => 'Tương Khắc (Đại Hung)', 'score' => -2];
        }

        // Mệnh khắc Tên (Khắc Xuất - Trung bình)
        // Ví dụ: Tên Kim (1), Mệnh Hỏa (3). Hỏa khắc Kim -> Mệnh chế ngự được Tên.
        if ($khac[$idMenh] == $idTen) {
            return ['msg' => 'Khắc Xuất (Bình thường)', 'score' => 0];
        }

        return ['msg' => 'Không xác định', 'score' => 0];
    }
}

// --- VÍ DỤ SỬ DỤNG ---
/*
$app = new TenPhongThuy();

// Ví dụ: Người sinh năm 1991 (Tân Mùi - Lộ Bàng Thổ)
// Tên "Hùng" (雄) - 12 nét. 
// Theo quy tắc số lý: Số cuối là 2 -> Hành Mộc.
// Mộc khắc Thổ -> Tên khắc Mệnh (Xấu).

$ketQua = $app->phanTichTen(1991, 12);

echo "Năm sinh: " . $ketQua['nguoi']['year'] . " (" . $ketQua['nguoi']['can_chi'] . ") - Mệnh " . $ketQua['nguoi']['hanh_text'] . "<br>";
echo "Tên có " . $ketQua['ten']['strokes'] . " nét - Thuộc hành " . $ketQua['ten']['hanh_text'] . "<br>";
echo "Kết luận: " . $ketQua['ket_luan'] . " (" . $ketQua['chi_tiet'] . ")";
*/
?>
