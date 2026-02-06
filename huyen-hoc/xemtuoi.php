<?php
/**
 * Class XemTuoi
 * Chức năng: Xem tuổi hợp khắc (Hôn nhân, Hợp tác).
 * Kết hợp: Thiên Can, Địa Chi, Ngũ Hành Nạp Âm và Cung Phi Bát Trạch.
 */

require_once 'TuViConstants.php';

class XemTuoi {
    
    // --- ĐIỂM SỐ CẤU HÌNH ---
    const SCORE_CAN_HOP = 2;
    const SCORE_CAN_PHA = -2;
    const SCORE_CHI_TAM_HOP = 3;
    const SCORE_CHI_LUC_HOP = 2;
    const SCORE_CHI_LUC_XUNG = -3;
    const SCORE_CHI_TU_HANH_XUNG = -1;
    const SCORE_MENH_TUONG_SINH = 4; 
    const SCORE_MENH_TUONG_HOA = 1;
    const SCORE_MENH_TUONG_KHAC = -4;
    const SCORE_CUNG_PHI_TOT = 3; // Sinh Khí, Diên Niên...
    const SCORE_CUNG_PHI_XAU = -3; // Tuyệt Mệnh, Ngũ Quỷ...

    // Bảng Cung Phi (Dùng cho Bát Trạch)
    // 1=Khảm, 2=Khôn, 3=Chấn, 4=Tốn, 5=Trung(Nam:Khôn, Nữ:Cấn), 6=Càn, 7=Đoài, 8=Cấn, 9=Ly
    // Công thức tính Cung Phi:
    // Nam: (10 - (YearSum % 9)) % 9. Nữ: ((YearSum % 9) + 5) % 9. (Nếu dư 0 thì là 9).
    const CUNG_PHI_NAME = [
        1 => 'Khảm (Thủy)', 2 => 'Khôn (Thổ)', 3 => 'Chấn (Mộc)', 
        4 => 'Tốn (Mộc)', 5 => 'Trung Cung', 6 => 'Càn (Kim)', 
        7 => 'Đoài (Kim)', 8 => 'Cấn (Thổ)', 9 => 'Ly (Hỏa)'
    ];

    // Ma trận phối Cung Phi (Bát San)
    // 1=Sinh Khí, 2=Diên Niên, 3=Thiên Y, 4=Phục Vị (Tốt)
    // -1=Tuyệt Mệnh, -2=Ngũ Quỷ, -3=Lục Sát, -4=Họa Hại (Xấu)
    // Index: [CungNam][CungNu]
    protected $batSanMap = []; 

    public function __construct() {
        $this->initBatSan();
    }

    /**
     * Hàm chính: So sánh 2 tuổi
     * @param int $year1 Năm sinh người A (Chồng/Chủ)
     * @param int $year2 Năm sinh người B (Vợ/Đối tác)
     * @param int $gender1 Giới tính người A (1=Nam, 0=Nữ)
     * @param int $gender2 Giới tính người B
     */
    public function soSanhTuoi($year1, $year2, $gender1 = 1, $gender2 = 0) {
        $info1 = $this->getYearInfo($year1, $gender1);
        $info2 = $this->getYearInfo($year2, $gender2);

        // 1. Thiên Can
        $canScore = $this->checkThienCan($info1['can_id'], $info2['can_id']);

        // 2. Địa Chi
        $chiScore = $this->checkDiaChi($info1['chi_id'], $info2['chi_id']);

        // 3. Ngũ Hành Nạp Âm
        $menhScore = $this->checkNguHanh($info1['hanh_id'], $info2['hanh_id']);

        // 4. Cung Phi Bát Trạch (Quan trọng trong hôn nhân)
        $cungScore = $this->checkCungPhi($info1['cung_phi_id'], $info2['cung_phi_id']);

        // 5. Tổng kết
        $totalScore = $canScore['score'] + $chiScore['score'] + $menhScore['score'] + $cungScore['score'];
        
        return [
            'nguoi_1' => $info1,
            'nguoi_2' => $info2,
            'phan_tich' => [
                'thien_can' => $canScore,
                'dia_chi'   => $chiScore,
                'ngu_hanh'  => $menhScore,
                'cung_phi'  => $cungScore
            ],
            'tong_diem' => $totalScore,
            'ket_luan'  => $this->getKetLuan($totalScore)
        ];
    }

