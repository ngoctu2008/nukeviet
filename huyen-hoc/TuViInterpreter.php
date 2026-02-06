<?php
/**
 * TuViInterpreter.php
 * Class chịu trách nhiệm luận giải, chuyển đổi dữ liệu lá số thành văn bản phong thủy.
 */

require_once 'TuViConstants.php';

class TuViInterpreter {
    private $laso;
    private $input;
    private $cuc;
    private $menhPos;
    private $thanPos;
    
    // Dữ liệu mẫu tóm tắt ý nghĩa 14 chính tinh (Dùng khi chưa có Database chi tiết)
    private $starMeanings = [
        'TU_VI' => 'Đế tinh, chủ về quyền uy, lãnh đạo, phúc thọ. Tính tình đôn hậu, trọng danh dự.',
        'THIEN_CO' => 'Thiện tinh, chủ về trí tuệ, mưu lược, huynh đệ. Tính hay suy nghĩ, khéo léo tay chân.',
        'THAI_DUONG' => 'Quyền tinh, chủ về quan lộc, danh tiếng, cha và chồng. Tính nóng nảy nhưng quang minh lỗi lạc.',
        'VU_KHUC' => 'Tài tinh, chủ về tiền bạc, cô quả. Tính cương nghị, quyết đoán, giỏi quản lý tài chính.',
        'THIEN_DONG' => 'Phúc tinh, chủ về hưởng thụ, thay đổi. Tính tình ôn hòa, hay thay đổi chí hướng, thích an nhàn.',
        'LIEM_TRINH' => 'Đào hoa tinh, chủ về tù tội hoặc liêm khiết, nóng nảy. Phân biệt rõ thiện ác, quan hệ rộng.',
        'THIEN_PHU' => 'Tài tinh (kho), chủ về tài lộc, thận trọng. Tính ôn hòa, giỏi giữ tiền, thích ổn định.',
        'THAI_AM' => 'Phú tinh, chủ về điền trạch, mẹ và vợ. Tính nhu mì, lãng mạn, yêu văn học nghệ thuật.',
        'THAM_LANG' => 'Đào hoa tinh, chủ về dục vọng, tửu sắc, tu hành. Đa tài đa nghệ, khéo giao tiếp, tham vọng lớn.',
        'CU_MON' => 'Ám tinh, chủ về ngôn ngữ, thị phi. Có tài ăn nói, hùng biện nhưng hay nghi ngờ, dễ mắc khẩu thiệt.',
        'THIEN_TUONG' => 'Quyền tinh, chủ về y thực, ấn tín. Thích công bằng, hay giúp đỡ người khác, có khí chất quân tử.',
        'THIEN_LUONG' => 'Ấm tinh, chủ về thọ, che chở, giám sát. Tính thanh cao, lương thiện, hay giáo huấn người khác.',
        'THAT_SAT' => 'Dũng tinh, chủ về uy quyền, sát phạt. Tính cương cường, nóng nảy, thích mạo hiểm, dũng cảm.',
        'PHA_QUAN' => 'Hao tinh, chủ về phu thê, hao tán, tiên phong. Tính ngang tàng, thích phá cũ đổi mới, không chịu ngồi yên.'
    ];

    public function __construct($calculatorData) {
        $this->laso = $calculatorData['laso'];
        $this->input = $calculatorData['info'];
        $this->cuc = $calculatorData['cuc'];
        $this->menhPos = $calculatorData['menh_pos'];
        $this->thanPos = $calculatorData['than_pos'];
    }

    // --- PHẦN 1: TỔNG QUAN ÂM DƯƠNG NGŨ HÀNH ---

