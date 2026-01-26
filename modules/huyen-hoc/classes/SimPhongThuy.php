<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class SimPhongThuy {

    public static function analyze($phone) {
        // Last 4 digits logic
        // 1. Take last 4 digits
        if (strlen($phone) < 4) return array('error' => 'Số quá ngắn');

        $last4 = substr($phone, -4);
        $val = intval($last4);

        // Algo: (Last4 / 80). Get decimal part. Multiply by 80.
        $div = $val / 80;
        $decimal = $div - floor($div);
        $resultIdx = round($decimal * 80);

        // Mock meaning
        $meaning = "Ý nghĩa số " . $resultIdx . " (Cát)";
        if ($resultIdx == 0) $meaning = "Đại cát (0)";
        if ($resultIdx == 4) $meaning = "Đại hung (4)";

        return array(
            'phone' => $phone,
            'last4' => $last4,
            'index' => $resultIdx,
            'meaning' => $meaning,
            'score' => ($resultIdx % 2 == 0) ? 8 : 5 // Mock score
        );
    }
}