    // --- HELPER FUNCTIONS ---

    private function getYearInfo($year, $gender) {
        // Can (0-9). Năm 4 = Giáp.
        $canID = ($year - 4) % 10; if($canID < 0) $canID += 10;
        
        // Chi (0-11). Năm 4 = Tý.
        $chiID = ($year - 4) % 12; if($chiID < 0) $chiID += 12;

        // Mệnh Ngũ Hành (Lấy từ TuViConstants: 1=Kim...5=Mộc)
        $hanhID = TuViConstants::getNapAmID($canID, $chiID); 
        $hanhInfo = TuViConstants::getNguHanhInfo($hanhID);

        // Tính Cung Phi
        $cungPhiID = $this->calculateCungPhi($year, $gender);

        return [
            'year' => $year,
            'gender' => $gender,
            'can_name' => TuViConstants::CAN[$canID],
            'can_id' => $canID,
            'chi_name' => TuViConstants::CHI[$chiID],
            'chi_id' => $chiID,
            'hanh_name' => $hanhInfo['name'],
            'hanh_id' => $hanhID,
            'cung_phi_name' => self::CUNG_PHI_NAME[$cungPhiID],
            'cung_phi_id' => $cungPhiID
        ];
    }

    /**
     * So sánh Thiên Can (Hợp/Phá)
     */
    private function checkThienCan($c1, $c2) {
        // Hợp: 0-5 (Giáp Kỷ), 1-6 (Ất Canh)... Hiệu số = 5
        if (abs($c1 - $c2) == 5) {
            return ['score' => self::SCORE_CAN_HOP, 'msg' => 'Tương Hợp (Tốt)', 'detail' => 'Thiên can hợp hóa, hỗ trợ nhau.'];
        }
        // Phá: 0-4 (Giáp Mậu), 1-5 (Ất Kỷ)... Hiệu số = 4 hoặc 6
        // Tuy nhiên theo Tử Vi: Can phá nhau không quá nguy hiểm như Chi xung.
        // Chỉ trừ điểm nhẹ nếu khắc ngũ hành can.
        // Logic đơn giản: 
        return ['score' => 0, 'msg' => 'Bình Hòa', 'detail' => 'Thiên can không xung không hợp.'];
    }

    /**
     * So sánh Địa Chi
     */
    private function checkDiaChi($c1, $c2) {
        $diff = abs($c1 - $c2);
        
        // Tam Hợp: 4, 8
        if ($diff == 4 || $diff == 8) return ['score' => self::SCORE_CHI_TAM_HOP, 'msg' => 'Tam Hợp (Rất Tốt)', 'detail' => 'Địa chi thuộc nhóm Tam Hợp cục.'];
        
        // Lục Xung: 6
        if ($diff == 6) return ['score' => self::SCORE_CHI_LUC_XUNG, 'msg' => 'Lục Xung (Xấu)', 'detail' => 'Địa chi xung khắc mạnh, dễ cãi vã.'];
        
        // Lục Hợp: Tý(0)+Sửu(1), Dần(2)+Hợi(11)...
        // Cặp số: 0-1, 2-11, 3-10, 4-9, 5-8, 6-7
        $sum = $c1 + $c2;
        if (($c1==0 && $c2==1) || ($c1==1 && $c2==0)) return ['score' => self::SCORE_CHI_LUC_HOP, 'msg' => 'Lục Hợp (Tốt)', 'detail' => ''];
        // ... Check các cặp còn lại hoặc dùng mảng map
        
        return ['score' => 0, 'msg' => 'Bình Hòa', 'detail' => ''];
    }

    /**
     * So sánh Ngũ Hành (Sinh/Khắc)
     * ID: 1=Kim, 2=Mộc, 3=Thủy, 4=Hỏa, 5=Thổ (Lưu ý ID này phải khớp Constants)
     * Nếu Constants của bạn là: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc thì dùng logic dưới:
     */
    private function checkNguHanh($h1, $h2) {
        // Giả sử dùng chuẩn ID mới: 1=Kim, 2=Thủy, 3=Hỏa, 4=Thổ, 5=Mộc
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1]; // Kim->Thủy->Mộc->Hỏa->Thổ->Kim
        $khac = [1=>5, 5=>2, 2=>3, 3=>4, 4=>1]; // Kim->Mộc->Thủy->Hỏa->Thổ->Kim

