<?php
/**
 * Class TuViVanHan
 * Chức năng:
 * 1. Xác định cung Đại Hạn, Tiểu Hạn, Lưu Niên.
 * 2. An các Sao Lưu (Lưu Thái Tuế, Lưu Lộc Tồn, Lưu Kình/Đà, Lưu Tang/Hổ...).
 * 3. Bình giải chi tiết vận hạn năm xem.
 */

namespace NukeViet\Module\HuyenHoc;

// Assuming TuViConstants is available or we will define it.
// To avoid errors, I will define getName here if not exists, or handle it.
// The user provided code uses `require_once 'TuViConstants.php';`.
// I will adjust to use namespaced logic or check for the file.

class TuViVanHan {
    protected $lasoGoc;      // Lá số gốc (để lấy sao cố định)
    protected $userInfo;     // Thông tin người dùng
    protected $yearXem;      // Năm muốn xem hạn
    protected $canNamXem;    // Can của năm xem (0-9)
    protected $chiNamXem;    // Chi của năm xem (0-11)

    // Vị trí các cung hạn
    public $posDaiHan;       // Vị trí cung Đại Hạn
    public $posTieuHan;      // Vị trí cung Tiểu Hạn
    public $posLuuThaiTue;   // Vị trí cung Lưu Thái Tuế

    // Danh sách sao lưu đã an
    public $saoLuu = [];

    /**
     * @param TuViCalculator|TuViLapSo $calculator Đối tượng lá số gốc
     * @param int $yearXem Năm dương lịch muốn xem (VD: 2026)
     */
    public function __construct($calculator, $yearXem) {
        // Adapt to either TuViCalculator or TuViLapSo structure
        // TuViLapSo has static methods but doesn't store state like $lasoGoc unless instantiated or result passed.
        // User code expects $calculator->laso and $calculator->input.

        if (is_array($calculator)) {
            // If array passed (result of TuViLapSo::lapLaSo)
            $this->lasoGoc = $calculator['dia_ban'];
            $this->userInfo = [
                'year' => $calculator['meta']['birth_year'],
                'gender' => $calculator['meta']['gender'],
                'chi_year' => $calculator['meta']['chiYear']
            ];
        } else {
            // Object
            $this->lasoGoc = $calculator->laso;
            $this->userInfo = $calculator->input;
        }

        $this->yearXem = $yearXem;

        // Tính Can Chi năm xem
        // 4 = Giáp, 4 = Tý (Mốc 1984 Giáp Tý)
        $this->canNamXem = ($yearXem - 4) % 10;
        if($this->canNamXem < 0) $this->canNamXem += 10;

        $this->chiNamXem = ($yearXem - 4) % 12;
        if($this->chiNamXem < 0) $this->chiNamXem += 12;

        $this->khoiTaoHan();
        $this->anSaoLuu();
    }

    // --- PHẦN 1: TÍNH VỊ TRÍ CUNG HẠN ---

    private function khoiTaoHan() {
        $tuoiAm = $this->yearXem - $this->userInfo['year'] + 1;
        $gioiTinh = $this->userInfo['gender']; // 1=Nam, 0=Nữ

        // 1. Tìm Đại Hạn (10 năm)
        // Duyệt qua 12 cung, tìm cung có phạm vi tuổi chứa tuổi hiện tại
        foreach ($this->lasoGoc as $cung) {
            $startAge = $cung['dai_van']; // Adjusted key from 'dai_han' to 'dai_van' based on TuViLapSo
            if ($tuoiAm >= $startAge && $tuoiAm < ($startAge + 10)) {
                $this->posDaiHan = $cung['index']; // Adjusted 'id' to 'index'
                break;
            }
        }

        // 2. Tìm Tiểu Hạn (1 năm)
        // Quy tắc:
        // Dần Ngọ Tuất khởi Thìn. Thân Tý Thìn khởi Tuất.
        // Tỵ Dậu Sửu khởi Mùi. Hợi Mão Mùi khởi Sửu.
        // Nam thuận, Nữ nghịch.
        $chiNamSinh = $this->userInfo['chi_year'];
        $khoiTieuHan = 0;

        // Nhóm Tam Hợp -> Cung Khởi
        if (in_array($chiNamSinh, [2, 6, 10])) $khoiTieuHan = 4; // Dần Ngọ Tuất -> Thìn
        elseif (in_array($chiNamSinh, [8, 0, 4])) $khoiTieuHan = 10; // Thân Tý Thìn -> Tuất
        elseif (in_array($chiNamSinh, [5, 9, 1])) $khoiTieuHan = 7; // Tỵ Dậu Sửu -> Mùi
        else $khoiTieuHan = 1; // Hợi Mão Mùi -> Sửu (1)

        // Tính vị trí: Từ cung Khởi tính là tuổi gốc, Nam thuận Nữ nghịch đến tuổi hiện tại
        // Tuy nhiên công thức phổ biến: Cung khởi là 1 tuổi? Hay Cung khởi là tuổi sinh?
        // Công thức chuẩn: Cung Khởi tính theo Chi Năm sinh.
        // Ví dụ Nam Thân Tý Thìn, Khởi Tuất. Năm Tý thì tiểu hạn ở đâu?
        // Cách tính đơn giản hơn: Dùng Lịch Vạn Sự tra bảng hoặc công thức xung chiếu.
        // Ở đây dùng công thức: (Cung Khởi + (Tuổi - 1) * chiều) % 12
        $dir = ($gioiTinh == 1) ? 1 : -1;
        $this->posTieuHan = $this->mod($khoiTieuHan + ($tuoiAm - 1) * $dir);

        // 3. Tìm Lưu Thái Tuế (Cung có chi trùng chi năm xem)
        $this->posLuuThaiTue = $this->chiNamXem;
    }

