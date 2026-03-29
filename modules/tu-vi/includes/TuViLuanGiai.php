<?php

namespace NukeViet\Module\TuVi\Includes;

use NukeViet\Module\TuVi\Includes\TuViConstants;

class TuViLuanGiai {

    private $dictionary = [];
    private $rules = [];

    public function __construct() {
        $this->loadData();
    }

    private function loadData() {
        // Load Dictionary
        $dictFile = NV_ROOTDIR . '/modules/tu-vi/data/dictionary_stars.json';
        if (file_exists($dictFile)) {
            $json = file_get_contents($dictFile);
            $this->dictionary = json_decode($json, true);
        }

        // Load Rules
        $rulesFile = NV_ROOTDIR . '/modules/tu-vi/data/rules_tuvi.json';
        if (file_exists($rulesFile)) {
            $json = file_get_contents($rulesFile);
            $this->rules = json_decode($json, true);
        }
    }

    /**
     * Hàm chính để luận giải 1 cung
     * @param array $stars_in_palace Danh sách sao tại cung (Format: [['name'=>'Tử Vi', 'brightness'=>'M', 'type'=>'main'], ...])
     * @param string $palace_code Mã cung (menh, tai, quan...)
     * @param bool $has_tuan Có Tuần không
     * @param bool $has_triet Có Triệt không
     */
    public function luanGiaiCung($stars_in_palace, $palace_code, $has_tuan, $has_triet) {
        $text_output = [];
        $processed_stars = [];
        $star_ids_in_palace = [];

        // Normalize Star Names to IDs for matching
        foreach ($stars_in_palace as $star) {
            $id = $this->vn_to_str($star['name']); // e.g., 'Tử Vi' -> 'tu_vi'
            $star_ids_in_palace[] = $id;
        }

        // Check for Vô Chính Diệu (No Main Stars)
        $has_main_star = false;
        foreach ($stars_in_palace as $star) {
            if (isset($star['type']) && $star['type'] == 'main') {
                $has_main_star = true;
                break;
            }
        }

        if (!$has_main_star) {
             // Find rule for Vo Chinh Dieu
             foreach ($this->rules as $rule) {
                 if (isset($rule['modifier']) && $rule['modifier'] == 'vo_chinh_dieu') {
                     $text_output[] = $rule['content'];
                     break; // Only one Vo Chinh Dieu rule
                 }
             }
        }

        // 1. ƯU TIÊN 1: Tìm các bộ sao (Cách cục)
        // Ví dụ: Tử Phủ đồng cung, Sát Phá Tham...
        // Note: For complex combinations like Sat Pha Tham (which are often split across Tri Hiep),
        // this simple function checks if they are ALL in the current palace OR relies on external logic to feed tri-hiep stars.
        // For this task, we assume we check strict presence in the current palace (Dong Cung) or passed in via $stars_in_palace if we pre-merge tri-hiep.
        // The user prompt implies: "Tìm thấy Rule: ['tu_vi', 'thien_phu'] tại Dần/Thân." -> implies Dong Cung.

        // However, standard Sat Pha Tham is often defined by the presence of one, implying the others in Tri Hiep.
        // But the rule example says "stars_array".
        // Let's check strict subset match for now.

        foreach ($this->rules as $rule) {
            if (empty($rule['stars_array'])) continue;

            // Check if all stars in rule exist in palace
            $intersect = array_intersect($rule['stars_array'], $star_ids_in_palace);
            if (count($intersect) == count($rule['stars_array'])) {
                // Matched!
                $text_output[] = $rule['content'];
                $processed_stars = array_merge($processed_stars, $rule['stars_array']);
            }
        }

        // 2. ƯU TIÊN 2: Luận chính tinh lẻ (nếu chưa nằm trong bộ sao)
        foreach ($stars_in_palace as $star) {
            $star_id = $this->vn_to_str($star['name']);

            // Only process main stars
            if (isset($star['type']) && $star['type'] == 'main' && !in_array($star_id, $processed_stars)) {

                $brightness = isset($star['brightness']) ? $star['brightness'] : 'M'; // Default M

                // Simplify Brightness to M (Good) vs H (Bad) for dictionary lookup
                // M, V, D, B -> M. H -> H.
                $lookup_brightness = ($brightness == 'H') ? 'H' : 'M';

                if ($has_tuan || $has_triet) {
                    // Logic: Modifier
                    // If Good (M) + Tuan/Triet -> Reduced (or Bad)
                    // If Bad (H) + Tuan/Triet -> Improved (Good)

                    if ($lookup_brightness == 'M') {
                         $meaning = $this->getMeaning($star_id, 'M', $palace_code);
                         $text_output[] = "Vốn có: " . $meaning . " Tuy nhiên do gặp Tuần/Triệt nên công danh trắc trở, tài lộc khó tụ, cần tu dưỡng nhiều.";
                    } else {
                         $meaning = $this->getMeaning($star_id, 'H', $palace_code);
                         $text_output[] = "Vốn là cách cục xấu: " . $meaning . " Nhưng nhờ gặp Tuần/Triệt án ngữ nên tai qua nạn khỏi, chuyển hung thành cát (Phản vi kỳ cách).";
                    }
                } else {
                    $meaning = $this->getMeaning($star_id, $lookup_brightness, $palace_code);
                    if ($meaning) $text_output[] = $meaning;
                }

                $processed_stars[] = $star_id;
            }
        }

        // 3. ƯU TIÊN 3: Luận phụ tinh (Gom nhóm)
        $good_stars = [];
        $bad_stars = [];

        // Define some known minor stars (could be loaded from JSON too)
        $bad_list = ['da_la', 'kinh_duong', 'hoa_ky', 'dia_khong', 'dia_kiep', 'hoa_tinh', 'linh_tinh', 'co_than', 'qua_tu', 'thien_khoc', 'thien_hu'];

        foreach ($stars_in_palace as $star) {
             // Assuming minor stars don't have 'type'='main' or we check explicit list
             if (!isset($star['type']) || $star['type'] != 'main') {
                 $id = $this->vn_to_str($star['name']);
                 if (in_array($id, $bad_list)) {
                     $bad_stars[] = $star['name'];
                 } else {
                     // Default good for now
                     $good_stars[] = $star['name'];
                 }
             }
        }

        if (!empty($good_stars)) {
            $text_output[] = "Lại được tọa thủ/hội chiếu bởi các cát tinh: " . implode(", ", $good_stars) . " mang lại sự may mắn, hanh thông.";
        }

        if (!empty($bad_stars)) {
            $text_output[] = "Tuy nhiên cần đề phòng sự phá hoại hoặc trở ngại do các sao: " . implode(", ", $bad_stars) . " gây ra.";
        }

        return implode(" ", $text_output);
    }

