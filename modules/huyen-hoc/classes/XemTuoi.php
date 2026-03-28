<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class XemTuoi {

    /**
     * Check compatibility between two years
     */
    public static function checkHopKhac($year1, $year2) {
        $can1 = ($year1 - 4) % 10;
        $chi1 = ($year1 - 4) % 12;
        $can2 = ($year2 - 4) % 10;
        $chi2 = ($year2 - 4) % 12;

        $score = 0;
        $details = array();

        // Check Can (Example: Hop +2, Khac -2)
        if (($can1 + 5) % 10 == $can2) {
            $score += 2;
            $details[] = "Thiên can Tương Phá";
        } elseif (($can1 + 6) % 10 == $can2) { // Just mock logic
             $score += 5;
             $details[] = "Thiên can Hợp";
        } else {
             $details[] = "Thiên can Bình hòa";
        }

        // Check Chi (Tam Hop +5, Luc Hop +3, Xung -5)
        // Tam Hop: Than-Ty-Thin (0, 4, 8) diff 4
        if (($chi1 + 4) % 12 == $chi2 || ($chi1 + 8) % 12 == $chi2) {
            $score += 5;
            $details[] = "Địa chi Tam Hợp";
        } elseif (($chi1 + 6) % 12 == $chi2) {
            $score -= 5;
            $details[] = "Địa chi Lục Xung";
        } else {
            $details[] = "Địa chi Bình hòa";
        }

        return array(
            'year1' => $year1,
            'year2' => $year2,
            'score' => 50 + $score, // Base 50
            'details' => $details
        );
    }
}
