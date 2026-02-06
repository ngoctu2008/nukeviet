<?php
/**
 * TuViCalculator.php
 * Class chịu trách nhiệm tính toán vị trí các sao và lập lá số Tử Vi.
 */

require_once 'TuViConstants.php';

class TuViCalculator {
    public $input;      // Dữ liệu đầu vào: day, month, year, hour, gender...
    public $laso = [];  // Mảng chứa 12 cung sau khi an sao
    public $cuc = 0;    // Cục (2-6)
    public $menhPos = 0; // Vị trí cung Mệnh
    public $thanPos = 0; // Vị trí cung Thân

    /**
     * @param array $input Mảng chứa: ['day'=>int, 'month'=>int, 'year'=>int (can), 'hour'=>int (chi), 'gender'=>int (1:Nam, 0:Nữ), 'chi_year'=>int]
     */
    public function __construct($input) {
        $this->input = $input;
        $this->init12Cung();
    }

    // --- CÁC HÀM TIỆN ÍCH ---

    // Khởi tạo khung 12 cung
    private function init12Cung() {
        for ($i = 0; $i < 12; $i++) {
            $this->laso[$i] = [
                'id' => $i,
                'name' => TuViConstants::CHI[$i],
                'cung_chuc' => '',
                'chinh_tinh' => [],
                'phu_tinh' => [], // Cát tinh & Hung tinh
                'vong_trang_sinh' => '',
                'tuan' => false,
                'triet' => false,
                'than' => false,
                'dai_han' => 0, // Tuổi bắt đầu đại hạn
                'tieu_han' => '' // Tên năm tiểu hạn
            ];
        }
    }

    // Hàm Modulo xử lý số âm (trả về 0-11)
    private function mod($val, $n = 12) {
        return (($val % $n) + $n) % $n;
    }

    // Thêm sao vào cung
    private function addSao($pos, $saoName, $type = 'phu_tinh') {
        $pos = $this->mod($pos);
        if ($type == 'chinh_tinh') {
            $this->laso[$pos]['chinh_tinh'][] = $saoName;
        } else {
            $this->laso[$pos]['phu_tinh'][] = $saoName;
        }
    }

    // Kiểm tra Âm Dương Thuận Lý (Dương Nam, Âm Nữ = Thuận; Âm Nam, Dương Nữ = Nghịch)
    private function isThuanLy() {
        // Can năm: Chẵn là Dương (Giáp, Bính...), Lẻ là Âm (Ất, Đinh...) -> Lưu ý index mảng Can: 0=Giáp (Dương), 1=Ất (Âm)
        // Quy ước input: gender 1=Nam, 0=Nữ
        // Can Index: 0,2,4,6,8 là Dương. 1,3,5,7,9 là Âm.
        
        $isYearDuong = ($this->input['can_year'] % 2 == 0); 
        $isNam = ($this->input['gender'] == 1);

        if (($isNam && $isYearDuong) || (!$isNam && !$isYearDuong)) {
            return 1; // Chiều Thuận (Clockwise)
        }
        return -1; // Chiều Nghịch (Counter-Clockwise)
    }

    // --- CÁC HÀM AN SAO CHÍNH ---

    // 1. Lập Mệnh, Thân, Cục, Đại Hạn
    public function anCoBan() {
        $thang = $this->input['month'];
        $gio = $this->input['hour']; // 0=Tý, 1=Sửu...

        // 1.1 An Mệnh: Tháng thuận, Giờ nghịch (Khởi dần = 2)
        $this->menhPos = $this->mod(2 + ($thang - 1) - $gio);
        
        // 1.2 An Thân: Tháng thuận, Giờ thuận
        $this->thanPos = $this->mod(2 + ($thang - 1) + $gio);
        $this->laso[$this->thanPos]['than'] = true;

        // 1.3 An Tên Cung Chức (Mệnh, Phụ, Phúc...) - Đi Nghịch
        $tenCung = ['Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc', 'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'];
        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($this->menhPos - $i);
            $this->laso[$pos]['cung_chuc'] = $tenCung[$i];
        }