    public function luanGiaiTongQuan($canYear, $chiYear, $gender, $cuc) {
        $html = "";

        // 1. Am Duong Thuan Ly
        $isThuanLy = false;
        if ($gender == 1) { // Nam
            if ($canYear % 2 == 0) $isThuanLy = true; // Duong Nam
        } else { // Nu
            if ($canYear % 2 != 0) $isThuanLy = true; // Am Nu
        }

        if ($isThuanLy) {
             $html .= "<p><strong>Âm Dương Thuận Lý:</strong> Người được hưởng vòng vận đi thuận chiều, cuộc đời gặp nhiều may mắn, thuận lợi hơn người khác. Dễ đạt được ý nguyện.</p>";
        } else {
             $html .= "<p><strong>Âm Dương Nghịch Lý:</strong> Người có vòng vận đi nghịch, cuộc đời thường phải trải qua thử thách, phấn đấu nhiều mới thành công. Tính cách thường kiên cường, không chịu khuất phục.</p>";
        }

        // 2. Menh Cuc
        $menhID = TuViConstants::getNapAmID($canYear, $chiYear);

        $cucHanhID = 0;
        switch($cuc) {
            case 2: $cucHanhID = 3; break; // Thuy
            case 3: $cucHanhID = 2; break; // Moc
            case 4: $cucHanhID = 1; break; // Kim
            case 5: $cucHanhID = 5; break; // Tho
            case 6: $cucHanhID = 4; break; // Hoa
        }

        // 1=Kim, 2=Moc, 3=Thuy, 4=Hoa, 5=Tho
        $sinh = [1=>3, 3=>2, 2=>4, 4=>5, 5=>1];

        $tuongQuan = "";
        if ($menhID == $cucHanhID) {
            $tuongQuan = "Mệnh Cục Bình Hòa: Cuộc đời êm đềm, sự nghiệp tương xứng với tài năng.";
        } elseif ($sinh[$menhID] == $cucHanhID) {
            $tuongQuan = "Mệnh Sinh Cục: Người hay vất vả vì người khác, làm lợi cho đời, hay bị thiệt thòi.";
        } elseif ($sinh[$cucHanhID] == $menhID) {
            $tuongQuan = "Cục Sinh Mệnh: Hoàn cảnh ưu đãi, dễ gặp may mắn, thời thế tạo anh hùng.";
        } else {
            $tuongQuan = "Mệnh Cục Tương Khắc: Cuộc đời nhiều trở ngại nhưng nhờ nghị lực mà vượt qua hoàn cảnh.";
        }

        $html .= "<p><strong>" . $tuongQuan . "</strong></p>";

        return $html;
    }

    private function getMeaning($star_id, $brightness, $scope) {
        // Find in dictionary
        foreach ($this->dictionary as $entry) {
            if ($entry['id'] == $star_id && $entry['brightness'] == $brightness) {
                // Scope check? Dictionary currently has 'menh'.
                // If scope matches or entry is generic (scope='all' or missing)
                // For this sample, we treat 'menh' content as applicable generally or fallback.
                return $entry['content'];
            }
        }
        return "";
    }

    // Helper to convert Vietnamese string to code (e.g. Tử Vi -> tu_vi)
    private function vn_to_str($str) {
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd'=>'đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i'=>'í|ì|ỉ|ĩ|ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ',
            'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D'=>'Đ',
            'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I'=>'Í|Ì|Ỉ|Ĩ|Ị',
            'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );

        foreach($unicode as $nonUnicode=>$uni){
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        $str = str_replace(' ', '_', $str);
        return strtolower($str);
    }
}
