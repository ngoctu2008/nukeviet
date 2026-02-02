<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

namespace NukeViet\Module\HuyenHoc;

class TuViLuanGiai {

    protected $db;
    protected $table;

    public function __construct() {
        global $db, $db_config, $module_data;
        $this->db = $db;
        // Sanitize module_data to match action_mysql.php (replace - with _)
        $mod_data_sanitized = str_replace('-', '_', $module_data);
        $this->table = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . $mod_data_sanitized . "_interpretations";
    }

    /**
     * Get Interpretation for the whole chart
     */
    public function luanGiai($chart) {
        $result = [];

        // 1. Luan Giai Tong Quan (Menh / Than)
        $menhIdx = $this->findPalaceIndex($chart['dia_ban'], 'Mệnh');
        $thanIdx = $this->findPalaceIndex($chart['dia_ban'], 'Thân'); // Note: ' (Thân)' suffix in name logic

        if ($menhIdx !== false) {
            $result['tong_quan_menh'] = $this->getPalaceReading($chart['dia_ban'][$menhIdx], 'menh');
        }

        if ($thanIdx !== false && $thanIdx !== $menhIdx) {
            $result['tong_quan_than'] = $this->getPalaceReading($chart['dia_ban'][$thanIdx], 'than');
        }

        // 2. Luan Giai Tong Quan & Van Han (Based on Menh Stars)
        $result['overview'] = [];
        $result['limit'] = [];

        if ($menhIdx !== false) {
            $menhPalace = $chart['dia_ban'][$menhIdx];
            if (!empty($menhPalace['chinh_tinh'])) {
                foreach ($menhPalace['chinh_tinh'] as $star) {
                    // Overview
                    $ovContent = $this->fetchContent($star['code'], 'all', 'overview');
                    if ($ovContent) {
                        $result['overview'][] = [
                            'star' => $star['name'],
                            'content' => $ovContent
                        ];
                    }
                    // Limit
                    $limContent = $this->fetchContent($star['code'], 'all', 'limit');
                    if ($limContent) {
                        $result['limit'][] = [
                            'star' => $star['name'],
                            'content' => $limContent
                        ];
                    }
                }
            }
        }

        // 3. Luan Giai 12 Cung
        foreach ($chart['dia_ban'] as $i => $palace) {
            // Determine palace key (e.g., 'phu_mau', 'quan_loc'...) based on name
            $key = $this->normalizePalaceName($palace['palace_name']);
            $reading = $key ? $this->getPalaceReading($palace, $key) : [];

            // Store by Key (for legacy/alias access)
            if ($key) {
                $result[$key] = $reading;
            }

            // Store by Index (0-11) for easier iteration in template/controller
            // Assuming $chart['dia_ban'] is indexed 0-11
            $result[$i] = $reading;
        }

        return $result;
    }

    private function findPalaceIndex($diaBan, $namePart) {
        foreach ($diaBan as $idx => $p) {
            if (mb_strpos($p['palace_name'], $namePart) !== false) return $idx;
        }
        return false;
    }

    private function normalizePalaceName($name) {
        // Strip ' (Thân)' and accents
        $name = preg_replace('/\s*\(.*?\)/', '', $name); // remove (Thân)
        $map = [
            'Mệnh' => 'menh', 'Phụ Mẫu' => 'phu_mau', 'Phúc Đức' => 'phuc_duc',
            'Điền Trạch' => 'dien_trach', 'Quan Lộc' => 'quan_loc', 'Nô Bộc' => 'no_boc',
            'Thiên Di' => 'thien_di', 'Tật Ách' => 'tat_ach', 'Tài Bạch' => 'tai_bach',
            'Tử Tức' => 'tu_tuc', 'Phu Thê' => 'phu_the', 'Huynh Đệ' => 'huynh_de'
        ];
        return isset($map[$name]) ? $map[$name] : null;
    }

