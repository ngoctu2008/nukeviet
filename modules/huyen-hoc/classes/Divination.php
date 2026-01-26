<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class Divination {

    public static function gieoQue() {
        // Weighted random
        // Mock 64 hexagrams or 384 yao
        // Use mt_rand seeded by microtime
        mt_srand(microtime(true) * 1000);
        $id = mt_rand(1, 64);

        return array(
            'id' => $id,
            'name' => 'Quẻ số ' . $id,
            'image' => 'images/que/' . $id . '.png',
            'meaning' => 'Đại cát (Demo meaning for hexagram ' . $id . ')'
        );
    }
}
