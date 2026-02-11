<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

use NukeViet\Module\HuyenHoc\LunarCalendar;
use NukeViet\Module\HuyenHoc\TuViConstants;
use NukeViet\Module\HuyenHoc\TuViMuonTuoi;

class TuViXemNgay {
    protected $userYear;    // Năm sinh gia chủ (Âm)
    protected $userGender;  // 1: Nam, 0: Nữ
    protected $currentDate; // Ngày cần xem (Dương lịch Y-m-d)
    protected $lunarDate;   // Ngày Âm lịch [day, month, year, can, chi]
    protected $solarDate;   // [d, m, y]

    // Mảng các sao xấu cố định theo ngày/tháng (Sát chủ, Thọ tử...)
    const BAD_DAYS = [
        'SAT_CHU_DUONG' => [1=>0, 2=>1, 3=>2, 4=>3, 5=>4, 6=>5, 7=>6, 8=>7, 9=>8, 10=>9, 11=>10, 12=>11], // Mock: Index Chi (0=Ty...)
        'THO_TU' => [1=>10, 2=>4], // Mock
        'TAM_NUONG' => [3, 7, 13, 18, 22, 27], // Ngày âm
        'NGUYET_KY' => [5, 14, 23] // Ngày âm
    ];

    public function __construct($birthYear, $gender, $dateToCheck) {
        $this->userYear = $birthYear;
        $this->userGender = $gender;
        $this->currentDate = $dateToCheck;

        // Parse dateToCheck (Y-m-d)
        $parts = explode('-', $dateToCheck);
        $d = (int)$parts[2]; $m = (int)$parts[1]; $y = (int)$parts[0];
        $this->solarDate = ['d' => $d, 'm' => $m, 'y' => $y];

        $this->lunarDate = $this->convertSolarToLunar($d, $m, $y);
    }

    /**
     * Hàm chính: Điều phối chức năng xem ngày theo mục đích
     */
    public function phanTichNgay($mucDich) {
        $result = [
            'thong_tin_ngay' => $this->getThongTinCoBan(),
            'binh_giai' => [],
            'diem_so' => 0, // Thang 10
            'ket_luan' => '',
            'hoa_giai' => null,
            'is_bad_day' => false
        ];

        // Basic check: Tam Nuong, Nguyet Ky
        if (in_array($this->lunarDate['day'], self::BAD_DAYS['TAM_NUONG'])) {
            $result['binh_giai'][] = "Phạm Tam Nương (Ngày xấu).";
            $result['diem_so'] -= 2;
            $result['is_bad_day'] = true;
        }
        if (in_array($this->lunarDate['day'], self::BAD_DAYS['NGUYET_KY'])) {
            $result['binh_giai'][] = "Phạm Nguyệt Kỵ (Ngày xấu).";
            $result['diem_so'] -= 2;
            $result['is_bad_day'] = true;
        }

        switch ($mucDich) {
            case 'KHAI_TRUONG':
                $result = $this->xemKhaiTruong($result);
                break;
            case 'LAM_NHA': // Động thổ, Cất nóc
                $result = $this->xemLamNha($result);
                break;
            case 'CUOI_HOI':
                $result = $this->xemCuoiHoi($result);
                break;
            case 'MA_CHAY':
                $result = $this->xemMaChay($result);
                break;
            default:
                $result['binh_giai'][] = "Mục đích chung: Xem ngày tốt xấu cơ bản.";
                $result = $this->xemKhaiTruong($result); // Fallback to general good day logic
        }

        return $result;
    }

