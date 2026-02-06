<?php
/**
 * TuViConstants.php
 * Chứa toàn bộ hằng số, bảng tra cứu và dữ liệu tĩnh cho hệ thống Tử Vi.
 */

class TuViConstants {
    // --- 1. DỮ LIỆU CƠ BẢN ---
    
    const CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    const CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
    
    // Ngũ hành ID: 1=Kim, 2=Mộc, 3=Thủy, 4=Hỏa, 5=Thổ
    const NGU_HANH = [
        1 => ['name' => 'Kim', 'css' => 'hanh-kim', 'color' => '#FFD700'],
        2 => ['name' => 'Mộc', 'css' => 'hanh-moc', 'color' => '#228B22'],
        3 => ['name' => 'Thủy', 'css' => 'hanh-thuy', 'color' => '#1E90FF'],
        4 => ['name' => 'Hỏa', 'css' => 'hanh-hoa', 'color' => '#FF4500'],
        5 => ['name' => 'Thổ', 'css' => 'hanh-tho', 'color' => '#8B4513']
    ];

    const CUNG_CHUC = [
        'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc', 
        'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
    ];

    // --- 2. DỮ LIỆU TÍNH TOÁN CỤC & MỆNH ---

    // Ma trận Cục (Tra theo Can Năm và Chi Cung Mệnh)
    // Giá trị: 2=Thủy, 3=Mộc, 4=Kim, 5=Thổ, 6=Hỏa
    // Row Index (Can Năm): 0=Giáp/Kỷ, 1=Ất/Canh, 2=Bính/Tân, 3=Đinh/Nhâm, 4=Mậu/Quý
    // Col Index (Chi Mệnh): 0=Tý, 1=Sửu... 11=Hợi
    const CUC_MATRIX = [
        [4, 4, 2, 2, 6, 6, 4, 4, 2, 2, 6, 6], // Giáp, Kỷ
        [3, 3, 5, 5, 4, 4, 3, 3, 5, 5, 4, 4], // Ất, Canh
        [2, 2, 6, 6, 3, 3, 2, 2, 6, 6, 3, 3], // Bính, Tân
        [5, 5, 4, 4, 2, 2, 5, 5, 4, 4, 2, 2], // Đinh, Nhâm
        [6, 6, 3, 3, 5, 5, 6, 6, 3, 3, 5, 5]  // Mậu, Quý
    ];