    public function luanGiaiTongQuan() {
        $canNam = $this->input['can_year']; // 0=Giáp (Dương), 1=Ất (Âm)...
        $chiNam = $this->input['chi_year']; // 0=Tý (Dương), 1=Sửu (Âm)...
        $gioiTinh = $this->input['gender']; // 1=Nam, 0=Nữ
        
        $html = "<div class='luan-giai-box'>";
        $html .= "<h3>1. Tổng Quan Âm Dương Ngũ Hành</h3>";

        // 1. Âm Dương Thuận Lý/Nghịch Lý
        $namDuong = ($canNam % 2 == 0);
        $menhDuong = ($gioiTinh == 1); // Nam là Dương
        
        // Logic: Nam Dương/Nữ Âm là thuận. Nam Âm/Nữ Dương là nghịch.
        // Tuy nhiên chuẩn tử vi: Xét Can năm sinh so với Giới tính.
        // Dương Nam (Can Dương), Âm Nữ (Can Âm) -> Thuận lý.
        // Âm Nam (Can Âm), Dương Nữ (Can Dương) -> Nghịch lý.
        
        $amDuongText = "";
        if (($namDuong && $gioiTinh == 1) || (!$namDuong && $gioiTinh == 0)) {
             $amDuongText = "<strong>Âm Dương Thuận Lý:</strong> Người được hưởng vòng vận đi thuận chiều, cuộc đời gặp nhiều may mắn, thuận lợi hơn người khác. Dễ đạt được ý nguyện.";
        } else {
             $amDuongText = "<strong>Âm Dương Nghịch Lý:</strong> Người có vòng vận đi nghịch, cuộc đời thường phải trải qua thử thách, phấn đấu nhiều mới thành công. Tính cách thường kiên cường, không chịu khuất phục.";
        }
        $html .= "<p>$amDuongText</p>";

        // 2. Mệnh và Cục (Sinh/Khắc)
        // Cần hàm tra nạp âm Mệnh (Giả sử có trong Constants hoặc tính tay). 
        // Ở đây giả định data input đã có nạp âm ID (1=Kim...5=Thổ)
        // Để demo, ta dùng logic so sánh ID Hành Cục và Hành Mệnh.
        // Cục: 2=Thủy, 3=Mộc, 4=Kim, 5=Thổ, 6=Hỏa.
        
        $cucHanhID = 0;
        switch($this->cuc) {
            case 2: $cucHanhID = 3; break; // Thủy
            case 3: $cucHanhID = 2; break; // Mộc
            case 4: $cucHanhID = 1; break; // Kim
            case 5: $cucHanhID = 5; break; // Thổ
            case 6: $cucHanhID = 4; break; // Hỏa
        }

        // Giả sử input có 'menh_hanh_id' (Nếu chưa có phải viết hàm tính Nạp âm lục thập hoa giáp)
        // Code tạm: $menhHanhID = TuViConstants::getNapAm($canNam, $chiNam);
        // Ở đây tôi giả lập Mệnh sinh Cục để demo
        $menhHanhID = 3; // Ví dụ Mệnh Thủy
        
        $tuongQuan = "";
        if ($menhHanhID == $cucHanhID) {
            $tuongQuan = "<strong>Mệnh Cục Bình Hòa:</strong> Cuộc đời êm đềm, sự nghiệp tương xứng với tài năng.";
        } elseif ($this->isSinh($menhHanhID, $cucHanhID)) { // Mệnh sinh Cục
            $tuongQuan = "<strong>Mệnh Sinh Cục:</strong> Người hay vất vả vì người khác, làm lợi cho đời, hay bị thiệt thòi.";
        } elseif ($this->isSinh($cucHanhID, $menhHanhID)) { // Cục sinh Mệnh
            $tuongQuan = "<strong>Cục Sinh Mệnh:</strong> Hoàn cảnh ưu đãi, dễ gặp may mắn, thời thế tạo anh hùng.";
        } elseif ($this->isKhac($menhHanhID, $cucHanhID)) { // Mệnh khắc Cục
            $tuongQuan = "<strong>Mệnh Khắc Cục:</strong> Cuộc đời nhiều trở ngại nhưng nhờ nghị lực mà vượt qua hoàn cảnh.";
        } else { // Cục khắc Mệnh
            $tuongQuan = "<strong>Cục Khắc Mệnh:</strong> Hoàn cảnh khắc nghiệt, hay gặp nghịch cảnh, đời nhiều gian truân.";
        }
        $html .= "<p>$tuongQuan</p>";
        $html .= "</div>";
        return $html;
    }

    // --- PHẦN 2: LUẬN GIẢI 12 CUNG ---

