<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViYLy {

    private $tatAch;
    private $menhElement;

    // Instance Constructor
    public function __construct($calcData) {
        if (isset($calcData['dia_ban'])) {
            $diaBan = $calcData['dia_ban'];
            $meta = $calcData['meta'];

            // Find Tat Ach index
            $menhIdx = $meta['menh_idx'];
            // Tat Ach is Menh + 5 (clockwise) or -7
            // Menh=0, Phu=1, Phuc=2, Dien=3, Quan=4, No=5, Di=6, Tat=7? No.
            // Let's check TuViLapSo: Menh, Phu Mau, Phuc Duc, Dien Trach, Quan Loc, No Boc, Thien Di, Tat Ach...
            // Indices: 0, 1, 2, 3, 4, 5, 6, 7.
            // So Tat Ach is Menh + 7?
            // Wait, previous file `TuViLapSo` defined:
            // $palaceNames = array('Mệnh', 'Phụ Mẫu', 'Phúc Đức', 'Điền Trạch', 'Quan Lộc', 'Nô Bộc', 'Thiên Di', 'Tật Ách', ...);
            // So index 7 relative to Menh? No, Loop fills array.
            // $posMenh is starting point. Then loop $i=0..11 backwards (Counter-Clockwise).
            // Palace 0 is Menh. Palace 1 is Huynh De (if counter-clockwise)?
            // Wait, standard Tu Vi:
            // An Cung: Menh -> Phu Mau -> Phuc Duc (Nghich hay Thuan?)
            // Nam Thuan Nu Nghich ONLY for Dai Van.
            // An Cung Chuc: Always Counter-Clockwise (Nghich).
            // Index 0: Menh. Index 11: Phụ Mẫu. Index 10: Phúc Đức.
            // Let's check `TuViLapSo` loop again.
            // $pos = ($posMenh - $i) % 12.
            // i=0: Menh. i=1: Phu Mau (at Menh-1). i=2: Phuc Duc (at Menh-2).
            // So Palaces are placed Counter-Clockwise.
            // Tat Ach is at index 7 in the names array.
            // So Position = ($posMenh - 7).

            // But we need to find the palace in `$diaBan` that has `palace_name` containing "Tật Ách".
            // Since `$diaBan` is 0..11 indexed by Earthly Branch (Ty..Hoi).

            $tatAchIdx = -1;
            foreach ($diaBan as $idx => $p) {
                if (mb_strpos($p['palace_name'], 'Tật Ách') !== false) {
                    $tatAchIdx = $idx;
                    break;
                }
            }

            $this->tatAch = ($tatAchIdx >= 0) ? $diaBan[$tatAchIdx] : [];
            $this->menhElement = $meta['menh_element_id'];
        }
    }

    // Instance Method: Chan Doan Benh
    public function chanDoanBenh() {
        if (empty($this->tatAch)) return ['cung_tat'=>'Không xác định', 'diagnosis'=>['Chưa có dữ liệu'], 'diet'=>[]];

        $result = self::diagnoseHealth($this->tatAch, $this->menhElement);
        return $result; // Returns full array including diagnosis lines
    }

    // Instance Method: Goi Y Thuc Duong
    public function goiYThucDuong() {
        if (empty($this->tatAch)) return [];
        $result = self::diagnoseHealth($this->tatAch, $this->menhElement);
        return $result['diet'];
    }

    // Static Logic (preserved)
    public static function diagnoseHealth($cungTatAch, $menhElement) {
        $diagnosis = [];
        $warnings = [];

        // 1. Analyze Stars in Tat Ach
        $stars = array_merge($cungTatAch['chinh_tinh'], $cungTatAch['phu_tinh_xau']);

        foreach ($stars as $s) {
            $code = $s['code'];
            // Specific Health meanings
            switch ($code) {
                case 'thien_co': $warnings[] = "Gan mật kém, hay lo nghĩ gây suy nhược thần kinh."; break;
                case 'thai_duong': $warnings[] = "Huyết áp cao, mắt kém, bệnh tim mạch."; break;
                case 'vu_khuc': $warnings[] = "Bệnh hô hấp, phổi, mũi họng."; break;
                case 'thien_dong': $warnings[] = "Bệnh tiêu hóa, dạ dày lạnh, hay đau bụng."; break;
                case 'liem_trinh': $warnings[] = "Nóng trong, mụn nhọt, bệnh về máu."; break;
                case 'tham_lang': $warnings[] = "Bệnh gan, thận, hoặc do tửu sắc quá độ."; break;
                case 'cu_mon': $warnings[] = "Dạ dày, thực quản, bệnh miệng."; break;
                case 'thien_tuong': $warnings[] = "Bệnh ngoài da, dị ứng, hoặc bàng quang."; break;
                case 'thien_luong': $warnings[] = "Tỳ vị (tiêu hóa) yếu, nhưng gặp bệnh mau khỏi."; break;
                case 'that_sat': $warnings[] = "Bệnh phổi, ho hen, hoặc chấn thương kim khí."; break;
                case 'pha_quan': $warnings[] = "Bệnh thận, máu huyết, hoặc bệnh phụ nữ/nam khoa."; break;
                case 'hoa_ky': $warnings[] = "Mắt kém, khí huyết không thông, hay bị bệnh lặt vặt lâu khỏi."; break;
                case 'kinh_duong': $warnings[] = "Dễ bị phẫu thuật, chấn thương tay chân."; break;
                case 'da_la': $warnings[] = "Bệnh mãn tính, răng miệng, xương khớp."; break;
                case 'dia_khong': case 'dia_kiep': $warnings[] = "Ung nhọt, bệnh lạ, hoặc bệnh về khí huyết."; break;
            }
        }

        if (empty($warnings)) {
            $warnings[] = "Cung Tật Ách tốt, ít bệnh tật nguy hiểm. Chú ý giữ gìn sức khỏe theo mùa.";
        }

        // 2. Recommend Diet based on Menh Element (Balance)
        $diet = self::getDietAdvice($menhElement);

        return [
            'cung_tat' => $cungTatAch['palace_name'],
            'diagnosis' => $warnings,
            'diet' => $diet
        ];
    }

    private static function getDietAdvice($elementId) {
        $elName = $elementId;
        if (is_numeric($elementId)) {
             // 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc (from TuViLapSo map usually)
             // Check TuViLapSo $nhNames = [1=>'Thủy', 2=>'Hỏa', 3=>'Thổ', 4=>'Kim', 5=>'Mộc'];
             $map = [1=>'Thủy', 2=>'Hỏa', 3=>'Thổ', 4=>'Kim', 5=>'Mộc'];
             $elName = isset($map[$elementId]) ? $map[$elementId] : 'Unknown';
        }

        switch ($elName) {
            case 'Kim':
                return [
                    'name' => 'Mệnh Kim (Phổi/Đại tràng)',
                    'mau_sac' => 'Trắng, Xám, Ghi, Vàng, Nâu đất',
                    'thuc_pham' => 'Gạo trắng, củ cải, lê, tỏi, gừng, thịt gà. Nên ăn cay vừa phải để bổ Phổi.',
                    'loi_khuyen' => 'Tránh hút thuốc. Tập hít thở sâu. Giữ ấm cổ họng.'
                ];
            case 'Mộc':
                return [
                    'name' => 'Mệnh Mộc (Gan/Mật)',
                    'mau_sac' => 'Xanh lá, Đen, Xanh dương',
                    'thuc_pham' => 'Rau xanh, cải bó xôi, chanh, giấm, thịt bò. Vị chua đi vào Gan.',
                    'loi_khuyen' => 'Hạn chế rượu bia. Tránh thức khuya. Nên tập yoga hoặc đi dạo dưới cây xanh.'
                ];
            case 'Thủy':
                return [
                    'name' => 'Mệnh Thủy (Thận/Bàng quang)',
                    'mau_sac' => 'Đen, Xanh dương, Trắng, Xám',
                    'thuc_pham' => 'Đậu đen, rong biển, hải sản, thịt lợn. Vị mặn (vừa phải) tốt cho Thận.',
                    'loi_khuyen' => 'Uống đủ nước. Tránh nhịn tiểu. Giữ ấm vùng lưng và chân.'
                ];
            case 'Hỏa':
                return [
                    'name' => 'Mệnh Hỏa (Tim/Ruột non)',
                    'mau_sac' => 'Đỏ, Hồng, Tím, Xanh lá',
                    'thuc_pham' => 'Mướp đắng, rau đắng, tim lợn, dưa hấu. Vị đắng thanh nhiệt tốt cho Tim.',
                    'loi_khuyen' => 'Tránh xúc động mạnh. Hạn chế đồ cay nóng quá mức. Tập thiền định.'
                ];
            case 'Thổ':
                return [
                    'name' => 'Mệnh Thổ (Tỳ/Vị)',
                    'mau_sac' => 'Vàng, Nâu, Đỏ, Hồng',
                    'thuc_pham' => 'Khoai lang, bí ngô, cà rốt, thịt bò, mật ong. Vị ngọt tự nhiên tốt cho Tỳ vị.',
                    'loi_khuyen' => 'Ăn uống đúng giờ. Tránh lo nghĩ quá nhiều hại dạ dày. Hạn chế đồ lạnh sống.'
                ];
            default:
                return ['name' => 'Chưa xác định', 'mau_sac'=>'', 'thuc_pham'=>'', 'loi_khuyen'=>''];
        }
    }
}
