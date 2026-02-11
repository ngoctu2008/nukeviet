<?php
/**
 * Class LichVanNien
 * Chức năng: Chuyển đổi Dương - Âm, Tính Can Chi, Tiết Khí, Hoàng Đạo.
 * Thuật toán: Dựa trên phương pháp tính của Hồ Ngọc Đức.
 */

namespace NukeViet\Module\HuyenHoc;

// Use core LunarCalendar logic for conversions
use NukeViet\Module\HuyenHoc\LunarCalendar;

class LichVanNien {

    // --- HẰNG SỐ DỮ LIỆU ---
    const CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    const CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

    // 24 Tiết Khí (Placeholder mapping - Real logic in LunarCalendar if available, or simple map here)
    const TIET_KHI = [
        'Tiểu hàn', 'Đại hàn', 'Lập xuân', 'Vũ thủy', 'Kinh trập', 'Xuân phân',
        'Thanh minh', 'Cốc vũ', 'Lập hạ', 'Tiểu mãn', 'Mang chủng', 'Hạ chí',
        'Tiểu thử', 'Đại thử', 'Lập thu', 'Xử thử', 'Bạch lộ', 'Thu phân',
        'Hàn lộ', 'Sương giáng', 'Lập đông', 'Tiểu tuyết', 'Đại tuyết', 'Đông chí'
    ];

    public function __construct() {}

    /**
     * Hàm chính: Lấy đầy đủ thông tin Lịch Vạn Niên
     * @param int $d Ngày dương
     * @param int $m Tháng dương
     * @param int $y Năm dương
     * @param int $gio Giờ xem (0-23)
     */
    public function getInfo($d, $m, $y, $gio = 12) {
        // 1. Chuyển đổi sang Âm Lịch
        $lunar = $this->convertSolar2Lunar($d, $m, $y);

        // 2. Tính JDN (Julian Day Number) để tính Can Chi Ngày chuẩn xác
        $jdn = $this->getJDN($d, $m, $y);

        // 3. Tính Can Chi
        $canChiNam = $this->getCanChiNam($lunar['year']);
        $canChiThang = $this->getCanChiThang($lunar['year'], $lunar['month']);
        $canChiNgay = $this->getCanChiNgay($jdn);
        $canChiGio = $this->getCanChiGio($jdn, $gio);

        // 4. Tiết khí & Hoàng đạo
        // Use LunarCalendar for Tiet Khi if available, otherwise fallback
        if (class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
            $tietKhi = LunarCalendar::getTietKhi($d, $m, $y);
        } else {
            $tietKhi = $this->getTietKhi($d, $m, $y);
        }

        $hoangDao = $this->checkNgayHoangDao($lunar['month'], $this->getChiNgayID($jdn));

        return [
            'duong_lich' => sprintf('%02d/%02d/%04d', $d, $m, $y),
            'am_lich' => [
                'day' => $lunar['day'],
                'month' => $lunar['month'],
                'year' => $lunar['year'],
                'leap' => $lunar['leap'], // Nhuận hay không
                'text' => $lunar['day'] . "/" . $lunar['month'] . ($lunar['leap'] ? " (Nhuận)" : "") . "/" . $lunar['year']
            ],
            'can_chi' => [
                'nam' => $canChiNam,
                'thang' => $canChiThang,
                'ngay' => $canChiNgay,
                'gio' => $canChiGio
            ],
            'ids' => [ // Trả về ID để các class khác dùng (0=Giáp/Tý...)
                'can_nam' => ($lunar['year'] + 6) % 10,
                'chi_nam' => ($lunar['year'] + 8) % 12,
                'can_ngay' => ($jdn + 9) % 10,
                'chi_ngay' => ($jdn + 1) % 12
            ],
            'tiet_khi' => $tietKhi,
            'ngay_hoang_dao' => $hoangDao
        ];
    }

    // --- 1. CHUYỂN ĐỔI DƯƠNG -> ÂM (Using Core Logic) ---

