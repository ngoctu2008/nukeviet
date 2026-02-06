<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class SimPhongThuy {

    // 80 Linh So Meanings (Simplified)
    private static $MEANINGS_80 = array(
        0 => 'Viên mãn, như ý, vạn sự tốt lành (Đại Cát)', // Usually 0 maps to 80 or 81 logic, but 80%80=0
        1 => 'Phát triển, thịnh vượng, mọi việc như ý (Đại Cát)',
        2 => 'Biến động, trôi nổi, sự nghiệp không thành (Hung)',
        3 => 'Danh lợi song thu, thành công rực rỡ (Đại Cát)',
        4 => 'Gian nan, vất vả, khó thành đại nghiệp (Hung)',
        5 => 'Làm ăn phát đạt, bạn bè giúp đỡ (Đại Cát)',
        6 => 'Trời cho an nhàn, phú quý vinh hoa (Cát)',
        7 => 'Cương nghị, quyết đoán, nhưng dễ độc đoán (Cát)',
        8 => 'Nỗ lực vươn lên, thành công qua gian khổ (Cát)',
        9 => 'Bất hạnh, cùng cực, sự nghiệp dở dang (Hung)',
        10 => 'Vạn sự kết thúc, chết chóc, u tối (Đại Hung)',
        11 => 'Gia vận tốt lành, được người trọng vọng (Đại Cát)',
        12 => 'Bạc nhược, yếu đuối, mưu sự khó thành (Hung)',
        13 => 'Tài chí hơn người, thành công rực rỡ (Đại Cát)',
        14 => 'Tan vỡ, đau buồn, gia đạo bất hòa (Hung)',
        15 => 'Phúc thọ song toàn, sự nghiệp hưng vượng (Đại Cát)',
        16 => 'Quý nhân phù trợ, thành công dễ dàng (Đại Cát)',
        17 => 'Cương trực, có quyền uy, vượt qua khó khăn (Cát)',
        18 => 'Thành công nhờ nỗ lực, danh lợi đều có (Cát)',
        19 => 'Nhiều tai nạn, trắc trở, khó khăn chồng chất (Hung)',
        20 => 'Sự nghiệp lụi bại, lo âu, phiền muộn (Đại Hung)',
        21 => 'Minh nguyệt quay đầu, nhà nhà kính trọng (Đại Cát)',
        22 => 'Thiên tài nhưng thiếu thời vận, dễ thất bại (Hung)',
        23 => 'Mặt trời mọc, danh tiếng vang xa (Đại Cát)',
        24 => 'Tay trắng làm nên, tài lộc dồi dào (Đại Cát)',
        25 => 'Thông minh, khéo léo nhưng tính tình kiêu ngạo (Cát)',
        26 => 'Biến hóa kỳ lạ, anh hùng hào kiệt (Bán Cát Bán Hung)',
        27 => 'Dục vọng vô tận, dễ chuốc lấy thất bại (Hung)',
        28 => 'Hào kiệt nhưng gặp họa, trôi nổi vô định (Hung)',
        29 => 'Dục vọng lớn, tài trí nhưng tham lam (Bán Cát)',
        30 => 'Phù trầm bất định, lúc thành lúc bại (Bán Hung)',
        31 => 'Tài dũng song toàn, trí tuệ minh mẫn (Đại Cát)',
        32 => 'Cầu được ước thấy, thịnh vượng may mắn (Đại Cát)',
        33 => 'Quyền uy, tài đức, danh tiếng lẫy lừng (Đại Cát)',
        34 => 'Gia đạo tan vỡ, tai nạn liên miên (Hung)',
        35 => 'Ôn hòa, mềm mỏng, sự nghiệp bình ổn (Cát)',
        36 => 'Anh hùng sơ vận, nhưng dễ sa cơ lỡ vận (Hung)',
        37 => 'Hào khí ngất trời, uy quyền hiển hách (Đại Cát)',
        38 => 'Nghệ thuật tinh thông, nhưng ý chí yếu đuối (Bán Hung)',
        39 => 'Phú quý vinh hoa, nhưng dễ kiêu căng (Đại Cát)',
        40 => 'Cẩn thận suy nghĩ, nhưng vận số kém may (Hung)',
        41 => 'Đức vọng cao dày, sự nghiệp vững chắc (Đại Cát)',
        42 => 'Mười nghề chín nghề bỏ, khó thành công (Hung)',
        43 => 'Hoa trong gương, trăng trong nước, hư ảo (Hung)',
        44 => 'Buồn phiền, thất bại, sự nghiệp sụp đổ (Hung)',
        45 => 'Vượt mọi khó khăn, thành công rực rỡ (Đại Cát)',
        46 => 'Gặp khó khăn, quý nhân không giúp (Hung)',
        47 => 'Danh lợi song thu, con cháu thảo hiền (Đại Cát)',
        48 => 'Tài trí, mưu lược, thành công lớn (Đại Cát)',
        49 => 'Hung cát đan xen, hậu vận không tốt (Bán Hung)',
        50 => 'Một thành một bại, cuộc đời chìm nổi (Bán Hung)',
        51 => 'Thịnh vượng rồi suy tàn, tuổi già cô độc (Bán Hung)',
        52 => 'Biết nhìn xa trông rộng, sự nghiệp thành công (Cát)',
        53 => 'Nội tâm u uất, bên ngoài hào nhoáng (Bán Hung)',
        54 => 'Tai nạn, rủi ro, sự nghiệp khó thành (Hung)',
        55 => 'Bề ngoài tươi sáng, bên trong rối ren (Bán Hung)',
        56 => 'Tổ nghiệp lụi tàn, vất vả gian nan (Hung)',
        57 => 'Trời quang mây tạnh, sau mưa lại sáng (Cát)',
        58 => 'Khổ trước sướng sau, kiên trì sẽ thành (Bán Cát)',
        59 => 'Mất phương hướng, không có định kiến (Hung)',
        60 => 'Tối tăm, mờ mịt, không lối thoát (Hung)',
        61 => 'Danh lợi song thu, nhưng cần tu dưỡng (Cát)',
        62 => 'Suy bại, đường đời gập ghềnh (Hung)',
        63 => 'Phú quý vinh hoa, vạn sự cát tường (Đại Cát)',
        64 => 'Cốt nhục chia lìa, tai nạn dồn dập (Hung)',
        65 => 'Phú quý trường thọ, gia đạo hưng vượng (Đại Cát)',
        66 => 'Trong ngoài bất hòa, tiến thoái lưỡng nan (Hung)',
        67 => 'Đường lợi thông suốt, sự nghiệp vững vàng (Cát)',
        68 => 'Lập nghiệp hưng gia, vạn sự như ý (Đại Cát)',
        69 => 'Đứng núi này trông núi nọ, khó thành công (Hung)',
        70 => 'U buồn, tẻ nhạt, cuộc đời cô quạnh (Hung)',
        71 => 'Hưởng phúc đức, nhưng tinh thần bất an (Bán Hung)',
        72 => 'Bề ngoài vui vẻ, bên trong sầu khổ (Hung)',
        73 => 'Chí cao nhưng tài hèn, khó thành đại nghiệp (Bán Hung)',
        74 => 'Trí tuệ kém cỏi, cuộc đời vất vả (Hung)',
        75 => 'Thủ giữ bình an, không nên mạo hiểm (Bán Cát)',
        76 => 'Gia sản khánh kiệt, đời sống bần hàn (Hung)',
        77 => 'Vui sướng nửa chừng, hậu vận kém (Bán Hung)',
        78 => 'Gia nghiệp sa sút, tuổi già cô đơn (Bán Hung)',
        79 => 'Hồi phục sức lực, chờ thời cơ (Bán Cát)',
        80 => 'Gặp nhiều trở ngại, khó đạt mục đích (Hung)'
    );

    public static function analyze($phone) {
        $result = [];
        $phone = preg_replace('/[^0-9]/', '', $phone);
        $result['phone'] = $phone;

        if (strlen($phone) < 6) return ['error' => 'Số điện thoại quá ngắn'];

        // 1. Am Duong Balance
        $odd = 0; $even = 0;
        $len = strlen($phone);
        for ($i = 0; $i < $len; $i++) {
            if ($phone[$i] % 2 == 0) $even++; else $odd++;
        }
        $result['am_duong'] = [
            'odd' => $odd,
            'even' => $even,
            'balance' => ($odd == $even) ? 'Cân bằng Âm Dương (Tốt)' : (($odd > $even) ? 'Dương thịnh Âm suy' : 'Âm thịnh Dương suy')
        ];

        // 2. Ngu Hanh (Based on Last Digit)
        // 1,6=Thuy; 2,7=Hoa; 3,8=Moc; 4,9=Kim; 0,5=Tho
        $lastDigit = intval(substr($phone, -1));
        $nguHanhMap = [
            1=>'Thủy', 6=>'Thủy',
            2=>'Hỏa', 7=>'Hỏa',
            3=>'Mộc', 8=>'Mộc',
            4=>'Kim', 9=>'Kim',
            0=>'Thổ', 5=>'Thổ'
        ];
        $result['ngu_hanh'] = isset($nguHanhMap[$lastDigit]) ? $nguHanhMap[$lastDigit] : 'Không xác định';

        // 3. 4-Digit Feng Shui (80 Linh So)
        $last4 = substr($phone, -4);
        $val = intval($last4);
        $div = $val / 80;
        $decimal = $div - floor($div);
        $idx80 = round($decimal * 80);
        if ($idx80 == 0) $idx80 = 80; // Handle 0 result

        $result['sim_4_so'] = [
            'so' => $last4,
            'index' => $idx80,
            'meaning' => isset(self::$MEANINGS_80[$idx80]) ? self::$MEANINGS_80[$idx80] : 'Chưa có dữ liệu'
        ];

        // 4. Kinh Dich (Hexagram)
        // Split: First 5 (or half) vs Last 5 (or half).
        // Standard: Use first 5 digits sum for Upper, Last 5 for Lower. (If 10 digits)
        // If 11 digits? Split 6/5? Or 5/6? Usually 5/6.
        // Let's take first half / second half.
        $mid = floor($len / 2);
        $strUpper = substr($phone, 0, $mid);
        $strLower = substr($phone, $mid);

        $sumUpper = 0; for($i=0; $i<strlen($strUpper); $i++) $sumUpper += intval($strUpper[$i]);
        $sumLower = 0; for($i=0; $i<strlen($strLower); $i++) $sumLower += intval($strLower[$i]);

        $remUpper = $sumUpper % 8; if($remUpper==0) $remUpper=8;
        $remLower = $sumLower % 8; if($remLower==0) $remLower=8;

        $trigrams = [
            1 => 'Càn (Thiên)', 2 => 'Đoài (Trạch)', 3 => 'Ly (Hỏa)', 4 => 'Chấn (Lôi)',
            5 => 'Tốn (Phong)', 6 => 'Khảm (Thủy)', 7 => 'Cấn (Sơn)', 8 => 'Khôn (Địa)'
        ];

        $result['kinh_dich'] = [
            'thuong_quai' => $trigrams[$remUpper],
            'ha_quai' => $trigrams[$remLower],
            'que_name' => 'Quẻ ' . $trigrams[$remUpper] . ' trên ' . $trigrams[$remLower]
        ];

        return $result;
    }
}
