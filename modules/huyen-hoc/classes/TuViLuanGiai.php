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
    protected $starMeanings = [];

    public function __construct($starMeanings = []) {
        global $db, $db_config, $module_data;
        $this->db = $db;
        $prefix = isset($db_config['prefix']) ? $db_config['prefix'] : 'nv4';
        $modData = isset($module_data) ? $module_data : 'huyen-hoc';
        $this->table = $prefix . "_" . NV_LANG_DATA . "_" . str_replace('-', '_', $modData) . "_interpretations";
        $this->starMeanings = $starMeanings;
    }

    public function setStarMeanings($meanings) {
        $this->starMeanings = $meanings;
    }

    public function luanGiai($chart) {
        $result = [];

        // 1. Tien Thien
        $result['tien_thien'] = $this->assessPreDestiny($chart);

        // 2. Score
        $result['score'] = $this->calculateScore($chart);

        // 3. Luan Giai 12 Cung
        foreach ($chart['dia_ban'] as $i => $palace) {
            $key = $this->normalizePalaceName($palace['palace_name']);

            if (empty($palace['chinh_tinh'])) {
                $borrowed = $this->resolveVCD($i, $chart);
                $palace['chinh_tinh_borrowed'] = $borrowed;
            }

            $reading = $key ? $this->getPalaceReading($palace, $key, $chart) : ['chinh_tinh'=>[], 'phu_tinh'=>[], 'general'=>[]];

            $patterns = $this->identifyPatterns($palace, $key, $chart);
            if ($patterns) {
                foreach ($patterns as $pat) {
                    $reading['general'][] = ['star' => 'Cách Cục', 'content' => $pat['content']];
                }
            }

            $strength = $this->assessPalaceStrength($palace, $key, $chart['thien_ban']['menh_ngu_hanh']);
            $reading['evaluation'] = $strength;

            if ($key) $result[$key] = $reading;
            $result[$i] = $reading;
        }

        // 4. Tong Quan
        if (empty($result['tong_quan_menh'])) {
             $menhPalace = $chart['dia_ban'][$chart['meta']['menh_idx']];
             $starList = [];
             foreach($menhPalace['chinh_tinh'] as $s) $starList[] = $s['name'];
             $result['tong_quan_menh'] = "Mệnh an tại " . $menhPalace['name'] . ", có các sao chính: " . (implode(', ', $starList) ?: "Vô Chính Diệu");
        }

        if (empty($result['tong_quan_than'])) {
             $thanPalace = $chart['dia_ban'][$chart['meta']['than_idx']];
             $starList = [];
             foreach($thanPalace['chinh_tinh'] as $s) $starList[] = $s['name'];
             $result['tong_quan_than'] = "Thân cư " . $thanPalace['name'] . ", có các sao chính: " . (implode(', ', $starList) ?: "Vô Chính Diệu");
        }

        return $result;
    }

    private function assessPreDestiny($chart) {
        $meta = $chart['meta'];
        $comments = [];

        $canYear = $meta['canYear'];
        $menhIdx = $meta['menh_idx'];
        $isYearYang = ($canYear % 2 == 0);
        $isPalaceYang = ($menhIdx % 2 == 0);

        if ($isYearYang == $isPalaceYang) {
             $keyAD = 'MENH_AM_DUONG_THUAN_LY';
        } else {
             $keyAD = 'MENH_AM_DUONG_NGHICH_LY';
        }
        $comments['am_duong'] = $this->fetchContent($keyAD, 'general', 'pattern');
        if (!$comments['am_duong']) {
            $comments['am_duong'] = ($isYearYang == $isPalaceYang)
                ? "Âm Dương Thuận Lý: Độ số gia tăng, gặp nhiều thuận lợi."
                : "Âm Dương Nghịch Lý: Độ số giảm bớt, cần nỗ lực nhiều hơn.";
        }

        $cucElMap = [2 => 1, 6 => 2, 5 => 3, 4 => 4, 3 => 5];
        $menhEl = $meta['menh_element_id'];
        $cucID = $meta['cuc_id'];
        $cucEl = isset($cucElMap[$cucID]) ? $cucElMap[$cucID] : 0;

        $keyCuc = 'MENH_CUC_BINH_HOA';
        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4];
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4];

        if ($menhEl == $cucEl) $keyCuc = 'MENH_CUC_BINH_HOA';
        elseif (isset($sinh[$cucEl]) && $sinh[$cucEl] == $menhEl) $keyCuc = 'CUC_SINH_MENH';
        elseif (isset($sinh[$menhEl]) && $sinh[$menhEl] == $cucEl) $keyCuc = 'MENH_SINH_CUC';
        elseif (isset($khac[$cucEl]) && $khac[$cucEl] == $menhEl) $keyCuc = 'CUC_KHAC_MENH';
        elseif (isset($khac[$menhEl]) && $khac[$menhEl] == $cucEl) $keyCuc = 'MENH_KHAC_CUC';

        $comments['cuc_menh'] = $this->fetchContent($keyCuc, 'general', 'pattern');
        if (!$comments['cuc_menh']) {
             $defaultCuc = [
                 'MENH_CUC_BINH_HOA' => "Cục Mệnh Bình Hòa: Cuộc đời bình ổn.",
                 'CUC_SINH_MENH' => "Cục Sinh Mệnh: Được hoàn cảnh ưu đãi, dễ thành công.",
                 'MENH_SINH_CUC' => "Mệnh Sinh Cục: Phải hao tâm tổn trí cho hoàn cảnh.",
                 'CUC_KHAC_MENH' => "Cục Khắc Mệnh: Hoàn cảnh khắc nghiệt, nhiều thử thách.",
                 'MENH_KHAC_CUC' => "Mệnh Khắc Cục: Khắc chế được hoàn cảnh, nghị lực phi thường."
             ];
             $comments['cuc_menh'] = isset($defaultCuc[$keyCuc]) ? $defaultCuc[$keyCuc] : "";
        }

        return $comments;
    }

    private function getPalaceReading($palaceData, $palaceKey, $chart) {
        $readings = ['chinh_tinh' => [], 'phu_tinh' => [], 'general' => []];

        $starsToCheck = $palaceData['chinh_tinh'];
        $isBorrowed = false;

        if (empty($starsToCheck) && !empty($palaceData['chinh_tinh_borrowed'])) {
            $starsToCheck = $palaceData['chinh_tinh_borrowed'];
            $isBorrowed = true;
            $readings['general'][] = ['star' => 'Vô Chính Diệu', 'content' => "Cung Vô Chính Diệu, mượn sao xung chiếu."];
        }

        foreach ($starsToCheck as $star) {
            $content = '';

            $chiKey = TuViLapSo::$DIA_CHI_KEYS[$palaceData['index']];
            if ($chiKey == 'ty_chuot') $chiKey = 'ty';
            if ($chiKey == 'ty_ran') $chiKey = 'ti';

            $posKey = 'SAO_' . strtoupper($star['code']) . '_CU_' . strtoupper($chiKey);
            $content = $this->fetchContent($posKey, 'general', 'pattern');

            if (!$content) {
                 $content = $this->fetchContent($star['code'], $palaceKey, 'main');
            }

            if (!$content && isset($this->starMeanings[$star['code']])) {
                $info = $this->starMeanings[$star['code']];
                if (isset($info['y_nghia'][$palaceKey])) {
                    $content = $info['y_nghia'][$palaceKey];
                } elseif (isset($info['dac_tinh'])) {
                    $content = $info['dac_tinh'];
                }
            }

            if (!$content) $content = "Đang cập nhật...";

            if ($palaceData['tuan'] || $palaceData['triet']) {
                $ttKey = 'SAO_' . strtoupper($star['code']) . '_GAP_TUAN_TRIET';
                $ttContent = $this->fetchContent($ttKey, 'general', 'pattern');
                if (!$ttContent) $ttContent = "Ý nghĩa thay đổi, giảm bớt sự tốt/xấu.";
                $content .= " <br><b>Gặp Tuần/Triệt:</b> " . $ttContent;
            }

            if ($isBorrowed) $content = "(Xung Chiếu) " . $content;

            $readings['chinh_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
        }

        $allPhu = array_merge($palaceData['phu_tinh_tot'], $palaceData['phu_tinh_xau']);
        foreach ($allPhu as $star) {
            $content = $this->fetchContent($star['code'], 'general', 'meaning');

            if (!$content && isset($this->starMeanings[$star['code']])) {
                $info = $this->starMeanings[$star['code']];
                if (isset($info['y_nghia'][$palaceKey])) {
                    $content = $info['y_nghia'][$palaceKey];
                } elseif (isset($info['y_nghia']['general'])) {
                    $content = $info['y_nghia']['general'];
                } elseif (isset($info['dac_tinh'])) {
                    $content = $info['dac_tinh'];
                }
            }

            if ($content) {
                 $readings['phu_tinh'][] = ['star_code' => $star['code'], 'star' => $star['name'], 'content' => $content];
            }
        }

        $minorCombs = $this->analyzeMinorStarCombinations($palaceData, $palaceKey);
        if ($minorCombs) {
            foreach ($minorCombs as $comb) {
                $readings['general'][] = ['star' => 'Bộ Sao Phụ', 'content' => $comb['content']];
            }
        }

        return $readings;
    }

    private function analyzeMinorStarCombinations($palace, $palaceKey) {
        $combinations = [];
        $hasStarLocal = function($code) use ($palace) {
            foreach (array_merge($palace['phu_tinh_tot'], $palace['phu_tinh_xau']) as $s) {
                if ($s['code'] == $code) return true;
            }
            return false;
        };

        if ($hasStarLocal('dao_hoa') && $hasStarLocal('hong_loan')) $combinations[] = ['code' => 'DAO_HONG', 'content' => "Đào Hồng hội chiếu: Duyên dáng, thu hút người khác phái."];
        if ($hasStarLocal('van_xuong') && $hasStarLocal('van_khuc')) $combinations[] = ['code' => 'XUONG_KHUC', 'content' => "Xương Khúc đồng cung: Văn hay chữ tốt, học hành thông minh."];
        if ($hasStarLocal('dia_khong') && $hasStarLocal('dia_kiep')) $combinations[] = ['code' => 'KHONG_KIEP', 'content' => "Không Kiếp đồng cung: Gian nan, thăng trầm, nhưng phát dã như lôi."];

        return $combinations;
    }

    private function resolveVCD($palaceIndex, $chart) {
        $oppositeIndex = ($palaceIndex + 6) % 12;
        return $chart['dia_ban'][$oppositeIndex]['chinh_tinh'];
    }

    private function identifyPatterns($palace, $palaceKey, $chart) {
        return [];
    }

    private function assessPalaceStrength($palace, $palaceKey, $menhElement) {
        $score = 0;
        $goodStars = 0;
        $badStars = 0;

        foreach ($palace['chinh_tinh'] as $s) {
            if (in_array($s['dacs'], ['M', 'V', 'Đ'])) { $score += 2; $goodStars++; }
            elseif ($s['dacs'] == 'H') { $score -= 2; $badStars++; }
        }
        foreach ($palace['phu_tinh_tot'] as $s) { $score += 1; $goodStars++; }
        foreach ($palace['phu_tinh_xau'] as $s) { $score -= 1; $badStars++; }

        $eval = "";
        if ($score > 5) $eval = "Cung Vượng (Rất Tốt)";
        elseif ($score > 0) $eval = "Cung Khá (Tốt)";
        elseif ($score == 0) $eval = "Bình thường";
        elseif ($score > -5) $eval = "Cung Yếu (Xấu)";
        else $eval = "Cung Hãm (Rất Xấu)";

        return [
            'rating' => $eval,
            'score' => $score,
            'good_count' => $goodStars,
            'bad_count' => $badStars,
            'text' => "$eval - Tổng điểm đánh giá: $score. ($goodStars sao tốt, $badStars sao xấu)."
        ];
    }

    private function calculateScore($chart) {
        return 75;
    }

    private function fetchContent($starKey, $palaceKey, $topic = '') {
        if (!$this->db) return '';

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

    public function generateStructuredReport($laSo) {
        $analysis = $this->luanGiai($laSo);
        $report = [
            'section_1' => [
                'info' => "Đương số: " . $laSo['thien_ban']['ho_ten'],
                'am_duong' => $analysis['tien_thien']['am_duong'],
                'cuc_menh' => $analysis['tien_thien']['cuc_menh'],
                'menh_text' => $analysis['tong_quan_menh'] ?? '...',
                'than_text' => $analysis['tong_quan_than'] ?? '...'
            ],
            'section_2' => [],
            'section_3' => [],
            'score' => $analysis['score']
        ];

        foreach ($laSo['dia_ban'] as $i => $p) {
             if (isset($analysis[$i])) {
                 $report['section_2'][] = [
                     'name' => $p['palace_name'],
                     'reading' => $analysis[$i],
                     'evaluation' => isset($analysis[$i]['evaluation']) ? $analysis[$i]['evaluation'] : null
                 ];
             }
        }

        // Section 3: Limits
        // Check if birth_year is available in meta
        if (isset($laSo['meta']['birth_year']) && is_numeric($laSo['meta']['birth_year'])) {
            $currentYear = date('Y');
            $birthYear = $laSo['meta']['birth_year'];
            $age = $currentYear - $birthYear + 1; // Lunar Age approx

            $limitAnalysis = $this->luanGiaiHan($laSo, $age, $currentYear);

            if (isset($limitAnalysis['dai_van'])) {
                $report['section_3']['dai_van'] = $limitAnalysis['dai_van'];
            }
            if (isset($limitAnalysis['tieu_van'])) {
                $report['section_3']['tieu_van'] = $limitAnalysis['tieu_van'];
            }
        }

        return $report;
    }

    public function luanGiaiHan($chart, $age, $year) {
        // Find Dai Van
        $daiVanIdx = -1;
        $found = false;
        foreach ($chart['dia_ban'] as $i => $p) {
            $start = $p['dai_van'];
            if ($age >= $start && $age < ($start + 10)) {
                $daiVanIdx = $i;
                $found = true;
                break;
            }
        }

        $results = [];

        if ($found) {
            $daiVanPalace = $chart['dia_ban'][$daiVanIdx];
            $results['dai_van'] = [
                'name' => "Đại Vận ($age - " . ($daiVanPalace['dai_van']+9) . " tuổi) tại " . $daiVanPalace['name'],
                'reading' => $this->getPalaceReading($daiVanPalace, 'general', $chart),
                'evaluation' => $this->assessPalaceStrength($daiVanPalace, 'general', $chart['thien_ban']['menh_ngu_hanh'])
            ];
        }

        // Tieu Van
        // Reuse logic from TuViLapSo if available
        $birthYear = isset($chart['meta']['birth_year']) ? $chart['meta']['birth_year'] : ($year - $age + 1);
        $limitInfo = TuViLapSo::getLimitInfoForYear(
            $chart['meta']['chiYear'],
            $chart['meta']['gender'],
            $year,
            $birthYear
        );

        if ($limitInfo) {
            $tvIdx = $limitInfo['tieu_van_idx'];
            $tvPalace = $chart['dia_ban'][$tvIdx];
             $results['tieu_van'] = [
                'name' => "Tiểu Vận năm $year (" . $limitInfo['target_chi'] . ") tại " . $tvPalace['name'],
                'reading' => $this->getPalaceReading($tvPalace, 'general', $chart),
                'evaluation' => $this->assessPalaceStrength($tvPalace, 'general', $chart['thien_ban']['menh_ngu_hanh'])
            ];
        }

        return $results;
    }
}
