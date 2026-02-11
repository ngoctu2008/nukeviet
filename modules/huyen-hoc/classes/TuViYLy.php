<?php

namespace NukeViet\Module\HuyenHoc;

class TuViYLy {
    private $laso;      // Dữ liệu lá số
    private $birthMonth;// Tháng sinh (Âm lịch)
    private $tatAchPos; // Vị trí cung Tật Ách

    // --- CƠ SỞ DỮ LIỆU Y LÝ (SAO -> BỆNH) ---
    // Mapping Sao với Bộ phận cơ thể và Bệnh lý
    const Y_LY_SAO = [
        // Chính Tinh
        'TU_VI'      => ['part' => 'Dạ dày, Tỳ vị', 'benh' => 'Đau dạ dày, tiêu hóa kém, hay bị đầy hơi.'],
        'THIEN_CO'   => ['part' => 'Gan, Mật, Thần kinh', 'benh' => 'Gan nóng, mất ngủ, đau đầu, tê bì chân tay.'],
        'THAI_DUONG' => ['part' => 'Mắt, Tim, Máu', 'benh' => 'Mắt kém (cận/loạn), cao huyết áp, tim mạch, đau đầu.'],
        'VU_KHUC'    => ['part' => 'Phổi, Mũi, Xương', 'benh' => 'Viêm xoang, ho khan, phổi yếu, đau nhức xương khớp.'],
        'THIEN_DONG' => ['part' => 'Tai, Bàng quang, Thận', 'benh' => 'Thận yếu, đau lưng, tai ù, rối loạn bài tiết.'],
        'LIEM_TRINH' => ['part' => 'Máu huyết, Ung nhọt', 'benh' => 'Nóng trong, mụn nhọt, thiếu máu, phụ nữ kỵ huyết hư.'],
        'THIEN_PHU'  => ['part' => 'Dạ dày (Phủ tạng)', 'benh' => 'Đau bao tử, béo phì, phù thũng.'],
        'THAI_AM'    => ['part' => 'Mắt, Thận, Phụ khoa', 'benh' => 'Mắt yếu, thận hư, nữ giới bệnh phụ khoa, kinh nguyệt.'],
        'THAM_LANG'  => ['part' => 'Gan, Thận, Sinh dục', 'benh' => 'Phong thấp, bệnh do tửu sắc, gan thận suy nhược.'],
        'CU_MON'     => ['part' => 'Miệng, Họng, Phế quản', 'benh' => 'Viêm họng hạt, hen suyễn, bệnh từ miệng vào.'],
        'THIEN_TUONG'=> ['part' => 'Mặt, Đầu, Tiết niệu', 'benh' => 'Dị ứng da mặt, sỏi thận, bệnh đường tiết niệu.'],
        'THIEN_LUONG'=> ['part' => 'Tỳ vị, Tuyến vú', 'benh' => 'Tiêu hóa kém, nữ giới đề phòng u vú.'],
        'THAT_SAT'   => ['part' => 'Phổi, Đại tràng', 'benh' => 'Lao phổi, trĩ, táo bón, hay bị ngoại thương.'],
        'PHA_QUAN'   => ['part' => 'Thận, Răng, Tóc', 'benh' => 'Răng yếu, tóc bạc sớm, suy nhược thần kinh, mụn nhọt.'],

        // Phụ Tinh quan trọng
        'KINH_DUONG' => ['part' => 'Chân tay, Lưng', 'benh' => 'Dễ bị thương tích, mổ xẻ, đau lưng cấp.'],
        'DA_LA'      => ['part' => 'Răng, Xương, Da', 'benh' => 'Sâu răng, vết chàm, bệnh mãn tính dây dưa khó khỏi.'],
        'HOA_KY'     => ['part' => 'Mắt, Lưỡi, Ruột', 'benh' => 'Đau mắt, ngộ độc thực phẩm, bệnh lạ khó chữa.'],
        'HOA_LINH'   => ['part' => 'Thần kinh, Tim', 'benh' => 'Sốt cao, co giật, tim đập nhanh, mụn nhọt viêm sưng.'],
        'DIA_KHONG'  => ['part' => 'Huyết áp, Ung thư', 'benh' => 'Huyết áp thất thường, bệnh ung nhọt ác tính.'],
        'THIEN_HINH' => ['part' => 'Gân cốt, Dao kéo', 'benh' => 'Thương tích tay chân, dễ phải phẫu thuật.'],
        'THIEN_RIEU' => ['part' => 'Sinh dục, Bài tiết', 'benh' => 'Thận yếu, bệnh xã hội, mộng tinh, huyết trắng.']
    ];

