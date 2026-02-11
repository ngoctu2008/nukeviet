<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViYLy {

    // Ngu Hanh mapping to Body Parts & Tastes
    // Kim: Phổi, Ruột già - Cay
    // Mộc: Gan, Mật - Chua
    // Thủy: Thận, Bàng quang - Mặn
    // Hỏa: Tim, Ruột non - Đắng
    // Thổ: Dạ dày, Lá lách - Ngọt

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
        // Principle: Eat foods of Generating Element (Sinh) and Same Element (Hoa). Avoid Controlling (Khac).
        // Also: Weak organ needs tonifying.

        $diet = self::getDietAdvice($menhElement);

        return [
            'cung_tat' => $cungTatAch['palace_name'],
            'diagnosis' => $warnings,
            'diet' => $diet
        ];
    }

    private static function getDietAdvice($elementId) {
        // 1=Kim, 2=Thuy, 3=Hoa, 4=Tho, 5=Moc (Checking standard again or using generic names)
        // Using name string for safety if ID varies
        // Map ID to Name if int
        $elName = $elementId;
        if (is_numeric($elementId)) {
             // Assuming: 1=Thuy, 2=Hoa, 3=Tho, 4=Kim, 5=Moc (from TuViLapSo)
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
