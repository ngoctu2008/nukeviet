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
    protected $userYear;    // Năm sinh gia chủ (Âm/Dương depending on context, usually Lunar Year stored as Int)
    protected $partnerYear; // Năm sinh đối tác (Vợ/Chồng/Người yêu) - Optional
    protected $userGender;  // 1: Nam, 0: Nữ
    protected $currentDate; // Ngày cần xem (Dương lịch Y-m-d)
    protected $lunarDate;   // Ngày Âm lịch [day, month, year, can, chi]
    protected $solarDate;   // [d, m, y]
    protected $muonTuoiTool;

    // Mảng các sao xấu cố định theo ngày/tháng (Sát chủ, Thọ tử...)
    const BAD_DAYS = [
        'TAM_NUONG' => [3, 7, 13, 18, 22, 27],
        'NGUYET_KY' => [5, 14, 23],
        // Tho Tu: Month -> Chi Index (0=Ty...)
        'THO_TU' => [1=>10, 2=>4, 3=>11, 4=>5, 5=>0, 6=>6, 7=>1, 8=>7, 9=>2, 10=>8, 11=>3, 12=>9],
        // Sat Chu Duong: Month -> Chi Index
        'SAT_CHU_DUONG' => [1=>0, 2=>5, 3=>7, 4=>3, 5=>8, 6=>10, 7=>11, 8=>1, 9=>6, 10=>9, 11=>2, 12=>4]
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

        // Init Muon Tuoi for current year
        if (class_exists('\NukeViet\Module\HuyenHoc\TuViMuonTuoi')) {
            $this->muonTuoiTool = new \NukeViet\Module\HuyenHoc\TuViMuonTuoi($y);
        }
    }

    public function setPartnerYear($y) {
        $this->partnerYear = $y;
    }

    /**
     * Hàm chính: Điều phối chức năng xem ngày theo mục đích
     */
    public function phanTichNgay($mucDich) {
        $result = [
            'thong_tin_ngay' => $this->getThongTinCoBan(),
            'binh_giai' => [],
            'diem_so' => 5, // Thang 10
            'ket_luan' => '',
            'hoa_giai' => null,
            'is_bad_day' => false
        ];

        // 1. Kiểm tra Bách Kỵ
        if ($this->checkBachKy($result)) {
            // Bach Ky triggers bad day flag but we continue analysis to show why
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

    // --- 1. LOGIC XEM KHAI TRƯƠNG ---
    private function xemKhaiTruong($res) {
        $dayCanChi = $this->lunarDate['can_chi'];
        $thangAm = $this->lunarDate['month'];
        $ngayAm = $this->lunarDate['day'];

        // Ưu tiên ngày tốt theo mùa/tháng (Heuristic)
        // Ví dụ tháng 1: 6, 10, 15, 19, 21
        if ($thangAm == 1 && in_array($ngayAm, [6, 10, 15, 19, 21])) {
            $res['binh_giai'][] = "Ngày vàng khai trương tháng Giêng: Mang lại tài lộc.";
            $res['diem_so'] += 5;
        }

        if ($this->userYear > 0) {
            $userCanChi = $this->getCanChi($this->userYear);

            // Xung Tuoi
            if ($this->isXung($userCanChi['chi'], $dayCanChi['chi'])) {
                $res['binh_giai'][] = "Xấu: Ngày xung tuổi chủ (Lục Xung). Khách vắng, dễ trục trặc.";
                $res['diem_so'] -= 5;
            } elseif ($this->isTamHop($userCanChi['chi'], $dayCanChi['chi'])) {
                $res['binh_giai'][] = "Tốt: Ngày Tam Hợp, vượng khí cho chủ sự.";
                $res['diem_so'] += 3;
            }

            // Ngu Hanh
            // TODO: Add detailed Ngu Hanh check if needed
        }

        // Sao Tot
        // ...

        // Kết luận
        if ($res['diem_so'] > 6) $res['ket_luan'] = "Khai Trương Đại Cát";
        elseif ($res['diem_so'] > 0) $res['ket_luan'] = "Khá";
        else $res['ket_luan'] = "Không Tốt";

        return $res;
    }

    // --- 2. LOGIC XEM LÀM NHÀ (Kèm Hóa Giải) ---
    private function xemLamNha($res) {
        // 1. Check hạn Nam (Yearly Limits for Owner)
        if ($this->muonTuoiTool) {
            $checkUser = $this->muonTuoiTool->analyzeCandidate($this->userYear, $this->userYear);

            if (!$checkUser['is_eligible']) {
                $res['diem_so'] = 0; // Force low score for year

                $bad = [];
                if ($checkUser['bad_factors']['kim_lau']) $bad[] = "Kim Lâu";
                if ($checkUser['bad_factors']['hoang_oc']) $bad[] = "Hoang Ốc";
                if ($checkUser['bad_factors']['tam_tai']) $bad[] = "Tam Tai";
                if ($checkUser['bad_factors']['thai_tue']) $bad[] = "Thái Tuế";

                $badStr = implode(', ', $bad);
                $res['binh_giai'][] = "Tuổi {$checkUser['age']} không đẹp để làm nhà năm nay: Phạm {$badStr}. Nên mượn tuổi.";
                $res['ket_luan'] = "Tuổi Xấu Động Thổ";

                // Hóa giải
                $candidates = $this->muonTuoiTool->timNguoiMuonTuoi($this->userYear);
                $goiY = [];
                foreach(array_slice($candidates, 0, 10) as $cand) {
                    $goiY[] = "{$cand['can_chi']} ({$cand['birth_year']})";
                }
                $res['hoa_giai'] = [
                    'phuong_phap' => 'Mượn tuổi động thổ',
                    'danh_sach_goi_y' => $goiY
                ];
            } else {
                $res['binh_giai'][] = "Tuổi {$checkUser['age']} đẹp, không phạm hạn lớn. Có thể tự đứng tên.";

                // Check Day
                $dayChi = $this->lunarDate['can_chi']['chi'];
                $userChi = $this->getCanChi($this->userYear)['chi'];

                if ($this->isTamHop($userChi, $dayChi)) {
                    $res['binh_giai'][] = "Ngày Tam Hợp, vượng khí cho xây cất.";
                    $res['diem_so'] += 4;
                }

                $res['ket_luan'] = ($res['diem_so'] >= 7) ? "Ngày Đại Cát Động Thổ" : "Ngày Bình Thường";
            }
        }

        return $res;
    }

    // --- 3. LOGIC XEM CƯỚI HỎI ---
    private function xemCuoiHoi($res) {
        // Determine Bride's Age
        $brideYear = ($this->userGender == 0) ? $this->userYear : $this->partnerYear;

        if ($brideYear) {
            // Use Solar Year to align with general expectation (e.g. Planning for "Year 2026")
            // This matches the Building logic which uses Solar Year for Age.
            $age = $this->solarDate['y'] - $brideYear + 1;
            $rem = $age % 9;

            if (in_array($rem, [1, 3, 6, 8])) {
                $res['binh_giai'][] = "CẢNH BÁO: Tuổi cô dâu ($age) phạm Kim Lâu. Không nên cưới năm nay.";
                $res['diem_so'] -= 5;
            } else {
                $res['binh_giai'][] = "Tuổi cô dâu đẹp, không phạm Kim Lâu.";
                $res['diem_so'] += 2;
            }
        } else {
            $res['binh_giai'][] = "Lưu ý: Chưa cung cấp năm sinh cô dâu để tính Kim Lâu.";
        }

        // Check Day Specifics for Wedding (Thien Hy, etc - placeholders for now)
        // Avoid bad days already checked in checkBachKy

        if ($res['diem_so'] >= 7) $res['ket_luan'] = "Ngày Đại Cát cho Cưới Hỏi";
        elseif ($res['diem_so'] >= 5) $res['ket_luan'] = "Ngày Có Thể Cưới (Trung Bình)";
        else $res['ket_luan'] = "Không Tốt cho Cưới Hỏi";

        return $res;
    }

    // --- 4. LOGIC XEM MA CHAY (TRUNG TANG) ---
    private function xemMaChay($res) {
        // Simple day check for Funeral
        $userChi = $this->getCanChi($this->userYear)['chi'];
        $dayChi = $this->lunarDate['can_chi']['chi'];

        if ($userChi == $dayChi) {
            $res['binh_giai'][] = "ĐẠI KỴ: Ngày Trùng Tang (Ngày trùng tuổi người mất).";
            $res['diem_so'] = -100;
        }

        // Check Sat Chu Am (Specifically for Funeral)
        // Sat Chu Duong is for Living (Wedding, Building). Sat Chu Am is for Dead.
        // Assuming we have Sat Chu Am data. If not, use generic bad day warning.

        return $res;
    }

    /**
     * Tinh Trung Tang Full (Cho Tab Tang Le)
     */
    public function xemTrungTang($deceasedInfo, $headYear, $relatives) {
        $age = $deceasedInfo['age'];
        $gender = $deceasedInfo['gender'];
        $deathTime = $deceasedInfo['death_time'];

        $month = $deathTime['m'];
        $day = $deathTime['d'];
        $hourChi = $deathTime['h_chi'];

        // 1. Calculate Palaces (Cung) - Palm Method
        // Nam khoi Dan (2) thuan. Nu khoi Than (8) nghich.
        $startPos = ($gender == 1) ? 2 : 8;
        $direction = ($gender == 1) ? 1 : -1;

        // Count Age (10, 20...)
        // Step 1: 10s. Start at $startPos (10). 20 at next...
        $tens = floor($age / 10);
        $units = $age % 10;

        // Move tens
        $pos = $startPos;
        for ($i=1; $i<$tens; $i++) {
             $pos = $this->moveStep($pos, $direction);
        }
        $posTens = $pos;

        // Move units (from tens pos)
        // 11 is next step.
        // If age=10 (tens=1, units=0), pos is startPos.
        // If age=11 (tens=1, units=1), pos is startPos + 1 step.
        for ($i=0; $i<$units; $i++) { // 1st unit is 1 step away? Or 10->11 is 1 step.
             // Usually: 10 at A. 11 at B.
             // If units=1 (11), loop once. Correct.
             // Exception: If tens=0 (Age < 10). Start at 1 year old?
             // Usually start at StartPos for 1 year old? Or 10?
             // If < 10, start at StartPos (1).
             if ($tens == 0 && $i == 0) $pos = $startPos; // Reset start for units only
             else $pos = $this->moveStep($pos, $direction);
        }
        $cungTuoi = $pos;

        // Month (From Tuoi)
        $pos = $cungTuoi;
        for ($i=1; $i<$month; $i++) {
            $pos = $this->moveStep($pos, $direction);
        }
        $cungThang = $pos;

        // Day (From Thang)
        $pos = $cungThang;
        for ($i=1; $i<$day; $i++) {
            $pos = $this->moveStep($pos, $direction);
        }
        $cungNgay = $pos;

        // Hour (From Ngay)
        // Ty is step 1.
        $stepsH = $hourChi + 1;
        $pos = $cungNgay;
        for ($i=1; $i<$stepsH; $i++) {
            $pos = $this->moveStep($pos, $direction);
        }
        $cungGio = $pos;

        // 2. Evaluate
        $evaluate = function($idx) {
            // Nhap Mo: Thin(4), Tuat(10), Suu(1), Mui(7)
            if (in_array($idx, [4, 10, 1, 7])) return ['type'=>'nhap_mo', 'text'=>'Nhập Mộ (Tốt)'];
            // Thien Di: Ty(0), Ngo(6), Dan(2), Than(8) ? Or Ty(0), Hoi(11), Dan(2), Than(8)?
            // User requested: "Dan Than Ty Hoi - Thien Di"
            if (in_array($idx, [2, 8, 5, 11])) return ['type'=>'thien_di', 'text'=>'Thiên Di (Bình thường)'];
            // Trung Tang: Ty Ngo Mao Dau? No, Ty(Snake) is Thien Di.
            // Let's check keys: 0=Ty(Rat), 5=Ty(Snake).
            // User: "Dan Than Ty Hoi". Ty(Snake) is 5. Hoi is 11. Dan is 2. Than is 8.
            // So [2, 8, 5, 11] is Thien Di.

            // "Ty Ngo Mao Dau - Trung Tang". Ty(Rat) is 0. Ngo is 6. Mao is 3. Dau is 9.
            if (in_array($idx, [0, 6, 3, 9])) return ['type'=>'trung_tang', 'text'=>'Trùng Tang (Xấu)'];

            return ['type'=>'unknown', 'text'=>'?'];
        };

        $resTuoi = $evaluate($cungTuoi);
        $resThang = $evaluate($cungThang);
        $resNgay = $evaluate($cungNgay);
        $resGio = $evaluate($cungGio);

        $countTT = 0; $countNM = 0;
        foreach ([$resTuoi, $resThang, $resNgay, $resGio] as $r) {
            if ($r['type'] == 'trung_tang') $countTT++;
            if ($r['type'] == 'nhap_mo') $countNM++;
        }

        if ($countTT > 0 && $countNM == 0) {
            $conclusion = "ĐẠI HUNG: Phạm Trùng Tang ($countTT cung).";
            $alertClass = "danger";
        } elseif ($countNM > 0) {
            $conclusion = "ĐẠI CÁT: Được Nhập Mộ ($countNM cung).";
            $alertClass = "success";
        } else {
            $conclusion = "Thiên Di: Ra đi nhẹ nhàng.";
            $alertClass = "warning";
        }

        // 3. Conflicts
        $conflicts = [];
        if ($headYear > 0) {
            $dChi = ($deceasedInfo['year'] - 4) % 12;
            $hChi = ($headYear - 4) % 12;
            if (abs($dChi - $hChi) == 6) $conflicts[] = "Tuổi Trưởng nam xung với người mất.";
        }
        // Relatives
        if (!empty($relatives)) {
            $dChi = ($deceasedInfo['year'] - 4) % 12;
            foreach ($relatives as $rYear) {
                $rChi = ($rYear - 4) % 12;
                if (abs($dChi - $rChi) == 6) $conflicts[] = "Người thân ($rYear) xung với người mất.";
            }
        }

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

    // --- TRA CỨU & GỢI Ý ---

    public function goiYNgayTotTrongThang($month, $year, $purpose) {
        $listDays = [];
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($d = 1; $d <= $daysInMonth; $d++) {
            $date = "$year-$month-$d";
            $app = new TuViXemNgay($this->userYear, $this->userGender, $date);
            $analysis = $app->phanTichNgay($purpose);

            if ($analysis['diem_so'] > 0 && !$analysis['is_bad_day']) {
                $jd = LunarCalendar::jdn($d, $month, $year);
                $chiDay = ($jd + 1) % 12;
                $hoangDao = LunarCalendar::getNgayHoangDao($chiDay, $app->lunarDate['month']);
                $gioTotList = LunarCalendar::getGioHoangDao($chiDay);
                $gioTotStr = [];
                foreach ($gioTotList as $g) $gioTotStr[] = $g['name'];

                $listDays[] = [
                    'day' => $d,
                    'lunar_day' => $app->lunarDate['day'],
                    'lunar_month' => $app->lunarDate['month'],
                    'can_chi' => $app->lunarDate['can_chi']['name'],
                    'hoang_dao' => (strpos($hoangDao, 'Hoàng Đạo') !== false) ? 'Có' : '-',
                    'truc' => LunarCalendar::getTruc($d, $month, $year),
                    'gio_tot' => implode(', ', $gioTotStr),
                    'diem' => $analysis['diem_so'],
                    'ly_do' => implode('; ', $analysis['binh_giai'])
                ];
            }
        }
        return $listDays;
    }

    // --- Helpers ---
    private function checkBachKy(&$res) {
        $d = $this->lunarDate['day'];
        $m = $this->lunarDate['month'];
        $chi = $this->lunarDate['can_chi']['chi'];

        if (in_array($d, self::BAD_DAYS['TAM_NUONG'])) {
            $res['binh_giai'][] = "Ngày Tam Nương (Xấu).";
            $res['diem_so'] -= 4;
        }
        if (in_array($d, self::BAD_DAYS['NGUYET_KY'])) {
            $res['binh_giai'][] = "Ngày Nguyệt Kỵ (Xấu).";
            $res['diem_so'] -= 4;
        }
        if (isset(self::BAD_DAYS['THO_TU'][$m]) && self::BAD_DAYS['THO_TU'][$m] == $chi) {
            $res['binh_giai'][] = "Ngày Thọ Tử (Đại hung).";
            $res['diem_so'] = -10; // Block
            return true;
        }
        if (isset(self::BAD_DAYS['SAT_CHU_DUONG'][$m]) && self::BAD_DAYS['SAT_CHU_DUONG'][$m] == $chi) {
            $res['binh_giai'][] = "Ngày Sát Chủ Dương (Xấu).";
            $res['diem_so'] -= 5;
        }
        return false;
    }

    private function getCanChi($year) {
        return ['can' => ($year-4)%10, 'chi' => ($year-4)%12];
    }

    private function isXung($chi1, $chi2) {
        return abs($chi1 - $chi2) == 6;
    }

    private function isTamHop($chi1, $chi2) {
        $diff = abs($chi1 - $chi2);
        return ($diff == 4 || $diff == 8);
    }

    private function checkSinhKhac($hanhNgay, $hanhNguoi) {
        // Simplified scoring
        return ['score' => 0, 'msg' => 'Bình hòa'];
    }

    private function hasGoodStars($dayInfo, $starCodes) {
        return false;
    }

    private function getThongTinCoBan() {
        return "Ngày " . $this->lunarDate['day'] . " tháng " . $this->lunarDate['month'] . " năm " . $this->lunarDate['year'];
    }

    private function convertSolarToLunar($d, $m, $y) {
        $lunar = LunarCalendar::convertSolar2Lunar($d, $m, $y);
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

    private function moveStep($pos, $direction) {
        $pos += $direction;
        if ($pos > 11) $pos = 0;
        if ($pos < 0) $pos = 11;
        return $pos;
    }
}