    // Bảng Nạp Âm Lục Thập Hoa Giáp (Tra hành của Bản Mệnh)
    // Key = CanIndex_ChiIndex. Value = ID Ngũ Hành (1=Kim, 2=Mộc, 3=Thủy, 4=Hỏa, 5=Thổ)
    // Dùng để so sánh Tương quan Ngũ Hành giữa Mệnh và Cục.
    const NAP_AM = [
        // Giáp Tý, Ất Sửu (Hải Trung Kim)
        '0_0' => 1, '1_1' => 1, 
        // Bính Dần, Đinh Mão (Lư Trung Hỏa)
        '2_2' => 4, '3_3' => 4,
        // Mậu Thìn, Kỷ Tỵ (Đại Lâm Mộc)
        '4_4' => 2, '5_5' => 2,
        // Canh Ngọ, Tân Mùi (Lộ Bàng Thổ)
        '6_6' => 5, '7_7' => 5,
        // Nhâm Thân, Quý Dậu (Kiếm Phong Kim)
        '8_8' => 1, '9_9' => 1,
        // Giáp Tuất, Ất Hợi (Sơn Đầu Hỏa)
        '0_10'=> 4, '1_11'=> 4,
        // Bính Tý, Đinh Sửu (Giản Hạ Thủy)
        '2_0' => 3, '3_1' => 3,
        // Mậu Dần, Kỷ Mão (Thành Đầu Thổ)
        '4_2' => 5, '5_3' => 5,
        // Canh Thìn, Tân Tỵ (Bạch Lạp Kim)
        '6_4' => 1, '7_5' => 1,
        // Nhâm Ngọ, Quý Mùi (Dương Liễu Mộc)
        '8_6' => 2, '9_7' => 2,
        // Giáp Thân, Ất Dậu (Tuyền Trung Thủy)
        '0_8' => 3, '1_9' => 3,
        // Bính Tuất, Đinh Hợi (Ốc Thượng Thổ)
        '2_10'=> 5, '3_11'=> 5,
        // Mậu Tý, Kỷ Sửu (Tích Lịch Hỏa)
        '4_0' => 4, '5_1' => 4,
        // Canh Dần, Tân Mão (Tùng Bách Mộc)
        '6_2' => 2, '7_3' => 2,
        // Nhâm Thìn, Quý Tỵ (Trường Lưu Thủy)
        '8_4' => 3, '9_5' => 3,
        // Giáp Ngọ, Ất Mùi (Sa Trung Kim)
        '0_6' => 1, '1_7' => 1,
        // Bính Thân, Đinh Dậu (Sơn Hạ Hỏa)
        '2_8' => 4, '3_9' => 4,
        // Mậu Tuất, Kỷ Hợi (Bình Địa Mộc)
        '4_10'=> 2, '5_11'=> 2,
        // Canh Tý, Tân Sửu (Bích Thượng Thổ)
        '6_0' => 5, '7_1' => 5,
        // Nhâm Dần, Quý Mão (Kim Bạch Kim)
        '8_2' => 1, '9_3' => 1,
        // Giáp Thìn, Ất Tỵ (Phú Đăng Hỏa)
        '0_4' => 4, '1_5' => 4,
        // Bính Ngọ, Đinh Mùi (Thiên Hà Thủy)
        '2_6' => 3, '3_7' => 3,
        // Mậu Thân, Kỷ Dậu (Đại Trạch Thổ)
        '4_8' => 5, '5_9' => 5,
        // Canh Tuất, Tân Hợi (Thoa Xuyến Kim)
        '6_10'=> 1, '7_11'=> 1,
        // Nhâm Tý, Quý Sửu (Tang Đố Mộc)
        '8_0' => 2, '9_1' => 2,
        // Giáp Dần, Ất Mão (Đại Khê Thủy)
        '0_2' => 3, '1_3' => 3,
        // Bính Thìn, Đinh Tỵ (Sa Trung Thổ)
        '2_4' => 5, '3_5' => 5,
        // Mậu Ngọ, Kỷ Mùi (Thiên Thượng Hỏa)
        '4_6' => 4, '5_7' => 4,
        // Canh Thân, Tân Dậu (Thạch Lựu Mộc)
        '6_8' => 2, '7_9' => 2,
        // Nhâm Tuất, Quý Hợi (Đại Hải Thủy)
        '8_10'=> 3, '9_11'=> 3
    ];

    // --- 3. DỮ LIỆU SAO (TINH DIỆU) ---

