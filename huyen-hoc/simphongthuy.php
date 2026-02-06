<?php
/**
 * Class SimPhongThuy
 * Chức năng: 
 * 1. Tự động tính Ngũ Hành Nạp Âm cho 60 Hoa Giáp.
 * 2. Tính ngũ hành bản mệnh theo Năm sinh.
 * 3. Phân tích SIM theo phương pháp chia cặp số (Lục thập hoa giáp).
 */

class SimPhongThuy {

    // --- CẤU HÌNH DỮ LIỆU CƠ BẢN ---

    // 1. Thiên Can (Dùng để tính tuổi và Nạp âm)
    // Giá trị 'val' dùng cho công thức Nạp âm: 
    // Giáp/Ất=1, Bính/Đinh=2, Mậu/Kỷ=3, Canh/Tân=4, Nhâm/Quý=5
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

    // 3. ID Ngũ Hành (Kết quả của công thức Nạp âm)
    // Công thức: (Can + Chi). Nếu > 5 thì trừ 5.
    // Kết quả: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
    const NGU_HANH = [
        1 => ['name' => 'Kim',  'color' => '#f1c40f'], // Vàng
        2 => ['name' => 'Thủy', 'color' => '#3498db'], // Xanh dương
        3 => ['name' => 'Hỏa',  'color' => '#e74c3c'], // Đỏ
        4 => ['name' => 'Thổ',  'color' => '#8e44ad'], // Nâu/Tím
        5 => ['name' => 'Mộc',  'color' => '#2ecc71']  // Xanh lá
    ];

    // Mảng chứa dữ liệu 60 Hoa Giáp sau khi khởi tạo
    protected $tableHoaGiap = [];

    public function __construct() {
        $this->generateLucThapHoaGiap();
    }

    // --- PHẦN 1: KHỞI TẠO DỮ LIỆU ---

    /**
     * Thuật toán sinh bảng 60 Hoa Giáp tự động
     * Không cần nhập tay, đảm bảo chính xác theo công thức cổ.
     */
    private function generateLucThapHoaGiap() {
        // Vòng hoa giáp bắt đầu từ Giáp Tý
        // Index trong mảng CONST: Giáp = 4, Tý = 4
        $canIdx = 4; 
        $chiIdx = 4;

        for ($i = 1; $i <= 60; $i++) {
            // 1. Lấy thông tin Can Chi
            $canData = self::THIEN_CAN[$canIdx];
            $chiData = self::DIA_CHI[$chiIdx];
            
            // 2. Tính Ngũ Hành Nạp Âm
            // CT: (ValCan + ValChi). Nếu > 5 thì trừ 5.
            $sum = $canData['val'] + $chiData['val'];
            if ($sum > 5) $sum -= 5;
            $hanhID = $sum; // 1..5

            // 3. Lưu vào bảng (Key là số từ 1-60)
            $this->tableHoaGiap[$i] = [
                'id' => $i,
                'name' => $canData['name'] . ' ' . $chiData['name'],
                'hanh_id' => $hanhID,
                'hanh_text' => self::NGU_HANH[$hanhID]['name']
            ];

            // 4. Tăng index cho vòng lặp sau
            $canIdx++; if ($canIdx > 9) $canIdx = 0;
            $chiIdx++; if ($chiIdx > 11) $chiIdx = 0;
        }
    }

    // --- PHẦN 2: TÍNH TOÁN NGƯỜI DÙNG ---

    /**
     * Lấy ngũ hành nạp âm của người dùng theo năm sinh
     */
    public function getUserInfo($year) {
        // Lấy số cuối của năm để tìm Can (0=Canh... 9=Kỷ)
        $canIdx = $year % 10;
        
        // Lấy dư 12 để tìm Chi (0=Thân... 11=Mùi)
        $chiIdx = $year % 12;

        // Tính nạp âm
        $canVal = self::THIEN_CAN[$canIdx]['val'];
        $chiVal = self::DIA_CHI[$chiIdx]['val'];
        $sum = $canVal + $chiVal;
        if ($sum > 5) $sum -= 5;

        return [
            'year' => $year,
            'can_chi' => self::THIEN_CAN[$canIdx]['name'] . ' ' . self::DIA_CHI[$chiIdx]['name'],
            'hanh_id' => $sum,
            'hanh_text' => self::NGU_HANH[$sum]['name'],
            'color' => self::NGU_HANH[$sum]['color']
        ];
    }

    // --- PHẦN 3: PHÂN TÍCH SIM ---