        // 1.4 Xác định Cục
        // Can Năm (0-9). Cần Can tháng Dần của năm đó để tính Cục, nhưng dùng Ma trận Cục tra Can Năm/Chi Mệnh nhanh hơn.
        // Index Can: ghép cặp (Giáp/Kỷ=0...) -> Logic trong Constant
        $canPair = floor($this->input['can_year'] % 10 % 5); 
        $this->cuc = TuViConstants::CUC_MATRIX[$canPair][$this->menhPos];

        // 1.5 An Đại Hạn (Dương Nam/Âm Nữ thuận, Âm Nam/Dương Nữ nghịch)
        $direction = $this->isThuanLy();
        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($this->menhPos + ($i * $direction));
            $this->laso[$pos]['dai_han'] = $this->cuc + ($i * 10);
        }
    }

    // 2. An Chính Tinh (Tử Vi & Thiên Phủ)
    public function anChinhTinh() {
        $ngay = $this->input['day'];
        $cuc = $this->cuc;

        // 2.1 An Tử Vi
        $div = floor($ngay / $cuc);
        $rem = $ngay % $cuc;
        $bu = ($rem == 0) ? 0 : ($cuc - $rem);
        $thuong = ($rem == 0) ? $div : floor(($ngay + $bu) / $cuc);
        
        // Công thức: Cung Dần (2) + Thương - 1 +/- Bù
        // Bù lẻ trừ, Bù chẵn cộng
        $offset = ($bu % 2 == 0) ? $bu : -$bu;
        $tuViPos = $this->mod(2 + $thuong - 1 + $offset);
        $this->addSao($tuViPos, 'TU_VI', 'chinh_tinh');

        // 2.2 An Thiên Phủ (Đối xứng qua trục Dần Thân: Tổng = 10)
        $thienPhuPos = $this->mod(10 - $tuViPos);
        $this->addSao($thienPhuPos, 'THIEN_PHU', 'chinh_tinh');

        // 2.3 Vòng Tử Vi (Đi Nghịch): Liêm(4)-Trống-Trống-Đồng(7)-Vũ(8)-Dương(9)-Trống-Cơ(11)
        // (Offset so với Tử Vi: -1=Cơ, -3=Dương, -4=Vũ, -5=Đồng, -8=Liêm)
        $this->addSao($tuViPos - 1, 'THIEN_CO', 'chinh_tinh');
        $this->addSao($tuViPos - 3, 'THAI_DUONG', 'chinh_tinh');
        $this->addSao($tuViPos - 4, 'VU_KHUC', 'chinh_tinh');
        $this->addSao($tuViPos - 5, 'THIEN_DONG', 'chinh_tinh');
        $this->addSao($tuViPos - 8, 'LIEM_TRINH', 'chinh_tinh');

        // 2.4 Vòng Thiên Phủ (Đi Thuận): Âm(1)-Tham(2)-Cự(3)-Tướng(4)-Lương(5)-Sát(6)-Phá(10)
        $this->addSao($thienPhuPos + 1, 'THAI_AM', 'chinh_tinh');
        $this->addSao($thienPhuPos + 2, 'THAM_LANG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 3, 'CU_MON', 'chinh_tinh');
        $this->addSao($thienPhuPos + 4, 'THIEN_TUONG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 5, 'THIEN_LUONG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 6, 'THAT_SAT', 'chinh_tinh');
        $this->addSao($thienPhuPos + 10, 'PHA_QUAN', 'chinh_tinh');
    }

    // 3. An Sao Theo Giờ (Giờ sinh)
    public function anSaoGio() {
        $gio = $this->input['hour']; // 0-11
        // Văn Xương (Khởi Tuất nghịch), Văn Khúc (Khởi Thìn thuận)
        $xuong = $this->mod(10 - $gio); // Tuất = 10
        $khuc = $this->mod(4 + $gio);   // Thìn = 4
        $this->addSao($xuong, 'VAN_XUONG');
        $this->addSao($khuc, 'VAN_KHUC');

        // Địa Không (Khởi Hợi nghịch), Địa Kiếp (Khởi Hợi thuận)
        $khong = $this->mod(11 - $gio);
        $kiep = $this->mod(11 + $gio);
        $this->addSao($khong, 'DIA_KHONG');
        $this->addSao($kiep, 'DIA_KIEP');

        // Thai Phụ (Khởi Ngọ thuận), Phong Cáo (Khởi Dần thuận)
        $this->addSao(6 + $gio, 'THAI_PHU'); // Ngọ = 6
        $this->addSao(2 + $gio, 'PHONG_CAO'); // Dần = 2
    }

    // 4. An Sao Theo Tháng (Tháng sinh)
    public function anSaoThang() {
        $thang = $this->input['month'];
        // Tả Phụ (Thìn thuận), Hữu Bật (Tuất nghịch)
        $ta = $this->mod(4 + ($thang - 1));
        $huu = $this->mod(10 - ($thang - 1));
        $this->addSao($ta, 'TA_PHU');
        $this->addSao($huu, 'HUU_BAT');

        // Thiên Hình (Dậu thuận), Thiên Riêu (Sửu thuận)
        $hinh = $this->mod(9 + ($thang - 1));
        $rieu = $this->mod(1 + ($thang - 1));
        $this->addSao($hinh, 'THIEN_HINH');
        $this->addSao($rieu, 'THIEN_RIEU');
        // Thiên Y đồng cung Thiên Riêu
        $this->addSao($rieu, 'THIEN_Y');
        
        // Thiên Giải (Thân nghịch), Địa Giải (Mùi thuận - tùy phái, thường lấy theo ngày hoặc tháng)
        // Ở đây dùng công thức phổ biến: Địa Giải theo tháng (Mùi đi thuận?) -> Không, Địa Giải cố định hoặc theo tháng.
        // Chọn công thức: Thiên Giải (Thân nghịch tháng), Địa Giải (Mùi tiến tháng)
        $this->addSao($this->mod(8 - ($thang-1)), 'THIEN_GIAI');
        $this->addSao($this->mod(7 + ($thang-1)), 'DIA_GIAI');
    }

    // 5. An Sao Theo Can Năm (Lộc Tồn, Kình Đà, Khôi Việt, Tứ Hóa)
    public function anSaoCanNam() {
        $can = $this->input['can_year']; // 0=Giáp... 9=Quý

        // 5.1 Lộc Tồn & Bác Sỹ (Vòng Lộc Tồn)
        // Bảng vị trí Lộc Tồn: Giáp->Dần(2), Ất->Mão(3), Bính/Mậu->Tỵ(5), Đinh/Kỷ->Ngọ(6), Canh->Thân(8), Tân->Dậu(9), Nhâm->Hợi(11), Quý->Tý(0)
        $locTonMap = [2, 3, 5, 6, 5, 6, 8, 9, 11, 0];
        $posLoc = $locTonMap[$can];
        
        $this->addSao($posLoc, 'LOC_TON');
        $this->addSao($posLoc, 'BAC_SY'); // Bác Sỹ luôn đồng cung Lộc Tồn

        // Kình Dương (Trước Lộc), Đà La (Sau Lộc)
        $this->addSao($posLoc + 1, 'KINH_DUONG');
        $this->addSao($posLoc - 1, 'DA_LA');

        // An các sao còn lại của Vòng Lộc Tồn (Lực Sĩ, Thanh Long...)
        // Dương Nam/Âm Nữ: Thuận. Âm Nam/Dương Nữ: Nghịch.
        $dir = $this->isThuanLy();
        $vongLocTon = ['LOC_TON','LUC_SI','THANH_LONG','TIEU_HAO','TUONG_QUAN','TAU_THU','PHI_LIEM','HY_THAN','BENH_PHU','DAI_HAO','PHUC_BINH','QUAN_PHU_L'];
        // Quan Phủ vòng Lộc Tồn đặt tên khác để tránh trùng Quan Phù vòng Thái Tuế
        for ($i = 1; $i < 12; $i++) {
            $this->addSao($posLoc + ($i * $dir), $vongLocTon[$i]);
        }
        
        // 5.2 Khôi Việt (Quý Nhân)
        // Giáp Mậu (Sửu Mùi), Ất Kỷ (Tý Thân), Bính Đinh (Hợi Dậu), Canh Tân (Ngọ Dần), Nhâm Quý (Tỵ Mão)
        // Mảng [Khôi, Việt]
        $kvMap = [
            0 => [1, 7], 1 => [0, 8], 2 => [11, 9], 3 => [11, 9], 4 => [1, 7],
            5 => [0, 8], 6 => [6, 2], 7 => [6, 2], 8 => [5, 3], 9 => [5, 3]
        ];
        $this->addSao($kvMap[$can][0], 'THIEN_KHOI');
        $this->addSao($kvMap[$can][1], 'THIEN_VIET');
        
        // 5.3 Tứ Hóa (Khoa, Quyền, Lộc, Kỵ)
        // Cần bảng tra Tứ Hóa trong Constants. Ví dụ: Giáp (Liêm, Phá, Vũ, Dương) -> Liêm=Lộc, Phá=Quyền...
        // Giả sử có hàm static TuViConstants::getTuHoa($can) trả về ['HOA_LOC'=>'LIEM_TRINH', ...]
        $tuHoa = TuViConstants::getTuHoa($can); 
        foreach ($this->laso as &$cung) {
            foreach ($cung['chinh_tinh'] as $sao) {
                if ($sao == $tuHoa['HOA_LOC']) $cung['phu_tinh'][] = 'HOA_LOC';
                if ($sao == $tuHoa['HOA_QUYEN']) $cung['phu_tinh'][] = 'HOA_QUYEN';
                if ($sao == $tuHoa['HOA_KHOA']) $cung['phu_tinh'][] = 'HOA_KHOA';
                if ($sao == $tuHoa['HOA_KY']) $cung['phu_tinh'][] = 'HOA_KY';
            }
            // Một số phái an Tứ hóa cho Phụ tinh (Văn Xương, Văn Khúc...), cần check thêm mảng phu_tinh
            foreach ($cung['phu_tinh'] as $sao) {
                 if ($sao == $tuHoa['HOA_LOC']) $cung['phu_tinh'][] = 'HOA_LOC';
                 if ($sao == $tuHoa['HOA_QUYEN']) $cung['phu_tinh'][] = 'HOA_QUYEN';
                 if ($sao == $tuHoa['HOA_KHOA']) $cung['phu_tinh'][] = 'HOA_KHOA';
                 if ($sao == $tuHoa['HOA_KY']) $cung['phu_tinh'][] = 'HOA_KY';
            }
        }
        unset($cung); // Break reference
        
        // 5.4 Lưu Hà (Theo Can)
        $haMap = [9, 10, 7, 8, 5, 6, 8, 9, 11, 0]; // Giáp->Dậu, Ất->Tuất...
        $this->addSao($haMap[$can], 'LUU_HA');
        
        // 5.5 Thiên Trù (Theo Can)
        $truMap = [5, 6, 0, 5, 6, 8, 2, 6, 9, 10]; // Giáp->Tỵ, Ất->Ngọ...
        $this->addSao($truMap[$can], 'THIEN_TRU');
    }

    // 6. An Sao Theo Chi Năm (Thái Tuế, Hỏa Linh, Cô Quả...)
    public function anSaoChiNam() {
        $chi = $this->input['chi_year'];

        // 6.1 Vòng Thái Tuế (Luôn thuận)
        $vongThaiTue = ['THAI_TUE','THIEU_DUONG','TANG_MON','THIEU_AM','QUAN_PHU','TU_PHU','TUE_PHA','LONG_DUC','BACH_HO','PHUC_DUC','DIEU_KHACH','TRUC_PHU'];
        for ($i = 0; $i < 12; $i++) {
            $this->addSao($chi + $i, $vongThaiTue[$i]);
        }

        // 6.2 Hỏa Tinh, Linh Tinh (Dựa vào Chi Năm + Giờ Sinh)
        // Nhóm Dần Ngọ Tuất (2,6,10): Hỏa khởi Sửu, Linh khởi Mão
        // Nhóm Thân Tý Thìn (8,0,4): Hỏa khởi Dần, Linh khởi Tuất
        // Nhóm Tỵ Dậu Sửu (5,9,1): Hỏa khởi Mão, Linh khởi Tuất
        // Nhóm Hợi Mão Mùi (11,3,7): Hỏa khởi Dậu, Linh khởi Tuất
        // Hỏa thuận, Linh nghịch (từ vị trí khởi đến giờ sinh)
        
        $khoiHoa = 1; $khoiLinh = 3; // Mặc định nhóm Dần Ngọ Tuất
        if (in_array($chi, [8,0,4])) { $khoiHoa = 2; $khoiLinh = 10; }
        if (in_array($chi, [5,9,1])) { $khoiHoa = 3; $khoiLinh = 10; }
        if (in_array($chi, [11,3,7])) { $khoiHoa = 9; $khoiLinh = 10; }
        
        $gio = $this->input['hour'];
        $this->addSao($khoiHoa + $gio, 'HOA_TINH');
        $this->addSao($khoiLinh - $gio, 'LINH_TINH');

        // 6.3 Thiên Mã (Theo Tam Hợp cục của Chi năm)
        // Dần Ngọ Tuất -> Thân. Thân Tý Thìn -> Dần. Tỵ Dậu Sửu -> Hợi. Hợi Mão Mùi -> Tỵ.
        // Mã luôn ở cung Dịch Mã (Dần Thân Tỵ Hợi) xung với hành Tam hợp
        if (in_array($chi, [2,6,10])) $ma = 8;
        elseif (in_array($chi, [8,0,4])) $ma = 2;
        elseif (in_array($chi, [5,9,1])) $ma = 11;
        else $ma = 5;
        $this->addSao($ma, 'THIEN_MA');
        
        // 6.4 Cô Thần, Quả Tú
        // Dần Mão Thìn (Phương Đông) -> Cô ở Tỵ, Quả ở Sửu...
        // Logic: Cô Thần ở Tứ Sinh trước 1 cung so với nhóm, Quả Tú ở Tứ Mộ sau nhóm?
        // Công thức chuẩn:
        // Hợi Tý Sửu (hướng Bắc): Cô Dần, Quả Tuất
        // Dần Mão Thìn (hướng Đông): Cô Tỵ, Quả Sửu
        // Tỵ Ngọ Mùi (hướng Nam): Cô Thân, Quả Thìn
        // Thân Dậu Tuất (hướng Tây): Cô Hợi, Quả Mùi
        if (in_array($chi, [11,0,1])) { $co=2; $qua=10; }
        elseif (in_array($chi, [2,3,4])) { $co=5; $qua=1; }
        elseif (in_array($chi, [5,6,7])) { $co=8; $qua=4; }
        else { $co=11; $qua=7; }
        $this->addSao($co, 'CO_THAN');
        $this->addSao($qua, 'QUA_TU');
        
        // 6.5 Phượng Các (Tuất thuận), Giải Thần (Tuất nghịch)
        $this->addSao(10 + $chi, 'PHUONG_CAC'); // Tuất = 10 -> Sai? Phượng Các an theo Tuất của năm? 
        // Chính xác: Khởi Tuất, đi thuận đến Chi năm.
        // Giải Thần: Khởi Tuất, đi nghịch đến Chi năm. (Có sách an Giải Thần đồng cung Phượng Các).
        // Theo Tử Vi chuẩn: Giải Thần đồng cung Phượng Các.
        $this->addSao(10 + $chi, 'GIAI_THAN');
        
        // Long Trì (Thìn thuận)
        $this->addSao(4 + $chi, 'LONG_TRI'); 
        
        // Thiên Khốc (Ngọ nghịch), Thiên Hư (Ngọ thuận)
        $this->addSao(6 - $chi, 'THIEN_KHOC');
        $this->addSao(6 + $chi, 'THIEN_HU');
        
        // Đào Hoa (Tại 4 cung Tý Ngọ Mão Dậu - Mộc dục của cục ngũ hành tam hợp)
        // Dần Ngọ Tuất (Hỏa) -> Đào ở Mão
        // Thân Tý Thìn (Thủy) -> Đào ở Dậu
        // Tỵ Dậu Sửu (Kim) -> Đào ở Ngọ
        // Hợi Mão Mùi (Mộc) -> Đào ở Tý
        if (in_array($chi, [2,6,10])) $dao = 3;
        elseif (in_array($chi, [8,0,4])) $dao = 9;
        elseif (in_array($chi, [5,9,1])) $dao = 6;
        else $dao = 0;
        $this->addSao($dao, 'DAO_HOA');
        
        // Hồng Loan (Mão nghịch)
        $this->addSao(3 - $chi, 'HONG_LOAN');
        // Thiên Hỷ (Đối Hồng Loan)
        $this->addSao(3 - $chi + 6, 'THIEN_HY');
    }

    // 7. An Vòng Tràng Sinh (Theo Cục và Giới tính)
    public function anVongTrangSinh() {
        // Cục: 2-Thủy, 3-Mộc, 4-Kim, 5-Thổ, 6-Hỏa
        // Vị trí Tràng Sinh: Thủy/Thổ (Thân), Mộc (Hợi), Kim (Tỵ), Hỏa (Dần)
        // Lưu ý: Thổ Cục khởi Tràng sinh tại Thân giống Thủy Cục
        
        $start = 0;
        switch ($this->cuc) {
            case 2: $start = 8; break; // Thân
            case 3: $start = 11; break; // Hợi
            case 4: $start = 5; break; // Tỵ
            case 5: $start = 8; break; // Thân (Thổ theo Thủy)
            case 6: $start = 2; break; // Dần
        }
        
        // Chiều: Dương Nam/Âm Nữ -> Thuận. Âm Nam/Dương Nữ -> Nghịch.
        $dir = $this->isThuanLy();
        $vongTS = ['Tràng Sinh','Mộc Dục','Quan Đới','Lâm Quan','Đế Vượng','Suy','Bệnh','Tử','Mộ','Tuyệt','Thai','Dưỡng'];
        
        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($start + ($i * $dir));
            $this->laso[$pos]['vong_trang_sinh'] = $vongTS[$i];
        }
    }

    // 8. An Tuần - Triệt
    public function anTuanTriet() {
        // 8.1 Triệt (Dựa vào Can năm)
        // Giáp Kỷ (Thân Dậu), Ất Canh (Ngọ Mùi), Bính Tân (Thìn Tỵ), Đinh Nhâm (Dần Mão), Mậu Quý (Tý Sửu)
        $can = $this->input['can_year'];
        // Logic: (can % 5) -> 0=Giáp/Kỷ -> Thân(8). 
        $startTriet = [8, 6, 4, 2, 0];
        $pos1 = $startTriet[$can % 5];
        $pos2 = $pos1 + 1;
        $this->laso[$pos1]['triet'] = true;
        $this->laso[$pos2]['triet'] = true;

        // 8.2 Tuần (Dựa vào hoa giáp của năm sinh - Tuần Trung Không Vong)
        // Công thức: Lấy Chi - Can. Nếu < 0 thì + 12. 
        // Kết quả là Chi của cung kết thúc tuần giáp. Tuần đóng ở 2 cung kế tiếp.
        // Ví dụ: Giáp Thìn (0, 4). 4-0 = 4 (Thìn). Tuần đóng Tỵ, Ngọ? Không.
        // Cách tính: (Chi - Can + 10) % 12 là vị trí đầu của Tuần?
        // Chính xác: Vị trí Tuần = (Chi - Can + 10) % 12 và +1.
        // Ví dụ Giáp Thìn: (4 - 0 + 10) % 12 = 14%12 = 2 (Dần). 
        // Tuần đóng Dần (2) và Mão (3).
        $posTuan1 = $this->mod($this->input['chi_year'] - $this->input['can_year'] + 10);
        $posTuan2 = $this->mod($posTuan1 + 1);
        $this->laso[$posTuan1]['tuan'] = true;
        $this->laso[$posTuan2]['tuan'] = true;
    }

    // --- HÀM TỔNG HỢP: LẬP LÁ SỐ ---
    public function execute() {
        $this->anCoBan(); // Mệnh, Thân, Cục
        $this->anChinhTinh(); // Tử Vi, Thiên Phủ...
        $this->anSaoGio(); // Xương Khúc, Không Kiếp...
        $this->anSaoThang(); // Tả Hữu, Hình Riêu...
        $this->anSaoCanNam(); // Lộc Tồn, Khôi Việt, Tứ Hóa...
        $this->anSaoChiNam(); // Thái Tuế, Hỏa Linh, Đào Hồng...
        $this->anVongTrangSinh();
        $this->anTuanTriet();
        
        return [
            'info' => $this->input,
            'cuc' => $this->cuc,
            'menh_pos' => $this->menhPos,
            'than_pos' => $this->thanPos,
            'laso' => $this->laso
        ];
    }
}
?>