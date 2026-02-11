<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViSaoHan {

    // Cuu Dieu Tinh Quan logic is already in TuViLapSo::getLimitInfoForYear.
    // This class can be used to get detailed meaning of the annual star and limit.

    public static function getSaoHanMeaning($saoName, $hanName) {
        $saoMeanings = [
            'La Hầu' => "Khẩu thiệt tinh. Chủ về chuyện thị phi, kiện tụng, bệnh mắt, tai nạn. Nam kỵ hơn Nữ.",
            'Kế Đô' => "Hung tinh. Chủ về ám muội, thị phi, đau khổ, hao tài tốn của. Nữ kỵ hơn Nam.",
            'Thái Dương' => "Cát tinh. Chủ về an khang thịnh vượng, công danh hiển đạt. Tốt cho Nam, Nữ vất vả.",
            'Thái Âm' => "Cát tinh. Chủ về danh lợi, hỷ sự, bất động sản. Tốt cho Nữ.",
            'Mộc Đức' => "Cát tinh. Chủ về an vui, hòa hợp. Tốt cho việc cưới hỏi, nhưng đề phòng bệnh mắt.",
            'Vân Hớn' => "Trung tinh. Chủ về thủ cựu, đề phòng khẩu thiệt, nóng nảy, kiện tụng.",
            'Thổ Tú' => "Trung tinh. Chủ về tiểu nhân, gia đạo không yên, chăn nuôi thất bát.",
            'Thái Bạch' => "Hung tinh. Chủ về hao tán tiền bạc, tiểu nhân quấy phá, bệnh tật. 'Thái Bạch quét sạch cửa nhà'.",
            'Thủy Diệu' => "Trung tinh. Chủ về tài lộc nhưng kỵ sông nước, dễ bị tai tiếng thị phi."
        ];

        $hanMeanings = [
            'Huỳnh Tuyền' => "Đại hạn. Chủ về bệnh nặng, nguy hiểm tính mạng. Kỵ đi sông nước.",
            'Tam Kheo' => "Tiểu hạn. Chủ về đau mắt, tay chân, xe cộ xước xát.",
            'Ngũ Mộ' => "Tiểu hạn. Chủ về hao tài, mất của, tai bay vạ gió.",
            'Thiên Tinh' => "Xấu. Chủ về thị phi, kiện tụng, ngộ độc thực phẩm.",
            'Toán Tận' => "Đại hạn. Chủ về tai nạn bất ngờ, hao tài tốn của, đàn ông kỵ nhất.",
            'Thiên La' => "Xấu. Chủ về tâm linh, ma quỷ quấy phá, tinh thần bất an.",
            'Địa Võng' => "Xấu. Chủ về rắc rối, thị phi, tai tiếng, không nên đi xa vào giờ Tuất-Hợi.",
            'Diêm Vương' => "Xấu. Chủ về tin buồn từ xa, bệnh lâu ngày khó khỏi, nhưng làm ăn lại có tài lộc."
        ];

        return [
            'sao_desc' => isset($saoMeanings[$saoName]) ? $saoMeanings[$saoName] : '',
            'han_desc' => isset($hanMeanings[$hanName]) ? $hanMeanings[$hanName] : ''
        ];
    }
}