    private function convertSolar2Lunar($dd, $mm, $yy) {
        if (class_exists('\\NukeViet\\Module\\HuyenHoc\\LunarCalendar')) {
            return LunarCalendar::convertSolar2Lunar($dd, $mm, $yy, 7.0);
        }

        // Fallback placeholder logic (Should not happen if LunarCalendar exists)
        $lunarDay = $dd - 1;
        $lunarMonth = $mm;
        $lunarYear = $yy;
        if ($lunarDay <= 0) {
            $lunarMonth--;
            if ($lunarMonth <= 0) { $lunarMonth = 12; $lunarYear--; }
            $lunarDay += 29;
        }
        return ['day' => $lunarDay, 'month' => $lunarMonth, 'year' => $lunarYear, 'leap' => 0];
    }

    // --- 2. TÍNH CAN CHI (LOGIC CHUẨN JDN) ---

    // Tính Julian Day Number (Ngày Julius) - Cốt lõi của lịch pháp
    private function getJDN($d, $m, $y) {
        if ($m < 3) {
            $m += 12;
            $y -= 1;
        }
        $a = floor($y / 100);
        $b = 2 - $a + floor($a / 4);
        $jdn = floor(365.25 * ($y + 4716)) + floor(30.6001 * ($m + 1)) + $d + $b - 1524.5;
        return (int)$jdn;
    }

    private function getCanChiNam($year) {
        // Can: 4=Giáp, 5=Ất... -> (Year+6)%10
        $can = self::CAN[($year + 6) % 10];
        // Chi: 4=Tý, 5=Sửu... -> (Year+8)%12
        $chi = self::CHI[($year + 8) % 12];
        return "$can $chi";
    }

    private function getCanChiThang($year, $month) {
        // Công thức tìm Can tháng: (CanNăm * 2 + 1) = Can tháng 1 (Dần)
        // Can Năm ID: 0=Giáp (thực ra index mảng là 0=Giáp, nhưng công thức toán học cần map)
        // Map chuẩn: Giáp(0), Kỷ(5) -> Bính(2). Ất(1), Canh(6) -> Mậu(4).
        $canNamID = ($year + 6) % 10; // 0=Giáp

        // Tìm can tháng Giêng (Tháng 1 - Dần)
        // Giáp/Kỷ khởi Bính(2). Ất/Canh khởi Mậu(4). Bính/Tân khởi Canh(6). Đinh/Nhâm khởi Nhâm(8). Mậu/Quý khởi Giáp(0).
        $startCan = ($canNamID % 5) * 2 + 2;
        if ($startCan >= 10) $startCan -= 10;

        $canThangID = ($startCan + ($month - 1)) % 10;

        // Chi tháng: Tháng 1 luôn là Dần (2), Tháng 2 là Mão (3)...
        $chiThangID = ($month + 1) % 12;

        return self::CAN[$canThangID] . " " . self::CHI[$chiThangID];
    }

    private function getCanChiNgay($jdn) {
        // JDN gốc can chi:
        // Can: (JDN + 9) % 10. 0=Giáp.
        // Chi: (JDN + 1) % 12. 0=Tý.
        $canID = ($jdn + 9) % 10;
        $chiID = ($jdn + 1) % 12;
        return self::CAN[$canID] . " " . self::CHI[$chiID];
    }

    private function getChiNgayID($jdn) {
        return ($jdn + 1) % 12;
    }

    private function getCanChiGio($jdn, $gio) {
        // 1. Tìm Can Ngày
        $canNgayID = ($jdn + 9) % 10;

        // 2. Tìm Can Giờ Tý (Khởi giờ)
        // Giáp/Kỷ khởi Giáp(0). Ất/Canh khởi Bính(2)...
        $startCan = ($canNgayID % 5) * 2;

        // 3. Tính số múi giờ (0-11) từ giờ 0-23
        // 23h-1h: Tý (0), 1h-3h: Sửu (1)...
        // Công thức: (Giờ + 1) / 2
        $chiGioID = floor(($gio + 1) / 2) % 12;

        $canGioID = ($startCan + $chiGioID) % 10;

        return self::CAN[$canGioID] . " " . self::CHI[$chiGioID];
    }

