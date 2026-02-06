<?php
/**
 * Class TuViDateSelector
 * Chức năng: Xem ngày tốt xấu chuyên sâu, kết hợp Lịch Vạn Sự và Vận khí Lưu Nhật trên lá số Tử Vi.
 */

require_once 'TuViConstants.php';

class TuViDateSelector {

    // --- DỮ LIỆU ĐẦU VÀO ---
    protected $birthYear;     // Năm sinh (Dương lịch)
    protected $birthMonth;    // Tháng sinh (Âm lịch)
    protected $birthHourChi;  // Chi giờ sinh (0=Tý...11=Hợi)
    protected $menhHanhID;    // ID Ngũ hành bản mệnh (1=Kim...5=Mộc)
    protected $lasoGoc;       // Mảng 12 cung của lá số gốc (để tra sao cố định)
    
    // Dữ liệu ngày cần xem
    protected $dateInput;     // ['day', 'month', 'year', 'can_day', 'chi_day', 'hanh_id', 'truc', 'sao']

    // --- HẰNG SỐ LỊCH PHÁP ---
    
    // 12 Kiến Trừ (Trực) - Điểm số cơ bản: 1=Tốt, 0=Bình, -1=Xấu
    const TRUC_SCORES = [
        'Kiến' => 1, 'Trừ' => 0, 'Mãn' => 1, 'Bình' => 1, 'Định' => 1, 'Chấp' => 0,
        'Phá' => -2, 'Nguy' => -1, 'Thành' => 1, 'Thu' => -1, 'Khai' => 1, 'Bế' => -1
    ];

    // 28 Sao (Nhị Thập Bát Tú) - Điểm số cơ bản
    const SAO_28_SCORES = [
        // Đông
        'Giác'=>1, 'Cang'=>-1, 'Đê'=>-1, 'Phòng'=>1, 'Tâm'=>-1, 'Vĩ'=>1, 'Cơ'=>1,
        // Bắc
        'Đẩu'=>1, 'Ngưu'=>-1, 'Nữ'=>-1, 'Hư'=>-1, 'Nguy'=>-1, 'Thất'=>1, 'Bích'=>1,
        // Tây
        'Khuê'=>-1, 'Lâu'=>1, 'Vị'=>1, 'Mão'=>1, 'Tất'=>1, 'Chủy'=>-1, 'Sâm'=>1,
        // Nam
        'Tỉnh'=>1, 'Quỷ'=>-1, 'Liễu'=>-1, 'Tinh'=>1, 'Trương'=>1, 'Dực'=>-1, 'Chẩn'=>1
    ];

    /**
     * Khởi tạo
     * @param TuViCalculator $calculator Đối tượng đã tính toán lá số
     * @param array $dateData Dữ liệu ngày cần xem ['day'=>..., 'month'=>..., 'year'=>..., 'can_day'=>..., 'chi_day'=>..., 'hanh_id'=>..., 'truc'=>..., 'sao'=>...]
     */
    public function __construct($calculator, $dateData) {
        $this->lasoGoc = $calculator->laso;
        $this->birthYear = $calculator->input['year'];
        $this->birthMonth = $calculator->input['month'];
        $this->birthHourChi = $calculator->input['hour'];
        
        // Tính mệnh hành ID từ Can Chi năm sinh (dùng hàm static của Constants)
        $this->menhHanhID = TuViConstants::getNapAmID($calculator->input['can_year'], $calculator->input['chi_year']);

        $this->dateInput = $dateData;
    }

    // --- PHẦN 1: THUẬT TOÁN TỬ VI (LƯU NHẬT) ---