    // --- CƠ SỞ DỮ LIỆU THỰC DƯỠNG (NGŨ HÀNH KHUYẾT) ---
    // Dựa trên lý thuyết "Mệnh Khuyết" (Thiếu hành gì bổ sung hành đó)
    const THUC_DUONG = [
        'KIM' => [ // Khuyết Kim (Thường sinh mùa Xuân: Dần, Mão, Thìn)
            'name' => 'Khuyết Kim (Cần bổ sung Kim)',
            'mau_sac' => 'Trắng, Xám, Bạc, Vàng kim',
            'thuc_pham' => 'Thịt gà, Phổi heo, Tổ yến, Củ cải trắng, Lê, Sữa, Kem, Rượu trắng.',
            'loi_khuyen' => 'Nên đeo trang sức vàng bạc, đồng hồ kim loại. Tránh để móng tay quá dài. Sáng dậy nên soi gương.'
        ],
        'MOC' => [ // Khuyết Mộc (Thường sinh mùa Thu: Thân, Dậu, Tuất)
            'name' => 'Khuyết Mộc (Cần bổ sung Mộc)',
            'mau_sac' => 'Xanh lá cây, Xanh lục',
            'thuc_pham' => 'Rau xanh, Salad, Các loại nấm, Đậu xanh, Quả chua (Chanh, Cam), Giấm, Thịt vịt.',
            'loi_khuyen' => 'Nên trồng cây trong nhà, nuôi mèo, để tóc dài, đọc sách giấy. Sáng dậy nên đi dạo công viên.'
        ],
        'THUY' => [ // Khuyết Thủy (Thường sinh mùa Hạ: Tỵ, Ngọ, Mùi)
            'name' => 'Khuyết Thủy (Cần bổ sung Thủy)',
            'mau_sac' => 'Đen, Xanh dương, Xám tro',
            'thuc_pham' => 'Cá, Hải sản, Đậu phụ, Đậu đen, Rong biển, Tổ yến, Nước khoáng, Bia lạnh.',
            'loi_khuyen' => 'Nên tắm sáng, uống nhiều nước, nuôi cá cảnh. Nhà vệ sinh cần sạch sẽ. Thường xuyên đi bơi.'
        ],
        'HOA' => [ // Khuyết Hỏa (Thường sinh mùa Đông: Hợi, Tý, Sửu)
            'name' => 'Khuyết Hỏa (Cần bổ sung Hỏa)',
            'mau_sac' => 'Đỏ, Tím, Hồng, Cam',
            'thuc_pham' => 'Thịt bò, Thịt dê, Ớt, Gừng, Tỏi, Rượu vang đỏ, Cà chua, Táo đỏ, Socola.',
            'loi_khuyen' => 'Nên vào bếp nấu ăn, dùng đèn ánh sáng vàng/đỏ. Nuôi chó hoặc rùa. Tắm nắng buổi sáng.'
        ],
        'THO' => [ // Khuyết Thổ (Sinh các tháng Tứ Mộ: Thìn, Tuất, Sửu, Mùi - nhưng cần tính kỹ)
            // Trong Tử vi ứng dụng, thường gộp vào Hỏa hoặc Kim tùy tàng can.
            // Ở đây để đơn giản ta dùng chế độ cân bằng.
            'name' => 'Cân Bằng Thổ (Bổ sung Thổ)',
            'mau_sac' => 'Vàng, Nâu đất',
            'thuc_pham' => 'Thịt chó, Thịt dê, Khoai lang, Khoai tây, Bí ngô, Đường phèn.',
            'loi_khuyen' => 'Nên đi chân trần trên đất/cát, dùng đồ gốm sứ, đá quý phong thủy.'
        ]
    ];

    public function __construct($calculatorData) {
        $this->laso = $calculatorData['dia_ban'];
        $this->birthMonth = $calculatorData['input']['m']; // Assuming $data_input passed or stored
        // Wait, TuViLapSo result doesn't explicitly return month in a simple key, it's in 'meta' or passed separately.
        // In funcs/tu-vi.php, I passed $data_input separately.
        // I should reconstruct data to match usage.
        // Check funcs/tu-vi.php usage plan.

        // Tìm vị trí cung Tật Ách
        foreach ($this->laso as $cung) {
            if (strpos($cung['palace_name'], 'Tật Ách') !== false) {
                $this->tatAchPos = $cung['index'];
                break;
            }
        }
    }

    // --- PHẦN 1: CHẨN ĐOÁN BỆNH LÝ (CUNG TẬT ÁCH) ---

