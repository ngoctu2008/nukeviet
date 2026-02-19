<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViSaoHan {

    protected $birthYear;
    protected $gender;
    protected $viewYear;

    public function __construct($birthYear, $gender, $viewYear) {
        $this->birthYear = $birthYear;
        $this->gender = $gender;
        $this->viewYear = $viewYear;
    }

    public function execute() {
        // Calculate Age (Lunar)
        $age = $this->viewYear - $this->birthYear + 1;

        // Use TuViLapSo helper to get stars
        // We need chiYear for getLimitInfoForYear, but for Sao Han (Cuu Dieu) only Age & Gender matter.
        // However, TuViLapSo requires chiYear for Tam Tai / Pham Thai Tue logic.
        // Let's get chiYear from birthYear.

        // Chi: 0=Than, 1=Dau, 2=Tuat... No.
        // Standard Chi: 0=Ty, 1=Suu, 2=Dan...
        // 1984 (Giap Ty) -> 0.
        // Formula: ($year - 4) % 12.
        $chiYear = ($this->birthYear - 4) % 12;
        if ($chiYear < 0) $chiYear += 12;

        $limitInfo = TuViLapSo::getLimitInfoForYear($chiYear, $this->gender, $this->viewYear, $this->birthYear);

        if (!$limitInfo) return "<div class='alert alert-danger'>Lỗi tính toán sao hạn.</div>";

        $sao = $limitInfo['sao_han'];
        $han = $limitInfo['han'];

        // Get Detailed Meanings
        $meanings = self::getSaoHanMeaning($sao['name'], $han['name']);

        $html = "<div class='sao-han-box'>";
        $html .= "<h3>Sao Hạn Năm " . $this->viewYear . " (Tuổi " . $age . ")</h3>";

        // Sao Chieu Menh
        $classSao = ($sao['type'] == 'tot') ? 'good' : (($sao['type'] == 'xau') ? 'bad' : 'neutral');
        $html .= "<div class='sao-row $classSao'>";
        $html .= "<h4>Sao Chiếu Mệnh: <strong>" . $sao['name'] . "</strong></h4>";
        $html .= "<p>" . $meanings['sao_desc'] . "</p>";
        $html .= "</div>";

        // Han
        $html .= "<div class='han-row'>";
        $html .= "<h4>Hạn: <strong>" . $han['name'] . "</strong></h4>";
        $html .= "<p>" . $meanings['han_desc'] . "</p>";
        $html .= "</div>";

        // Tam Tai / Kim Lau / Thai Tue logic handled in TuViVanHan?
        // Or duplicate here? Usually "Sao Han" refers to Cuu Dieu & Bat Han.
        // TuViVanHan handles Chart limits.
        // So this is sufficient.

        $html .= "</div>";

        return $html;
    }

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
