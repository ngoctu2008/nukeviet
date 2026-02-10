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

        $fullDetails = array_merge($hoDetails, $demDetails, $tenDetails);

        // 2. Calculate Strokes
        $sHo = self::sumStrokes($hoDetails);
        $sDem = self::sumStrokes($demDetails);
        $sTen = self::sumStrokes($tenDetails);

        $countHo = count($hoDetails);
        $countDem = count($demDetails);
        $countTen = count($tenDetails);

        // 3. Calculate 5 Grids (Ngu Cach)
        // Standard Tu Vi Nam Hoc logic based on number of words in Surname and Name

        $thien = 0; $dia = 0; $nhan = 0; $ngoai = 0; $tong = 0;

        // Total Strokes (Tong Cach)
        $tong = $sHo + $sDem + $sTen;

        // Thien Cach
        if ($countHo == 1) {
            $thien = $sHo + 1;
        } else {
            $thien = $sHo; // Double surname: Sum of surname strokes
        }

        // Dia Cach
        // Dia = Name + Middle Name strokes (+ 1 if single char name with no middle)
        // If (Dem + Ten) count == 1, Dia = sTen + 1.
        // Else Dia = sDem + sTen.
        if (($countDem + $countTen) == 1) {
             $dia = ($sDem + $sTen) + 1;
        } else {
             $dia = $sDem + $sTen;
        }

        // Nhan Cach
        // Usually Last Char of Surname + First Char of Name (Middle or First)
        $lastHoStroke = end($hoDetails)['strokes'];

        // First Char of Name (Check Dem first, then Ten)
        $firstNameStroke = 0;
        if ($countDem > 0) {
            $firstNameStroke = reset($demDetails)['strokes'];
        } else {
            $firstNameStroke = reset($tenDetails)['strokes'];
        }

        $nhan = $lastHoStroke + $firstNameStroke;

        // Ngoai Cach
        // Rule: (Total Strokes + Adjust) - Nhan Cach
        // Common formula:
        // Single Ho + Single Ten: Ngoai = 1+1=2. (Tong - Nhan + 2)
        // Single Ho + Multi Ten: Ngoai = Last Name Stroke + 1. (Tong - Nhan + 1)
        // Double Ho + Single Ten: Ngoai = First Surname Stroke + 1. (Tong - Nhan + 1)
        // Double Ho + Multi Ten: Ngoai = First Surname Stroke + Last Name Stroke. (Tong - Nhan)
        // Let's use the explicit formulas based on word counts.

        $firstHoStroke = reset($hoDetails)['strokes'];
        $lastNameStroke = end($tenDetails)['strokes'];

        if ($countHo == 1 && ($countDem + $countTen) == 1) {
            $ngoai = 2;
        } elseif ($countHo == 1 && ($countDem + $countTen) > 1) {
            // Single Surname, Multi Name
            // Ngoai = Last Name Char + 1 ? No, usually (Tong - Nhan) + 1.
            // Let's check: Tong = S_Ho + S_Dem + S_Ten. Nhan = S_Ho + S_Dem. (If Name=Dem+Ten)
            // Wait, Nhan = LastHo + FirstDem.
            // If Single Ho (H1), Multi Name (D1, T1).
            // Tong = H1 + D1 + T1.
            // Nhan = H1 + D1.
            // Ngoai = T1 + 1.
            // (Tong - Nhan) + 1 = (H1+D1+T1) - (H1+D1) + 1 = T1 + 1. Correct.
            $ngoai = $lastNameStroke + 1;
        } elseif ($countHo > 1 && ($countDem + $countTen) == 1) {
            // Double Surname, Single Name.
            // Tong = H1 + H2 + N1.
            // Nhan = H2 + N1.
            // Ngoai = H1 + 1.
            // (Tong - Nhan) + 1 = (H1+H2+N1) - (H2+N1) + 1 = H1 + 1. Correct.
            $ngoai = $firstHoStroke + 1;
        } else {
            // Double Surname, Multi Name.
            // Tong = H1 + H2 + D1 + T1.
            // Nhan = H2 + D1.
            // Ngoai = H1 + T1.
            // (Tong - Nhan) = (H1+H2+D1+T1) - (H2+D1) = H1 + T1. Correct.
            $ngoai = $firstHoStroke + $lastNameStroke;
        }


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
            'ngu_cach' => [
                'thien' => self::evaluateCach('Thiên Cách', $thien),
                'nhan' => self::evaluateCach('Nhân Cách', $nhan),
                'dia' => self::evaluateCach('Địa Cách', $dia),
                'ngoai' => self::evaluateCach('Ngoại Cách', $ngoai),
                'tong' => self::evaluateCach('Tổng Cách', $tong)
            ]
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
            // Try exact match (lowercase) first (for accented differentiation)
            $keyExact = mb_strtolower($word);
            // Try normalized match (unaccented) second
            $keyNorm = (function_exists('change_alias')) ? change_alias($word) : $keyExact;
            $keyNorm = preg_replace('/[^a-z0-9]/', '', $keyNorm);

            $info = null;
            if (isset(self::$HAN_VIET_DICT[$keyExact])) {
                $info = self::$HAN_VIET_DICT[$keyExact];
            } elseif (isset(self::$HAN_VIET_DICT[$keyNorm])) {
                $info = self::$HAN_VIET_DICT[$keyNorm];
            }

            if ($info) {
                $details[] = [
                    'word' => $word,
                    'han' => $info['han'],
                    'strokes' => $info['strokes'],
                    'meaning' => $info['meaning']
                ];
            } else {
                // Fallback
                $strokes = mb_strlen($word) * 2; // Rough estimate
                if ($strokes < 2) $strokes = 2;

                $details[] = [
                    'word' => $word,
                    'han' => '?',
                    'strokes' => $strokes,
                    'meaning' => 'Chưa có dữ liệu'
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
        // Fix standard limit logic
        $val81 = $val;
        while ($val81 > 81) {
            $val81 -= 80;
        }
        if ($val81 == 0) $val81 = 81; // Should not happen if loop correct but standard modulo logic

        $meaning = isset(self::$MEANINGS_81[$val81]) ? self::$MEANINGS_81[$val81] : 'Bình thường (Bán Cát)';

        // Ngu Hanh (Last digit: 1,2=Moc, 3,4=Hoa, 5,6=Tho, 7,8=Kim, 9,0=Thuy)
        $last = substr($val, -1);
        $el = '';
        if (in_array($last, [1,2])) $el = 'Mộc';
        elseif (in_array($last, [3,4])) $el = 'Hỏa';
        elseif (in_array($last, [5,6])) $el = 'Thổ';
        elseif (in_array($last, [7,8])) $el = 'Kim';
        else $el = 'Thủy';

        // Score (Simple heuristic from string)
        $scoreClass = 'text-warning'; // Default
        if (strpos($meaning, 'Đại Cát') !== false) $scoreClass = 'text-success bold';
        elseif (strpos($meaning, '(Cát)') !== false) $scoreClass = 'text-info';
        elseif (strpos($meaning, 'Đại Hung') !== false) $scoreClass = 'text-danger bold';
        elseif (strpos($meaning, '(Hung)') !== false) $scoreClass = 'text-danger';

        return [
            'name' => $name,
            'val' => $val,
            'meaning' => $meaning,
            'element' => $el,
            'class' => $scoreClass
        ];
    }
}
