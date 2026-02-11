<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViAdvanced {
    private $laso;      // Dữ liệu 12 cung gốc (Dia Ban)
    private $menhPos;   // Vị trí cung Mệnh gốc (Index 0-11)
    private $thanPos;   // Vị trí cung Thân gốc (Index 0-11)

    // Danh sách 12 cung chức theo thứ tự Nghịch chiều kim đồng hồ (Quy tắc an cung)
    const CUNG_CHUC_ORDER = [
        'Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc',
        'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'
    ];

    public function __construct($calculatorData) {
        // Adapt input format: calculatorData should have ['dia_ban', 'meta' => ['menh_idx', 'than_idx']]
        if (isset($calculatorData['dia_ban'])) {
            $this->laso = $calculatorData['dia_ban'];
            $this->menhPos = isset($calculatorData['meta']['menh_idx']) ? $calculatorData['meta']['menh_idx'] : 0;
            $this->thanPos = isset($calculatorData['meta']['than_idx']) ? $calculatorData['meta']['than_idx'] : 0;
        } else {
            // Fallback direct assignment if already processed
            $this->laso = isset($calculatorData['laso']) ? $calculatorData['laso'] : [];
            $this->menhPos = isset($calculatorData['menh_pos']) ? $calculatorData['menh_pos'] : 0;
            $this->thanPos = isset($calculatorData['than_pos']) ? $calculatorData['than_pos'] : 0;
        }
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
                // Cung Phu Thê gốc làm Mệnh mới
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
        // Nguyên tắc: Cung Mới đi Nghịch chiều (giảm index) để an Phụ, Phúc, Điền...
        // Tuy nhiên trong mảng $laso index 0..11 là Tý..Hợi cố định.
        // Ta cần map lại: Cung chức Mới -> Cung thực tế.

        $newLasoMapping = [];

        // Duyệt qua 12 cung chức tiêu chuẩn
        foreach (self::CUNG_CHUC_ORDER as $i => $tenCungMoi) {
            // Tính vị trí cung thực tế tương ứng
            // Mệnh mới tại $newMenhPos.
            // Cung chức an Nghịch: Mệnh(0), Phụ(-1), Phúc(-2)...
            // Công thức: (PosMoi - i + 12) % 12
            $realPos = ($newMenhPos - $i + 12) % 12;

            // Lấy dữ liệu cung gốc
            $cungGoc = $this->laso[$realPos];

            // Luận giải sơ bộ sự chuyển đổi (Phi tinh)
            // Ví dụ: Cung Tài của Vợ là cung Phúc của mình.
            $desc = "Cung $tenCungMoi của Người thân là cung {$cungGoc['palace_name']} của Đương số.";

            $newLasoMapping[] = [
                'chuc_nang_moi' => $tenCungMoi,
                'cung_goc' => $cungGoc, // Chứa toàn bộ sao
                'relation_desc' => $desc,
                'real_pos' => $realPos
            ];
        }

        return [
            'title' => $title,
            'mapping' => $newLasoMapping,
            'new_menh_pos' => $newMenhPos
        ];
    }

    // --- PHẦN 2: NHẬN DIỆN CÁCH CỤC (PATTERN RECOGNITION) ---

    public function detectCachCuc() {
        $patterns = [];

        // Lấy dữ liệu Cung Mệnh
        $menh = $this->laso[$this->menhPos];
        $idMenh = $menh['index']; // 0=Tý...

        // Helper to check star presence (lowercase keys)
        $hasStar = function($palace, $starCode) {
            $allStars = array_merge($palace['chinh_tinh'], $palace['phu_tinh_tot'], $palace['phu_tinh_xau']);
            // Also check borrowed stars if VCD?
            if (empty($palace['chinh_tinh']) && !empty($palace['chinh_tinh_borrowed'])) {
                $allStars = array_merge($allStars, $palace['chinh_tinh_borrowed']);
            }

            foreach ($allStars as $s) {
                if ($s['code'] == $starCode) return true;
            }
            return false;
        };

        // --- A. CÁCH CỤC TỐT (CÁT CÁCH) ---

        // 1. Tử Phủ Triều Viên (Tử Vi, Thiên Phủ ở Mệnh hoặc Tam hợp chiếu về Mệnh)
        // Điều kiện: Mệnh có Tử Vi, Phủ ở Tam hợp. Hoặc Mệnh Phủ, Tử ở Tam hợp.
        // Đơn giản hóa: Tử Vi và Thiên Phủ cùng hội chiếu vào Mệnh (không tính xung chiếu).
        // Cách kiểm tra nhanh: Mệnh tại Dần hoặc Thân. Tử Phủ đồng cung.
        // Hoặc Mệnh tại Tý/Ngọ/Thìn/Tuất có Tử Vi Thiên Phủ hội họp.
        // Simplified check: Tu Vi & Thien Phu both influence Menh (Direct or Tri-unity).
        // Let's check direct presence first.

        $tuViPresent = $hasStar($menh, 'tu_vi');
        $thienPhuPresent = $hasStar($menh, 'thien_phu');

        // Check Tri-unity (Tam Hop)
        $tamHop1 = $this->laso[($idMenh + 4) % 12];
        $tamHop2 = $this->laso[($idMenh + 8) % 12];

        if (!$tuViPresent) $tuViPresent = ($hasStar($tamHop1, 'tu_vi') || $hasStar($tamHop2, 'tu_vi'));
        if (!$thienPhuPresent) $thienPhuPresent = ($hasStar($tamHop1, 'thien_phu') || $hasStar($tamHop2, 'thien_phu'));

        if ($tuViPresent && $thienPhuPresent) {
            $patterns[] = [
                'name' => 'Tử Phủ Triều Viên',
                'type' => 'good',
                'content' => 'Đế tinh và Tài tinh hội tụ. Cả đời tài lộc dồi dào, quyền uy hiển hách, văn võ song toàn.'
            ];
        }

        // 2. Nguyệt Lãng Thiên Môn (Thái Âm tại Hợi)
        if ($idMenh == 11 && $hasStar($menh, 'thai_am')) { // 11 = Hợi
            $patterns[] = [
                'name' => 'Nguyệt Lãng Thiên Môn',
                'type' => 'good',
                'content' => 'Trăng sáng cửa trời. Cách cục đại phú quý, hào hoa phong nhã, làm quan chức lớn hoặc đại gia điền sản.'
            ];
        }

        // 3. Nhật Chiếu Lôi Môn (Thái Dương tại Mão)
        if ($idMenh == 3 && $hasStar($menh, 'thai_duong')) { // 3 = Mão
            $patterns[] = [
                'name' => 'Nhật Chiếu Lôi Môn',
                'type' => 'good',
                'content' => 'Mặt trời mọc ở cửa sấm (phương Đông). Công danh hiển hách, ban đầu rực rỡ, danh tiếng vang xa.'
            ];
        }

        // 4. Thạch Trung Ẩn Ngọc (Cự Môn tại Tý/Ngọ + Khoa/Lộc)
        if (($idMenh == 0 || $idMenh == 6) && $hasStar($menh, 'cu_mon')) {
            // Check thêm Hóa Khoa hoặc Hóa Lộc
            if ($hasStar($menh, 'hoa_khoa') || $hasStar($menh, 'hoa_loc') || $hasStar($menh, 'loc_ton')) {
                $patterns[] = [
                    'name' => 'Thạch Trung Ẩn Ngọc',
                    'type' => 'good',
                    'content' => 'Ngọc ẩn trong đá. Tài năng ẩn giấu, sau này mới phát lộ rực rỡ. Nên khiêm tốn thì thành công bền vững.'
                ];
            }
        }

        // 5. Minh Châu Xuất Hải (Nhật ở Mão, Nguyệt ở Hợi chiếu Mệnh ở Mùi)
        if ($idMenh == 7) { // Mùi
            // Check Mão có Nhật, Hợi có Nguyệt
            $cungMao = $this->laso[3];
            $cungHoi = $this->laso[11];
            if ($hasStar($cungMao, 'thai_duong') && $hasStar($cungHoi, 'thai_am')) {
                 $patterns[] = [
                    'name' => 'Minh Châu Xuất Hải',
                    'type' => 'good',
                    'content' => 'Viên ngọc sáng từ biển bay lên. Công danh lừng lẫy, thi cử đỗ đạt cao, được trọng dụng.'
                ];
            }
        }

        // --- B. CÁCH CỤC XẤU (HUNG CÁCH) ---

        // 1. Mã Đầu Đới Kiếm (Kình Dương ở Ngọ)
        if ($idMenh == 6 && $hasStar($menh, 'kinh_duong')) { // 6 = Ngọ
            // Nếu có thêm hàng Can Bính Mậu thì lại thành cách tốt (trấn ngữ biên cương)
            // Ở đây xét thuần túy hình tượng
            $patterns[] = [
                'name' => 'Mã Đầu Đới Kiếm',
                'type' => 'bad',
                'content' => 'Gươm treo cổ ngựa. Tính tình hung bạo, tai nạn xe cộ, thương tích, cuộc đời nhiều hiểm nguy rình rập.'
            ];
        }

        // 2. Mệnh Không Thân Kiếp (Mệnh Địa Không, Thân Địa Kiếp - hoặc ngược lại)
        $than = $this->laso[$this->thanPos];
        $hasKhongMenh = $hasStar($menh, 'dia_khong');
        $hasKiepThan = $hasStar($than, 'dia_kiep');
        // Hoặc ngược lại
        $hasKiepMenh = $hasStar($menh, 'dia_kiep');
        $hasKhongThan = $hasStar($than, 'dia_khong');

        if (($hasKhongMenh && $hasKiepThan) || ($hasKiepMenh && $hasKhongThan)) {
            $patterns[] = [
                'name' => 'Mệnh Không Thân Kiếp',
                'type' => 'bad',
                'content' => 'Tác sự hư không. Người khôn ngoan nhưng số phận long đong, làm nhiều hưởng ít, tiền bạc tụ tán thất thường, đời phiêu bạt.'
            ];
        }

        // 3. Linh Xương Đà Vũ (Linh Tinh + Văn Xương + Đà La + Vũ Khúc)
        // Cách cục rất xấu chủ về tai nạn sông nước hoặc tự vẫn.
        // Check 4 sao này có hội họp tại Mệnh không (Tam hop + Xung chieu).

        $starsToCheck = ['vu_khuc', 'linh_tinh', 'van_xuong', 'da_la'];
        $foundCount = 0;

        // Collect all stars influencing Menh
        $influencingPalaces = [$menh, $tamHop1, $tamHop2, $this->laso[($idMenh + 6) % 12]];
        $allCodes = [];
        foreach ($influencingPalaces as $p) {
             $stars = array_merge($p['chinh_tinh'], $p['phu_tinh_tot'], $p['phu_tinh_xau']);
             foreach ($stars as $s) $allCodes[] = $s['code'];
        }
        $allCodes = array_unique($allCodes);

        foreach ($starsToCheck as $code) {
            if (in_array($code, $allCodes)) $foundCount++;
        }

        if ($foundCount >= 3) { // Hội tụ 3/4 là đáng lo
             $patterns[] = [
                'name' => 'Linh Xương Đà Vũ',
                'type' => 'bad',
                'content' => 'Hạn chí đầu hà. Cách cục rất xấu, dễ gặp tai nạn sông nước, tâm lý u uất, đề phòng nghĩ quẩn.'
            ];
        }

        return $patterns;
    }

    // Helper: Tìm ID cung theo tên (VD: 'Phu Thê' -> 10)
    private function findCungID($tenCungChuc) {
        foreach ($this->laso as $cung) {
            // Normalize name check
            if (mb_strpos($cung['palace_name'], $tenCungChuc) !== false) return $cung['index'];
        }
        return 0;
    }
}
