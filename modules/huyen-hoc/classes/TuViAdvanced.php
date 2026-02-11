<?php

namespace NukeViet\Module\HuyenHoc;

class TuViAdvanced {
    protected $laso;
    protected $menhPos;
    protected $thanPos;

    const CUNG_CHUC_ORDER = [
        'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
        'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
    ];

    public function __construct($calculatorData) {
        $this->laso = $calculatorData['dia_ban'];
        $this->menhPos = $calculatorData['meta']['menh_idx'];
        $this->thanPos = $calculatorData['meta']['than_idx'];
    }

    // --- PHẦN 1: LẬP CỰC & PHI CUNG (XEM NGƯỜI THÂN) ---
    /**
     * Chuyển đổi lá số để xem cho người thân (Thuyết Thể - Dụng)
     * @param string $moiQuanHe 'PHU_THE', 'PHU_MAU', 'HUYNH_DE', 'TU_TUC'
     */
    public function lapCucNguoiThan($moiQuanHe) {
        // 1. Xác định Cung Mệnh Mới (Lập Thái Cực)
        $newMenhPos = 0;
        $title = "";

        switch ($moiQuanHe) {
            case 'PHU_THE': // Xem Chồng/Vợ
                $newMenhPos = $this->findCungID('Phu Thê');
                $title = "Lá Số Của Người Phối Ngẫu (Xem qua cung Phu Thê)";
                break;
            case 'PHU_MAU': // Xem Cha Mẹ
                $newMenhPos = $this->findCungID('Phụ Mẫu');
                $title = "Lá Số Của Cha Mẹ (Xem qua cung Phụ Mẫu)";
                break;
            case 'HUYNH_DE': // Xem Anh Em
                $newMenhPos = $this->findCungID('Huynh Đệ');
                $title = "Lá Số Của Anh Em (Xem qua cung Huynh Đệ)";
                break;
            case 'TU_TUC': // Xem Con Cái
                $newMenhPos = $this->findCungID('Tử Tức');
                $title = "Lá Số Của Con Cái (Xem qua cung Tử Tức)";
                break;
            default:
                return null;
        }

        // 2. An lại 12 cung chức dựa trên Mệnh mới (Phi Cung)
        $newLasoMapping = [];

        foreach (self::CUNG_CHUC_ORDER as $i => $tenCungMoi) {
            // Mệnh mới tại $newMenhPos. Cung chức an Nghịch: Mệnh(0), Phụ(-1), Phúc(-2)...
            // Wait, standard Tu Vi palace order is Counter-Clockwise (Nghich) ?
            // Ty (Menh) -> Suu (Phu Mau) -> Dan (Phuc Duc)... ?
            // Usually Palaces are fixed on Earthly Branches.
            // But the *assignment* of Palace Names depends on Month/Hour.
            // Menh -> Phu -> Phuc -> Dien -> Quan -> No -> Di -> Tat -> Tai -> Tu -> Phu -> Huynh.
            // This sequence is Counter-Clockwise (Nghich) relative to the chart?
            // Let's check TuViLapSo.
            // $pos = ($posMenh - $i) % 12;
            // $posMenh is determined. Then loop i=0..11. pos = Menh - i.
            // So if Menh is at 2 (Dan).
            // i=1 (Phu Mau) -> 2-1 = 1 (Suu).
            // i=2 (Phuc Duc) -> 2-2 = 0 (Ty).
            // So yes, Palace Names are assigned Counter-Clockwise.

            $realPos = ($newMenhPos - $i + 12) % 12;

            // Map keys
            $keyMap = array_flip(TuViLapSo::$DIA_CHI_KEYS);
            // We need to access via index, but $this->laso is keyed by 'ty_chuot', 'suu'... in funcs/tu-vi.php!
            // Wait, in funcs/tu-vi.php I did: $diaBanKeyed[$palace['key']] = $palace;
            // But here I'm passing $laSoData['dia_ban'] BEFORE re-keying?
            // In funcs/tu-vi.php:
            // $laSoData = TuViLapSo::lapLaSo(...)
            // $laSoData['dia_ban'] is indexed 0..11 initially in TuViLapSo.
            // But in funcs/tu-vi.php I added star meanings loop: foreach ($laSoData['dia_ban'] as &$palace)...
            // And THEN at the end: $diaBanKeyed = ...
            // So if I instantiate TuViAdvanced BEFORE re-keying, $this->laso is 0..11.
            // I will assume standard integer index.

            $cungGoc = $this->laso[$realPos];
            $desc = "Cung $tenCungMoi của Người thân là cung {$cungGoc['palace_name']} của Đương số.";

            // Extract Chinh Tinh names
            $saoList = [];
            foreach($cungGoc['chinh_tinh'] as $s) $saoList[] = $s['name'];

            $newLasoMapping[] = [
                'chuc_nang_moi' => $tenCungMoi,
                'cung_goc' => $cungGoc,
                'sao_chinh' => implode(', ', $saoList),
                'relation_desc' => $desc
            ];
        }

        return [
            'title' => $title,
            'mapping' => $newLasoMapping
        ];
    }

