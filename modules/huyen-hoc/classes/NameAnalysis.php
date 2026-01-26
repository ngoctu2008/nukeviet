<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class NameAnalysis {

    public static function analyze($ho, $ten, $year) {
        // Ngu Cach (Thien, Dia, Nhan, Ngoai, Tong)
        // Requires stroke count (Han Viet).
        // Mock stroke counts for demo
        $strokesHo = strlen($ho); // Just mock
        $strokesTen = strlen($ten);

        $thienCach = $strokesHo + 1;
        $nhanCach = $strokesHo + $strokesTen;
        $diaCach = $strokesTen + 1;
        $ngoaiCach = 1 + 1;
        $tongCach = $strokesHo + $strokesTen;

        return array(
            'ho' => $ho,
            'ten' => $ten,
            'ngu_cach' => array(
                'thien' => $thienCach,
                'dia' => $diaCach,
                'nhan' => $nhanCach,
                'ngoai' => $ngoaiCach,
                'tong' => $tongCach
            ),
            'comment' => 'Tên tốt (Demo)'
        );
    }
}