    // Ma trận độ sáng 14 Chính Tinh (M=Miếu, V=Vượng, Đ=Đắc, B=Bình, H=Hãm)
    // Index mảng con tương ứng 12 cung: 0=Tý, 1=Sửu, 2=Dần... 11=Hợi
    const DO_SANG = [
        'TU_VI'      => ['B', 'Đ', 'M', 'B', 'V', 'M', 'M', 'Đ', 'M', 'B', 'V', 'B'],
        'THIEN_CO'   => ['V', 'H', 'V', 'V', 'V', 'B', 'M', 'H', 'V', 'V', 'V', 'B'],
        'THAI_DUONG' => ['H', 'H', 'V', 'M', 'V', 'V', 'M', 'Đ', 'B', 'H', 'H', 'H'],
        'VU_KHUC'    => ['V', 'M', 'Đ', 'H', 'M', 'B', 'V', 'M', 'Đ', 'H', 'M', 'H'],
        'THIEN_DONG' => ['V', 'H', 'M', 'Đ', 'B', 'H', 'H', 'H', 'V', 'Đ', 'B', 'M'],
        'LIEM_TRINH' => ['B', 'Đ', 'M', 'H', 'V', 'H', 'B', 'Đ', 'M', 'H', 'V', 'H'],
        'THIEN_PHU'  => ['M', 'M', 'M', 'B', 'M', 'B', 'V', 'M', 'B', 'B', 'M', 'B'],
        'THAI_AM'    => ['V', 'M', 'H', 'H', 'H', 'H', 'H', 'B', 'B', 'V', 'V', 'M'],
        'THAM_LANG'  => ['H', 'M', 'Đ', 'H', 'M', 'H', 'H', 'M', 'Đ', 'H', 'M', 'H'],
        'CU_MON'     => ['V', 'H', 'M', 'M', 'H', 'H', 'V', 'H', 'M', 'M', 'H', 'V'],
        'THIEN_TUONG'=> ['V', 'M', 'M', 'H', 'M', 'Đ', 'V', 'M', 'M', 'H', 'M', 'B'],
        'THIEN_LUONG'=> ['V', 'Đ', 'V', 'V', 'M', 'H', 'M', 'Đ', 'V', 'H', 'M', 'H'],
        'THAT_SAT'   => ['M', 'Đ', 'M', 'H', 'H', 'V', 'M', 'Đ', 'M', 'H', 'H', 'V'],
        'PHA_QUAN'   => ['M', 'V', 'H', 'H', 'V', 'Đ', 'M', 'V', 'H', 'H', 'V', 'Đ']
    ];

    // Bảng An Tứ Hóa theo Can Năm (Khoa, Quyền, Lộc, Kỵ)
    // Index mảng cha: 0=Giáp, 1=Ất...
    // Quy tắc Nam Phái phổ biến
    const TU_HOA = [
        0 => ['HOA_LOC'=>'LIEM_TRINH', 'HOA_QUYEN'=>'PHA_QUAN', 'HOA_KHOA'=>'VU_KHUC', 'HOA_KY'=>'THAI_DUONG'], // Giáp
        1 => ['HOA_LOC'=>'THIEN_CO',   'HOA_QUYEN'=>'THIEN_LUONG','HOA_KHOA'=>'TU_VI',   'HOA_KY'=>'THAI_AM'],    // Ất
        2 => ['HOA_LOC'=>'THIEN_DONG', 'HOA_QUYEN'=>'THIEN_CO',   'HOA_KHOA'=>'VAN_XUONG','HOA_KY'=>'LIEM_TRINH'], // Bính
        3 => ['HOA_LOC'=>'THAI_AM',    'HOA_QUYEN'=>'THIEN_DONG', 'HOA_KHOA'=>'THIEN_CO', 'HOA_KY'=>'CU_MON'],     // Đinh
        4 => ['HOA_LOC'=>'THAM_LANG',  'HOA_QUYEN'=>'THAI_AM',    'HOA_KHOA'=>'HUU_BAT',  'HOA_KY'=>'THIEN_CO'],   // Mậu
        5 => ['HOA_LOC'=>'VU_KHUC',    'HOA_QUYEN'=>'THAM_LANG',  'HOA_KHOA'=>'THIEN_LUONG','HOA_KY'=>'VAN_KHUC'], // Kỷ
        6 => ['HOA_LOC'=>'THAI_DUONG', 'HOA_QUYEN'=>'VU_KHUC',    'HOA_KHOA'=>'THAI_AM',  'HOA_KY'=>'THIEN_DONG'], // Canh (Có phái Nhật Vũ Âm Đồng)
        7 => ['HOA_LOC'=>'CU_MON',     'HOA_QUYEN'=>'THAI_DUONG', 'HOA_KHOA'=>'VAN_KHUC', 'HOA_KY'=>'VAN_XUONG'],  // Tân
        8 => ['HOA_LOC'=>'THIEN_LUONG','HOA_QUYEN'=>'TU_VI',      'HOA_KHOA'=>'TA_PHU',   'HOA_KY'=>'VU_KHUC'],    // Nhâm (Có phái Lương Vi Phụ Vũ)
        9 => ['HOA_LOC'=>'PHA_QUAN',   'HOA_QUYEN'=>'CU_MON',     'HOA_KHOA'=>'THAI_AM',  'HOA_KY'=>'THAM_LANG']   // Quý
    ];