    /**
     * Tinh Trung Tang (Nhap Mo, Thien Di, Trung Tang)
     * Rule: Men -> 10 start Dan (2), Clockwise. Women -> 10 start Than (8), Counter-Clockwise.
     * Sequence: 10 -> 20 -> ... -> Age -> Month -> Day -> Hour.
     * Fall into:
     * - Than, Ty, Thin, Ngo: Nhap Mo (Good)
     * - Ty, Hoi, Dan, Dau: Thien Di (Normal)
     * - Suu, Mui, Tuat, Mao: Trung Tang (Bad)
     * (Simplified mapping - Different schools vary, using common one)
     */
    public function xemTrungTang($deceasedInfo, $headYear, $relatives) {
        $age = $deceasedInfo['age'];
        $gender = $deceasedInfo['gender']; // 1=Nam, 0=Nu
        $deathTime = $deceasedInfo['death_time']; // ['m'=>..., 'd'=>..., 'h'=>...] Lunar

        $month = $deathTime['m'];
        $day = $deathTime['d'];
        $hourChi = $deathTime['h_chi']; // 0-11

        // 1. Calculate Palaces (Cung)
        $startPos = ($gender == 1) ? 2 : 8; // Dan : Than
        $direction = ($gender == 1) ? 1 : -1;

        // Count Age (10, 20...)
        $tens = floor($age / 10);
        $units = $age % 10;

        // 10s step
        $pos = $startPos;
        for ($i=1; $i<$tens; $i++) { // 10 is start, so start counting from 20
             $pos = ($pos + $direction);
             if ($pos > 11) $pos = 0; if ($pos < 0) $pos = 11;
        }

        // Units step (From 10s pos)
        if ($units > 0) {
            // First unit counts 1 step? Or 11 is next? Usually: 10 at X. 11 at X+1.
            // So if age 13: 10->X. 11->X+1. 12->X+2. 13->X+3.
            for ($i=0; $i<$units; $i++) {
                $pos = ($pos + $direction);
                if ($pos > 11) $pos = 0; if ($pos < 0) $pos = 11;
            }
        }
        $cungTuoi = $pos; // Nien (Age)

        // Month step (From Age pos)
        // Month 1 is next step? Or start at Age pos?
        // Usually: Month 1 at Age Pos? Or Age Pos + 1?
        // Let's assume: From Age Pos, count months. Month 1 = Age Pos.
        $pos = $cungTuoi;
        for ($i=1; $i<$month; $i++) {
            $pos = ($pos + $direction);
            if ($pos > 11) $pos = 0; if ($pos < 0) $pos = 11;
        }
        $cungThang = $pos;

        // Day step
        $pos = $cungThang;
        for ($i=1; $i<$day; $i++) {
            $pos = ($pos + $direction);
            if ($pos > 11) $pos = 0; if ($pos < 0) $pos = 11;
        }
        $cungNgay = $pos;

        // Hour step (From Day pos, count Chi Hour? Or number of hours?)
        // Ty (0) = 1?
        // Let's count steps: Ty is step 1.
        $stepsH = $hourChi + 1;
        $pos = $cungNgay;
        for ($i=1; $i<$stepsH; $i++) {
            $pos = ($pos + $direction);
            if ($pos > 11) $pos = 0; if ($pos < 0) $pos = 11;
        }
        $cungGio = $pos;

        // 2. Evaluate
        // Nhom:
        // Nhap Mo: Thin (4), Tuat (10), Suu (1), Mui (7) -> Earth/Grave?
        // Let's use the standard "Dan Than Ty Hoi - Thien Di", "Ty Ngo Mao Dau - Trung Tang", "Thin Tuat Suu Mui - Nhap Mo".

        $evaluate = function($idx) {
            if (in_array($idx, [4, 10, 1, 7])) return ['type'=>'nhap_mo', 'text'=>'Nhập Mộ (Tốt)'];
            if (in_array($idx, [2, 8, 5, 11])) return ['type'=>'thien_di', 'text'=>'Thiên Di (Bình thường)'];
            return ['type'=>'trung_tang', 'text'=>'Trùng Tang (Xấu)'];
        };

        $resTuoi = $evaluate($cungTuoi);
        $resThang = $evaluate($cungThang);
        $resNgay = $evaluate($cungNgay);
        $resGio = $evaluate($cungGio);

        $conclusion = "Bình thường.";
        $alertClass = "info";

        $countTT = 0; $countNM = 0;
        foreach ([$resTuoi, $resThang, $resNgay, $resGio] as $r) {
            if ($r['type'] == 'trung_tang') $countTT++;
            if ($r['type'] == 'nhap_mo') $countNM++;
        }

        if ($countTT > 0 && $countNM == 0) {
            $conclusion = "ĐẠI HUNG: Phạm Trùng Tang ($countTT cung). Cần làm lễ trấn yểm.";
            $alertClass = "danger";
        } elseif ($countNM > 0) {
            $conclusion = "ĐẠI CÁT: Được Nhập Mộ ($countNM cung). Người mất yên nghỉ, con cháu phát đạt.";
            $alertClass = "success";
        } else {
            $conclusion = "Thiên Di: Ra đi nhẹ nhàng, không phạm trùng tang.";
            $alertClass = "warning";
        }

        // 3. Check Conflicts
        $conflicts = [];
        // Head (Truong Nam) vs Deceased Year (Tu Hanh Xung)
        $dChi = ($deceasedInfo['year'] - 4) % 12;
        $hChi = ($headYear - 4) % 12;
        if (abs($dChi - $hChi) == 6) $conflicts[] = "Tuổi Trưởng nam xung với tuổi người mất.";

        return [
            'tuoi_val' => TuViConstants::CHI[$cungTuoi], 'tuoi_text' => $resTuoi['text'],
            'thang_val' => TuViConstants::CHI[$cungThang], 'thang_text' => $resThang['text'],
            'ngay_val' => TuViConstants::CHI[$cungNgay], 'ngay_text' => $resNgay['text'],
            'gio_val' => TuViConstants::CHI[$cungGio], 'gio_text' => $resGio['text'],
            'main_conclusion' => $conclusion,
            'alert_class' => $alertClass,
            'conflicts' => $conflicts
        ];
    }