    /**
     * Get Tuan/Triet Meaning
     */
    private function getTuanTrietMeaning($hasTuan, $hasTriet) {
        if ($hasTuan && $hasTriet) {
            return "Cung này gặp cả Tuần và Triệt án ngữ. Tác động của sao tốt và sao xấu đều bị giảm đi đáng kể. Sự nghiệp và tình cảm dễ gặp trắc trở buổi đầu nhưng về sau ổn định.";
        } elseif ($hasTuan) {
            return "Cung này gặp Tuần Không. Sự ảnh hưởng diễn ra từ từ, càng về sau càng rõ rệt. Thường làm chậm lại sự phát triển hoặc giảm bớt tính chất hung hãn của sát tinh.";
        } elseif ($hasTriet) {
            return "Cung này gặp Triệt Lộ. Tác động mạnh mẽ ở giai đoạn tiền vận (trước 30 tuổi), gây ngăn trở, gãy đổ, nhưng về sau tác động giảm dần.";
        }
        return "";
    }

    /**
     * Get Reading for a specific Palace
     */
    private function getPalaceReading($palaceData, $palaceKey) {
        $readings = [
            'chinh_tinh' => [],
            'phu_tinh' => [],
            'general' => []
        ];

        // 1. Chinh Tinh
        if (!empty($palaceData['chinh_tinh'])) {
            foreach ($palaceData['chinh_tinh'] as $star) {
                $content = $this->fetchContent($star['code'], $palaceKey, 'main');
                if ($content) {
                    $readings['chinh_tinh'][] = [
                        'star' => $star['name'],
                        'content' => $content
                    ];
                }
            }
        }

        // 2. Phu Tinh (Tot/Xau)
        $allPhu = array_merge($palaceData['phu_tinh_tot'], $palaceData['phu_tinh_xau']);
        foreach ($allPhu as $star) {
            // Minor stars might not have specific palace readings, but general meanings
            $content = $this->fetchContent($star['code'], 'general', 'meaning');
            if ($content) {
                 $readings['phu_tinh'][] = [
                    'star' => $star['name'],
                    'content' => $content
                ];
            }
        }

        // 3. General Palace Reading (e.g. for Empty Palace or specific configuration)
        // Use 'general' as star_key
        $generalContent = $this->fetchContent('general', $palaceKey, 'main');
        if ($generalContent) {
             $readings['general'][] = [
                'star' => 'Lời bàn chung',
                'content' => $generalContent
            ];
        }

        // 4. Tuan / Triet
        $ttMeaning = $this->getTuanTrietMeaning($palaceData['tuan'], $palaceData['triet']);
        if ($ttMeaning) {
            $readings['general'][] = [
                'star' => 'Tuần / Triệt',
                'content' => $ttMeaning
            ];
        }

        return $readings;
    }

    /**
     * Fetch from Database
     */
    private function fetchContent($starKey, $palaceKey, $topic = '') {
        // Prioritize Specific Palace > All
        // Order by: (palace_key = :palace) DESC to ensure specific match comes first

        $sql = "SELECT content FROM " . $this->table . "
                WHERE star_key = :star
                AND (palace_key = :palace OR palace_key = 'all' OR palace_key = 'general')";

        if ($topic) {
            $sql .= " AND topic = :topic";
        }

        // Sort specific first
        $sql .= " ORDER BY CASE WHEN palace_key = :palace THEN 1 ELSE 2 END ASC LIMIT 1";

        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':star', $starKey);
            $stmt->bindValue(':palace', $palaceKey);
            if ($topic) $stmt->bindValue(':topic', $topic);

            $stmt->execute();
            $row = $stmt->fetch();
            return $row ? $row['content'] : '';
        } catch (\Exception $e) {
            return '';
        }
    }

    /**
     * Import Data Helper (for setup)
     */
    public function importData($data) {
        // Expects array of [star_key, palace_key, topic, content]
        foreach ($data as $row) {
            $sql = "INSERT INTO " . $this->table . " (star_key, palace_key, topic, content) VALUES (
                :star, :palace, :topic, :content
            )";
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':star', $row['star_key']);
            $stmt->bindValue(':palace', $row['palace_key']);
            $stmt->bindValue(':topic', $row['topic']);
            $stmt->bindValue(':content', $row['content']);
            $stmt->execute();
        }
    }
}