    // --- PHẦN 2: AN SAO LƯU (ĐỘNG) ---

    private function anSaoLuu() {
        // 1. Lưu Thái Tuế (Tại cung năm xem)
        $this->addSaoLuu($this->posLuuThaiTue, 'L_THAI_TUE');

        // 2. Lưu Lộc Tồn (Theo Can năm xem)
        // Giáp->Dần(2), Ất->Mão(3), Bính/Mậu->Tỵ(5), Đinh/Kỷ->Ngọ(6), Canh->Thân(8), Tân->Dậu(9), Nhâm->Hợi(11), Quý->Tý(0)
        $mapLoc = [2, 3, 5, 6, 5, 6, 8, 9, 11, 0];
        $posLoc = $mapLoc[$this->canNamXem];
        $this->addSaoLuu($posLoc, 'L_LOC_TON');

        // 3. Lưu Kình Dương (Trước Lộc), Lưu Đà La (Sau Lộc)
        $this->addSaoLuu($posLoc + 1, 'L_KINH_DUONG');
        $this->addSaoLuu($posLoc - 1, 'L_DA_LA');

        // 4. Lưu Thiên Mã (Theo Tam Hợp Chi năm xem)
        // Dần Ngọ Tuất->Thân(8), Thân Tý Thìn->Dần(2), Tỵ Dậu Sửu->Hợi(11), Hợi Mão Mùi->Tỵ(5)
        $chi = $this->chiNamXem;
        if (in_array($chi, [2,6,10])) $posMa = 8;
        elseif (in_array($chi, [8,0,4])) $posMa = 2;
        elseif (in_array($chi, [5,9,1])) $posMa = 11;
        else $posMa = 5;
        $this->addSaoLuu($posMa, 'L_THIEN_MA');

        // 5. Lưu Bạch Hổ, Lưu Tang Môn
        // Khởi theo Thái Tuế. Thái Tuế ở đâu, Tang Môn cách đó +2, Bạch Hổ cách đó +8 (Đối Tang Môn)
        // Quy tắc vòng Thái Tuế: Tuế(1), ... Tang(3) ... Hổ(9)
        $posTang = $this->posLuuThaiTue + 2;
        $posHo = $this->posLuuThaiTue + 8;
        $this->addSaoLuu($posTang, 'L_TANG_MON');
        $this->addSaoLuu($posHo, 'L_BACH_HO');

        // 6. Lưu Khốc, Lưu Hư
        // Khốc: Ngọ nghịch đến năm xem. Hư: Ngọ thuận đến năm xem.
        // Ngọ = 6.
        $this->addSaoLuu(6 - $chi, 'L_THIEN_KHOC');
        $this->addSaoLuu(6 + $chi, 'L_THIEN_HU');
    }

    // --- PHẦN 3: LUẬN GIẢI VẬN HẠN ---

