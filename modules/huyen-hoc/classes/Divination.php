<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class Divination {

    /**
     * Gieo quẻ Khổng Minh (1-384)
     * @param int $duration Thời gian user lắc điện thoại/giữ chuột (ms)
     * @return array Kết quả quẻ
     */
    public function getKhongMinhHexagram($duration) {
        // 1. Tạo độ ngẫu nhiên dựa trên "Tâm" (hành động lắc)
        // Lấy microtime hiện tại làm nhiễu
        $time_seed = (float)microtime(true) * 10000;

        // Kết hợp với thời gian lắc của người dùng
        $final_seed = $time_seed + $duration;

        // Khởi tạo bộ sinh số ngẫu nhiên Mersenne Twister (tốt hơn rand)
        mt_srand((int)$final_seed);

        // 2. Lấy quẻ (Từ 1 đến 384)
        $hex_id = mt_rand(1, 384);

        // For testing phase with limited data, ensure we get a valid ID from the sample set (1-5)
        // If data file is full 384, remove this mod logic.
        // But the user only provided 5 sample records.
        // Let's check if the file exists and how many keys.
        // For now, let's just return the random ID, and getMeaning will handle "Not found".
        // OR map to 1-5 for demo purposes if ID > 5.
        // The Prompt asked for "Weighted Random" in original plan but "mt_rand" in detailed instruction.
        // I will stick to the detailed instruction: return 1-384.

        // 3. Lấy nội dung từ Data
        return $this->getPoemContent($hex_id);
    }

    private function getPoemContent($id) {
        // Đọc từ file JSON
        $json_path = NV_ROOTDIR . '/modules/huyen-hoc/data/khong_minh_384.json';
        if (!file_exists($json_path)) {
            return null;
        }

        $json = file_get_contents($json_path);
        $data = json_decode($json, true);

        // Fallback for demo if ID not in data (since we only have 5)
        if (!isset($data[$id])) {
            // For demo purposes, map large ID to 1-5
            $demo_id = ($id % 5) + 1;
            $result = isset($data[$demo_id]) ? $data[$demo_id] : null;
            if ($result) {
                $result['id'] = $id; // Keep the rolled ID but show demo content
                $result['note'] = "Demo Content (Data for ID $id missing)";
            }
            return $result;
        }

        return $data[$id];
    }
}
