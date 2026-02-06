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
        $this->table = $db_config['prefix'] . "_" . NV_LANG_DATA . "_" . str_replace('-', '_', $module_data) . "_interpretations";
    }

    /**
     * Get Interpretation for the whole chart
     */
    public function luanGiai($chart) {
        $result = [];

        // 1. Tien Thien (Goc re)
        $result['tien_thien'] = $this->assessPreDestiny($chart);

        // 2. Score
        $result['score'] = $this->calculateScore($chart);

        // 3. Luan Giai 12 Cung & Patterns
        foreach ($chart['dia_ban'] as $i => $palace) {
            $key = $this->normalizePalaceName($palace['palace_name']);

            // Standard reading
            $reading = $key ? $this->getPalaceReading($palace, $key) : ['chinh_tinh'=>[], 'phu_tinh'=>[], 'general'=>[]];

            // Patterns (Cach Cuc)
            $patterns = $this->identifyPatterns($palace, $key, $chart);
            if ($patterns) {
                foreach ($patterns as $pat) {
                    $reading['general'][] = ['star' => 'Cách Cục', 'content' => $pat['content']];
                }
            }

            // Cuong Nhuoc (Strength)
            $strength = $this->assessPalaceStrength($palace, $key, $chart['thien_ban']['menh_ngu_hanh']); // Using menh element or palace element?
            // Usually strength of Palace vs Star.
            // Let's add strength note to general.
            $reading['general'][] = ['star' => 'Đánh giá', 'content' => $strength];

            if ($key) $result[$key] = $reading;
            $result[$i] = $reading;
        }

        return $result;
    }

    /**
     * Section 1: Tien Thien Analysis
     */
    private function assessPreDestiny($chart) {
        $tb = $chart['thien_ban'];
        return [
            'am_duong' => $tb['am_duong_ly'], // calculated in TuViLapSo
            'cuc_menh' => $tb['cuc_menh_ly'], // calculated in TuViLapSo
            'menh_chu' => 'Mệnh Chủ: (Cập nhật sau)', // TODO: Implement based on Year
            'than_chu' => 'Thân Chủ: (Cập nhật sau)'
        ];
    }

    /**
     * Section 2: Palace Strength (Cuong Nhuoc)
     */
    private function assessPalaceStrength($palace, $palaceKey, $menhElement) {
        // Simple logic based on Chinh Tinh brightness
        $score = 0;
        $count = 0;
        foreach ($palace['chinh_tinh'] as $s) {
            if (in_array($s['dacs'], ['M', 'V', 'Đ'])) $score += 2;
            elseif ($s['dacs'] == 'H') $score -= 2;
            $count++;
        }

        $eval = "Bình thường";
        if ($count > 0) {
            if ($score > 0) $eval = "Cung Vượng (Nhiều sao sáng)";
            elseif ($score < 0) $eval = "Cung Nhược (Nhiều sao hãm)";
        } else {
            $eval = "Vô Chính Diệu";
        }

        // Element comparison could go here (Star Element vs Palace Element)
        return $eval;
    }

    /**
     * Section 3: Pattern Recognition (Cach Cuc)
     * Check DB for rules or hardcode key ones.
     * User asked for "solid architecture", so DB based is better if possible.
     * But for now, hybrid.
     */
    private function identifyPatterns($palace, $palaceKey, $chart) {
        $patterns = [];

        // 1. Tu Phu Vu Tuong (Tu Vi, Thien Phu, Vu Khuc, Thien Tuong) in Tam Phuong Tu Chinh
        // This requires checking associated palaces (Tam Hop, Xung Chieu).
        // Let's stick to simple local patterns first or key famous ones.

        // Example: Menh co Tu Vi Ngo (Tu Vi Cu Ngo)
        if ($palaceKey == 'menh' && $palace['key'] == 'ngo') {
            if ($this->hasStar($palace, 'tu_vi')) {
                $patterns[] = ['code' => 'TU_VI_CU_NGO', 'content' => 'Tử Vi Cư Ngọ: Đế vương cách, cực kỳ quý hiển.'];
            }
        }

        // Example: Sat Pha Tham (That Sat, Pha Quan, Tham Lang)
        // Usually these 3 are in Tam Hop. If Menh has one, the others are likely in Tam Hop.
        // Check if Menh has one of them.
        if ($palaceKey == 'menh') {
            if ($this->hasStar($palace, 'that_sat') || $this->hasStar($palace, 'pha_quan') || $this->hasStar($palace, 'tham_lang')) {
                 $patterns[] = ['code' => 'SAT_PHA_THAM', 'content' => 'Sát Phá Tham: Mẫu người hành động, biến động mạnh, thường lập nghiệp trong gian khó.'];
            }
        }

        // Check DB for dynamic rules
        // Table: stars_required (JSON array of codes), condition_code, content
        // Query interpretations where topic='pattern' and palace_key=:palaceKey (or 'all')
        // This would require fetching ALL patterns and filtering in PHP or complex SQL.
        // Simplified: Fetch patterns for this palace key.

        return $patterns;
    }

    /**
     * Section 4: Quantitative Scoring
     */
    private function calculateScore($chart) {
        $total = 50; // Base score

        // Analyze Menh, Than, Tai, Quan
        $palacesToCheck = ['menh', 'than', 'tai_bach', 'quan_loc'];
        foreach ($chart['dia_ban'] as $p) {
            $key = $this->normalizePalaceName($p['palace_name']);
            if (in_array($key, $palacesToCheck)) {
                // Chinh Tinh
                foreach ($p['chinh_tinh'] as $s) {
                    if (in_array($s['dacs'], ['M', 'V'])) $total += 5;
                    elseif ($s['dacs'] == 'Đ') $total += 3;
                    elseif ($s['dacs'] == 'H') $total -= 3;
                }
                // Phu Tinh Tot
                $total += count($p['phu_tinh_tot']);
                // Phu Tinh Xau
                $total -= count($p['phu_tinh_xau']);

                // Tuan / Triet
                if ($p['tuan'] || $p['triet']) $total -= 2;
            }
        }

        return max(0, min(100, $total));
    }

    private function hasStar($palace, $starCode) {
        foreach ($palace['chinh_tinh'] as $s) if ($s['code'] == $starCode) return true;
        foreach ($palace['phu_tinh_tot'] as $s) if ($s['code'] == $starCode) return true;
        foreach ($palace['phu_tinh_xau'] as $s) if ($s['code'] == $starCode) return true;
        return false;
    }

    /**
     * Generate Structured Report
     */
    public function generateStructuredReport($laSo) {
        // Use luanGiai() result to build text
        $analysis = $this->luanGiai($laSo);

        $report = [
            'section_1' => [
                'info' => "Đương số: " . $laSo['thien_ban']['ho_ten'],
                'am_duong' => [$analysis['tien_thien']['am_duong']],
                'menh_than' => ['menh' => '...', 'than' => '...'] // Fill from main logic if needed
            ],
            'section_2' => [], // Details
            'section_3' => [], // Van Han (from Controller/AJAX usually)
            'score' => $analysis['score']
        ];

        // Fill Section 2
        foreach ($laSo['dia_ban'] as $i => $p) {
             $report['section_2'][] = [
                 'name' => $p['palace_name'],
                 'reading' => $analysis[$i]
             ];
        }

        return $report;
    }

    // ... (Keep existing fetchContent, findPalaceIndex, etc.) ...
    private function normalizePalaceName($name) {
        $name = preg_replace('/\s*\(.*?\)/', '', $name);
        $map = [
            'Mệnh' => 'menh', 'Phụ Mẫu' => 'phu_mau', 'Phúc Đức' => 'phuc_duc',
            'Điền Trạch' => 'dien_trach', 'Quan Lộc' => 'quan_loc', 'Nô Bộc' => 'no_boc',
            'Thiên Di' => 'thien_di', 'Tật Ách' => 'tat_ach', 'Tài Bạch' => 'tai_bach',
            'Tử Tức' => 'tu_tuc', 'Phu Thê' => 'phu_the', 'Huynh Đệ' => 'huynh_de'
        ];
        return isset($map[$name]) ? $map[$name] : null;
    }

    private function getPalaceReading($palaceData, $palaceKey) {
        $readings = ['chinh_tinh' => [], 'phu_tinh' => [], 'general' => []];

        if (!empty($palaceData['chinh_tinh'])) {
            foreach ($palaceData['chinh_tinh'] as $star) {
                $content = $this->fetchContent($star['code'], $palaceKey, 'main');
                if ($content) {
                    $readings['chinh_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
                }
            }
        }

        $allPhu = array_merge($palaceData['phu_tinh_tot'], $palaceData['phu_tinh_xau']);
        foreach ($allPhu as $star) {
            $content = $this->fetchContent($star['code'], 'general', 'meaning');
            if ($content) {
                 $readings['phu_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
            }
        }

        // Tuan/Triet logic...

        return $readings;
    }

    private function fetchContent($starKey, $palaceKey, $topic = '') {
        $sql = "SELECT content FROM " . $this->table . " WHERE star_key = :star AND (palace_key = :palace OR palace_key = 'all' OR palace_key = 'general')";
        if ($topic) $sql .= " AND topic = :topic";
        $sql .= " ORDER BY CASE WHEN palace_key = :palace THEN 1 ELSE 2 END ASC LIMIT 1";
        try {
            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':star', $starKey);
            $stmt->bindValue(':palace', $palaceKey);
            if ($topic) $stmt->bindValue(':topic', $topic);
            $stmt->execute();
            $row = $stmt->fetch();
            return $row ? $row['content'] : '';
        } catch (\Exception $e) { return ''; }
    }
}
