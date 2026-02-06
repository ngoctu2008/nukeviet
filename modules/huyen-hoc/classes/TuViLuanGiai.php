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
        // Sanitize module_data to match action_mysql.php (remove hyphen)
        $mod_data_sanitized = str_replace('-', '', $module_data);
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

    /**
     * Generate Structured Report (Section I, II, III)
     */
    public function generateStructuredReport($laSo) {
        $report = [
            'section_1' => $this->genSection1($laSo),
            'section_2' => $this->genSection2($laSo),
            'section_3' => $this->genSection3($laSo)
        ];
        return $report;
    }

    private function genSection1($laSo) {
        $tb = $laSo['thien_ban'];
        $db = $laSo['dia_ban'];

        // 1. User Info
        $info = "Thông tin đương số: " . $tb['ho_ten'] . ", sinh năm " . $tb['nam_sinh'] . ".";

        // 2. Am Duong Ngu Hanh
        $adnh = [
            'Tuổi: ' . $tb['am_duong'],
            'Mệnh: ' . $tb['menh_ngu_hanh'],
            'Cục: ' . $tb['cuc'],
            'Tương quan Mệnh - Cục: ' . $tb['cuc_menh_ly']
        ];

        // 3. Menh - Than
        $menhIdx = $this->findPalaceIndex($db, 'Mệnh');
        $thanIdx = $this->findPalaceIndex($db, 'Thân');

        $menhContent = "Mệnh đóng tại cung " . $db[$menhIdx]['name'] . ".";
        // Check relation Mệnh Element vs Palace Element? (Kim Sinh Thuy example)
        // Need Palace Element.
        // Ty/Hoi=Thuy, Dan/Mao=Moc, Ty/Ngo=Hoa, Thin/Tuat/Suu/Mui=Tho, Than/Dau=Kim.
        $pElNames = [0=>'Thủy',1=>'Thổ',2=>'Mộc',3=>'Mộc',4=>'Thổ',5=>'Hỏa',6=>'Hỏa',7=>'Thổ',8=>'Kim',9=>'Kim',10=>'Thổ',11=>'Thủy'];
        $pElName = $pElNames[$menhIdx];
        $menhContent .= " Cung hành " . $pElName . ".";

        $thanInfo = TuViLapSo::getThanInfo($laSo['meta']['chiYear']); // Wait, getThanInfo needs Hour index? No, LapSo passes hh.
        // Actually, we can just look at $thanIdx.
        // But getThanInfo returns the meaning text.
        // We don't have hh here easily unless in meta.
        // Let's deduce Hour from Than pos relative to Menh?
        // PosThan = (2 + mm - 1 + hh) % 12. PosMenh = (2 + mm - 1 - hh) % 12.
        // Too complex. Let's just use the Palace Name of Than to fetch meaning from `getThanInfo` logic map (or duplicate it).
        // Actually `TuViLapSo::getThanInfo` expects Hour.
        // Let's implement helper here or just map based on the Than Palace Name (Quan Loc, Tai Bach...).
        $thanName = preg_replace('/\s*\(.*?\)/', '', $db[$thanIdx]['palace_name']);
        $thanMap = [
            'Mệnh' => 'Người tin vào chính mình, tự lập.',
            'Phúc Đức' => 'Coi trọng dòng họ, hưởng phúc tổ tiên.',
            'Quan Lộc' => 'Mẫu người của công việc, danh vọng.',
            'Thiên Di' => 'Thích hoạt động xã hội, hay di chuyển.',
            'Tài Bạch' => 'Coi trọng tiền bạc, có khiếu kinh doanh.',
            'Phu Thê' => 'Coi trọng gia đình, sự nghiệp ảnh hưởng bởi phối ngẫu.'
        ];
        $thanContent = "Thân cư " . $thanName . ". " . (isset($thanMap[$thanName]) ? $thanMap[$thanName] : '');

        return [
            'info' => $info,
            'am_duong' => $adnh,
            'menh_than' => [
                'menh' => $menhContent,
                'than' => $thanContent
            ]
        ];
    }

    private function genSection2($laSo) {
        $db = $laSo['dia_ban'];
        $palaces = ['Mệnh', 'Quan Lộc', 'Tài Bạch', 'Thiên Di', 'Phu Thê', 'Tử Tức', 'Phúc Đức', 'Điền Trạch'];

        $details = [];
        foreach ($palaces as $pName) {
            $idx = $this->findPalaceIndex($db, $pName);
            if ($idx === false) continue;

            $pData = $db[$idx];
            $key = $this->normalizePalaceName($pName);
            $reading = $this->getPalaceReading($pData, $key);

            // Check Patterns
            $patterns = $this->checkPatterns($pData, $pName);
            if ($patterns) {
                // Add patterns to general reading or separate?
                // Let's append to general
                foreach ($patterns as $pat) {
                    $reading['general'][] = ['star' => 'Cách Cục', 'content' => $pat];
                }
            }

            $details[] = [
                'name' => $pName . " (Tại " . $pData['name'] . ")",
                'reading' => $reading
            ];
        }
        return $details;
    }

    private function genSection3($laSo) {
        // Requires limit_info in meta
        if (!isset($laSo['meta']['limit_info'])) return [];
        $lim = $laSo['meta']['limit_info'];

        $content = [];
        $content[] = "Tuổi Âm: " . $lim['age_am'] . " tuổi.";

        // Dai Van
        // Need to find which Dai Van palace we are in.
        // Iterate dia_ban to find matching dai_van range.
        // Format: 36 - 45.
        // Logic: Palace has 'dai_van' start age. Next palace has start+10.
        // Current age $lim['age_am'].
        $daiVanIdx = -1;
        $daiVanStart = 0;
        foreach ($laSo['dia_ban'] as $idx => $p) {
             if ($lim['age_am'] >= $p['dai_van'] && $lim['age_am'] < $p['dai_van'] + 10) {
                 $daiVanIdx = $idx;
                 $daiVanStart = $p['dai_van'];
                 break;
             }
        }

        if ($daiVanIdx !== -1) {
            $dvName = $laSo['dia_ban'][$daiVanIdx]['palace_name'];
            $dvChi = $laSo['dia_ban'][$daiVanIdx]['name'];
            $content[] = "Đại vận (" . $daiVanStart . " - " . ($daiVanStart + 9) . " tuổi): Đang nằm tại cung " . $dvName . " (" . $dvChi . ").";

            // Add stars in Dai Van
            $stars = [];
            foreach ($laSo['dia_ban'][$daiVanIdx]['chinh_tinh'] as $s) $stars[] = $s['name'];
            if ($stars) $content[] = "Chính tinh đại vận: " . implode(", ", $stars) . ".";
        }

        // Luu Nien
        // Target Chi is in $lim.
        $content[] = "Lưu niên: Năm " . $lim['target_chi'] . ".";

        // Han & Sao
        $content[] = "Sao chiếu mệnh: " . $lim['sao_han']['name'] . " (" . ($lim['sao_han']['type']=='tot'?'Tốt':($lim['sao_han']['type']=='xau'?'Xấu':'Trung bình')) . ").";
        $content[] = "Hạn: " . $lim['han'] . ".";
        if ($lim['tam_tai']) $content[] = "Phạm Tam Tai: Có.";
        else $content[] = "Phạm Tam Tai: Không.";

        return $content;
    }

    private function checkPatterns($pData, $pName) {
        $patterns = [];
        // Helper to check star presence
        $hasStar = function($code) use ($pData) {
            foreach ($pData['chinh_tinh'] as $s) if ($s['code'] == $code) return true;
            foreach ($pData['phu_tinh_tot'] as $s) if ($s['code'] == $code) return true;
            foreach ($pData['phu_tinh_xau'] as $s) if ($s['code'] == $code) return true;
            return false;
        };

        // 1. Song Hao Mao Dau (Chung Thuy Trieu Dong)
        // Palace Chi: Mao(3) or Dau(9).
        // Star: Tieu Hao or Dai Hao.
        if (in_array($pData['key'], ['mao', 'dau'])) {
            if ($hasStar('tieu_hao') || $hasStar('dai_hao')) {
                $patterns[] = "Cách 'Chúng thủy triều đông' (Song Hao Mão Dậu): Chủ về người hào phóng, tiền bạc luân chuyển mạnh, thích hợp kinh doanh.";
            }
        }

        // 2. Dong Luong Ty Hoi
        // Palace Chi: Ty_Nho(5) or Hoi(11).
        // Star: Thien Dong or Thien Luong.
        if (in_array($pData['key'], ['ty_nho', 'hoi'])) {
            if ($hasStar('thien_dong') || $hasStar('thien_luong')) {
                 $patterns[] = "Cách 'Đồng Lương Tỵ Hợi' (Khách phiêu bồng): Chủ về sự thay đổi, di chuyển nhiều, thích tự do.";
            }
        }

        // 3. Nhat Nguyet Tranh Huy (Thai Am / Thai Duong at Suu/Mui)
        if (in_array($pData['key'], ['suu', 'mui'])) {
            if ($hasStar('thai_am') && $hasStar('thai_duong')) {
                 $patterns[] = "Cách 'Nhật Nguyệt Tranh Huy': Chủ về sự thăng trầm, mâu thuẫn nhưng cũng dễ thành đạt nếu có Tuần/Triệt hoặc Hóa Kỵ.";
            }
            // Or if one is here and other opposite?
            // In Suu/Mui, Thai Am and Thai Duong are always together (Dong Cung).
        }

        return $patterns;
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
                        'star_code' => $star['code'],
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
                    'star_code' => $star['code'],
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
