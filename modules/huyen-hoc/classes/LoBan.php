<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class LoBan {

    // Thước 52.2cm (Thông thủy) - 8 cung lớn, mỗi cung 65.25mm
    // Mỗi cung lớn chia 5 cung nhỏ (mỗi nhỏ ~13.05mm)
    public static $L52 = array(
        'length' => 522,
        'segment' => 65.25,
        'segments' => array(
            0 => array(
                'name' => 'Quý Nhân', 'good' => 1, 'desc' => 'Gặp quý nhân, đạt được điều hay',
                'subs' => array('Quyền lộc', 'Trung tín', 'Tác quan', 'Phát đạt', 'Thông minh')
            ),
            1 => array(
                'name' => 'Hiểm Họa', 'good' => 0, 'desc' => 'Dễ gặp chuyện không may',
                'subs' => array('Tán thành', 'Thời nhơn', 'Tự ải', 'Quan tư', 'Cô quả') // Example subs, to be verified
            ),
            2 => array(
                'name' => 'Thiên Tai', 'good' => 0, 'desc' => 'Gặp họa trời giáng',
                'subs' => array('Hoàn trường', 'Thiếu an', 'Bệnh tật', 'Thân tàn', 'Hệ suất')
            ),
            3 => array(
                'name' => 'Thiên Tài', 'good' => 1, 'desc' => 'Tài lộc trời cho',
                'subs' => array('Thi thơ', 'Văn học', 'Thanh quý', 'Tác lộc', 'Thiên lộc')
            ),
            4 => array(
                'name' => 'Nhân Lộc', 'good' => 1, 'desc' => 'Lộc từ người mang đến', // Phuc Loc? Known as Nhan Loc in some
                'subs' => array('Tử tôn', 'Phú quý', 'Tấn bửu', 'Thập thiện', 'Văn chương')
            ),
            5 => array(
                'name' => 'Cô Độc', 'good' => 0, 'desc' => 'Đơn độc, chia lìa',
                'subs' => array('Bạc nghịch', 'Vô vọng', 'Ly tán', 'Tửu thực', 'Dâm dục')
            ),
            6 => array(
                'name' => 'Thiên Tặc', 'good' => 0, 'desc' => 'Gặp trộm cướp, mất mát',
                'subs' => array('Phòng bệnh', 'Chiêu ôn', 'Ôn tai', 'Ngục tù', 'Quan tài')
            ),
            7 => array(
                'name' => 'Tể Tướng', 'good' => 1, 'desc' => 'Thăng quan tiến chức',
                'subs' => array('Đại tài', 'Thi thơ', 'Hoạch tài', 'Hiếu tử', 'Quý nhân')
            )
        )
    );

    // Thước 42.9cm (Dương trạch) - 8 cung lớn, mỗi cung 53.625mm
    // Mỗi cung lớn chia 4 cung nhỏ (mỗi nhỏ ~13.4mm)
    public static $L42 = array(
        'length' => 429,
        'segment' => 53.625,
        'segments' => array(
            0 => array(
                'name' => 'Tài', 'good' => 1, 'desc' => 'Tài lộc, sung túc',
                'subs' => array('Tài đức', 'Bảo khố', 'Lục hợp', 'Nghênh phúc')
            ),
            1 => array(
                'name' => 'Bệnh', 'good' => 0, 'desc' => 'Bệnh tật, ốm đau',
                'subs' => array('Thoái tài', 'Công sự', 'Lao chấp', 'Cô quả')
            ),
            2 => array(
                'name' => 'Ly', 'good' => 0, 'desc' => 'Chia ly, xa cách',
                'subs' => array('Trường khố', 'Kiếp tài', 'Quan quỷ', 'Thất thoát')
            ),
            3 => array(
                'name' => 'Nghĩa', 'good' => 1, 'desc' => 'Có tình có nghĩa',
                'subs' => array('Thiêm đinh', 'Ích lợi', 'Quý tử', 'Đại cát')
            ),
            4 => array(
                'name' => 'Quan', 'good' => 1, 'desc' => 'Quan vận hanh thông',
                'subs' => array('Thuận khoa', 'Hoành tài', 'Tiến ích', 'Phú quý')
            ),
            5 => array(
                'name' => 'Kiếp', 'good' => 0, 'desc' => 'Tai kiếp, mất mát',
                'subs' => array('Tử biệt', 'Thoái khẩu', 'Ly hương', 'Tài thất')
            ),
            6 => array(
                'name' => 'Hại', 'good' => 0, 'desc' => 'Bị hãm hại, xấu',
                'subs' => array('Tai chi', 'Tử tuyệt', 'Bệnh lâm', 'Khẩu thiệt')
            ),
            7 => array(
                'name' => 'Bản', 'good' => 1, 'desc' => 'Vốn liếng, gốc rễ',
                'subs' => array('Tài chí', 'Đăng khoa', 'Tiến bảo', 'Hưng vượng')
            )
        )
    );

    // Thước 38.8cm (Âm phần) - 10 cung lớn, mỗi cung 38.8mm
    // Mỗi cung lớn chia 4 cung nhỏ ?? Based on image "Đinh" has 4 subs: Phúc Tinh, Cấp Đệ, Tài Vượng, Đăng Khoa
    // Wait, 38.8mm / 4 = 9.7mm. Quite small.
    // Let's verify standard. Usually 38.8cm ruler has 10 segments.
    // Dinh (Good), Hai (Bad), Vuong (Good), Kho (Bad), Nghia (Good), Quan (Good), Tu (Bad), Hung (Good), That (Bad), Tai (Good).
    // Subs usually are not explicitly defined in some simplified versions, BUT the image shows them.
    // Let's implement the hierarchy from image for "Đinh".
    public static $L38 = array(
        'length' => 388,
        'segment' => 38.8,
        'segments' => array(
            0 => array('name' => 'Đinh', 'good' => 1, 'desc' => 'Có con trai', 'subs' => array('Phúc tinh', 'Cấp đệ', 'Tài vượng', 'Đăng khoa')),
            1 => array('name' => 'Hại', 'good' => 0, 'desc' => 'Tai họa', 'subs' => array('Khẩu thiệt', 'Bệnh lâm', 'Tử tuyệt', 'Tai chi')),
            2 => array('name' => 'Vượng', 'good' => 1, 'desc' => 'Thịnh vượng', 'subs' => array('Thiên đức', 'Hỷ sự', 'Tiến bảo', 'Nạp phúc')),
            3 => array('name' => 'Khổ', 'good' => 0, 'desc' => 'Đau khổ', 'subs' => array('Thất thoát', 'Quan quỷ', 'Kiếp tài', 'Vô tự')),
            4 => array('name' => 'Nghĩa', 'good' => 1, 'desc' => 'Tốt lành', 'subs' => array('Đại cát', 'Tài vượng', 'Ích lợi', 'Thiên khố')),
            5 => array('name' => 'Quan', 'good' => 1, 'desc' => 'Thăng tiến', 'subs' => array('Phú quý', 'Tiến bảo', 'Hoành tài', 'Thuận khoa')),
            6 => array('name' => 'Tử', 'good' => 0, 'desc' => 'Chết chóc', 'subs' => array('Ly hương', 'Tử biệt', 'Thoái đinh', 'Thất tài')),
            7 => array('name' => 'Hưng', 'good' => 1, 'desc' => 'Hưng thịnh', 'subs' => array('Đăng khoa', 'Quý tử', 'Thêm đinh', 'Hưng vượng')),
            8 => array('name' => 'Thất', 'good' => 0, 'desc' => 'Mất mát', 'subs' => array('Cô quả', 'Lao chấp', 'Công sự', 'Thoái tài')),
            9 => array('name' => 'Tài', 'good' => 1, 'desc' => 'Tài lộc', 'subs' => array('Nghênh phúc', 'Lục hợp', 'Tiến bảo', 'Tài đức'))
        )
    );

    /**
     * Calculate Lo Ban details
     */
    public static function calculate($length_cm) {
        $length_mm = $length_cm * 10;

        return array(
            '52' => self::getSegment($length_mm, self::$L52),
            '42' => self::getSegment($length_mm, self::$L42),
            '38' => self::getSegment($length_mm, self::$L38)
        );
    }

    private static function getSegment($val_mm, $config) {
        // Position in cycle
        $cyclePos = $val_mm % $config['length']; // 0..Length-1

        // Main segment index
        $idx = floor($cyclePos / $config['segment']);
        if ($idx >= count($config['segments'])) $idx = 0;

        $info = $config['segments'][$idx];

        // Sub segment index
        // Length of sub segment
        $numSubs = count($info['subs']);
        $subLen = $config['segment'] / $numSubs;

        // Position within main segment
        $posInSeg = $cyclePos - ($idx * $config['segment']);

        $subIdx = floor($posInSeg / $subLen);
        if ($subIdx >= $numSubs) $subIdx = $numSubs - 1;

        $subName = $info['subs'][$subIdx];

        return array(
            'val' => $val_mm / 10,
            'name' => $info['name'],
            'good' => $info['good'],
            'desc' => $info['desc'],
            'sub_name' => $subName,
            'scope' => ($config['length'] == 522) ? 'Khoảng không thông thủy (cửa, cửa sổ...)' : (($config['length'] == 429) ? 'Khối xây dựng (bếp, bệ, bậc...)' : 'Đồ nội thất (bàn thờ, tủ...)')
        );
    }
}