    public function luanGiaiChiTietCung($cungID) {
        $cung = $this->laso[$cungID];
        $tenCung = $cung['cung_chuc'];
        $html = "<div class='cung-box'>";
        $html .= "<h4>Cung $tenCung " . ($cung['than'] ? "(Thân Cư)" : "") . "</h4>";

        // 1. Kiểm tra Vô Chính Diệu
        if (empty($cung['chinh_tinh'])) {
            $html .= "<p><strong>Vô Chính Diệu:</strong> Cung không có chính tinh tọa thủ. ";
            if ($cung['tuan'] || $cung['triet']) {
                $html .= "Đắc Tuần/Triệt án ngữ nên trở nên sáng sủa, thu hút tinh hoa của các cung chiếu về.";
            } else {
                $html .= "Dễ bị tác động bởi hoàn cảnh. Cần xem cung xung chiếu để luận đoán.";
            }
            $html .= "</p>";
            // Lấy sao xung chiếu
            $xungChieuID = ($cungID + 6) % 12;
            $saoXung = implode(', ', $this->laso[$xungChieuID]['chinh_tinh']);
            if ($saoXung) $html .= "<p><em>(Mượn chính tinh xung chiếu: $saoXung)</em></p>";
        }

        // 2. Luận Chính Tinh
        foreach ($cung['chinh_tinh'] as $sao) {
            $doSang = $this->getDoSang($sao, $cungID); // M, V, Đ, H
            $txtDoSang = $this->translateDoSang($doSang);
            
            // Xử lý Tuần Triệt tác động lên Chính tinh
            $note = "";
            if (($cung['tuan'] || $cung['triet']) && $doSang == 'H') {
                $note = " - <span style='color:green'>Phản vi kỳ cách (Tốt lên nhờ Tuần/Triệt)</span>";
            } elseif (($cung['tuan'] || $cung['triet']) && in_array($doSang, ['M', 'V'])) {
                $note = " - <span style='color:orange'>Bị Tuần/Triệt chiết giảm uy lực</span>";
            }

            $meaning = $this->starMeanings[$sao] ?? "";
            $html .= "<p><strong>$sao ($txtDoSang)$note:</strong> $meaning</p>";
        }

        // 3. Luận Phụ Tinh tiêu biểu (Ví dụ)
        $phuTinhTot = array_intersect($cung['phu_tinh'], ['THIEN_KHOI','THIEN_VIET','TA_PHU','HUU_BAT','VAN_XUONG','VAN_KHUC','HOA_LOC','HOA_QUYEN','HOA_KHOA','LOC_TON']);
        if (!empty($phuTinhTot)) {
            $html .= "<p><strong>Cát Tinh hội tụ:</strong> " . implode(', ', $phuTinhTot) . ". Chủ về sự may mắn, trợ lực.</p>";
        }

        $phuTinhXau = array_intersect($cung['phu_tinh'], ['DIA_KHONG','DIA_KIEP','KINH_DUONG','DA_LA','HOA_LINH','LINH_TINH','HOA_KY']);
        if (!empty($phuTinhXau)) {
            $html .= "<p><strong>Sát Tinh xâm phạm:</strong> " . implode(', ', $phuTinhXau) . ". Cần đề phòng trắc trở, tiểu nhân.</p>";
        }

        $html .= "</div>";
        return $html;
    }

    // --- PHẦN 3: NHẬN DIỆN CÁCH CỤC ---

    public function nhanDienCachCuc() {
        $menh = $this->laso[$this->menhPos];
        $stars = $menh['chinh_tinh'];
        $patterns = [];

        // 1. Sát Phá Tham
        if (count(array_intersect(['THAT_SAT', 'PHA_QUAN', 'THAM_LANG'], $stars)) > 0) {
            // Check tam hợp để chắc chắn (Logic đơn giản hóa: chỉ cần 1 sao ở Mệnh là thuộc bộ này)
            $patterns[] = "<strong>Sát Phá Tham:</strong> Mẫu người hành động, cuộc đời nhiều biến động, thích hợp võ nghiệp, kinh doanh mạo hiểm. Có khả năng khai phá, không chịu an phận.";
        }

        // 2. Tử Phủ Vũ Tướng
        if (count(array_intersect(['TU_VI', 'THIEN_PHU', 'VU_KHUC', 'THIEN_TUONG'], $stars)) > 0) {
            $patterns[] = "<strong>Tử Phủ Vũ Tướng:</strong> Mẫu người lãnh đạo, ổn định. Tài lộc dồi dào, có khả năng quản lý tốt. Cuộc đời thường được hưởng phúc, ít sóng gió hơn Sát Phá Tham.";
        }

        // 3. Cơ Nguyệt Đồng Lương
        if (count(array_intersect(['THIEN_CO', 'THAI_AM', 'THIEN_DONG', 'THIEN_LUONG'], $stars)) > 0) {
            $patterns[] = "<strong>Cơ Nguyệt Đồng Lương:</strong> Mẫu người tham mưu, văn phòng, giáo dục, y bác sĩ. Tính tình ôn hòa, thích ổn định, khéo léo, hợp với công việc hành chính, chuyên môn.";
        }

        // 4. Cự Nhật (Cự Môn + Thái Dương)
        if (in_array('CU_MON', $stars) || in_array('THAI_DUONG', $stars)) {
             // Logic chính xác: Dần Thân
             if ($this->menhPos == 2 || $this->menhPos == 8) {
                 $patterns[] = "<strong>Cự Nhật Đồng Cung:</strong> Cách cục tốt đẹp ở Dần/Thân. Chủ về người có tài ăn nói, ngoại giao, làm việc với người nước ngoài, thầy giáo, luật sư.";
             }
        }

        // 5. Nhật Nguyệt (Thái Dương + Thái Âm)
        if (in_array('THAI_AM', $stars) && in_array('THAI_DUONG', $stars)) {
            $patterns[] = "<strong>Nhật Nguyệt Đồng Tranh:</strong> (Tại Sửu/Mùi). Tính cách mâu thuẫn nhưng thông minh, đa tài. Thường phải xa quê hương lập nghiệp.";
        }

        if (empty($patterns)) {
            return "<p>Lá số mang sắc thái hỗn hợp, cần luận giải chi tiết từng sao.</p>";
        }

        return "<div class='cach-cuc-box'><h3>Cách Cục Nổi Bật</h3>" . implode('<br>', $patterns) . "</div>";
    }