    public function luanGiaiChiTiet() {
        $namXemText = $this->getCanChiName($this->canNamXem, $this->chiNamXem);
        $tuoiAm = $this->yearXem - $this->userInfo['year'] + 1;

        $html = "<div class='van-han-report'>";
        $html .= "<h3>Dự Báo Vận Hạn Năm $namXemText ($this->yearXem)</h3>";
        $html .= "<p>Tuổi Âm: <strong>$tuoiAm tuổi</strong></p>";

        // 1. Luận Đại Hạn (Gốc 10 năm)
        if (isset($this->posDaiHan) && isset($this->lasoGoc[$this->posDaiHan])) {
            $cungDaiHan = $this->lasoGoc[$this->posDaiHan];
            $html .= "<div class='han-box dai-han'>";
            $html .= "<h4>1. Đại Hạn 10 năm (Tại cung {$cungDaiHan['name']} - {$cungDaiHan['palace_name']})</h4>";
            $html .= $this->phanTichCung($cungDaiHan, 'DAI_HAN');
            $html .= "</div>";
        } else {
             $html .= "<div class='han-box dai-han'>";
             $html .= "<h4>1. Đại Hạn 10 năm</h4>";
             $html .= "<p>Đương số chưa nhập đại hạn (Tuổi nhỏ hơn Cục số).</p>";
             $html .= "</div>";
        }

        // 2. Luận Tiểu Hạn (Gốc 1 năm)
        $cungTieuHan = $this->lasoGoc[$this->posTieuHan];
        $html .= "<div class='han-box tieu-han'>";
        $html .= "<h4>2. Tiểu Hạn 1 năm (Tại cung {$cungTieuHan['name']} - {$cungTieuHan['palace_name']})</h4>";
        $html .= $this->phanTichCung($cungTieuHan, 'TIEU_HAN');
        $html .= "</div>";

        // 3. Luận Lưu Niên (Biến động thực tế)
        $cungLuu = $this->lasoGoc[$this->posLuuThaiTue];
        $html .= "<div class='han-box luu-nien'>";
        $html .= "<h4>3. Vận Khí Lưu Niên (Cung {$cungLuu['name']} - Chứa Lưu Thái Tuế)</h4>";

        // Phân tích Sao Lưu (Quan trọng nhất của xem hạn)
        // $saoLuuTaiCung = $this->getSaoLuuTaiCung($this->posLuuThaiTue); // Lưu Thái Tuế
        // $saoLuuMenh = $this->getSaoLuuTaiCung($this->lasoGoc[0]['index']); // Xem Lưu sao chiếu vào Mệnh gốc

        $html .= $this->luanSaoLuu($this->saoLuu); // Luận toàn bộ hệ thống sao lưu
        $html .= "</div>";

        // 4. Tổng kết Cát Hung
        $html .= $this->tongKetHan();

        $html .= "</div>";
        return $html;
    }

    // --- LOGIC PHÂN TÍCH CHI TIẾT ---

    private function phanTichCung($cung, $type) {
        $msg = "";
        // Check chính tinh
        if (empty($cung['chinh_tinh'])) $msg .= "<p>- Cung Vô Chính Diệu, hạn dễ bị tác động bởi môi trường bên ngoài, thiếu chủ kiến.</p>";

        // Flatten phu_tinh arrays for easier check
        $phuTinhCodes = [];
        if (isset($cung['phu_tinh_tot'])) foreach ($cung['phu_tinh_tot'] as $s) $phuTinhCodes[] = strtoupper($s['code']);
        if (isset($cung['phu_tinh_xau'])) foreach ($cung['phu_tinh_xau'] as $s) $phuTinhCodes[] = strtoupper($s['code']);

        // Check Sát tinh cố định
        $satTinhList = ['DIA_KHONG', 'DIA_KIEP', 'KINH_DUONG', 'DA_LA', 'HOA_TINH', 'LINH_TINH'];
        $satTinh = array_intersect($phuTinhCodes, $satTinhList);
        if (!empty($satTinh)) {
            $msg .= "<p class='bad'>- Gặp Sát tinh (" . implode(', ', $satTinh) . "): Đề phòng trở ngại, hao tài hoặc sức khỏe kém trong giai đoạn này.</p>";
        }

        // Check Cát tinh
        $catTinhList = ['HOA_LOC', 'HOA_QUYEN', 'HOA_KHOA', 'LOC_TON', 'THIEN_KHOI', 'THIEN_VIET'];
        $catTinh = array_intersect($phuTinhCodes, $catTinhList);
        if (!empty($catTinh)) {
            $msg .= "<p class='good'>- Hội Cát tinh (" . implode(', ', $catTinh) . "): Có cơ hội thăng tiến, quý nhân phù trợ.</p>";
        }

        // Tuần Triệt
        if ($cung['tuan'] || $cung['triet']) {
            $msg .= "<p>- Gặp Tuần/Triệt: Mọi việc khởi đầu nan, hoặc có sự thay đổi lớn, không nên giữ lề lối cũ.</p>";
        }

        return $msg ? $msg : "<p>- Vận hạn bình hòa.</p>";
    }