    /**
     * Tính vị trí cung Lưu Nhật trên lá số
     * Quy tắc: Lưu Niên -> Đẩu Quân (Tháng 1) -> Lưu Nguyệt -> Lưu Nhật
     */
    public function getCungLuuNhat() {
        // 1. Tìm cung Lưu Niên (Lưu Thái Tuế)
        // Cung Lưu Niên nằm tại cung có Địa Chi trùng với Chi của năm xem.
        // Ví dụ: Năm Ngọ, Lưu Thái Tuế tại cung Ngọ (index 6 - nếu quy ước 0=Tý)
        // Input dateInput['year'] là năm dương, cần tính Chi năm xem. 
        // Giả sử dateInput có 'chi_year' (0-11).
        $chiNamXem = $this->dateInput['chi_year']; 
        $posLuuNien = $chiNamXem; 

        // 2. Tìm Đẩu Quân (Vị trí tháng Giêng)
        // Khẩu quyết: "Nghịch đến tháng sinh, Thuận đến giờ sinh" từ cung Lưu Niên.
        // Công thức Nghịch tháng: (Pos - (Month - 1))
        $step1 = $posLuuNien - ($this->birthMonth - 1);
        
        // Công thức Thuận giờ: + (Hour - 1). Lưu ý Hour 0=Tý là giờ thứ 1 -> (0).
        // Nếu quy ước input hour: 0=Tý, 1=Sửu... thì cộng trực tiếp $this->birthHourChi.
        // Nếu quy ước 1=Tý... thì phải -1. Ở đây theo Calculator trước là 0=Tý.
        $dauQuan = $step1 + $this->birthHourChi;
        
        // Chuẩn hóa về 0-11
        $dauQuan = $this->mod($dauQuan, 12);

        // 3. Tìm Lưu Nguyệt (Tháng cần xem)
        // Từ Đẩu Quân (Tháng 1), đếm thuận đến tháng xem.
        $thangXem = $this->dateInput['month'];
        $posLuuNguyet = $this->mod($dauQuan + ($thangXem - 1));

        // 4. Tìm Lưu Nhật (Ngày cần xem)
        // Từ cung Lưu Nguyệt là mùng 1, đếm thuận đến ngày xem.
        $ngayXem = $this->dateInput['day'];
        $posLuuNhat = $this->mod($posLuuNguyet + ($ngayXem - 1));

        return $posLuuNhat;
    }

    // --- PHẦN 2: LOGIC LỊCH VẠN SỰ (TỔNG QUÁT) ---

    /**
     * Đánh giá Ngũ Hành Sinh Khắc (Ngày vs Mệnh)
     */
    private function danhGiaNguHanh() {
        $hanhNgay = $this->dateInput['hanh_id']; // 1=Kim...5=Mộc
        $hanhMenh = $this->menhHanhID;

        // Sinh: Kim(1)->Thủy(2)->Mộc(5)->Hỏa(3)->Thổ(4)->Kim(1)
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1];
        // Khắc: Kim(1)->Mộc(5)->Thổ(4)->Thủy(2)->Hỏa(3)->Kim(1)
        $khac = [1=>5, 5=>4, 4=>2, 2=>3, 3=>1];

        if ($sinh[$hanhNgay] == $hanhMenh) return ['score' => 2, 'msg' => "Ngũ hành Tương Sinh (Rất Tốt). Ngày sinh cho Mệnh, mọi việc thuận lợi."];
        if ($hanhNgay == $hanhMenh) return ['score' => 1, 'msg' => "Ngũ hành Tương Hòa (Tốt). Ngày và Mệnh bình hòa, ổn định."];
        if ($khac[$hanhNgay] == $hanhMenh) return ['score' => -2, 'msg' => "Ngũ hành Tương Khắc (Xấu). Ngày khắc Mệnh (Khắc Nhập), dễ gặp cản trở."];
        if ($khac[$hanhMenh] == $hanhNgay) return ['score' => 0, 'msg' => "Ngũ hành Khắc Xuất (Trung Bình). Mệnh chế ngự được Ngày, vất vả mới thành."];
        
