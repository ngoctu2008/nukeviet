<?php

namespace NukeViet\Module\HuyenHoc;

class TuViHuongNghiep {
    private $laso;          // Dữ liệu lá số
    private $quanLocPos;    // Vị trí cung Quan Lộc
    private $canNamSinh;    // Can năm sinh (Để tìm Lai Nhân Cung)

    // 1. Dữ liệu Nghề Nghiệp theo Sao (Hiện đại hóa)
    const NGHE_NGHIEP_MAP = [
        'TU_VI' => [
            'group' => 'Lãnh đạo, Quản lý',
            'jobs' => 'Chính trị gia, Giám đốc điều hành, Quản lý nhân sự, Chủ doanh nghiệp, Trang sức đá quý.'
        ],
        'THIEN_CO' => [
            'group' => 'Kỹ thuật, Trí tuệ',
            'jobs' => 'Công nghệ thông tin (IT), Lập trình, Kỹ sư máy móc, Chiến lược gia, Tôn giáo, Thiết kế đồ họa.'
        ],
        'THAI_DUONG' => [
            'group' => 'Công chúng, Năng lượng',
            'jobs' => 'Giáo dục, Chính trị, Năng lượng (Điện/Xăng dầu), Truyền thông, Quan hệ công chúng, Luật sư.'
        ],
        'VU_KHUC' => [
            'group' => 'Tài chính, Kim khí',
            'jobs' => 'Ngân hàng, Kế toán, Kiểm toán, Chứng khoán, Kinh doanh kim loại/xe hơi, Quân sự.'
        ],
        'THIEN_DONG' => [
            'group' => 'Dịch vụ, Phúc lợi',
            'jobs' => 'Du lịch, Nhà hàng khách sạn, Tâm lý học, Bác sĩ nhi khoa, Dịch vụ giải trí, Kinh doanh đồ ăn uống.'
        ],
        'LIEM_TRINH' => [
            'group' => 'Giám sát, Kỹ thuật tinh vi',
            'jobs' => 'Thanh tra, Giám sát, Kỹ thuật điện tử, Lập trình viên, Pháp luật, Ngành làm đẹp/thẩm mỹ.'
        ],
        'THIEN_PHU' => [
            'group' => 'Tài chính, Quản trị',
            'jobs' => 'Quản lý kho quỹ, Ngân hàng, Bất động sản, Hành chính nhân sự, Nông lâm nghiệp.'
        ],
        'THAI_AM' => [
            'group' => 'Bất động sản, Nghệ thuật',
            'jobs' => 'Kinh doanh BĐS, Kiến trúc sư, Khách sạn, Mỹ phẩm, Nghệ thuật, Y dược (Phụ khoa/Mắt).'
        ],
        'THAM_LANG' => [
            'group' => 'Giao tiếp, Giải trí',
            'jobs' => 'Nghệ thuật biểu diễn, Kinh doanh giải trí (Bar/Pub), Ngoại giao, Phong thủy/Tâm linh, Đầu tư mạo hiểm.'
        ],
        'CU_MON' => [
            'group' => 'Ngôn ngữ, Y dược',
            'jobs' => 'Luật sư, Giáo viên, Sales/Marketing, Bác sĩ/Dược sĩ, Ẩm thực, Nhà phê bình.'
        ],
        'THIEN_TUONG' => [
            'group' => 'Hành chính, Dịch vụ',
            'jobs' => 'Bảo hiểm, Thư ký/Trợ lý, Bác sĩ, Dịch vụ công, Thời trang (May mặc).'
        ],
        'THIEN_LUONG' => [
            'group' => 'Giáo dục, Y tế, Giám sát',
            'jobs' => 'Giáo viên, Bác sĩ/Đông y, Thanh tra, Kiểm toán, Từ thiện, Bảo hiểm.'
        ],
        'THAT_SAT' => [
            'group' => 'Quân sự, Công nghiệp nặng',
            'jobs' => 'Công an/Quân đội, Kỹ sư cơ khí, Xây dựng, Phẫu thuật viên, Kinh doanh sắt thép.'
        ],
        'PHA_QUAN' => [
            'group' => 'Sáng tạo, Phá cũ đổi mới',
            'jobs' => 'Thương mại xuất nhập khẩu, Xây dựng (phá dỡ), Vận tải biển, Thủy hải sản, Tiếp thị sáng tạo.'
        ]
    ];