    // --- 1. LOGIC XEM KHAI TRƯƠNG ---
    private function xemKhaiTruong($res) {
        if ($this->userYear > 0) {
            $userCanChi = $this->getCanChi($this->userYear);
            $dayCanChi = $this->lunarDate['can_chi'];

            // 1. Kiểm tra Xung Tuổi (Tứ Hành Xung)
            if ($this->isXung($userCanChi['chi'], $dayCanChi['chi'])) {
                $res['binh_giai'][] = "Xấu: Ngày " . TuViConstants::CHI[$dayCanChi['chi']] . " xung với tuổi chủ (Lục Xung/Tứ Hành Xung). Không nên mở hàng.";
                $res['diem_so'] -= 5;
            } else {
                if ($this->isTamHop($userCanChi['chi'], $dayCanChi['chi'])) {
                    $res['binh_giai'][] = "Tốt: Ngày thuộc Tam Hợp với chủ sự, hanh thông.";
                    $res['diem_so'] += 3;
                }
            }

            // 2. Kiểm tra Ngũ Hành (Nạp âm)
            $menhChu = TuViConstants::getNapAm($userCanChi['can'], $userCanChi['chi']);
            $menhNgay = TuViConstants::getNapAm($dayCanChi['can'], $dayCanChi['chi']);
            // Check Sinh/Khac (Custom simple check for now or use TuViMuonTuoi logic if accessible, but here simplified)
            // Using ID compare: 1=Kim, 2=Thuy...
            $checkHanh = $this->checkSinhKhac($menhNgay['id'], $menhChu['id']);

            $res['binh_giai'][] = "Ngũ hành: " . $checkHanh['msg'];
            $res['diem_so'] += $checkHanh['score'];
        }

        // 3. Kiểm tra Sao tốt (Lộc Tồn, Thiên Mã...)
        if ($this->hasGoodStars($this->lunarDate['can_chi'], ['LOC_TON', 'THIEN_MA'])) {
            $res['binh_giai'][] = "Đại Cát: Ngày có sao Lộc/Mã, lợi cho cầu tài, buôn bán.";
            $res['diem_so'] += 2;
        }

        // Kết luận
        if ($res['diem_so'] > 5) $res['ket_luan'] = "Rất Tốt";
        elseif ($res['diem_so'] > 0) $res['ket_luan'] = "Khá";
        elseif ($res['diem_so'] > -5) $res['ket_luan'] = "Trung Bình";
        else $res['ket_luan'] = "Xấu";

        return $res;
    }