    // --- 3. TÍNH TIẾT KHÍ & HOÀNG ĐẠO ---

    private function getTietKhi($d, $m, $y) {
        // Fallback simple map if LunarCalendar not available
        $TERMS = [
            1 => [6 => 'Tiểu hàn', 21 => 'Đại hàn'],
            2 => [4 => 'Lập xuân', 19 => 'Vũ thủy'],
            3 => [6 => 'Kinh trập', 21 => 'Xuân phân'],
            4 => [5 => 'Thanh minh', 20 => 'Cốc vũ'],
            5 => [6 => 'Lập hạ', 21 => 'Tiểu mãn'],
            6 => [6 => 'Mang chủng', 22 => 'Hạ chí'],
            7 => [7 => 'Tiểu thử', 23 => 'Đại thử'],
            8 => [8 => 'Lập thu', 23 => 'Xử thử'],
            9 => [8 => 'Bạch lộ', 23 => 'Thu phân'],
            10 => [8 => 'Hàn lộ', 24 => 'Sương giáng'],
            11 => [8 => 'Lập đông', 22 => 'Tiểu tuyết'],
            12 => [7 => 'Đại tuyết', 22 => 'Đông chí']
        ];

        $term = 'Đông chí';
        if (isset($TERMS[$m])) {
            foreach ($TERMS[$m] as $dayBound => $tName) {
                if ($d >= $dayBound) {
                    $term = $tName;
                }
            }
        }
        if ($m == 1 && $d < 6) $term = 'Đông chí';

        return $term;
    }

    private function checkNgayHoangDao($thangAm, $chiNgayID) {
        // Bảng Lục Thần Hoàng Đạo: 1=Thanh Long, 2=Minh Đường...
        // Quy tắc: Tháng 1, 7 khởi Tý. Tháng 2, 8 khởi Dần...

        $khoi = 0;
        switch ($thangAm) {
            case 1: case 7: $khoi = 0; break; // Tý
            case 2: case 8: $khoi = 2; break; // Dần
            case 3: case 9: $khoi = 4; break; // Thìn
            case 4: case 10: $khoi = 6; break; // Ngọ
            case 5: case 11: $khoi = 8; break; // Thân
            case 6: case 12: $khoi = 10; break; // Tuất
        }

        // Sao của ngày = (ChiNgày - Khởi + 12) % 12
        $offset = ($chiNgayID - $khoi + 12) % 12;
        // Index 0 (Thanh Long) matches user's array logic?
        // User logic: 1.T.Long (Tot), index offset?
        // Let's check: If ChiNgay == Khoi -> Offset 0. Star 1 (Thanh Long).
        // User array index: 0-11.
        // User said: "Thứ tự sao: 1.T.Long... Index mảng 0-11: Tốt là 0, 1, 4, 5, 7, 10."
        // So yes, 0 is Thanh Long.

        $hoangDaoIndex = [0, 1, 4, 5, 7, 10]; // Các vị trí tốt

        $saoNames = [
            0 => 'Thanh Long', 1 => 'Minh Đường', 2 => 'Thiên Cương', 3 => 'Chu Tước',
            4 => 'Kim Quỹ', 5 => 'Bảo Quang', 6 => 'Thiên Hình', 7 => 'Ngọc Đường',
            8 => 'Thiên Lao', 9 => 'Nguyên Vũ', 10 => 'Tư Mệnh', 11 => 'Câu Trần'
        ];

        $saoName = isset($saoNames[$offset]) ? $saoNames[$offset] : '';

        if (in_array($offset, $hoangDaoIndex)) {
            return ["type" => "Hoàng Đạo", "msg" => "$saoName (Tốt)", "is_good" => true];
        } else {
            return ["type" => "Hắc Đạo", "msg" => "$saoName (Xấu)", "is_good" => false];
        }
    }
}
