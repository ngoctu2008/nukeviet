<?php
namespace NukeViet\Module\TuVi\Includes;

use NukeViet\Module\TuVi\Includes\TuViConstants;

/**
 * TuViCalculator.php
 * Class chịu trách nhiệm tính toán vị trí các sao và lập lá số Tử Vi.
 */

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
        $isYearDuong = ($this->input['can_year'] % 2 == 0);
        $isNam = ($this->input['gender'] == 1);

        if (($isNam && $isYearDuong) || (!$isNam && !$isYearDuong)) {
            return 1; // Chiều Thuận
        }
        return -1; // Chiều Nghịch
    }

    // --- CÁC HÀM AN SAO CHÍNH ---

    public function anCoBan() {
        $thang = $this->input['month'];
        $gio = $this->input['hour'];

        $this->menhPos = $this->mod(2 + ($thang - 1) - $gio);

        $this->thanPos = $this->mod(2 + ($thang - 1) + $gio);
        $this->laso[$this->thanPos]['than'] = true;

        $tenCung = ['Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc', 'Thiên Di', 'Tật Ách', 'Tài Bạch', 'Tử Tức', 'Phu Thê', 'Huynh Đệ'];
        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($this->menhPos - $i);
            $this->laso[$pos]['cung_chuc'] = $tenCung[$i];
        }

        $canPair = floor($this->input['can_year'] % 10 % 5);
        $this->cuc = TuViConstants::CUC_MATRIX[$canPair][$this->menhPos];

        $direction = $this->isThuanLy();
        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($this->menhPos + ($i * $direction));
            $this->laso[$pos]['dai_han'] = $this->cuc + ($i * 10);
        }
    }

    public function anChinhTinh() {
        $ngay = $this->input['day'];
        $cuc = $this->cuc;

        $div = floor($ngay / $cuc);
        $rem = $ngay % $cuc;
        $bu = ($rem == 0) ? 0 : ($cuc - $rem);
        $thuong = ($rem == 0) ? $div : floor(($ngay + $bu) / $cuc);

        $offset = ($bu % 2 == 0) ? $bu : -$bu;
        $tuViPos = $this->mod(2 + $thuong - 1 + $offset);
        $this->addSao($tuViPos, 'TU_VI', 'chinh_tinh');

        $thienPhuPos = $this->mod(10 - $tuViPos);
        $this->addSao($thienPhuPos, 'THIEN_PHU', 'chinh_tinh');

        $this->addSao($tuViPos - 1, 'THIEN_CO', 'chinh_tinh');
        $this->addSao($tuViPos - 3, 'THAI_DUONG', 'chinh_tinh');
        $this->addSao($tuViPos - 4, 'VU_KHUC', 'chinh_tinh');
        $this->addSao($tuViPos - 5, 'THIEN_DONG', 'chinh_tinh');
        $this->addSao($tuViPos - 8, 'LIEM_TRINH', 'chinh_tinh');

        $this->addSao($thienPhuPos + 1, 'THAI_AM', 'chinh_tinh');
        $this->addSao($thienPhuPos + 2, 'THAM_LANG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 3, 'CU_MON', 'chinh_tinh');
        $this->addSao($thienPhuPos + 4, 'THIEN_TUONG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 5, 'THIEN_LUONG', 'chinh_tinh');
        $this->addSao($thienPhuPos + 6, 'THAT_SAT', 'chinh_tinh');
        $this->addSao($thienPhuPos + 10, 'PHA_QUAN', 'chinh_tinh');
    }

    public function anSaoGio() {
        $gio = $this->input['hour'];
        $xuong = $this->mod(10 - $gio);
        $khuc = $this->mod(4 + $gio);
        $this->addSao($xuong, 'VAN_XUONG');
        $this->addSao($khuc, 'VAN_KHUC');

        $khong = $this->mod(11 - $gio);
        $kiep = $this->mod(11 + $gio);
        $this->addSao($khong, 'DIA_KHONG');
        $this->addSao($kiep, 'DIA_KIEP');

        $this->addSao(6 + $gio, 'THAI_PHU');
        $this->addSao(2 + $gio, 'PHONG_CAO');
    }

    public function anSaoThang() {
        $thang = $this->input['month'];
        $ta = $this->mod(4 + ($thang - 1));
        $huu = $this->mod(10 - ($thang - 1));
        $this->addSao($ta, 'TA_PHU');
        $this->addSao($huu, 'HUU_BAT');

        $hinh = $this->mod(9 + ($thang - 1));
        $rieu = $this->mod(1 + ($thang - 1));
        $this->addSao($hinh, 'THIEN_HINH');
        $this->addSao($rieu, 'THIEN_RIEU');
        $this->addSao($rieu, 'THIEN_Y');

        $this->addSao($this->mod(8 - ($thang-1)), 'THIEN_GIAI');
        $this->addSao($this->mod(7 + ($thang-1)), 'DIA_GIAI');
    }

    public function anSaoCanNam() {
        $can = $this->input['can_year'];

        $locTonMap = [2, 3, 5, 6, 5, 6, 8, 9, 11, 0];
        $posLoc = $locTonMap[$can];

        $this->addSao($posLoc, 'LOC_TON');
        $this->addSao($posLoc, 'BAC_SY');

        $this->addSao($posLoc + 1, 'KINH_DUONG');
        $this->addSao($posLoc - 1, 'DA_LA');

        $dir = $this->isThuanLy();
        $vongLocTon = ['LOC_TON','LUC_SI','THANH_LONG','TIEU_HAO','TUONG_QUAN','TAU_THU','PHI_LIEM','HY_THAN','BENH_PHU','DAI_HAO','PHUC_BINH','QUAN_PHU_L'];
        for ($i = 1; $i < 12; $i++) {
            $this->addSao($posLoc + ($i * $dir), $vongLocTon[$i]);
        }

        $kvMap = [
            0 => [1, 7], 1 => [0, 8], 2 => [11, 9], 3 => [11, 9], 4 => [1, 7],
            5 => [0, 8], 6 => [6, 2], 7 => [6, 2], 8 => [5, 3], 9 => [5, 3]
        ];
        $this->addSao($kvMap[$can][0], 'THIEN_KHOI');
        $this->addSao($kvMap[$can][1], 'THIEN_VIET');

        $tuHoa = TuViConstants::getTuHoa($can);
        foreach ($this->laso as &$cung) {
            foreach ($cung['chinh_tinh'] as $sao) {
                if ($sao == $tuHoa['HOA_LOC']) $cung['phu_tinh'][] = 'HOA_LOC';
                if ($sao == $tuHoa['HOA_QUYEN']) $cung['phu_tinh'][] = 'HOA_QUYEN';
                if ($sao == $tuHoa['HOA_KHOA']) $cung['phu_tinh'][] = 'HOA_KHOA';
                if ($sao == $tuHoa['HOA_KY']) $cung['phu_tinh'][] = 'HOA_KY';
            }
            foreach ($cung['phu_tinh'] as $sao) {
                 if ($sao == $tuHoa['HOA_LOC']) $cung['phu_tinh'][] = 'HOA_LOC';
                 if ($sao == $tuHoa['HOA_QUYEN']) $cung['phu_tinh'][] = 'HOA_QUYEN';
                 if ($sao == $tuHoa['HOA_KHOA']) $cung['phu_tinh'][] = 'HOA_KHOA';
                 if ($sao == $tuHoa['HOA_KY']) $cung['phu_tinh'][] = 'HOA_KY';
            }
        }
        unset($cung);

        $haMap = [9, 10, 7, 8, 5, 6, 8, 9, 11, 0];
        $this->addSao($haMap[$can], 'LUU_HA');

        $truMap = [5, 6, 0, 5, 6, 8, 2, 6, 9, 10];
        $this->addSao($truMap[$can], 'THIEN_TRU');
    }

    public function anSaoChiNam() {
        $chi = $this->input['chi_year'];

        $vongThaiTue = ['THAI_TUE','THIEU_DUONG','TANG_MON','THIEU_AM','QUAN_PHU','TU_PHU','TUE_PHA','LONG_DUC','BACH_HO','PHUC_DUC','DIEU_KHACH','TRUC_PHU'];
        for ($i = 0; $i < 12; $i++) {
            $this->addSao($chi + $i, $vongThaiTue[$i]);
        }

        $khoiHoa = 1; $khoiLinh = 3;
        if (in_array($chi, [8,0,4])) { $khoiHoa = 2; $khoiLinh = 10; }
        if (in_array($chi, [5,9,1])) { $khoiHoa = 3; $khoiLinh = 10; }
        if (in_array($chi, [11,3,7])) { $khoiHoa = 9; $khoiLinh = 10; }

        $gio = $this->input['hour'];
        $this->addSao($khoiHoa + $gio, 'HOA_TINH');
        $this->addSao($khoiLinh - $gio, 'LINH_TINH');

        if (in_array($chi, [2,6,10])) $ma = 8;
        elseif (in_array($chi, [8,0,4])) $ma = 2;
        elseif (in_array($chi, [5,9,1])) $ma = 11;
        else $ma = 5;
        $this->addSao($ma, 'THIEN_MA');

        if (in_array($chi, [11,0,1])) { $co=2; $qua=10; }
        elseif (in_array($chi, [2,3,4])) { $co=5; $qua=1; }
        elseif (in_array($chi, [5,6,7])) { $co=8; $qua=4; }
        else { $co=11; $qua=7; }
        $this->addSao($co, 'CO_THAN');
        $this->addSao($qua, 'QUA_TU');

        $this->addSao(10 + $chi, 'PHUONG_CAC');
        $this->addSao(10 + $chi, 'GIAI_THAN');

        $this->addSao(4 + $chi, 'LONG_TRI');

        $this->addSao(6 - $chi, 'THIEN_KHOC');
        $this->addSao(6 + $chi, 'THIEN_HU');

        if (in_array($chi, [2,6,10])) $dao = 3;
        elseif (in_array($chi, [8,0,4])) $dao = 9;
        elseif (in_array($chi, [5,9,1])) $dao = 6;
        else $dao = 0;
        $this->addSao($dao, 'DAO_HOA');

        $this->addSao(3 - $chi, 'HONG_LOAN');
        $this->addSao(3 - $chi + 6, 'THIEN_HY');
    }

    public function anVongTrangSinh() {
        $start = 0;
        switch ($this->cuc) {
            case 2: $start = 8; break; // Thân
            case 3: $start = 11; break; // Hợi
            case 4: $start = 5; break; // Tỵ
            case 5: $start = 8; break; // Thân
            case 6: $start = 2; break; // Dần
        }

        $dir = $this->isThuanLy();
        $vongTS = ['Tràng Sinh','Mộc Dục','Quan Đới','Lâm Quan','Đế Vượng','Suy','Bệnh','Tử','Mộ','Tuyệt','Thai','Dưỡng'];

        for ($i = 0; $i < 12; $i++) {
            $pos = $this->mod($start + ($i * $dir));
            $this->laso[$pos]['vong_trang_sinh'] = $vongTS[$i];
        }
    }

    public function anTuanTriet() {
        $can = $this->input['can_year'];
        $startTriet = [8, 6, 4, 2, 0];
        $pos1 = $startTriet[$can % 5];
        $pos2 = $pos1 + 1;
        $this->laso[$pos1]['triet'] = true;
        $this->laso[$pos2]['triet'] = true;

        $posTuan1 = $this->mod($this->input['chi_year'] - $this->input['can_year'] + 10);
        $posTuan2 = $this->mod($posTuan1 + 1);
        $this->laso[$posTuan1]['tuan'] = true;
        $this->laso[$posTuan2]['tuan'] = true;
    }

    public function execute() {
        $this->anCoBan();
        $this->anChinhTinh();
        $this->anSaoGio();
        $this->anSaoThang();
        $this->anSaoCanNam();
        $this->anSaoChiNam();
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