    // --- PHẦN 4: LUẬN THÂN CƯ ---

    public function luanGiaiThanCu() {
        $cungThan = $this->laso[$this->thanPos];
        $tenCung = $cungThan['cung_chuc'];
        
        $desc = "<h3>Luận Giải Hậu Vận (Thân cư $tenCung)</h3>";
        $desc .= "<p>Cung Thân tượng trưng cho hậu vận (thường sau 30 tuổi) và lĩnh vực mà đương số nỗ lực nhiều nhất:</p>";
        
        switch ($tenCung) {
            case 'Mệnh': 
                $desc .= "<p><strong>Thân Mệnh Đồng Cung:</strong> Người có tư tưởng và hành động nhất quán. Tính cách từ nhỏ đến lớn ít thay đổi. Tự lập, không dựa dẫm, tin vào năng lực bản thân.</p>"; break;
            case 'Phu Thê':
                $desc .= "<p><strong>Thân cư Phu Thê:</strong> Coi trọng gia đình, cuộc đời chịu ảnh hưởng lớn từ người phối ngẫu. Có xu hướng nể vợ/chồng. Hậu vận tốt xấu phụ thuộc nhiều vào hôn nhân.</p>"; break;
            case 'Tài Bạch':
                $desc .= "<p><strong>Thân cư Tài Bạch:</strong> Người thực tế, nhạy bén với tiền bạc. Coi trọng kinh tế, lấy sự giàu có làm thước đo thành công. Hậu vận thường thiên về kinh doanh, tài chính.</p>"; break;
            case 'Thiên Di':
                $desc .= "<p><strong>Thân cư Thiên Di:</strong> Người hướng ngoại, thích giao thiệp. Thường xuyên phải di chuyển, đi xa. Ra ngoài xã hội thường gặp may mắn hơn ở nhà. Thích hợp ly hương lập nghiệp.</p>"; break;
            case 'Quan Lộc':
                $desc .= "<p><strong>Thân cư Quan Lộc:</strong> Người đam mê công việc, coi trọng danh vọng, chức tước. Hậu vận dành nhiều thời gian cho sự nghiệp. Dễ làm quản lý, lãnh đạo.</p>"; break;
            case 'Phúc Đức':
                $desc .= "<p><strong>Thân cư Phúc Đức:</strong> Người coi trọng đời sống tinh thần, thích làm việc thiện, lo lắng cho dòng họ. Hậu vận được hưởng phúc tổ tiên, gặp nạn hóa lành.</p>"; break;
        }
        return $desc;
    }

    // --- HÀM HỖ TRỢ ---

    private function getDoSang($sao, $cungID) {
        if (isset(TuViConstants::DO_SANG[$sao])) {
            return TuViConstants::DO_SANG[$sao][$cungID];
        }
        return 'B'; // Mặc định Bình hòa
    }

    private function translateDoSang($code) {
        $map = [
            'M' => 'Miếu địa (Rất tốt)',
            'V' => 'Vượng địa (Tốt)',
            'Đ' => 'Đắc địa (Khá)',
            'B' => 'Bình hòa',
            'H' => 'Hãm địa (Xấu)'
        ];
        return $map[$code] ?? '';
    }

    private function isSinh($hanhA, $hanhB) {
        // 1=Kim, 2=Mộc, 3=Thủy, 4=Hỏa, 5=Thổ
        // Kim sinh Thủy (1->3), Thủy sinh Mộc (3->2), Mộc sinh Hỏa (2->4), Hỏa sinh Thổ (4->5), Thổ sinh Kim (5->1)
        $pairs = ['1-3', '3-2', '2-4', '4-5', '5-1'];
        return in_array("$hanhA-$hanhB", $pairs);
    }

    private function isKhac($hanhA, $hanhB) {
        // Kim khắc Mộc (1->2), Mộc khắc Thổ (2->5), Thổ khắc Thủy (5->3), Thủy khắc Hỏa (3->4), Hỏa khắc Kim (4->1)
        $pairs = ['1-2', '2-5', '5-3', '3-4', '4-1'];
        return in_array("$hanhA-$hanhB", $pairs);
    }
}
?>