<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
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
        $lang = defined('NV_LANG_DATA') ? NV_LANG_DATA : 'vi';
        $modData = isset($module_data) ? $module_data : 'huyen-hoc';
        $this->table = $prefix . "_" . $lang . "_" . str_replace('-', '_', $modData) . "_interpretations";
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

            // Integrate Advanced Pattern Recognition for Menh
            if ($key == 'menh' && class_exists('\\NukeViet\\Module\\HuyenHoc\\TuViAdvanced')) {
                $adv = new TuViAdvanced(['dia_ban' => $chart['dia_ban'], 'meta' => $chart['meta']]);
                $advPatterns = $adv->detectCachCuc();
                if ($advPatterns) {
                    foreach ($advPatterns as $pat) {
                         $reading['general'][] = ['star' => 'Cách Cục Đặc Biệt', 'content' => "<strong>" . $pat['name'] . ":</strong> " . $pat['content']];
                    }
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

    public function luanGiaiNguoiThan($chart, $relation) {
        if (!class_exists('\\NukeViet\\Module\\HuyenHoc\\TuViAdvanced')) return null;

        $adv = new TuViAdvanced(['dia_ban' => $chart['dia_ban'], 'meta' => $chart['meta']]);
        $shiftedData = $adv->lapCucNguoiThan($relation);

        if (!$shiftedData) return null;

        $result = [
            'title' => $shiftedData['title'],
            'readings' => []
        ];

        // Analyze mapped palaces
        foreach ($shiftedData['mapping'] as $item) {
            $palaceName = $item['chuc_nang_moi'];
            $palaceData = $item['cung_goc'];
            $realPos = $item['real_pos'];

            // Normalize key for reading lookup (e.g. "Mệnh" -> "menh")
            $key = $this->normalizePalaceName($palaceName);

            // Use existing reading logic but pass the "real" palace data
            // Note: $palaceData contains the stars of the REAL position.
            // But we are interpreting it as the NEW function (e.g. original Phuc Duc is now Spouse's Quan Loc).
            // So we should look up meanings for "Quan Loc" ($key='quan_loc') using stars in $palaceData.

            // Handle VCD for the shifted palace
            if (empty($palaceData['chinh_tinh'])) {
                 $borrowed = $this->resolveVCD($realPos, $chart);
                 $palaceData['chinh_tinh_borrowed'] = $borrowed;
            }

            $reading = $this->getPalaceReading($palaceData, $key, $chart);

            $result['readings'][] = [
                'name' => $palaceName,
                'original_name' => $palaceData['palace_name'],
                'desc' => $item['relation_desc'],
                'reading' => $reading,
                'stars' => $this->summarizeStars($palaceData)
            ];
        }

        return $result;
    }

    private function summarizeStars($palace) {
        $main = [];
        foreach($palace['chinh_tinh'] as $s) $main[] = $s['name'];
        if (empty($main) && !empty($palace['chinh_tinh_borrowed'])) {
            $borrowed = [];
            foreach($palace['chinh_tinh_borrowed'] as $s) $borrowed[] = $s['name'];
            $mainStr = "Vô Chính Diệu (mượn " . implode(', ', $borrowed) . ")";
        } else {
            $mainStr = implode(', ', $main);
        }
        return $mainStr;
    }

    public function assessPreDestiny($chart) {
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
        $allStars = array_merge($palace['phu_tinh_tot'], $palace['phu_tinh_xau']);
        $codes = array_map(function($s) { return $s['code']; }, $allStars);

        // Tu Linh: Long Tri, Phuong Cac, Bach Ho, Hoa Cai
        if (count(array_intersect(['long_tri', 'phuong_cac', 'bach_ho', 'hoa_cai'], $codes)) >= 3) {
            $combinations[] = ['code' => 'TU_LINH', 'content' => "Bộ Tứ Linh (Long Phượng Hổ Cái): Công danh hiển hách, sự nghiệp vẻ vang, được trọng vọng."];
        }

        // Luc Sat (Hoi Tu): Kinh Da Khong Kiep Hoa Linh
        $satCount = count(array_intersect(['kinh_duong', 'da_la', 'dia_khong', 'dia_kiep', 'hoa_tinh', 'linh_tinh'], $codes));
        if ($satCount >= 3) {
            $combinations[] = ['code' => 'LUC_SAT_HOI_TU', 'content' => "Lục Sát hội tụ: Cuộc đời nhiều sóng gió, tai ương, cần tu tâm dưỡng tính để hóa giải."];
        }

        // Dao Hong
        if (in_array('dao_hoa', $codes) && in_array('hong_loan', $codes)) {
            $combinations[] = ['code' => 'DAO_HONG', 'content' => "Đào Hồng hội chiếu: Duyên dáng, thu hút người khác phái, tình duyên phong phú."];
        }

        // Xuong Khuc
        if (in_array('van_xuong', $codes) && in_array('van_khuc', $codes)) {
            $combinations[] = ['code' => 'XUONG_KHUC', 'content' => "Xương Khúc đồng cung: Văn hay chữ tốt, học hành thông minh, có năng khiếu nghệ thuật."];
        }

        // Khong Kiep
        if (in_array('dia_khong', $codes) && in_array('dia_kiep', $codes)) {
            $combinations[] = ['code' => 'KHONG_KIEP', 'content' => "Không Kiếp đồng cung: Gian nan, thăng trầm, bạo phát bạo tàn."];
        }

        // Tu Hoa (Khoa Quyen Loc)
        $tuHoaCount = count(array_intersect(['hoa_khoa', 'hoa_quyen', 'hoa_loc'], $codes));
        if ($tuHoaCount >= 2) {
             $combinations[] = ['code' => 'TAM_HOA_LIEN_CHAU', 'content' => "Tam Hóa (Khoa Quyền Lộc): Phú quý song toàn, danh tiếng lẫy lừng."];
        }

        return $combinations;
    }

    private function resolveVCD($palaceIndex, $chart) {
        $oppositeIndex = ($palaceIndex + 6) % 12;
        return $chart['dia_ban'][$oppositeIndex]['chinh_tinh'];
    }

    private function identifyPatterns($palace, $palaceKey, $chart) {
        $patterns = [];
        $stars = [];
        foreach ($palace['chinh_tinh'] as $s) $stars[] = $s['code'];
        // If VCD, check borrowed
        if (empty($stars) && !empty($palace['chinh_tinh_borrowed'])) {
            foreach ($palace['chinh_tinh_borrowed'] as $s) $stars[] = $s['code'];
        }

        // 1. Tu Phu Vu Tuong (Leadership)
        // Check if any of Tu Vi, Thien Phu, Vu Khuc, Thien Tuong, Liem Trinh are present
        $groupTPVT = ['tu_vi', 'thien_phu', 'vu_khuc', 'thien_tuong', 'liem_trinh'];
        if (array_intersect($stars, $groupTPVT)) {
             $patterns[] = ['code' => 'TU_PHU_VU_TUONG', 'content' => "Cách Tử Phủ Vũ Tướng: Có tài lãnh đạo, quản lý, cuộc sống thường ổn định, uy quyền."];
        }

        // 2. Sat Pha Tham (Action)
        $groupSPT = ['that_sat', 'pha_quan', 'tham_lang'];
        if (array_intersect($stars, $groupSPT)) {
            $patterns[] = ['code' => 'SAT_PHA_THAM', 'content' => "Cách Sát Phá Tham: Cá tính mạnh mẽ, hành động quyết liệt, thích hợp kinh doanh hoặc võ nghiệp."];
        }

        // 3. Co Nguyet Dong Luong (Stability/Support)
        $groupCNDL = ['thien_co', 'thai_am', 'thien_dong', 'thien_luong'];
        if (array_intersect($stars, $groupCNDL)) {
            $patterns[] = ['code' => 'CO_NGUYET_DONG_LUONG', 'content' => "Cách Cơ Nguyệt Đồng Lương: Thích ổn định, làm công ăn lương, hành chính, văn phòng hoặc phúc lợi."];
        }

        // 4. Cu Nhat (Public/Speech)
        $groupCN = ['cu_mon', 'thai_duong'];
        if (array_intersect($stars, $groupCN)) {
            $patterns[] = ['code' => 'CU_NHAT', 'content' => "Cách Cự Nhật: Giỏi ăn nói, ngoại giao, làm việc liên quan đến ngôn ngữ, luật pháp hoặc chính trị."];
        }

        // 5. Nhat Nguyet (Sun/Moon)
        if (in_array('thai_duong', $stars) && in_array('thai_am', $stars)) {
             $patterns[] = ['code' => 'NHAT_NGUYET', 'content' => "Cách Nhật Nguyệt đồng tranh: Thông minh nhưng hay thay đổi, cuộc đời nhiều biến động sáng tối."];
        }

        return $patterns;
    }

    private function assessPalaceStrength($palace, $palaceKey, $menhElement) {
        $score = 0;
        $goodStars = 0;
        $badStars = 0;

        foreach ($palace['chinh_tinh'] as $s) {
            if (in_array($s['dacs'], ['M', 'V', 'Đ', 'D'])) { $score += 2; $goodStars++; }
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

    public function calculateScore($chart) {
        $score = 50; // Base score
        $menhIdx = $chart['meta']['menh_idx'];
        $menhPalace = $chart['dia_ban'][$menhIdx];

        // 1. Am Duong / Ngu Hanh (+/- 5-10 pts)
        $meta = $chart['meta'];
        $isYearYang = ($meta['canYear'] % 2 == 0);
        $isPalaceYang = ($menhIdx % 2 == 0);
        if ($isYearYang == $isPalaceYang) $score += 5; else $score -= 2;

        // Menh vs Cuc
        $cucMap = [2=>1, 6=>2, 5=>3, 4=>4, 3=>5]; // Cuc ID to Element ID
        $cucEl = isset($cucMap[$meta['cuc_id']]) ? $cucMap[$meta['cuc_id']] : 0;
        $menhEl = $meta['menh_element_id'];

        // Simple relation check (Sinh/Khac)
        $sinh = [4=>1, 1=>5, 5=>2, 2=>3, 3=>4]; // Kim(4)->Thuy(1)...
        $khac = [4=>5, 5=>3, 3=>1, 1=>2, 2=>4];

        if ($menhEl == $cucEl) $score += 2; // Binh Hoa
        elseif (isset($sinh[$cucEl]) && $sinh[$cucEl] == $menhEl) $score += 5; // Cuc Sinh Menh (Tot)
        elseif (isset($sinh[$menhEl]) && $sinh[$menhEl] == $cucEl) $score -= 2; // Menh Sinh Cuc (Hao)
        elseif (isset($khac[$cucEl]) && $khac[$cucEl] == $menhEl) $score -= 5; // Cuc Khac Menh (Xau)
        elseif (isset($khac[$menhEl]) && $khac[$menhEl] == $cucEl) $score += 3; // Menh Khac Cuc (Kha)

        // 2. Chinh Tinh in Menh
        $stars = $menhPalace['chinh_tinh'];
        if (empty($stars)) {
            // VCD: Check borrowed? usually VCD is weaker unless good borrowed stars.
            // Let's take borrowed stars but reduce value.
            $stars = $menhPalace['chinh_tinh_borrowed'];
            $score -= 5; // Penalty for VCD
        }

        foreach ($stars as $s) {
            switch ($s['dacs']) {
                case 'M': $score += 5; break; // Mieu
                case 'V': $score += 4; break; // Vuong
                case 'D': case 'Đ': $score += 3; break; // Dac
                case 'B': $score += 1; break; // Binh
                case 'H': $score -= 5; break; // Ham
                default: break;
            }
        }

        // 3. Phu Tinh (Luc Sat / Luc Cat)
        // Luc Cat: Van Xuong, Van Khuc, Ta Phu, Huu Bat, Thien Khoi, Thien Viet (+1 each)
        $goodCodes = ['van_xuong', 'van_khuc', 'ta_phu', 'huu_bat', 'thien_khoi', 'thien_viet', 'hoa_khoa', 'hoa_quyen', 'hoa_loc', 'loc_ton'];
        foreach ($menhPalace['phu_tinh_tot'] as $s) {
            if (in_array($s['code'], $goodCodes)) $score += 1;
        }

        // Luc Sat: Kinh Duong, Da La, Dia Khong, Dia Kiep, Hoa Tinh, Linh Tinh (-2 each unless Dac)
        $badCodes = ['kinh_duong', 'da_la', 'dia_khong', 'dia_kiep', 'hoa_tinh', 'linh_tinh'];
        foreach ($menhPalace['phu_tinh_xau'] as $s) {
            if (in_array($s['code'], $badCodes)) {
                if ($s['dacs'] == 'D' || $s['dacs'] == 'M') $score += 1; // Dac dia phat da nhu loi
                else $score -= 2;
            }
        }

        // Clamp
        if ($score > 100) $score = 100;
        if ($score < 0) $score = 0;

        return $score;
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