    private function luanSaoLuu($listSaoLuu) {
        $msg = "<ul>";

        foreach ($listSaoLuu as $item) {
            $cung = $this->lasoGoc[$item['pos']];
            $tenCung = $cung['palace_name']; // Adjusted key

            switch ($item['code']) {
                case 'L_LOC_TON':
                    $msg .= "<li><strong>Lưu Lộc Tồn tại $tenCung:</strong> Năm nay có lộc, cơ hội kiếm tiền tại cung $tenCung (VD: Điền trạch là mua bán đất, Quan lộc là tăng lương).</li>";
                    break;
                case 'L_THIEN_MA':
                    $msg .= "<li><strong>Lưu Thiên Mã tại $tenCung:</strong> Có sự dịch chuyển, đi lại nhiều, thay đổi liên quan đến $tenCung.</li>";
                    if ($cung['tuan'] || $cung['triet']) $msg .= " (Chú ý: Mã gặp Tuần Triệt là Mã què, đi lại cẩn thận xe cộ).";
                    break;
                case 'L_THAI_TUE':
                    $msg .= "<li><strong>Lưu Thái Tuế tại $tenCung:</strong> Sự chú ý dồn vào vấn đề của $tenCung. Dễ có thị phi hoặc tranh chấp lý lẽ tại đây.</li>";
                    break;
                case 'L_BACH_HO':
                case 'L_TANG_MON':
                    $msg .= "<li><strong>Lưu Tang/Hổ tại $tenCung:</strong> Lo lắng, buồn phiền hoặc vấn đề sức khỏe liên quan đến $tenCung.</li>";
                    break;
                case 'L_KINH_DUONG':
                case 'L_DA_LA':
                    $msg .= "<li><strong>Lưu Kình/Đà tại $tenCung:</strong> Đề phòng tiểu nhân, cản trở, trì trệ công việc của $tenCung.</li>";
                    break;
                case 'L_THIEN_KHOC':
                case 'L_THIEN_HU':
                    $msg .= "<li><strong>Lưu Khốc/Hư tại $tenCung:</strong> Chủ về chuyện buồn phiền, nước mắt, hoặc hư hại nhỏ tại $tenCung.</li>";
                    break;
            }
        }
        $msg .= "</ul>";
        return $msg;
    }

    private function tongKetHan() {
        // Logic Tam Tai
        $tamTai = false;
        $chiSinh = $this->userInfo['chi_year'];
        $chiNam = $this->chiNamXem;

        // Thân Tý Thìn (8,0,4) bị Dần Mão Thìn (2,3,4)
        if (in_array($chiSinh, [0,4,8]) && in_array($chiNam, [2,3,4])) $tamTai = true;

        // Dần Ngọ Tuất (2,6,10) bị Thân Dậu Tuất (8,9,10)
        if (in_array($chiSinh, [2,6,10]) && in_array($chiNam, [8,9,10])) $tamTai = true;

        // Tỵ Dậu Sửu (5,9,1) bị Hợi Tý Sửu (11,0,1)
        if (in_array($chiSinh, [5,9,1]) && in_array($chiNam, [11,0,1])) $tamTai = true;

        // Hợi Mão Mùi (11,3,7) bị Tỵ Ngọ Mùi (5,6,7)
        if (in_array($chiSinh, [11,3,7]) && in_array($chiNam, [5,6,7])) $tamTai = true;

        $msg = "<div class='summary-box'>";
        if ($tamTai) $msg .= "<p class='warn'>⚠ Năm nay phạm <strong>Tam Tai</strong>. Nên cẩn trọng đầu tư lớn, hạn chế đi sông nước.</p>";

        // Kim Lâu (Tuổi chia 9 dư 1,3,6,8)
        $tuoi = $this->yearXem - $this->userInfo['year'] + 1;
        $du = $tuoi % 9;
        if (in_array($du, [1, 3, 6, 8])) {
            $msg .= "<p class='warn'>⚠ Năm nay phạm <strong>Kim Lâu</strong>. Kỵ xây nhà, cưới hỏi (với nữ).</p>";
        }

        $msg .= "</div>";
        return $msg;
    }

    // --- HELPER FUNCTIONS ---

    private function addSaoLuu($pos, $code) {
        $pos = $this->mod($pos);
        $this->saoLuu[] = ['pos' => $pos, 'code' => $code];
    }

    private function getSaoLuuTaiCung($pos) {
        $res = [];
        foreach ($this->saoLuu as $s) {
            if ($s['pos'] == $pos) $res[] = $s['code'];
        }
        return $res;
    }

    private function mod($val, $n = 12) {
        return (($val % $n) + $n) % $n;
    }

    private function getCanChiName($can, $chi) {
        $CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
        $CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];
        return $CAN[$can] . ' ' . $CHI[$chi];
    }
}