    // 2. Bảng Tứ Hóa (Can -> Sao hóa Lộc/Kỵ)
    // Cấu trúc: CanID (0=Giáp) => ['LOC' => Sao, 'KY' => Sao]
    const TU_HOA_TABLE = [
        0 => ['LOC' => 'LIEM_TRINH', 'KY' => 'THAI_DUONG'], // Giáp
        1 => ['LOC' => 'THIEN_CO',   'KY' => 'THAI_AM'],    // Ất
        2 => ['LOC' => 'THIEN_DONG', 'KY' => 'LIEM_TRINH'], // Bính
        3 => ['LOC' => 'THAI_AM',    'KY' => 'CU_MON'],     // Đinh
        4 => ['LOC' => 'THAM_LANG',  'KY' => 'THIEN_CO'],   // Mậu
        5 => ['LOC' => 'VU_KHUC',    'KY' => 'VAN_KHUC'],   // Kỷ
        6 => ['LOC' => 'THAI_DUONG', 'KY' => 'THIEN_DONG'], // Canh
        7 => ['LOC' => 'CU_MON',     'KY' => 'VAN_XUONG'],  // Tân
        8 => ['LOC' => 'THIEN_LUONG','KY' => 'VU_KHUC'],    // Nhâm
        9 => ['LOC' => 'PHA_QUAN',   'KY' => 'THAM_LANG']   // Quý
    ];

    public function __construct($calculatorData) {
        $this->laso = $calculatorData['dia_ban'];
        $this->canNamSinh = $calculatorData['meta']['canYear'];

        // Tìm vị trí cung Quan Lộc
        foreach ($this->laso as $cung) {
            if (strpos($cung['palace_name'], 'Quan Lộc') !== false) {
                $this->quanLocPos = $cung['index'];
                break;
            }
        }
    }

    // --- PHẦN 1: TƯ VẤN NGHỀ NGHIỆP (QUA SAO) ---

    public function getNgheNghiep() {
        $cungQuan = $this->laso[$this->quanLocPos];

        // Extract star codes
        $saoQuan = [];
        foreach($cungQuan['chinh_tinh'] as $s) $saoQuan[] = strtoupper($s['code']);

        $isVCD = empty($saoQuan);

        // Nếu VCD, mượn sao xung chiếu (Phu Thê)
        if ($isVCD) {
            $xungChieuPos = ($this->quanLocPos + 6) % 12;
            $cungXung = $this->laso[$xungChieuPos];
            foreach($cungXung['chinh_tinh'] as $s) $saoQuan[] = strtoupper($s['code']);
        }

        $listNghe = [];
        foreach ($saoQuan as $saoCode) {
            if (isset(self::NGHE_NGHIEP_MAP[$saoCode])) {
                $data = self::NGHE_NGHIEP_MAP[$saoCode];
                $info = TuViLapSo::getStarInfo(strtolower($saoCode));
                $name = $info ? $info['name'] : $saoCode;

                $listNghe[] = [
                    'sao' => $name,
                    'nhom' => $data['group'],
                    'goi_y' => $data['jobs']
                ];
            }
        }

        return [
            'is_vcd' => $isVCD,
            'list' => $listNghe
        ];
    }

    // --- PHẦN 2: LAI NHÂN CUNG (NGUYÊN NHÂN THÀNH BẠI) ---