        return ['score' => -0.5, 'msg' => "Ngũ hành Sinh Xuất (Hơi Xấu). Mệnh sinh cho Ngày, hao tổn tâm lực."];
    }

    /**
     * Đánh giá Trực và Sao (Nhị thập bát tú)
     */
    private function danhGiaTrucSao() {
        $truc = $this->dateInput['truc'];
        $sao = $this->dateInput['sao'];
        
        $scoreTruc = self::TRUC_SCORES[$truc] ?? 0;
        $scoreSao = self::SAO_28_SCORES[$sao] ?? 0;
        
        $msg = "Trực $truc, Sao $sao.";
        return ['score' => ($scoreTruc + $scoreSao), 'msg' => $msg];
    }

    /**
     * Kiểm tra Xung Thái Tuế (Chi Ngày vs Chi Tuổi)
     */
    private function checkXungTuoi() {
        $chiNgay = $this->dateInput['chi_day'];
        $chiTuoi = $this->birthYear % 12; // Lưu ý: Năm sinh dương % 12 ra Chi (4=Tý... cái này cần mapping chuẩn)
        // Mapping chuẩn năm Dương -> Chi:
        // ... 1984(Tý)=4, 1985(Sửu)=5... -> ($year - 4) % 12 = Chi Index (0=Tý)
        $chiTuoi = ($this->birthYear - 4) % 12;
        if ($chiTuoi < 0) $chiTuoi += 12;

        // Lục Xung: Hiệu số = 6
        if (abs($chiNgay - $chiTuoi) == 6) {
            return ['score' => -3, 'msg' => "Ngày Lục Xung với tuổi (" . TuViConstants::CHI[$chiNgay] . " xung " . TuViConstants::CHI[$chiTuoi] . "). Đại kỵ việc lớn."];
        }
        return ['score' => 0, 'msg' => ""];
    }

    // --- PHẦN 3: LOGIC CHUYÊN SÂU THEO MỤC ĐÍCH ---

    /**
     * Hàm chính: Xem ngày theo mục đích
     * @param string $mucDich 'KHAI_TRUONG', 'CUOI_HOI', 'XUAT_HANH', 'AN_TANG', 'TONG_QUAT'
     */
    public function phanTichNgay($mucDich = 'TONG_QUAT') {
        $log = [];
        $totalScore = 0;

        // 1. Đánh giá phần Cứng (Lịch Vạn Sự)
        $resHanh = $this->danhGiaNguHanh();
        $totalScore += $resHanh['score'];
        $log[] = $resHanh['msg'];

        $resTruc = $this->danhGiaTrucSao();
        $totalScore += $resTruc['score'];
        //$log[] = $resTruc['msg']; // Ít quan trọng, có thể ẩn

        $resXung = $this->checkXungTuoi();
        $totalScore += $resXung['score'];
        if ($resXung['score'] < 0) $log[] = $resXung['msg'];

        // 2. Đánh giá phần Mềm (Tử Vi Lưu Nhật)
        $posLuuNhat = $this->getCungLuuNhat();
        $cungData = $this->lasoGoc[$posLuuNhat];
        $tatCaSao = array_merge($cungData['chinh_tinh'], $cungData['phu_tinh']);
        
        $cungTen = $cungData['cung_chuc']; // Ví dụ: Điền Trạch, Quan Lộc...
        $log[] = "<strong>Vận khí ngày (Lưu Nhật):</strong> Rơi vào cung $cungTen (tại " . $cungData['name'] . ").";

        // Logic từng nghiệp vụ
        switch ($mucDich) {
            case 'KHAI_TRUONG': // Cầu tài, mở hàng
                // Cần: Lộc Tồn, Hóa Lộc, Vũ Khúc, Thiên Phủ, Thiên Mã.
                // Kỵ: Không Kiếp, Song Hao, Đà La.
                if ($this->hasStar($tatCaSao, ['LOC_TON', 'HOA_LOC', 'VU_KHUC', 'THIEN_PHU'])) {
                    $totalScore += 3; $log[] = "<span style='color:green'>Có Tài Tinh hội tụ (Lộc/Vũ/Phủ): Rất tốt để cầu tài, mở hàng đắt khách.</span>";
                }
                if ($this->hasStar($tatCaSao, ['THIEN_MA', 'TRANG_SINH', 'DE_VUONG'])) {
                    $totalScore += 1; $log[] = "Có Mã/Sinh/Vượng: Công việc trôi chảy, phát triển nhanh.";
                }
                if ($this->hasStar($tatCaSao, ['DIA_KHONG', 'DIA_KIEP', 'DAI_HAO', 'TIEU_HAO'])) {
                    $totalScore -= 4; $log[] = "<span style='color:red'>Gặp Không Kiếp/Song Hao: Hao tài tốn của, dễ lỗ vốn, kỵ khai trương.</span>";
                }
                if ($cungData['triet'] || $cungData['tuan']) {
                    $totalScore -= 2; $log[] = "Gặp Tuần/Triệt: Khởi đầu gian nan, bế tắc.";
                }
                break;

            case 'CUOI_HOI': // Tình duyên, gia đạo
                // Cần: Nhật Nguyệt sáng, Đào Hồng Hỷ, Thiên Phủ, Thiên Tướng.
                // Kỵ: Cô Quả, Tang Hổ, Khốc Hư, Kình Đà, Không Kiếp.
                if ($this->hasStar($tatCaSao, ['THIEN_HY', 'HONG_LOAN', 'DAO_HOA', 'THAI_AM'])) {
                    $totalScore += 3; $log[] = "<span style='color:green'>Có Hỷ Tín (Đào Hồng Hỷ): Tình duyên mặn nồng, cưới xin thuận lợi.</span>";
                }
                if ($this->hasStar($tatCaSao, ['CO_THAN', 'QUA_TU', 'VU_KHUC'])) { // Vũ Khúc là sao cô đơn
                    $totalScore -= 3; $log[] = "<span style='color:red'>Gặp Cô Thần/Quả Tú/Vũ Khúc: Dễ khắc khẩu, cô đơn, lạnh lẽo.</span>";
                }
                if ($this->hasStar($tatCaSao, ['TANG_MON', 'BACH_HO', 'THIEN_KHOC', 'THIEN_HU'])) {
                    $totalScore -= 3; $log[] = "<span style='color:red'>Gặp Tang Hổ/Khốc Hư: Có chuyện buồn phiền, nước mắt.</span>";
                }
                break;

            case 'XUAT_HANH': // Đi xa, du lịch
                // Cần: Thiên Mã, Thanh Long, Lưu Hà, Phượng Các.
                // Kỵ: Tuần Triệt (ngựa què), Kình Đà, Quan Phủ (giấy tờ), Hóa Kỵ.
                $hasMa = $this->hasStar($tatCaSao, ['THIEN_MA']);
                $isBiChan = ($cungData['tuan'] || $cungData['triet']);
                
                if ($hasMa && !$isBiChan) {
                    $totalScore += 3; $log[] = "<span style='color:green'>Thiên Mã đắc địa: Xuất hành hanh thông, đi nhanh về lẹ.</span>";
                }
                if ($hasMa && $isBiChan) {
                    $totalScore -= 4; $log[] = "<span style='color:red'>Thiên Mã gặp Tuần/Triệt (Ngựa gãy chân): Đi xa trắc trở, dễ hỏng xe hoặc tai nạn.</span>";
                }
                if ($this->hasStar($tatCaSao, ['HOA_KY', 'QUAN_PHU', 'QUAN_PHU_L'])) {
                    $totalScore -= 2; $log[] = "Gặp Hóa Kỵ/Quan Phủ: Dễ cãi cọ, rắc rối giấy tờ dọc đường.";
                }
                break;
                
            case 'AN_TANG': // Mai táng
                // Kỵ: Trùng tang (dựa vào ngày), Kỵ Liêm Tham hãm, Kỵ Sát tinh hạng nặng.
                // Logic: Tránh ngày trực Phá, Trực Nguy. Tránh cung Tật Ách lưu nhật xấu?
                if ($this->dateInput['truc'] == 'Phá') {
                    $totalScore -= 3; $log[] = "Trực Phá: Đại kỵ việc an táng, chôn cất.";
                }
                if ($this->hasStar($tatCaSao, ['LIEM_TRINH', 'THAM_LANG']) && $this->isHamDia($cungData, ['LIEM_TRINH', 'THAM_LANG'])) {
                     $totalScore -= 2; $log[] = "Gặp Liêm/Tham hãm: Tà khí nặng, không lợi.";
                }
                break;
            
            default: // Tổng quát
                if ($totalScore > 0) $log[] = "Ngày tốt chung cho mọi việc.";
                else $log[] = "Ngày bình thường hoặc xấu nhẹ.";
        }

        // 3. Kết luận
        return [
            'input_date' => $this->dateInput,
            'muc_dich' => $mucDich,
            'total_score' => $totalScore,
            'ket_luan' => $this->getKetLuanText($totalScore),
            'chi_tiet' => $log
        ];
    }

    // --- HELPER FUNCTIONS ---

    private function mod($val, $n = 12) {
        return (($val % $n) + $n) % $n;
    }

    private function hasStar($listSao, $targetList) {
        return !empty(array_intersect($listSao, $targetList));
    }

    // Hàm giả lập kiểm tra Hãm địa (Cần logic từ Constants hoặc Calculator truyền sang)
    private function isHamDia($cungData, $stars) {
        // Thực tế cần check bảng DO_SANG trong Constants. 
        // Ở đây tạm trả về false để tránh lỗi nếu không kết nối DB
        return false; 
    }

    private function getKetLuanText($score) {
        if ($score >= 5) return "ĐẠI CÁT (Rất Tốt)";
        if ($score >= 2) return "TIỂU CÁT (Tốt)";
        if ($score >= -1) return "BÌNH HÒA";
        if ($score >= -4) return "HUNG (Xấu)";
        return "ĐẠI HUNG (Rất Xấu)";
    }
}

// --- HƯỚNG DẪN SỬ DỤNG ---
/*
// B1. Có đối tượng Calculator đã chạy
// $tuviCalc = new TuViCalculator($inputUser); $tuviCalc->execute();

// B2. Chuẩn bị dữ liệu ngày xem (Ví dụ ngày 10/2/2026 Âm)
$dateData = [
    'day' => 10,
    'month' => 2,
    'chi_year' => 6, // Năm Ngọ (0=Tý...6=Ngọ)
    'chi_day' => 8,  // Giả sử ngày Thân
    'hanh_id' => 1,  // Ngày Kim
    'truc' => 'Thành',
    'sao' => 'Bích'
];

// B3. Khởi tạo và Xem
$selector = new TuViDateSelector($tuviCalc, $dateData);
$result = $selector->phanTichNgay('KHAI_TRUONG');

print_r($result);
*/
?>