    // --- PHẦN 2: NHẬN DIỆN CÁCH CỤC (PATTERN RECOGNITION) ---
    public function detectCachCuc() {
        $patterns = [];
        // Lấy dữ liệu Cung Mệnh
        $menh = $this->laso[$this->menhPos];
        $idMenh = $menh['index']; // 0=Tý...

        $saoMenh = [];
        foreach($menh['chinh_tinh'] as $s) $saoMenh[] = strtoupper($s['code']);

        $phuTinh = [];
        if(isset($menh['phu_tinh_tot'])) foreach($menh['phu_tinh_tot'] as $s) $phuTinh[] = strtoupper($s['code']);
        if(isset($menh['phu_tinh_xau'])) foreach($menh['phu_tinh_xau'] as $s) $phuTinh[] = strtoupper($s['code']);

        // --- A. CÁCH CỤC TỐT (CÁT CÁCH) ---

        // 1. Tử Phủ Triều Viên
        if (in_array($idMenh, [2, 8]) && in_array('TU_VI', $saoMenh) && in_array('THIEN_PHU', $saoMenh)) {
            $patterns[] = [
                'name' => 'Tử Phủ Đồng Cung',
                'type' => 'good',
                'desc' => 'Đế tinh và Tài tinh hội tụ. Cả đời tài lộc dồi dào, quyền uy hiển hách, văn võ song toàn.'
            ];
        }

        // 2. Nguyệt Lãng Thiên Môn (Thái Âm tại Hợi)
        if ($idMenh == 11 && in_array('THAI_AM', $saoMenh)) {
            $patterns[] = [
                'name' => 'Nguyệt Lãng Thiên Môn',
                'type' => 'good',
                'desc' => 'Trăng sáng cửa trời. Cách cục đại phú quý, hào hoa phong nhã, làm quan chức lớn hoặc đại gia điền sản.'
            ];
        }

        // 3. Nhật Chiếu Lôi Môn (Thái Dương tại Mão)
        if ($idMenh == 3 && in_array('THAI_DUONG', $saoMenh)) {
            $patterns[] = [
                'name' => 'Nhật Chiếu Lôi Môn',
                'type' => 'good',
                'desc' => 'Mặt trời mọc ở cửa sấm (phương Đông). Công danh hiển hách, ban đầu rực rỡ, danh tiếng vang xa.'
            ];
        }

        // 4. Thạch Trung Ẩn Ngọc (Cự Môn tại Tý/Ngọ + Khoa/Lộc)
        if (($idMenh == 0 || $idMenh == 6) && in_array('CU_MON', $saoMenh)) {
            if (in_array('HOA_KHOA', $phuTinh) || in_array('HOA_LOC', $phuTinh)) {
                $patterns[] = [
                    'name' => 'Thạch Trung Ẩn Ngọc',
                    'type' => 'good',
                    'desc' => 'Ngọc ẩn trong đá. Tài năng ẩn giấu, sau này mới phát lộ rực rỡ. Nên khiêm tốn thì thành công bền vững.'
                ];
            }
        }

        // 5. Minh Châu Xuất Hải (Nhật ở Mão, Nguyệt ở Hợi chiếu Mệnh ở Mùi)
        if ($idMenh == 7) { // Mùi
            $cungMao = $this->laso[3];
            $cungHoi = $this->laso[11];

            $saoMao = []; foreach($cungMao['chinh_tinh'] as $s) $saoMao[] = strtoupper($s['code']);
            $saoHoi = []; foreach($cungHoi['chinh_tinh'] as $s) $saoHoi[] = strtoupper($s['code']);

            if (in_array('THAI_DUONG', $saoMao) && in_array('THAI_AM', $saoHoi)) {
                $patterns[] = [
                    'name' => 'Minh Châu Xuất Hải',
                    'type' => 'good',
                    'desc' => 'Viên ngọc sáng từ biển bay lên. Công danh lừng lẫy, thi cử đỗ đạt cao, được trọng dụng.'
                ];
            }
        }

        // --- B. CÁCH CỤC XẤU (HUNG CÁCH) ---

        // 1. Mã Đầu Đới Kiếm (Kình Dương ở Ngọ)
        if ($idMenh == 6 && in_array('KINH_DUONG', $phuTinh)) {
            $patterns[] = [
                'name' => 'Mã Đầu Đới Kiếm',
                'type' => 'bad',
                'desc' => 'Gươm treo cổ ngựa. Tính tình hung bạo, tai nạn xe cộ, thương tích, cuộc đời nhiều hiểm nguy rình rập.'
            ];
        }

        // 2. Mệnh Không Thân Kiếp
        $than = $this->laso[$this->thanPos];
        $phuTinhThan = [];
        if(isset($than['phu_tinh_tot'])) foreach($than['phu_tinh_tot'] as $s) $phuTinhThan[] = strtoupper($s['code']);
        if(isset($than['phu_tinh_xau'])) foreach($than['phu_tinh_xau'] as $s) $phuTinhThan[] = strtoupper($s['code']);

        $hasKhongMenh = in_array('DIA_KHONG', $phuTinh);
        $hasKiepThan = in_array('DIA_KIEP', $phuTinhThan);
        $hasKiepMenh = in_array('DIA_KIEP', $phuTinh);
        $hasKhongThan = in_array('DIA_KHONG', $phuTinhThan);

        if (($hasKhongMenh && $hasKiepThan) || ($hasKiepMenh && $hasKhongThan)) {
            $patterns[] = [
                'name' => 'Mệnh Không Thân Kiếp',
                'type' => 'bad',
                'desc' => 'Tác sự hư không. Người khôn ngoan nhưng số phận long đong, làm nhiều hưởng ít, tiền bạc tụ tán thất thường, đời phiêu bạt.'
            ];
        }

        // 3. Linh Xương Đà Vũ
        $allStars = array_merge($saoMenh, $phuTinh);
        $count = 0;
        if (in_array('VU_KHUC', $allStars)) $count++;
        if (in_array('LINH_TINH', $allStars)) $count++;
        if (in_array('VAN_XUONG', $allStars)) $count++;
        if (in_array('DA_LA', $allStars)) $count++;

        if ($count >= 3) {
            $patterns[] = [
                'name' => 'Linh Xương Đà Vũ',
                'type' => 'bad',
                'desc' => 'Hạn chí đầu hà. Cách cục rất xấu, dễ gặp tai nạn sông nước, tâm lý u uất, đề phòng nghĩ quẩn.'
            ];
        }

        return $patterns;
    }

    // Helper: Tìm ID cung theo tên
    private function findCungID($tenCungChuc) {
        foreach ($this->laso as $cung) {
            // Need to clean up palace name (remove "(Thân)")
            $name = $cung['palace_name'];
            if (strpos($name, $tenCungChuc) !== false) {
                // simple check, assumes distinct names
                // "Phu The" vs "Phu Mau".
                // "Phu The" contains "Phu". "Phu Mau" contains "Phu".
                // Exact match or substring unique check.
                // "Phụ Mẫu", "Phu Thê".
                // "Tử Tức", "Huynh Đệ".

                // If $tenCungChuc is "Phụ Mẫu", strpos("Phụ Mẫu (Thân)", "Phụ Mẫu") is true.
                // If $tenCungChuc is "Phu Thê", strpos("Phu Thê", "Phu Thê") is true.
                return $cung['index'];
            }
        }
        return 0;
    }
}