    public function getLaiNhanCung() {
        // Lai Nhân Cung là cung có Can trùng với Can Năm Sinh
        // Need to calculate Can of each Palace.
        // Formula: CanMenh calculated in TuViLapSo.
        // CanMenh = (($canDan + ($posMenh - 2)) % 10)
        // CanPalace[i] = (CanMenh + (i - MenhPos)) % 10.
        // Or re-calculate: Can of Palace = (CanDan + (PalaceIndex - 2)) % 10.
        // CanDan = (($canYear % 5) + 1) * 2 - 2 ? No.
        // TuViLapSo: $canDan = (($canYear % 5) + 1) * 2; if>=10 -=10.

        $canYear = $this->canNamSinh;
        $canDan = (($canYear % 5) + 1) * 2;
        if ($canDan >= 10) $canDan -= 10;

        $laiNhanPos = -1;

        for ($i = 0; $i < 12; $i++) {
            // Index 0=Ty, 1=Suu, 2=Dan.
            // Steps from Dan (2).
            $steps = $i - 2;
            if ($steps < 0) $steps += 12;

            $canCung = ($canDan + $steps) % 10;

            if ($canCung == $this->canNamSinh) {
                $laiNhanPos = $i;
                break;
            }
        }

        if ($laiNhanPos == -1) return null;

        $cung = $this->laso[$laiNhanPos];
        // Clean palace name
        $tenCungFull = $cung['palace_name'];
        $tenCung = explode(' ', $tenCungFull)[0]; // First word? "Phu Mau", "Phuc Duc" -> 2 words.
        // Better: Remove "(Thân)"
        $tenCung = trim(str_replace('(Thân)', '', $tenCungFull));

        // Luận giải ý nghĩa Lai Nhân Cung
        $luanGiai = "";
        // Match partial
        if (strpos($tenCung, 'Mệnh')!==false) $luanGiai = "Sự nghiệp do chính bản thân tự quyết định, tự tay làm nên, không nhờ cậy ai.";
        elseif (strpos($tenCung, 'Phụ Mẫu')!==false) $luanGiai = "Thành bại do ảnh hưởng của Cha Mẹ, bằng cấp, hoặc cấp trên nâng đỡ.";
        elseif (strpos($tenCung, 'Phúc Đức')!==false) $luanGiai = "Sự nghiệp ảnh hưởng bởi phúc tổ tiên, hoặc do sở thích/lý tưởng tinh thần chi phối.";
        elseif (strpos($tenCung, 'Điền Trạch')!==false) $luanGiai = "Sự nghiệp gắn liền với đất đai, nhà cửa hoặc cơ sở kinh doanh tại gia.";
        elseif (strpos($tenCung, 'Quan Lộc')!==false) $luanGiai = "Là người cuồng công việc, sự nghiệp là lẽ sống. Thành bại do chính công việc tạo ra.";
        elseif (strpos($tenCung, 'Nô Bộc')!==false) $luanGiai = "Sự nghiệp phụ thuộc vào bạn bè, đối tác hoặc khách hàng (chúng sinh).";
        elseif (strpos($tenCung, 'Thiên Di')!==false) $luanGiai = "Thành bại do việc đi xa, xuất ngoại hoặc quan hệ xã hội bên ngoài.";
        elseif (strpos($tenCung, 'Tật Ách')!==false) $luanGiai = "Sự nghiệp ảnh hưởng bởi sức khỏe hoặc tâm tư thầm kín. Có thể làm nghề liên quan cơ thể.";
        elseif (strpos($tenCung, 'Tài Bạch')!==false) $luanGiai = "Lấy tiền bạc làm thước đo sự nghiệp. Thành bại do cách quản lý tài chính.";
        elseif (strpos($tenCung, 'Tử Tức')!==false) $luanGiai = "Sự nghiệp liên quan đến con cái, học trò hoặc hợp tác đầu tư nhỏ.";
        elseif (strpos($tenCung, 'Phu Thê')!==false) $luanGiai = "Sự nghiệp chịu ảnh hưởng lớn từ người phối ngẫu. Lấy vợ/chồng xong mới thành đạt (hoặc thất bại).";
        elseif (strpos($tenCung, 'Huynh Đệ')!==false) $luanGiai = "Thành bại do anh em hỗ trợ hoặc cạnh tranh. Cũng có thể là thành tựu kinh tế (Tài khố).";

        return [
            'cung_ten' => $tenCung,
            'cung_vi' => $cung['name'],
            'y_nghia' => $luanGiai
        ];
    }

    // --- PHẦN 3: PHI TINH TỨ HÓA (DÒNG CHẢY SỰ NGHIỆP) ---