        if ($h1 == $h2) return ['score' => self::SCORE_MENH_TUONG_HOA, 'msg' => 'Tương Hòa', 'detail' => 'Lưỡng hành tương tự, bình ổn.'];
        
        // 1 sinh 2 (Sinh Xuất - Hao)
        if ($sinh[$h1] == $h2) return ['score' => 0.5, 'msg' => 'Sinh Xuất', 'detail' => 'Mệnh chủ sinh cho đối phương, có phần thiệt thòi.'];
        // 2 sinh 1 (Sinh Nhập - Lợi)
        if ($sinh[$h2] == $h1) return ['score' => self::SCORE_MENH_TUONG_SINH, 'msg' => 'Tương Sinh (Rất Tốt)', 'detail' => 'Mệnh đối phương sinh cho mình, đại lợi.'];
        
        // 1 khắc 2 (Khắc Xuất - Chế ngự)
        if ($khac[$h1] == $h2) return ['score' => -1, 'msg' => 'Khắc Xuất', 'detail' => 'Mình khắc đối phương, mệt nhọc.'];
        // 2 khắc 1 (Khắc Nhập - Bị hại)
        if ($khac[$h2] == $h1) return ['score' => self::SCORE_MENH_TUONG_KHAC, 'msg' => 'Tương Khắc (Xấu)', 'detail' => 'Bị đối phương khắc chế, bất lợi.'];

        return ['score' => 0, 'msg' => 'Bình', 'detail' => ''];
    }

    /**
     * Tính Cung Phi (Nam/Nữ khác nhau)
     */
    private function calculateCungPhi($year, $gender) {
        $sum = array_sum(str_split($year));
        // Rút gọn sum đến khi còn 1 chữ số
        while ($sum > 9) {
            $sum = array_sum(str_split($sum));
        }
        
        if ($gender == 1) { // Nam: 11 - Sum
            $val = 11 - $sum;
            if ($val > 9) $val -= 9; // Nếu 11-1=10 -> 1
        } else { // Nữ: 4 + Sum
            $val = 4 + $sum;
            if ($val > 9) $val -= 9;
        }
        
        // Xử lý số 5 (Trung cung): Nam=Khôn(2), Nữ=Cấn(8)
        if ($val == 5) return ($gender == 1) ? 2 : 8;
        return $val;
    }

    /**
     * So sánh Cung Phi (Bát San)
     */
    private function checkCungPhi($cp1, $cp2) {
        // Cần bảng lookup Bát San (Sinh Khí, Diên Niên...)
        // Giản lược: Trả về tốt xấu dựa trên Đông Tứ/Tây Tứ Trạch
        // Đông Tứ: 1, 3, 4, 9. Tây Tứ: 2, 6, 7, 8.
        $nhom1 = in_array($cp1, [1,3,4,9]) ? 'Dong' : 'Tay';
        $nhom2 = in_array($cp2, [1,3,4,9]) ? 'Dong' : 'Tay';

        if ($nhom1 == $nhom2) {
            return ['score' => self::SCORE_CUNG_PHI_TOT, 'msg' => 'Cùng Nhóm (Tốt)', 'detail' => 'Cung Phi phối hợp tốt (Sinh Khí/Diên Niên/Thiên Y).'];
        }
        return ['score' => self::SCORE_CUNG_PHI_XAU, 'msg' => 'Khác Nhóm (Xấu)', 'detail' => 'Cung Phi xung khắc (Tuyệt Mệnh/Ngũ Quỷ).'];
    }
    
    // Hàm khởi tạo bảng Bát San chi tiết (Nếu cần chính xác từng cung)
    private function initBatSan() {
        // Code tạo ma trận 9x9...
    }

    private function getKetLuan($score) {
        if ($score >= 6) return "Rất Hợp (Đại Cát)";
        if ($score >= 2) return "Hợp (Cát)";
        if ($score >= -2) return "Bình Hòa";
        return "Xung Khắc (Hung)";
    }
}
?>