    // --- 2. LOGIC XEM LÀM NHÀ (Kèm Hóa Giải) ---
    private function xemLamNha($res) {
        $age = $this->lunarDate['year'] - $this->userYear + 1; // Tuổi mụ

        // 1. Check hạn Nam (Yearly Limits for Owner)
        // Requires TuViMuonTuoi class
        if (class_exists('NukeViet\Module\HuyenHoc\TuViMuonTuoi')) {
            $muonTuoiTool = new TuViMuonTuoi($this->solarDate['y']); // Use solar year for calculation context
            $checkUser = $muonTuoiTool->analyzeCandidate($this->userYear, $this->userYear);

            if (!$checkUser['is_eligible']) {
                $res['diem_so'] = -10;

                // Detailed Warning
                $bad = [];
                if ($checkUser['bad_factors']['kim_lau']) $bad[] = "Kim Lâu";
                if ($checkUser['bad_factors']['hoang_oc']) $bad[] = "Hoang Ốc (" . $checkUser['bad_factors']['hoang_oc'] . ")";
                if ($checkUser['bad_factors']['tam_tai']) $bad[] = "Tam Tai";
                if ($checkUser['bad_factors']['thai_tue']) $bad[] = "Thái Tuế";

                $res['binh_giai'][] = "CẢNH BÁO: Năm nay tuổi bạn phạm: " . implode(', ', $bad);
                $res['ket_luan'] = "Đại Kỵ Động Thổ";

                // --- LOGIC HÓA GIẢI (Mượn Tuổi) ---
                $res['binh_giai'][] = "GIẢI PHÁP: Nên mượn tuổi người khác để đứng tên động thổ.";

                // Tìm danh sách người hợp tuổi
                $candidates = $muonTuoiTool->timNguoiMuonTuoi($this->userYear);
                $goiY = [];
                foreach($candidates as $cand) {
                    $goiY[] = "Tuổi {$cand['birth_year']} ({$cand['can_chi']}) - {$cand['score']} điểm";
                }
                $res['hoa_giai'] = [
                    'phuong_phap' => 'Mượn tuổi động thổ',
                    'danh_sach_goi_y' => array_slice($goiY, 0, 5) // Lấy top 5
                ];
            } else {
                $res['binh_giai'][] = "Tuổi đẹp, không phạm Kim Lâu, Hoang Ốc. Có thể tự đứng tên.";
                // Continue day analysis
                $res = $this->xemKhaiTruong($res); // Reuse basic good day logic
            }
        }

        return $res;
    }

    // --- 3. LOGIC XEM MA CHAY ---
    private function xemMaChay($res) {
        $userChi = $this->getCanChi($this->userYear)['chi'];
        $dayChi = $this->lunarDate['can_chi']['chi'];

        if ($userChi == $dayChi) {
            $res['binh_giai'][] = "ĐẠI KỴ: Ngày Trùng Tang (Ngày trùng tuổi người mất). Kiêng an táng.";
            $res['diem_so'] = -100;
        }

        // Sát Chủ Âm (Example logic)
        // T1: Ty, T2: Ty... (Mock)
        if ($this->lunarDate['month'] == 1 && $dayChi == 5) { // Ty
             $res['binh_giai'][] = "Xấu: Ngày Sát Chủ Âm, không lợi cho tang lễ.";
             $res['diem_so'] -= 5;
        }

        return $res;
    }

    // --- 4. Logic Cuoi Hoi ---
    private function xemCuoiHoi($res) {
        // Tranh Co Than, Qua Tu, Kim Lau (Year check usually)
        // Day check:
        // Ky ngay Xung chong/vo.
        // Uu tien Ngay co Tai Duc, Thien Duc, Nguyet Duc.

        $res = $this->xemKhaiTruong($res); // Use base logic
        // Add specific marriage stars if data available
        return $res;
    }