    // --- 4. CÁC DANH SÁCH SAO ---

    const CHINH_TINH = [
        'TU_VI', 'THIEN_CO', 'THAI_DUONG', 'VU_KHUC', 'THIEN_DONG', 'LIEM_TRINH',
        'THIEN_PHU', 'THAI_AM', 'THAM_LANG', 'CU_MON', 'THIEN_TUONG', 'THIEN_LUONG',
        'THAT_SAT', 'PHA_QUAN'
    ];

    const VONG_THAI_TUE = [
        'THAI_TUE', 'THIEU_DUONG', 'TANG_MON', 'THIEU_AM', 'QUAN_PHU', 'TU_PHU',
        'TUE_PHA', 'LONG_DUC', 'BACH_HO', 'PHUC_DUC', 'DIEU_KHACH', 'TRUC_PHU'
    ];
    
    // Lưu ý: QUAN_PHU_L (Lộc Tồn) khác QUAN_PHU (Thái Tuế)
    const VONG_LOC_TON = [
        'LOC_TON', 'LUC_SI', 'THANH_LONG', 'TIEU_HAO', 'TUONG_QUAN', 'TAU_THU',
        'PHI_LIEM', 'HY_THAN', 'BENH_PHU', 'DAI_HAO', 'PHUC_BINH', 'QUAN_PHU_L'
    ];

    const VONG_TRANG_SINH = [
        'TRANG_SINH', 'MOC_DUC', 'QUAN_DOI', 'LAM_QUAN', 'DE_VUONG', 'SUY',
        'BENH', 'TU', 'MO', 'TUYET', 'THAI', 'DUONG'
    ];

    const LUC_SAT_TINH = ['KINH_DUONG', 'DA_LA', 'DIA_KHONG', 'DIA_KIEP', 'HOA_TINH', 'LINH_TINH'];
    const LUC_CAT_TINH = ['VAN_XUONG', 'VAN_KHUC', 'THIEN_KHOI', 'THIEN_VIET', 'TA_PHU', 'HUU_BAT'];

    // --- 5. CÁC HÀM TIỆN ÍCH STATIC ---

    /**
     * Lấy tên đầy đủ của Can Chi
     */
    public static function getName($can, $chi) {
        return self::CAN[$can] . " " . self::CHI[$chi];
    }

    /**
     * Lấy ID Ngũ Hành của Mệnh (Nạp Âm)
     * @param int $canIndex 0-9
     * @param int $chiIndex 0-11
     * @return int ID Ngũ Hành (1=Kim...5=Thổ)
     */
    public static function getNapAmID($canIndex, $chiIndex) {
        $key = $canIndex . '_' . $chiIndex;
        if (isset(self::NAP_AM[$key])) {
            return self::NAP_AM[$key];
        }
        return 1; // Fallback (Không nên xảy ra)
    }

    /**
     * Lấy Tứ Hóa của năm
     */
    public static function getTuHoa($canYear) {
        if (isset(self::TU_HOA[$canYear])) {
            return self::TU_HOA[$canYear];
        }
        return [];
    }

    /**
     * Lấy thông tin Ngũ Hành (Tên, màu sắc)
     */
    public static function getNguHanhInfo($id) {
        return self::NGU_HANH[$id] ?? ['name' => 'N/A', 'css' => '', 'color' => '#000'];
    }
}
?>