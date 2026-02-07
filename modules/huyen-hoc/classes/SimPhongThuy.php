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
        0 => 'Viên mãn, như ý, vạn sự tốt lành (Đại Cát)',
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
        67 => 'Đường lợi thông suốt, vạn sự như ý (Cát)',
        68 => 'Lập nghiệp hưng gia, phú quý vinh hoa (Đại Cát)',
        69 => 'Đứng núi này trông núi nọ, khó thành đại nghiệp (Hung)',
        70 => 'Phế vật, không còn gì, cuộc đời u ám (Hung)',
        71 => 'Nhẫn nhịn chịu đựng, chờ thời cơ (Bán Cát)',
        72 => 'Suối vàng chờ đón, tai họa bất ngờ (Hung)',
        73 => 'Chí cao nhưng sức yếu, khó thành công (Bán Hung)',
        74 => 'Hoàn cảnh không tốt, gặp nhiều trở ngại (Hung)',
        75 => 'Thủ được bình an, tránh xa thị phi (Bán Cát)',
        76 => 'Vấp ngã lại đứng lên, kiên trì sẽ thắng (Hung)',
        77 => 'Vui sướng cực độ, nhưng dễ sinh kiêu căng (Bán Cát)',
        78 => 'Già vẫn còn làm, vất vả nhưng có hậu (Bán Cát)',
        79 => 'Hồi quang phản chiếu, vinh hoa ngắn ngủi (Hung)',
        80 => 'Số phận đã định, quy về một mối (Cát)'
    );

    public static function analyze($phone) {
        // Get last 4 digits
        if (strlen($phone) < 4) return ['valid' => false];

        $last4 = substr($phone, -4);
        $val = intval($last4);

        // Algorithm: Val / 80. Take decimal part * 80.
        $div = $val / 80;
        $rem = $div - floor($div);
        $res = round($rem * 80);

        if ($res == 0) $res = 80; // Or 0 based on map

        // Determine meaning
        $meaning = isset(self::$MEANINGS_80[$res]) ? self::$MEANINGS_80[$res] : 'Không xác định';

        return [
            'valid' => true,
            'phone' => $phone,
            'last4' => $last4,
            'score' => $res,
            'meaning' => $meaning
        ];
    }
}