    // --- 4. TRA CỨU & GỢI Ý ---

    public function goiYNgayTotTrongThang($month, $year, $purpose) {
        $listDays = [];
        // Loop days in solar month? Or Lunar?
        // Usually View by Solar Month.
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = "$year-$month-$d";

            // Re-instantiate or reset?
            // Better to instantiate new object or update state.
            // Let's create new instance for clarity in loop
            $app = new TuViXemNgay($this->userYear, $this->userGender, $date);
            $analysis = $app->phanTichNgay($purpose);

            if ($analysis['diem_so'] > 0 && !$analysis['is_bad_day']) {
                $listDays[] = [
                    'day' => $d,
                    'lunar_day' => $app->lunarDate['day'],
                    'lunar_month' => $app->lunarDate['month'],
                    'can_chi' => $app->lunarDate['can_chi']['name'],
                    'diem' => $analysis['diem_so'],
                    'ly_do' => implode('; ', $analysis['binh_giai'])
                ];
            }
        }
        return $listDays;
    }

    // --- Helpers ---
    private function getCanChi($year) {
        return ['can' => ($year-4)%10, 'chi' => ($year-4)%12];
    }

    private function isXung($chi1, $chi2) {
        return abs($chi1 - $chi2) == 6;
    }

    private function isTamHop($chi1, $chi2) {
        // Groups: 0-4-8, 1-5-9, 2-6-10, 3-7-11
        $diff = abs($chi1 - $chi2);
        return ($diff == 4 || $diff == 8);
    }

    private function checkSinhKhac($hanhNgay, $hanhNguoi) {
        if ($hanhNgay == $hanhNguoi) return ['score' => 1, 'msg' => 'Tương Hòa'];
        // Mock Sinh relation
        // 1=Kim, 2=Thuy, 3=Hoa, 4=Tho, 5=Moc (Based on TuViConstants)
        // Kim(1)->Thuy(2)->Moc(5)->Hoa(3)->Tho(4)->Kim(1)
        $sinh = [1=>2, 2=>5, 5=>3, 3=>4, 4=>1];

        if (isset($sinh[$hanhNgay]) && $sinh[$hanhNgay] == $hanhNguoi) return ['score' => 2, 'msg' => 'Ngày sinh Chủ (Tốt)'];
        if (isset($sinh[$hanhNguoi]) && $sinh[$hanhNguoi] == $hanhNgay) return ['score' => -1, 'msg' => 'Chủ sinh Ngày (Sinh xuất - Hao)'];

        return ['score' => 0, 'msg' => 'Bình thường'];
    }

    private function hasGoodStars($dayInfo, $starCodes) {
        // Mock check
        // Real logic needs Star Data per Day Can/Chi
        return false;
    }

    private function getThongTinCoBan() {
        return "Ngày " . $this->lunarDate['day'] . " tháng " . $this->lunarDate['month'] . " năm " . $this->lunarDate['year'];
    }

    private function convertSolarToLunar($d, $m, $y) {
        $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
        $cc = LunarCalendar::getCanChi($lunar['year'], $lunar['month'], $lunar['day'], 0); // Note: Day CanChi needs JD
        // But getCanChi above returns Year/Month based on args. Day based on JD.

        // Correct Day Can Chi
        $jd = LunarCalendar::jdn($d, $m, $y);
        $canDay = ($jd + 9) % 10;
        $chiDay = ($jd + 1) % 12;

        return [
            'day' => $lunar['day'],
            'month' => $lunar['month'],
            'year' => $lunar['year'],
            'can_chi' => [
                'can' => $canDay,
                'chi' => $chiDay,
                'name' => TuViConstants::CAN[$canDay] . ' ' . TuViConstants::CHI[$chiDay]
            ]
        ];
    }
}
