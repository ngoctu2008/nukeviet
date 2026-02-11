<?php
/**
 * Class LichVanNien
 * Chức năng: Chuyển đổi Dương - Âm, Tính Can Chi, Tiết Khí, Hoàng Đạo.
 * Thuật toán: Dựa trên phương pháp tính của Hồ Ngọc Đức.
 */

namespace NukeViet\Module\HuyenHoc;

use NukeViet\Module\HuyenHoc\LunarCalendar;

class LichVanNien {

    const CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    const CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

    public function __construct() {}

    public function getInfo($d, $m, $y, $gio = 12) {
        $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);

        // JDN for Day Can Chi
        $jdn = LunarCalendar::jdn($d, $m, $y);

        $canChiNam = LunarCalendar::getCanChiYear($lunar['year']);
        $canChiThang = LunarCalendar::getCanChiMonth($lunar['month'], $lunar['year']);
        $canChiNgay = LunarCalendar::getCanChiDay($d, $m, $y);

        $tietKhi = LunarCalendar::getTietKhi($d, $m, $y);

        // Hoang Dao Day
        $chiNgayID = ($jdn + 1) % 12;
        $hoangDaoDayMsg = LunarCalendar::getNgayHoangDao($chiNgayID, $lunar['month']);

        // Ly Thuan Phong
        $lyThuanPhong = $this->getLyThuanPhong($lunar['month'], $lunar['day']);

        // Tuoi Xung
        $tuoiXung = $this->getTuoiXung($jdn);

        // Huong Xuat Hanh
        $huong = $this->getHuongXuatHanh($jdn);

        return [
            'duong_lich' => sprintf('%02d/%02d/%04d', $d, $m, $y),
            'am_lich' => [
                'day' => $lunar['day'],
                'month' => $lunar['month'],
                'year' => $lunar['year'],
                'leap' => $lunar['leap'],
                'text' => "Ngày " . $lunar['day'] . " tháng " . $lunar['month'] . " năm " . $lunar['year'] . ($lunar['leap'] ? " (Nhuận)" : "")
            ],
            'can_chi' => [
                'nam' => $canChiNam,
                'thang' => $canChiThang,
                'ngay' => $canChiNgay,
                'gio' => $this->getCanChiGio($jdn, $gio)
            ],
            'ids' => [
                'can_nam' => ($lunar['year'] + 6) % 10,
                'chi_nam' => ($lunar['year'] + 8) % 12,
                'can_ngay' => ($jdn + 9) % 10,
                'chi_ngay' => ($jdn + 1) % 12
            ],
            'tiet_khi' => $tietKhi,
            'ngay_hoang_dao' => ['msg' => $hoangDaoDayMsg, 'type' => (strpos($hoangDaoDayMsg, 'Hoàng Đạo') !== false ? 1 : 0)],
            'ly_thuan_phong' => $lyThuanPhong,
            'tuoi_xung' => $tuoiXung,
            'huong_xuat_hanh' => $huong
        ];
    }

    public function getCanChiGio($jdn, $gio) {
        $canNgayID = ($jdn + 9) % 10;
        $startCan = ($canNgayID % 5) * 2;
        $chiGioID = floor(($gio + 1) / 2) % 12;
        $canGioID = ($startCan + $chiGioID) % 10;
        return self::CAN[$canGioID] . " " . self::CHI[$chiGioID];
    }

    public function getLyThuanPhong($lunarMonth, $lunarDay) {
        $monthStartMap = [1=>1, 7=>1, 2=>3, 8=>3, 3=>4, 9=>4, 4=>5, 10=>5, 5=>6, 11=>6, 6=>2, 12=>2];
        $startPos = isset($monthStartMap[$lunarMonth]) ? $monthStartMap[$lunarMonth] : 1;

        $dayPos = ($startPos + $lunarDay - 1);
        while ($dayPos > 6) $dayPos -= 6;
        while ($dayPos <= 0) $dayPos += 6;

        $lyThuanPhongMap = [
            1 => 'Đại An', 2 => 'Lưu Niên', 3 => 'Tốc Hỷ',
            4 => 'Xích Khẩu', 5 => 'Tiểu Cát', 6 => 'Không Vong'
        ];

        $gioLTP = [];
        for ($i=0; $i<12; $i++) {
            $p = ($dayPos + $i);
            while ($p > 6) $p -= 6;
            $gioLTP[] = ['hour' => self::CHI[$i], 'name' => $lyThuanPhongMap[$p]];
        }
        return $gioLTP;
    }

    public function getTuoiXung($jdn) {
        $dCan = ($jdn + 9) % 10;
        $dChi = ($jdn + 1) % 12;

        $xChi = ($dChi + 6) % 12;
        $xChiName = self::CHI[$xChi];

        // Thien Khac
        $c1 = ($dCan + 6) % 10;
        $c2 = ($dCan + 4) % 10;

        return self::CAN[$c1] . ' ' . $xChiName . ', ' . self::CAN[$c2] . ' ' . $xChiName;
    }

    public function getHuongXuatHanh($jdn) {
        $dCan = ($jdn + 9) % 10;
        $huongMap = [
            0 => ['hy'=>'Đông Bắc', 'tai'=>'Đông Nam'], // Giap
            1 => ['hy'=>'Tây Bắc', 'tai'=>'Đông Nam'], // At
            2 => ['hy'=>'Tây Nam', 'tai'=>'Đông'], // Binh
            3 => ['hy'=>'Nam', 'tai'=>'Đông'], // Dinh
            4 => ['hy'=>'Đông Nam', 'tai'=>'Bắc'], // Mau
            5 => ['hy'=>'Đông Bắc', 'tai'=>'Nam'], // Ky
            6 => ['hy'=>'Tây Bắc', 'tai'=>'Tây Nam'], // Canh
            7 => ['hy'=>'Tây Nam', 'tai'=>'Tây Nam'], // Tan
            8 => ['hy'=>'Nam', 'tai'=>'Tây'], // Nham
            9 => ['hy'=>'Đông Nam', 'tai'=>'Tây'] // Quy
        ];
        $h = isset($huongMap[$dCan]) ? $huongMap[$dCan] : ['hy'=>'?', 'tai'=>'?'];
        return 'Hỷ Thần: ' . $h['hy'] . ' - Tài Thần: ' . $h['tai'];
    }
}
