<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class LoBan {

    // Thước 52.2cm (Thông thủy) - 8 cung
    // Chu kỳ 522mm. Mỗi cung 65.25mm
    public static $L52 = array(
        'length' => 522,
        'segment' => 65.25,
        'segments' => array(
            0 => array('name' => 'Quý Nhân', 'good' => 1, 'desc' => 'Gặp quý nhân, đạt được điều hay'),
            1 => array('name' => 'Hiểm Họa', 'good' => 0, 'desc' => 'Dễ gặp chuyện không may'),
            2 => array('name' => 'Thiên Tai', 'good' => 0, 'desc' => 'Gặp họa trời giáng'),
            3 => array('name' => 'Thiên Tài', 'good' => 1, 'desc' => 'Tài lộc trời cho'),
            4 => array('name' => 'Nhân Lộc', 'good' => 1, 'desc' => 'Lộc từ người mang đến'), // Often called Phúc Lộc or Nhân Lộc
            5 => array('name' => 'Cô Độc', 'good' => 0, 'desc' => 'Đơn độc, chia lìa'),
            6 => array('name' => 'Thiên Tặc', 'good' => 0, 'desc' => 'Gặp trộm cướp, mất mát'),
            7 => array('name' => 'Tể Tướng', 'good' => 1, 'desc' => 'Thăng quan tiến chức')
        )
    );

    // Thước 42.9cm (Dương trạch) - 8 cung
    // Chu kỳ 429mm. Mỗi cung 53.625mm
    public static $L42 = array(
        'length' => 429,
        'segment' => 53.625,
        'segments' => array(
            0 => array('name' => 'Tài', 'good' => 1, 'desc' => 'Tài lộc, sung túc'),
            1 => array('name' => 'Bệnh', 'good' => 0, 'desc' => 'Bệnh tật, ốm đau'),
            2 => array('name' => 'Ly', 'good' => 0, 'desc' => 'Chia ly, xa cách'),
            3 => array('name' => 'Nghĩa', 'good' => 1, 'desc' => 'Có tình có nghĩa, tốt đẹp'),
            4 => array('name' => 'Quan', 'good' => 1, 'desc' => 'Quan vận hanh thông'),
            5 => array('name' => 'Kiếp', 'good' => 0, 'desc' => 'Tai kiếp, mất mát'),
            6 => array('name' => 'Hại', 'good' => 0, 'desc' => 'Bị hãm hại, xấu'),
            7 => array('name' => 'Bản', 'good' => 1, 'desc' => 'Vốn liếng, gốc rễ vững chắc')
        )
    );

    // Thước 38.8cm (Âm phần) - 10 cung
    // Chu kỳ 388mm. Mỗi cung 38.8mm
    public static $L38 = array(
        'length' => 388,
        'segment' => 38.8,
        'segments' => array(
            0 => array('name' => 'Đinh', 'good' => 1, 'desc' => 'Có con trai, thêm người'),
            1 => array('name' => 'Hại', 'good' => 0, 'desc' => 'Tai họa'),
            2 => array('name' => 'Vượng', 'good' => 1, 'desc' => 'Thịnh vượng'),
            3 => array('name' => 'Khổ', 'good' => 0, 'desc' => 'Đau khổ, vất vả'),
            4 => array('name' => 'Nghĩa', 'good' => 1, 'desc' => 'Tốt lành'),
            5 => array('name' => 'Quan', 'good' => 1, 'desc' => 'Thăng tiến'),
            6 => array('name' => 'Tử', 'good' => 0, 'desc' => 'Chết chóc, tàn lụi'),
            7 => array('name' => 'Hưng', 'good' => 1, 'desc' => 'Hưng thịnh'),
            8 => array('name' => 'Thất', 'good' => 0, 'desc' => 'Mất mát'),
            9 => array('name' => 'Tài', 'good' => 1, 'desc' => 'Tài lộc')
        )
    );

    /**
     * Calculate Lo Ban for a given length (cm)
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
        // Calculate position in cycle
        $pos = $val_mm % $config['length'];

        // Calculate segment index
        // Use epsilon for float precision if needed, but direct division is usually fine for display logic
        $idx = floor($pos / $config['segment']);

        // Safety check
        if ($idx >= count($config['segments'])) {
            $idx = 0; // Should not happen with modulo
        }

        $info = $config['segments'][$idx];

        return array(
            'val' => $val_mm / 10, // cm
            'name' => $info['name'],
            'good' => $info['good'],
            'desc' => $info['desc'],
            'scope' => ($config['length'] == 522) ? 'Thông thủy (Cửa, Lọt sáng)' : (($config['length'] == 429) ? 'Dương trạch (Bếp, Bệ, Bậc)' : 'Âm phần (Ban thờ, Mộ)')
        );
    }
}