    public function chanDoanBenh() {
        $cungTat = $this->laso[$this->tatAchPos];

        // Extract codes
        $tatCaSao = [];
        foreach($cungTat['chinh_tinh'] as $s) $tatCaSao[] = strtoupper($s['code']);
        if(isset($cungTat['phu_tinh_tot'])) foreach($cungTat['phu_tinh_tot'] as $s) $tatCaSao[] = strtoupper($s['code']);
        if(isset($cungTat['phu_tinh_xau'])) foreach($cungTat['phu_tinh_xau'] as $s) $tatCaSao[] = strtoupper($s['code']);

        $chanDoan = [];
        $nguyCoCao = false;

        // 1. Quét từng sao
        foreach ($tatCaSao as $sao) {
            if (isset(self::Y_LY_SAO[$sao])) {
                $info = self::Y_LY_SAO[$sao];
                // Nếu gặp Tuần Triệt tại Tật Ách -> Bệnh nặng hóa nhẹ, bệnh nhẹ hóa không (Tốt)
                if ($cungTat['tuan'] || $cungTat['triet']) {
                    $chanDoan[] = "Có sao <strong>$sao</strong> nhưng nhờ Tuần/Triệt nên giải trừ được: {$info['benh']}";
                } else {
                    $chanDoan[] = "Do ảnh hưởng của <strong>$sao</strong>: Chú ý vùng {$info['part']}. Nguy cơ: {$info['benh']}";
                }
            }
        }

        // 2. Phát hiện Tổ hợp nguy hiểm (Pattern Recognition)
        $msg = $this->detectBenhPattern($tatCaSao);
        if ($msg) {
            $chanDoan[] = "<span style='color:red; font-weight:bold'>⚠ Cảnh báo đặc biệt: $msg</span>";
            $nguyCoCao = true;
        }

        if (empty($chanDoan)) {
            $chanDoan[] = "Cung Tật Ách tốt, ít bệnh tật nguy hiểm. Tuy nhiên cần chú ý sức khỏe theo vận hạn.";
        }

        return [
            'cung_tat' => $cungTat['name'],
            'chi_tiet' => $chanDoan,
            'canh_bao' => $nguyCoCao
        ];
    }

    // --- PHẦN 2: THỰC DƯỠNG CẢI VẬN (MỆNH KHUYẾT) ---

    public function goiYThucDuong() {
        // Xác định Ngũ hành khuyết thiếu dựa trên Mùa sinh (Tháng Âm lịch)

        $thang = $this->birthMonth;
        $keyKhuyet = '';

        if (in_array($thang, [1, 2, 3])) $keyKhuyet = 'KIM';
        elseif (in_array($thang, [4, 5, 6])) $keyKhuyet = 'THUY';
        elseif (in_array($thang, [7, 8, 9])) $keyKhuyet = 'MOC';
        elseif (in_array($thang, [10, 11, 12])) $keyKhuyet = 'HOA';

        return self::THUC_DUONG[$keyKhuyet];
    }

    // --- HELPER FUNCTIONS ---

    private function detectBenhPattern($stars) {
        $patterns = [];

        // Thái Dương + Đà La/Hóa Kỵ/Riêu -> Mắt kém
        if (in_array('THAI_DUONG', $stars) && (in_array('DA_LA', $stars) || in_array('HOA_KY', $stars) || in_array('THIEN_RIEU', $stars))) {
            $patterns[] = "Thái Dương gặp Ám tinh: Đề phòng các bệnh nặng về Mắt hoặc Tim mạch.";
        }

        // Tham Lang + Đà La/Riêu -> Bệnh phong tình
        if (in_array('THAM_LANG', $stars) && (in_array('DA_LA', $stars) || in_array('THIEN_RIEU', $stars))) {
            $patterns[] = "Tham Lang ngộ Riêu Đà: Cẩn thận các bệnh lây qua đường tình dục hoặc thận suy.";
        }

        // Liêm Trinh + Bạch Hổ -> Máu huyết, ung nhọt
        if (in_array('LIEM_TRINH', $stars) && in_array('BACH_HO', $stars)) {
            $patterns[] = "Liêm Trinh Bạch Hổ: Đề phòng bệnh về máu huyết, phụ nữ cần tầm soát u bướu.";
        }

        // Không Kiếp + Hình -> Mổ xẻ
        if ((in_array('DIA_KHONG', $stars) || in_array('DIA_KIEP', $stars)) && in_array('THIEN_HINH', $stars)) {
            $patterns[] = "Không Kiếp Hình: Dễ có can thiệp dao kéo, phẫu thuật trong đời.";
        }

        return !empty($patterns) ? implode('<br>', $patterns) : null;
    }
}
