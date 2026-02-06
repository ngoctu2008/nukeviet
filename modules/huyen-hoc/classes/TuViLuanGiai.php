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

        // 1. Tien Thien (Goc re) - Step 1 of Logic
        $result['tien_thien'] = $this->assessPreDestiny($chart);

        // 2. Score
        $result['score'] = $this->calculateScore($chart);

        // 3. Luan Giai 12 Cung & Patterns
        foreach ($chart['dia_ban'] as $i => $palace) {
            $key = $this->normalizePalaceName($palace['palace_name']);

            // Step 5: VCD Logic (Borrow stars)
            if (empty($palace['chinh_tinh'])) {
                $borrowed = $this->resolveVCD($i, $chart);
                $palace['chinh_tinh_borrowed'] = $borrowed;
            }

            // Standard reading with Enhanced Logic (Step 2 & 3)
            $reading = $key ? $this->getPalaceReading($palace, $key, $chart) : ['chinh_tinh'=>[], 'phu_tinh'=>[], 'general'=>[]];

            // Step 4: Patterns (Cach Cuc)
            $patterns = $this->identifyPatterns($palace, $key, $chart);
            if ($patterns) {
                foreach ($patterns as $pat) {
                    $reading['general'][] = ['star' => 'Cách Cục', 'content' => $pat['content']];
                }
            }

            // Cuong Nhuoc (Strength)
            $strength = $this->assessPalaceStrength($palace, $key, $chart['thien_ban']['menh_ngu_hanh']);
            $reading['general'][] = ['star' => 'Đánh giá', 'content' => $strength];

            if ($key) $result[$key] = $reading;
            $result[$i] = $reading;
        }

        return $result;
    }

    /**
     * Section 1: Tien Thien Analysis (Am Duong / Ngu Hanh)
     */
    private function assessPreDestiny($chart) {
        $meta = $chart['meta'];
        $comments = [];

        // 1. Am Duong Ly
        // 0=Giap (Yang), 1=At (Yin).
        $canYear = $meta['canYear'];
        $menhIdx = $meta['menh_idx']; // 0=Ty, 1=Suu

        $isYearYang = ($canYear % 2 == 0);
        $isPalaceYang = ($menhIdx % 2 == 0); // Ty (0) is Yang? Wait. Ty(0) is Yang, Suu(1) is Yin.

        // Ty(0) is Yang Water. Suu(1) is Yin Earth.
        // Even index = Yang? Yes. 0,2,4...
        // canYear: 0=Giap (Yang), 1=At (Yin). Even = Yang.

        if ($isYearYang == $isPalaceYang) {
             $keyAD = 'MENH_AM_DUONG_THUAN_LY';
        } else {
             $keyAD = 'MENH_AM_DUONG_NGHICH_LY';
        }
        $comments['am_duong'] = $this->fetchContent($keyAD, 'general', 'pattern');

        // 2. Ngu Hanh Sinh Khac (Menh vs Cuc)
        // Meta has menh_element_id and cuc_id. Need Cuc Element.
        // Map Cuc ID (2..6) to Element.
        // 2=Thuy, 3=Moc, 4=Kim, 5=Tho, 6=Hoa. (Based on TuViLapSo: $cucMap = array(1 => 2, 2 => 6, 3 => 5, 4 => 4, 5 => 3))
        // Wait, TuViLapSo $cucMap maps ElementID -> CucID.
        // 1(Thuy) -> 2. So CucID 2 is Water.
        // 2(Hoa) -> 6. So CucID 6 is Fire.
        // 3(Tho) -> 5. So CucID 5 is Earth.
        // 4(Kim) -> 4. So CucID 4 is Metal.
        // 5(Moc) -> 3. So CucID 3 is Wood.
        $cucElMap = [2 => 1, 6 => 2, 5 => 3, 4 => 4, 3 => 5];

        $menhEl = $meta['menh_element_id'];
        $cucID = $meta['cuc_id'];
        $cucEl = isset($cucElMap[$cucID]) ? $cucElMap[$cucID] : 0;

        $keyCuc = 'MENH_CUC_BINH_HOA';
        // Elements: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc
        // Sinh: 4->1, 1->5, 5->2, 2->3, 3->4
        // Khac: 4->5, 5->3, 3->1, 1->2, 2->4
        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4];
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4];

        if ($menhEl == $cucEl) $keyCuc = 'MENH_CUC_BINH_HOA';
        elseif (isset($sinh[$cucEl]) && $sinh[$cucEl] == $menhEl) $keyCuc = 'CUC_SINH_MENH';
        elseif (isset($sinh[$menhEl]) && $sinh[$menhEl] == $cucEl) $keyCuc = 'MENH_SINH_CUC';
        elseif (isset($khac[$cucEl]) && $khac[$cucEl] == $menhEl) $keyCuc = 'CUC_KHAC_MENH';
        elseif (isset($khac[$menhEl]) && $khac[$menhEl] == $cucEl) $keyCuc = 'MENH_KHAC_CUC';

        $comments['cuc_menh'] = $this->fetchContent($keyCuc, 'general', 'pattern');

        return $comments;
    }

    /**
     * Section 2 & 3: Enhanced Palace Reading
     */
    private function getPalaceReading($palaceData, $palaceKey, $chart) {
        $readings = ['chinh_tinh' => [], 'phu_tinh' => [], 'general' => []];

        $starsToCheck = $palaceData['chinh_tinh'];
        $isBorrowed = false;

        if (empty($starsToCheck) && !empty($palaceData['chinh_tinh_borrowed'])) {
            $starsToCheck = $palaceData['chinh_tinh_borrowed'];
            $isBorrowed = true;
            $readings['general'][] = ['star' => 'Vô Chính Diệu', 'content' => $this->fetchContent('VO_CHINH_DIEU_GENERAL', 'general', 'pattern')];
        }

        foreach ($starsToCheck as $star) {
            // Priority 1: Specific Position (Step 2 Logic)
            // Key format: SAO_{CODE}_{PALACE_KEY} e.g. SAO_TU_VI_MENH (Generic)
            // Or SAO_{CODE}_CU_{CHI} e.g. SAO_TU_VI_CU_NGO

            $chiKey = TuViLapSo::$DIA_CHI_KEYS[$palaceData['index']]; // ngo, ty, etc.
            $posKey = 'SAO_' . strtoupper($star['code']) . '_CU_' . strtoupper($chiKey);
            $content = $this->fetchContent($posKey, 'general', 'pattern'); // High priority

            if (!$content) {
                 // Priority 2: Generic Star in Palace (Existing logic)
                 $content = $this->fetchContent($star['code'], $palaceKey, 'main');
            }

            // Step 3: Tuan/Triet Logic
            if ($palaceData['tuan'] || $palaceData['triet']) {
                $ttKey = 'SAO_' . strtoupper($star['code']) . '_GAP_TUAN_TRIET';
                $ttContent = $this->fetchContent($ttKey, 'general', 'pattern');
                if ($ttContent) {
                    $content .= " <br><b>Gặp Tuần/Triệt:</b> " . $ttContent;
                } else {
                    $content .= " <br><b>Gặp Tuần/Triệt:</b> Ý nghĩa thay đổi, giảm bớt sự tốt/xấu.";
                }
            }

            if ($isBorrowed) $content = "(Xung Chiếu) " . $content;

            if ($content) {
                $readings['chinh_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
            }
        }

        // Phu Tinh
        $allPhu = array_merge($palaceData['phu_tinh_tot'], $palaceData['phu_tinh_xau']);
        foreach ($allPhu as $star) {
            $content = $this->fetchContent($star['code'], 'general', 'meaning');
            if ($content) {
                 $readings['phu_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
            }
        }

        return $readings;
    }

    /**
     * Step 5: Resolve VCD
     */
    private function resolveVCD($palaceIndex, $chart) {
        $oppositeIndex = ($palaceIndex + 6) % 12;
        return $chart['dia_ban'][$oppositeIndex]['chinh_tinh'];
    }

    /**
     * Section 3: Pattern Recognition (Step 4)
     */
    private function identifyPatterns($palace, $palaceKey, $chart) {
        $patterns = [];

        // Helper to check star presence in Trine (Tam Hop)
        $idx = $palace['index'];
        $trineIndices = [$idx, ($idx + 4) % 12, ($idx + 8) % 12];
        $oppIndex = ($idx + 6) % 12;
        $checkIndices = array_merge($trineIndices, [$oppIndex]); // Tam Phuong Tu Chinh

        $hasStarInSet = function($code) use ($checkIndices, $chart) {
            foreach ($checkIndices as $i) {
                $p = $chart['dia_ban'][$i];
                // Check all star lists
                foreach (array_merge($p['chinh_tinh'], $p['phu_tinh_tot'], $p['phu_tinh_xau']) as $s) {
                    if ($s['code'] == $code) return true;
                }
            }
            return false;
        };

        // 1. TU PHU VU TUONG
        if ($palaceKey == 'menh') {
            if ($this->hasStar($palace, 'tu_vi') && $hasStarInSet('thien_phu') && $hasStarInSet('vu_khuc') && $hasStarInSet('thien_tuong')) {
                 $patterns[] = ['code' => 'TU_PHU_VU_TUONG', 'content' => $this->fetchContent('CACH_TU_PHU_VU_TUONG', 'general', 'pattern')];
            }
        }

        // 2. SAT PHA THAM
        if ($palaceKey == 'menh') {
            if ($this->hasStar($palace, 'that_sat') && $hasStarInSet('pha_quan') && $hasStarInSet('tham_lang')) {
                 $patterns[] = ['code' => 'SAT_PHA_THAM', 'content' => $this->fetchContent('CACH_SAT_PHA_THAM', 'general', 'pattern')];
            }
            // Check variations: Pha Quan Thu Menh, Tham Lang Thu Menh
             elseif ($this->hasStar($palace, 'pha_quan') && $hasStarInSet('that_sat') && $hasStarInSet('tham_lang')) {
                 $patterns[] = ['code' => 'SAT_PHA_THAM', 'content' => $this->fetchContent('CACH_SAT_PHA_THAM', 'general', 'pattern')];
            }
             elseif ($this->hasStar($palace, 'tham_lang') && $hasStarInSet('pha_quan') && $hasStarInSet('that_sat')) {
                 $patterns[] = ['code' => 'SAT_PHA_THAM', 'content' => $this->fetchContent('CACH_SAT_PHA_THAM', 'general', 'pattern')];
            }
        }

        // 3. CO NGUYET DONG LUONG
         if ($palaceKey == 'menh') {
            // Usually Co Luong or Dong Luong or Co Nguyet.
            // Check presence of at least 3 of 4.
            $count = 0;
            if ($hasStarInSet('thien_co')) $count++;
            if ($hasStarInSet('thai_am')) $count++;
            if ($hasStarInSet('thien_dong')) $count++;
            if ($hasStarInSet('thien_luong')) $count++;

            if ($count >= 3 && ($this->hasStar($palace, 'thien_co') || $this->hasStar($palace, 'thai_am') || $this->hasStar($palace, 'thien_dong') || $this->hasStar($palace, 'thien_luong'))) {
                 $patterns[] = ['code' => 'CO_NGUYET_DONG_LUONG', 'content' => $this->fetchContent('CACH_CO_NGUYET_DONG_LUONG', 'general', 'pattern')];
            }
         }

        return $patterns;
    }

    private function assessPalaceStrength($palace, $palaceKey, $menhElement) {
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
        return $eval;
    }

    private function calculateScore($chart) {
        $total = 50;
        $palacesToCheck = ['menh', 'than', 'tai_bach', 'quan_loc'];
        foreach ($chart['dia_ban'] as $p) {
            $key = $this->normalizePalaceName($p['palace_name']);
            if (in_array($key, $palacesToCheck)) {
                foreach ($p['chinh_tinh'] as $s) {
                    if (in_array($s['dacs'], ['M', 'V'])) $total += 5;
                    elseif ($s['dacs'] == 'Đ') $total += 3;
                    elseif ($s['dacs'] == 'H') $total -= 3;
                }
                $total += count($p['phu_tinh_tot']);
                $total -= count($p['phu_tinh_xau']);
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

    public function generateStructuredReport($laSo) {
        $analysis = $this->luanGiai($laSo);
        $report = [
            'section_1' => [
                'info' => "Đương số: " . $laSo['thien_ban']['ho_ten'],
                'am_duong' => [$analysis['tien_thien']['am_duong'], $analysis['tien_thien']['cuc_menh']],
                'menh_than' => ['menh' => '...', 'than' => '...']
            ],
            'section_2' => [],
            'section_3' => [],
            'score' => $analysis['score']
        ];

        foreach ($laSo['dia_ban'] as $i => $p) {
             $report['section_2'][] = [
                 'name' => $p['palace_name'],
                 'reading' => $analysis[$i]
             ];
        }
        return $report;
    }

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