    /**
     * Phân tích số điện thoại
     * @param string $phone Số điện thoại
     * @param int $year Năm sinh
     */
    public function analyzeSim($phone, $year) {
        $userInfo = $this->getUserInfo($year);
        
        // 1. Xử lý chuỗi số: Bỏ ký tự lạ, lấy số
        $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
        
        // 2. Chia cặp số (Tách 2 số một)
        // VD: 0912345678 -> 09, 12, 34, 56, 78
        // Nếu lẻ, bỏ số đầu tiên (hoặc xử lý tuỳ quy ước, ở đây ta lấy cặp từ đuôi lên hoặc cắt đều)
        // Cách chia chuẩn: str_split
        $pairs = str_split($cleanPhone, 2);
        
        $analysis = [];
        $totalScore = 0;

        foreach ($pairs as $pairStr) {
            // Chỉ xử lý cặp đủ 2 số
            if (strlen($pairStr) < 2) continue; 

            $val = intval($pairStr);
            
            // 3. Quy đổi về 1-60
            // Quy tắc: 00 = 60. > 60 thì trừ 60.
            if ($val == 0) $mappedVal = 60;
            elseif ($val > 60) $mappedVal = $val - 60;
            else $mappedVal = $val;

            // 4. Lấy thông tin Hoa Giáp
            $hoaGiap = $this->tableHoaGiap[$mappedVal];
            
            // 5. So sánh ngũ hành
            $relation = $this->compareElement($hoaGiap['hanh_id'], $userInfo['hanh_id']);
            
            $totalScore += $relation['score'];

            $analysis[] = [
                'pair' => $pairStr,
                'mapped_val' => $mappedVal,
                'name' => $hoaGiap['name'],
                'hanh_sim' => $hoaGiap['hanh_text'],
                'relation' => $relation['msg'],
                'score' => $relation['score'],
                'color' => self::NGU_HANH[$hoaGiap['hanh_id']]['color']
            ];
        }

        // Kết luận
        $conclusion = "Bình thường";
        if ($totalScore > 2) $conclusion = "Đại Cát (Rất tốt)";
        elseif ($totalScore > 0) $conclusion = "Cát (Tốt)";
        elseif ($totalScore < -2) $conclusion = "Đại Hung (Rất xấu)";
        elseif ($totalScore < 0) $conclusion = "Hung (Xấu)";

        return [
            'user' => $userInfo,
            'phone' => $cleanPhone,
            'details' => $analysis,
            'total_score' => $totalScore,
            'conclusion' => $conclusion
        ];
    }

    /**
     * So sánh Ngũ hành Sim (A) và Ngũ hành Người (B)
     * Quy tắc: 1-Kim, 2-Thủy, 3-Hỏa, 4-Thổ, 5-Mộc
     */
    private function compareElement($idA, $idB) {
        // Mảng Tra Sinh/Khắc
        // Sinh: Kim(1)->Thủy(2)->Mộc(5)->Hỏa(3)->Thổ(4)->Kim(1)
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1];
        
        // Khắc: Kim(1)->Mộc(5)->Thổ(4)->Thủy(2)->Hỏa(3)->Kim(1)
        $khac = [1=>5, 5=>4, 4=>2, 2=>3, 3=>1];

        if ($idA == $idB) {
            return ['msg' => 'Tương hỗ (Bình)', 'score' => 1];
        }
        if ($sinh[$idA] == $idB) {
            return ['msg' => 'Sinh Nhập (Rất Tốt)', 'score' => 2]; // Sim sinh Người
        }
        if ($sinh[$idB] == $idA) {
            return ['msg' => 'Sinh Xuất (Hao)', 'score' => -0.5]; // Người sinh Sim
        }
        if ($khac[$idA] == $idB) {
            return ['msg' => 'Khắc Nhập (Xấu)', 'score' => -2]; // Sim khắc Người
        }
        if ($khac[$idB] == $idA) {
            return ['msg' => 'Khắc Xuất (Chế ngự)', 'score' => 0.5]; // Người khắc Sim
        }
        return ['msg' => 'Bình hòa', 'score' => 0];
    }
}

// --- HƯỚNG DẪN SỬ DỤNG (DEMO) ---
/*
$app = new SimPhongThuy();
// Phân tích số 0912345678 cho người sinh năm 1990 (Canh Ngọ - Lộ Bàng Thổ)
$result = $app->analyzeSim('0912345678', 1990);

echo "Chủ nhân: " . $result['user']['can_chi'] . " (" . $result['user']['hanh_text'] . ")<br>";
echo "Tổng điểm: " . $result['total_score'] . " - " . $result['conclusion'] . "<br><br>";

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Cặp số</th><th>Hoa Giáp</th><th>Hành Sim</th><th>Luận giải</th></tr>";
foreach ($result['details'] as $row) {
    echo "<tr>";
    echo "<td>{$row['pair']}</td>";
    echo "<td>{$row['name']}</td>";
    echo "<td style='color:{$row['color']}'>{$row['hanh_sim']}</td>";
    echo "<td>{$row['relation']}</td>";
    echo "</tr>";
}
echo "</table>";
*/
?>
