<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class NameAnalysis {

    private static $HAN_VIET_DICT = [];

    // 81 Linh So Meanings
    private static $MEANINGS_81 = array(
        1 => 'Vạn tượng khởi đầu, đại triển hồng đồ (Đại Cát)',
        2 => 'Một thịnh một suy, bấp bênh (Hung)',
        3 => 'Danh lợi song thu, thành công (Đại Cát)',
        4 => 'Tiền đồ gai góc, khổ nạn (Hung)',
        5 => 'Làm ăn phát đạt, trường thọ (Đại Cát)',
        6 => 'Trời ban số phận, an nhàn (Cát)',
        7 => 'Cương nghị, quyết đoán (Cát)',
        8 => 'Kiên trì vượt khó (Cát)',
        9 => 'Bần cùng, bất hạnh (Hung)',
        10 => 'Vạn sự kết thúc, u tối (Đại Hung)',
        11 => 'Gia vận tốt lành, vinh hoa (Đại Cát)',
        12 => 'Bạc nhược, thất bại (Hung)',
        13 => 'Tài chí hơn người (Đại Cát)',
        14 => 'Tan vỡ, bi ai (Hung)',
        15 => 'Phúc thọ song toàn (Đại Cát)',
        16 => 'Quý nhân phù trợ (Đại Cát)',
        17 => 'Vượt qua khó khăn (Cát)',
        18 => 'Thành công, danh lợi (Cát)',
        19 => 'Đa nạn, trắc trở (Hung)',
        20 => 'Sự nghiệp lụi bại (Đại Hung)',
        21 => 'Minh nguyệt quay đầu (Đại Cát)',
        22 => 'Thu thủy phùng sương, tài nghệ không gặp thời (Hung)',
        23 => 'Mặt trời mọc (Đại Cát)',
        24 => 'Tay trắng làm nên (Đại Cát)',
        25 => 'Thông minh, kiêu ngạo (Cát)',
        26 => 'Biến quái kỳ lạ, anh hùng hào kiệt (Bán Cát Bán Hung)',
        27 => 'Dục vọng vô tận, tự rước lấy họa (Hung)',
        28 => 'Hào kiệt lận đận, nạn tai (Hung)',
        29 => 'Dục vọng không đáy (Bán Hung)',
        30 => 'Phù trầm bất định, cát hung khó lường (Bán Hung)',
        31 => 'Tài dũng song toàn (Đại Cát)',
        32 => 'Cầu được ước thấy (Đại Cát)',
        33 => 'Quyền uy (Đại Cát)',
        34 => 'Gia đạo tan vỡ (Hung)',
        35 => 'Ôn hòa, bình an (Cát)',
        36 => 'Sóng gió trùng trùng (Hung)',
        37 => 'Hào khí (Đại Cát)',
        38 => 'Nghệ thuật, kỹ nghệ (Cát)',
        39 => 'Phú quý (Đại Cát)',
        40 => 'Lùi bước, an phận (Hung)',
        41 => 'Đức vọng (Đại Cát)',
        42 => 'Bác học đa tài (Cát)',
        43 => 'Bề ngoài hào nhoáng (Hung)',
        44 => 'Buồn phiền, lo lắng (Hung)',
        45 => 'Thuận buồm xuôi gió (Đại Cát)',
        46 => 'La lưới giăng mắc (Hung)',
        47 => 'Hoa khai nở nhụy (Đại Cát)',
        48 => 'Cố vấn, thầy giỏi (Đại Cát)',
        49 => 'Biến hóa khôn lường (Bán Cát)',
        50 => 'Một thành một bại (Bán Hung)',
        51 => 'Thịnh suy xen kẽ (Bán Cát)',
        52 => 'Nhìn xa trông rộng (Cát)',
        53 => 'Vẻ ngoài tốt đẹp (Bán Cát)',
        54 => 'Hiểm họa rình rập (Hung)',
        55 => 'Bề ngoài ôn hòa (Bán Cát)',
        56 => 'Nỗ lực không thành (Hung)',
        57 => 'Sau mưa trời sáng (Cát)',
        58 => 'Khổ tận cam lai (Bán Cát)',
        59 => 'Mất phương hướng (Hung)',
        60 => 'Đen tối, vô định (Hung)',
        61 => 'Danh lợi song thu (Cát)',
        62 => 'Suy bại, trắc trở (Hung)',
        63 => 'Phú quý vinh hoa (Đại Cát)',
        64 => 'Cốt nhục chia lìa (Hung)',
        65 => 'Phú quý trường thọ (Đại Cát)',
        66 => 'Trong ngoài bất nhất (Hung)',
        67 => 'Đường lợi thông suốt (Cát)',
        68 => 'Lập nghiệp hưng gia (Đại Cát)',
        69 => 'Động dao kéo (Hung)',
        70 => 'Vô vọng, trống rỗng (Hung)',
        71 => 'Khổ trước sướng sau (Bán Cát)',
        72 => 'Suối vàng gãy cánh (Hung)',
        73 => 'Chí cao tài mọn (Bán Hung)',
        74 => 'Trí tuệ bị che lấp (Hung)',
        75 => 'Thủ thường an phận (Bán Cát)',
        76 => 'Cốt nhục phân ly (Hung)',
        77 => 'Khổ tận cam lai (Bán Cát)',
        78 => 'Vãn cảnh thê lương (Bán Hung)',
        79 => 'Hồi quang phản chiếu (Hung)',
        80 => 'Thực quy về hư (Hung)',
        81 => 'Hoàn bản quy nguyên, vạn sự như ý (Đại Cát)'
    );

    public static function analyze($ho, $tenDem, $ten, $year) {
        self::loadDictionary();

        // 1. Parse Input
        $partsHo = self::splitWords($ho);
        $partsDem = self::splitWords($tenDem);
        $partsTen = self::splitWords($ten);

        $hoDetails = self::getWordDetails($partsHo);
        $demDetails = self::getWordDetails($partsDem);
        $tenDetails = self::getWordDetails($partsTen);

        // 2. Calculate Strokes
        $sHo = self::sumStrokes($hoDetails);
        $sDem = self::sumStrokes($demDetails);
        $sTen = self::sumStrokes($tenDetails);

        $countHo = count($hoDetails);
        $countDem = count($demDetails);
        $countTen = count($tenDetails);

        // 3. Calculate 5 Grids (Ngu Cach)

        $thien = 0; $dia = 0; $nhan = 0; $ngoai = 0; $tong = 0;

        // Total Strokes (Tong Cach)
        $tong = $sHo + $sDem + $sTen;

        // Thien Cach
        if ($countHo == 1) {
            $thien = $sHo + 1;
        } else {
            $thien = $sHo;
        }

        // Dia Cach
        if (($countDem + $countTen) == 1) {
             $dia = ($sDem + $sTen) + 1;
        } else {
             $dia = $sDem + $sTen;
        }

        // Nhan Cach
        $lastHoStroke = end($hoDetails)['strokes'];
        $firstNameStroke = 0;
        if ($countDem > 0) {
            $firstNameStroke = reset($demDetails)['strokes'];
        } else {
            $firstNameStroke = reset($tenDetails)['strokes'];
        }
        $nhan = $lastHoStroke + $firstNameStroke;

        // Ngoai Cach
        $firstHoStroke = reset($hoDetails)['strokes'];
        $lastNameStroke = end($tenDetails)['strokes'];

        if ($countHo == 1 && ($countDem + $countTen) == 1) {
            $ngoai = 2;
        } elseif ($countHo == 1 && ($countDem + $countTen) > 1) {
            $ngoai = $lastNameStroke + 1;
        } elseif ($countHo > 1 && ($countDem + $countTen) == 1) {
            $ngoai = $firstHoStroke + 1;
        } else {
            $ngoai = $firstHoStroke + $lastNameStroke;
        }

        // 4. Evaluate Grids
        $nguCach = [
            'thien' => self::evaluateCach('Thiên Cách', $thien),
            'nhan' => self::evaluateCach('Nhân Cách', $nhan),
            'dia' => self::evaluateCach('Địa Cách', $dia),
            'ngoai' => self::evaluateCach('Ngoại Cách', $ngoai),
            'tong' => self::evaluateCach('Tổng Cách', $tong)
        ];

        // 5. Am Duong Analysis
        $amDuong = self::analyzeAmDuong($hoDetails, $demDetails, $tenDetails);

        // 6. Tam Tai Analysis (Three Talents)
        $tamTai = self::analyzeTamTai($nguCach['thien']['element_code'], $nguCach['nhan']['element_code'], $nguCach['dia']['element_code']);

        // Construct Result
        return array(
            'input' => "$ho $tenDem $ten",
            'parts' => [
                'ho' => $hoDetails,
                'dem' => $demDetails,
                'ten' => $tenDetails
            ],
            'strokes' => [
                'ho' => $sHo,
                'dem' => $sDem,
                'ten' => $sTen,
                'total' => $tong
            ],
            'ngu_cach' => $nguCach,
            'am_duong' => $amDuong,
            'tam_tai' => $tamTai
        );
    }

    private static function loadDictionary() {
        if (empty(self::$HAN_VIET_DICT)) {
            $file = NV_ROOTDIR . '/modules/huyen-hoc/data/han_viet.json';
            if (file_exists($file)) {
                $content = file_get_contents($file);
                $data = json_decode($content, true);
                if (is_array($data)) {
                    self::$HAN_VIET_DICT = $data;
                }
            }
        }
    }

    private static function splitWords($str) {
        $str = trim($str);
        if (empty($str)) return [];
        return preg_split('/\s+/', $str);
    }

    private static function getWordDetails($words) {
        $details = [];
        foreach ($words as $word) {
            $keyExact = mb_strtolower($word);

            // Try 1: Exact
            $info = isset(self::$HAN_VIET_DICT[$keyExact]) ? self::$HAN_VIET_DICT[$keyExact] : null;

            // Try 2: Normalized (change_alias or simple strip)
            if (!$info) {
                $keyNorm = (function_exists('change_alias')) ? change_alias($word) : self::slugify($word);
                $keyNorm = preg_replace('/[^a-z0-9]/', '', $keyNorm);
                if (isset(self::$HAN_VIET_DICT[$keyNorm])) {
                    $info = self::$HAN_VIET_DICT[$keyNorm];
                }
            }

            // Try 3: Normalized Lowercase of Exact (Manual accent stripping if change_alias failed or unavailable)
            if (!$info) {
                $keyStrip = self::stripAccents($keyExact);
                if (isset(self::$HAN_VIET_DICT[$keyStrip])) {
                    $info = self::$HAN_VIET_DICT[$keyStrip];
                }
            }

            if ($info) {
                $details[] = [
                    'word' => $word,
                    'han' => $info['han'],
                    'strokes' => $info['strokes'],
                    'meaning' => $info['meaning'],
                    'element' => isset($info['element']) ? $info['element'] : ''
                ];
            } else {
                // Fallback
                $strokes = mb_strlen($word) * 2;
                if ($strokes < 2) $strokes = 2;

                $details[] = [
                    'word' => $word,
                    'han' => '?',
                    'strokes' => $strokes,
                    'meaning' => 'Chưa có dữ liệu',
                    'element' => ''
                ];
            }
        }
        return $details;
    }

    private static function sumStrokes($details) {
        $sum = 0;
        foreach ($details as $d) {
            $sum += $d['strokes'];
        }
        return $sum;
    }

    private static function evaluateCach($name, $val) {
        $val81 = $val;
        while ($val81 > 81) {
            $val81 -= 80;
        }
        if ($val81 == 0) $val81 = 81;

        $meaning = isset(self::$MEANINGS_81[$val81]) ? self::$MEANINGS_81[$val81] : 'Bình thường (Bán Cát)';

        // Ngu Hanh Code: 1=Moc, 2=Moc, 3=Hoa, 4=Hoa, 5=Tho, 6=Tho, 7=Kim, 8=Kim, 9=Thuy, 0=Thuy
        $last = substr($val, -1);
        $el = '';
        $elCode = ''; // moc, hoa, tho, kim, thuy

        if (in_array($last, [1,2])) { $el = 'Mộc'; $elCode = 'moc'; }
        elseif (in_array($last, [3,4])) { $el = 'Hỏa'; $elCode = 'hoa'; }
        elseif (in_array($last, [5,6])) { $el = 'Thổ'; $elCode = 'tho'; }
        elseif (in_array($last, [7,8])) { $el = 'Kim'; $elCode = 'kim'; }
        else { $el = 'Thủy'; $elCode = 'thuy'; }

        $scoreClass = 'text-warning';
        if (strpos($meaning, 'Đại Cát') !== false) $scoreClass = 'text-success bold';
        elseif (strpos($meaning, '(Cát)') !== false) $scoreClass = 'text-info';
        elseif (strpos($meaning, 'Đại Hung') !== false) $scoreClass = 'text-danger bold';
        elseif (strpos($meaning, '(Hung)') !== false) $scoreClass = 'text-danger';

        return [
            'name' => $name,
            'val' => $val,
            'meaning' => $meaning,
            'element' => $el,
            'element_code' => $elCode,
            'class' => $scoreClass
        ];
    }

    private static function analyzeAmDuong($ho, $dem, $ten) {
        $seq = [];
        $seqText = [];
        $balance = 0; // Close to 0 is good

        $all = array_merge($ho, $dem, $ten);
        foreach ($all as $word) {
            $s = $word['strokes'];
            if ($s % 2 == 0) {
                $seq[] = 'Âm';
                $seqText[] = '<span class="text-primary">Âm</span>';
                $balance--;
            } else {
                $seq[] = 'Dương';
                $seqText[] = '<span class="text-danger">Dương</span>';
                $balance++;
            }
        }

        $msg = '';
        $absBal = abs($balance);
        if ($absBal == 0 || $absBal == 1) {
            $msg = 'Cân bằng Âm Dương rất tốt (Cát)';
            $class = 'text-success bold';
        } elseif ($absBal == count($all)) {
            $msg = 'Thuần ' . ($balance > 0 ? 'Dương' : 'Âm') . ' (Hung) - Nên tránh';
            $class = 'text-danger bold';
        } else {
            $msg = 'Tương đối cân bằng (Bình thường)';
            $class = 'text-info';
        }

        return [
            'sequence' => implode(' - ', $seqText),
            'message' => $msg,
            'class' => $class
        ];
    }

    private static function analyzeTamTai($thien, $nhan, $dia) {
        // Elements: kim, moc, thuy, hoa, tho
        // Relation function
        $rel = function($from, $to) {
            $pairs = [
                'kim' => ['thuy' => 'sinh', 'moc' => 'khac'],
                'moc' => ['hoa' => 'sinh', 'tho' => 'khac'],
                'thuy' => ['moc' => 'sinh', 'hoa' => 'khac'],
                'hoa' => ['tho' => 'sinh', 'kim' => 'khac'],
                'tho' => ['kim' => 'sinh', 'thuy' => 'khac']
            ];
            if ($from == $to) return 'hoa'; // Ty Hoa
            if (isset($pairs[$from][$to])) return $pairs[$from][$to]; // Sinh/Khac

            // Reverse check for Sinh/Khac (e.g. from is child of to)
            // But standard Tam Tai checks Thien -> Nhan and Nhan -> Dia (One way flow or interactive?)
            // Usually we interpret the RELATIONSHIP.
            // If From generates To (Sinh Nhap - Good for To).
            // If From controls To (Khac Nhap - Bad for To).

            // Check if To generates From (Sinh Xuat - Bad for From)
            if (isset($pairs[$to][$from]) && $pairs[$to][$from] == 'sinh') return 'duoc_sinh'; // To sinh From
            if (isset($pairs[$to][$from]) && $pairs[$to][$from] == 'khac') return 'bi_khac'; // To khac From (Same as Khac Nhap above? No.)
            // Logic:
            // A sinh B: A loses, B gains.
            // A khac B: A dominates, B hurt.

            return 'binh_hoa';
        };

        $t_n = $rel($thien, $nhan); // Thien vs Nhan
        $n_d = $rel($nhan, $dia); // Nhan vs Dia

        // Interpretation
        // Thien -> Nhan: Success/Superior Support
        $msgTN = '';
        $scoreTN = 0;
        if ($t_n == 'sinh') { $msgTN = 'Thiên sinh Nhân: Được trời phú, quý nhân giúp đỡ, thành công thuận lợi (Đại Cát).'; $scoreTN = 2; }
        elseif ($t_n == 'hoa') { $msgTN = 'Thiên Nhân tỷ hòa: Quan hệ hòa thuận, bình ổn (Cát).'; $scoreTN = 1; }
        elseif ($t_n == 'duoc_sinh') { $msgTN = 'Nhân sinh Thiên: Phải nỗ lực nhiều, vất vả mới thành công (Bán Cát).'; $scoreTN = 0; }
        elseif ($t_n == 'khac') { $msgTN = 'Thiên khắc Nhân: Bị cấp trên chèn ép, hay gặp tai họa, ốm đau (Hung).'; $scoreTN = -2; }
        elseif ($t_n == 'bi_khac') { $msgTN = 'Nhân khắc Thiên: Chống đối cấp trên, không phục tùng, dễ thất bại (Hung).'; $scoreTN = -2; }
        else { $msgTN = 'Bình thường.'; }

        // Nhan -> Dia: Foundation/Spouse/Children
        $msgND = '';
        $scoreND = 0;
        if ($n_d == 'sinh') { $msgND = 'Nhân sinh Địa: Gia đình hòa thuận, con cái ngoan ngoãn, nền tảng vững chắc (Đại Cát).'; $scoreND = 2; }
        elseif ($n_d == 'hoa') { $msgND = 'Nhân Địa tỷ hòa: Vợ chồng hòa thuận (Cát).'; $scoreND = 1; }
        elseif ($n_d == 'duoc_sinh') { $msgND = 'Địa sinh Nhân: Được gia đình hỗ trợ, vợ/chồng giúp đỡ (Cát).'; $scoreND = 1; }
        elseif ($n_d == 'khac') { $msgND = 'Nhân khắc Địa: Khắc vợ/chồng/con cái, gia đạo bất an (Hung).'; $scoreND = -2; }
        elseif ($n_d == 'bi_khac') { $msgND = 'Địa khắc Nhân: Bị gia đình lấn lướt, nền tảng lung lay (Hung).'; $scoreND = -2; }

        $totalScore = $scoreTN + $scoreND;
        $finalMsg = '';
        $finalClass = '';
        if ($totalScore >= 3) { $finalMsg = 'Đại Cát'; $finalClass = 'text-success bold'; }
        elseif ($totalScore > 0) { $finalMsg = 'Cát'; $finalClass = 'text-info'; }
        elseif ($totalScore > -2) { $finalMsg = 'Bình Hòa'; $finalClass = 'text-warning'; }
        else { $finalMsg = 'Hung'; $finalClass = 'text-danger bold'; }

        return [
            'thien_nhan' => $msgTN,
            'nhan_dia' => $msgND,
            'score' => $totalScore,
            'final' => $finalMsg,
            'class' => $finalClass,
            'thien_el' => $thien,
            'nhan_el' => $nhan,
            'dia_el' => $dia
        ];
    }

    // Helpers
    private static function slugify($text) {
        // Simple manual slugify if change_alias not avail
        $text = mb_strtolower($text);
        $text = self::stripAccents($text);
        return preg_replace('/[^a-z0-9]/', '', $text);
    }

    private static function stripAccents($str) {
        $str = preg_replace("/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/", 'a', $str);
        $str = preg_replace("/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/", 'e', $str);
        $str = preg_replace("/(ì|í|ị|ỉ|ĩ)/", 'i', $str);
        $str = preg_replace("/(ò|ó|ọ|ỏ|õ|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/", 'o', $str);
        $str = preg_replace("/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/", 'u', $str);
        $str = preg_replace("/(ỳ|ý|ỵ|ỷ|ỹ)/", 'y', $str);
        $str = preg_replace("/(đ)/", 'd', $str);
        return $str;
    }
}