    public function getPhiTinhQuanLoc() {
        $cungQuan = $this->laso[$this->quanLocPos];

        // Calculate Can Quan
        $canYear = $this->canNamSinh;
        $canDan = (($canYear % 5) + 1) * 2;
        if ($canDan >= 10) $canDan -= 10;
        $steps = $this->quanLocPos - 2;
        if ($steps < 0) $steps += 12;
        $canQuanID = ($canDan + $steps) % 10;

        // Lấy bộ sao Tứ Hóa của Can Quan
        $boTuHoa = self::TU_HOA_TABLE[$canQuanID];

        $ketQua = [];

        // 1. Phi Hóa Lộc
        $saoLoc = $boTuHoa['LOC'];
        $cungLocPos = $this->timCungChuaSao($saoLoc);
        if ($cungLocPos !== false) {
            $tenCungLoc = $this->laso[$cungLocPos]['palace_name'];
            $ketQua['hoa_loc'] = [
                'sao' => $this->getSaoName($saoLoc),
                'nhap_cung' => $tenCungLoc,
                'y_nghia' => "Quan Lộc phi Hóa Lộc nhập cung $tenCungLoc: Công việc đem lại lợi ích, tiền bạc cho $tenCungLoc."
            ];
        }

        // 2. Phi Hóa Kỵ
        $saoKy = $boTuHoa['KY'];
        $cungKyPos = $this->timCungChuaSao($saoKy);
        if ($cungKyPos !== false) {
            $tenCungKy = $this->laso[$cungKyPos]['palace_name'];
            $ketQua['hoa_ky'] = [
                'sao' => $this->getSaoName($saoKy),
                'nhap_cung' => $tenCungKy,
                'y_nghia' => "Quan Lộc phi Hóa Kỵ nhập cung $tenCungKy: Công việc gây áp lực, mệt mỏi cho $tenCungKy."
            ];
        }

        return $ketQua;
    }

    // --- Helper Functions ---

    private function timCungChuaSao($saoCode) {
        $saoCode = strtolower($saoCode);
        foreach ($this->laso as $cung) {
            foreach($cung['chinh_tinh'] as $s) if($s['code'] == $saoCode) return $cung['index'];
            if(isset($cung['phu_tinh_tot'])) foreach($cung['phu_tinh_tot'] as $s) if($s['code'] == $saoCode) return $cung['index'];
            if(isset($cung['phu_tinh_xau'])) foreach($cung['phu_tinh_xau'] as $s) if($s['code'] == $saoCode) return $cung['index'];
        }
        return false;
    }

    private function getSaoName($code) {
        $info = TuViLapSo::getStarInfo(strtolower($code));
        return $info ? $info['name'] : $code;
    }

    // --- XUẤT BÁO CÁO FULL HTML ---

    public function renderReport() {
        $nghe = $this->getNgheNghiep();
        $laiNhan = $this->getLaiNhanCung();
        $phiTinh = $this->getPhiTinhQuanLoc();

        $html = "<div class='career-report'>";
        $html .= "<h3>ĐỊNH VỊ SỰ NGHIỆP & QUAN LỘC</h3>";

        // 1. Gợi ý nghề nghiệp
        $html .= "<div class='sec-job'><h4>1. Định hướng Ngành Nghề (Cung Quan Lộc)</h4>";
        if ($nghe['is_vcd']) $html .= "<p><em>(Cung Quan Vô Chính Diệu - Lấy sao xung chiếu để luận)</em></p>";
        $html .= "<ul>";
        foreach ($nghe['list'] as $item) {
            $html .= "<li><strong>Sao {$item['sao']} ({$item['nhom']}):</strong> {$item['goi_y']}</li>";
        }
        $html .= "</ul></div>";

        // 2. Lai Nhân Cung
        if ($laiNhan) {
            $html .= "<div class='sec-lai-nhan'><h4>2. Cội Nguồn Thành Bại (Lai Nhân Cung)</h4>";
            $html .= "<p>Lai Nhân Cung tại <strong>{$laiNhan['cung_ten']}</strong> ({$laiNhan['cung_vi']}).</p>";
            $html .= "<p><strong>Luận giải:</strong> {$laiNhan['y_nghia']}</p>";
            $html .= "</div>";
        }

        // 3. Phi Tinh
        $html .= "<div class='sec-phi-tinh'><h4>3. Dòng Chảy Sự Nghiệp (Phi Tinh Tứ Hóa)</h4>";
        if (isset($phiTinh['hoa_loc'])) {
            $html .= "<p class='good'>✔ <strong>Hóa Lộc:</strong> {$phiTinh['hoa_loc']['y_nghia']}</p>";
        }
        if (isset($phiTinh['hoa_ky'])) {
            $html .= "<p class='bad'>✖ <strong>Hóa Kỵ:</strong> {$phiTinh['hoa_ky']['y_nghia']}</p>";
        }
        $html .= "</div>";

        $html .= "</div>";
        return $html;
    }
}
