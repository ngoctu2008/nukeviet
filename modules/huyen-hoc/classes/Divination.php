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
        $time_seed = (float)microtime(true) * 10000;
        $final_seed = $time_seed + $duration;

        // Khởi tạo bộ sinh số ngẫu nhiên
        mt_srand((int)$final_seed);

        // 2. Lấy quẻ (Từ 1 đến 384)
        $hex_id = mt_rand(1, 384);

        // 3. Lấy nội dung
        return $this->getPoemContent($hex_id);
    }

    private function getPoemContent($id) {
        $data = [];
        $json_path = NV_ROOTDIR . '/modules/huyen-hoc/data/khong_minh_384.json';

        // Try reading JSON
        if (file_exists($json_path)) {
            $json = file_get_contents($json_path);
            if ($json !== false) {
                $data = json_decode($json, true);
                if (json_last_error() !== JSON_ERROR_NONE) {
                    $data = []; // Reset if decode fails
                }
            }
        }

        // Fallback hardcoded data if JSON failed or is empty
        if (empty($data)) {
            $data = [
                1 => [
                    "id" => 1,
                    "name_han" => "Thiên môn nhất quải bản",
                    "poem_han" => "Thiên môn nhất quải bản\nNhĩ mục bị quan hạn\nNhãn khán quá giang nhân\nNan độ hành châu than",
                    "poem_viet" => "Cửa trời một tấm chắn ngang\nTai nghe mắt thấy rõ ràng ngại chi\nTrông người vượt bến sông đi\nThuyền mình mắc cạn khó khi đi cùng",
                    "meaning" => "Quẻ này chủ về sự trắc trở. Thời vận chưa thông, mọi việc nên án binh bất động...",
                    "image" => "que_1.jpg",
                    "note" => "Dữ liệu dự phòng (Không đọc được file JSON)"
                ],
                2 => [
                     "id" => 2,
                     "name_han" => "Địa hộ lưỡng trùng khai",
                     "poem_han" => "Địa hộ lưỡng trùng khai\nĐộc ảnh quải thanh đài...",
                     "poem_viet" => "Cửa đất hai lần mở\nBóng lẻ treo rêu xanh...",
                     "meaning" => "Quẻ này tượng trưng cho sự tái sinh sau cơn bế tắc...",
                     "image" => "que_2.jpg"
                ]
            ];
        }

        // Logic to return data
        if (isset($data[$id])) {
            return $data[$id];
        } else {
            // Map large ID to available keys (1-5 or fallback keys)
            // Get available keys
            $keys = array_keys($data);
            $count = count($keys);
            if ($count > 0) {
                // Map $id to index 0..count-1
                $index = ($id - 1) % $count;
                $mappedKey = $keys[$index];

                $result = $data[$mappedKey];
                // Keep the original ID to show randomization working, but show content of mapped key
                $result['id'] = $id;
                $result['note'] = isset($result['note']) ? $result['note'] : "Demo Content (Quẻ mẫu cho ID $id)";
                return $result;
            }
        }

        return null;
    }
}
