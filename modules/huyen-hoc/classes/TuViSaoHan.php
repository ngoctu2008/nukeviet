<?php
/**
 * Class TuViSaoHan
 * Chức năng:
 * 1. Tính tuổi Âm, Sao Cửu Diệu, Hạn niên.
 * 2. Tính hạn Tam Tai, Kim Lâu, Hoang Ốc.
 * 3. Bình giải chi tiết và hướng dẫn cách cúng sao giải hạn (Văn khấn, lễ vật).
 */

namespace NukeViet\Module\HuyenHoc;

// require_once 'TuViConstants.php'; // Removed, assuming no external constants needed or will use internal.

class TuViSaoHan {
    protected $birthYear;
    protected $gender; // 1: Nam, 0: Nữ
    protected $viewYear;

    // --- CẤU HÌNH DỮ LIỆU SAO (CỬU DIỆU) ---
    // [Tính chất, Ngũ hành, Bài thơ, Cách cúng]
    const SAO_INFO = [
        'La Hầu' => [
            'type' => 'Hung Tinh (Xấu)',
            'hanh' => 'Kim',
            'desc' => 'Sao chính thất kiến hung tai. Nam rất kỵ, nữ cũng lo âu. Hay gặp thị phi, kiện tụng, bệnh về tai mắt, máu huyết. Nhiều chuyện phiền muộn.',
            'tho' => 'La Hầu hạn ấy nặng thay<br>Tháng giêng tháng bảy kỵ ngay chẳng hiền<br>Môn trung đổ bạc hao tiền<br>Tửu sắc tài khí đảo điên như là.',
            'cung' => [
                'ngay' => 'Mùng 8 âm lịch hàng tháng',
                'huong' => 'Chính Bắc',
                'den' => '9 ngọn nến',
                'mau' => 'Vàng (Bài vị giấy vàng)',
                'chu' => 'Thiên Cung Thần Thủ La Hầu Tinh Quân'
            ]
        ],
        'Ke Do' => [
            'type' => 'Hung Tinh (Xấu)',
            'hanh' => 'Thổ',
            'desc' => 'Sao Kế Đô kỵ nhất nữ giới. Dễ gặp tai nạn, hao tài tốn của, bệnh tật, thị phi, buồn rầu. Nam giới gặp cũng lận đận.',
            'tho' => 'Kế Đô sao ấy dài đông<br>Chia tay đôi ngả vợ chồng thư phong<br>Đàn bà chịu chữ sát phu<br>Đàn ông thị dục bất đồ chi tai.',
            'cung' => [
                'ngay' => '18 âm lịch hàng tháng',
                'huong' => 'Chính Tây',
                'den' => '20 ngọn nến',
                'mau' => 'Vàng (Giấy vàng)',
                'chu' => 'Thiên Vi Cung Phân Kế Đô Tinh Quân'
            ]
        ],
        'Thai Duong' => [
            'type' => 'Cát Tinh (Tốt)',
            'hanh' => 'Hỏa',
            'desc' => 'Thái Dương rực rỡ. Nam mạng gặp thì rồng lên mây, thăng quan tiến chức, tài lộc dồi dào. Nữ mạng thì vất vả, hay đau ốm.',
            'tho' => 'Thái Dương chiếu mệnh năm nay<br>Tháng mười tháng sáu có mà tiền vơ<br>Cầu trời lạy phật nam mô<br>Sao tốt ở phải cây khô ra chồi.',
            'cung' => [
                'ngay' => '27 âm lịch hàng tháng',
                'huong' => 'Chính Đông',
                'den' => '12 ngọn nến',
                'mau' => 'Vàng (Giấy vàng)',
                'chu' => 'Nhật Cung Thái Dương Thiên Tử Tinh Quân'
            ]
        ],
        'Thai Am' => [
            'type' => 'Cát Tinh (Tốt)',
            'hanh' => 'Thủy',
            'desc' => 'Chủ về danh lợi, hỉ sự. Nữ mạng gặp rất tốt, vui vẻ, hạnh phúc, có tài lộc. Nam mạng đi xa gặp may mắn.',
            'tho' => 'Thái Âm chiếu mệnh năm nay<br>Khỏi bị đau mắt trặc tay trẹo giò<br>Đàn ông ít nặng nhẹ lo<br>Đàn bà khẩu thiệt đôi co quấy rầy.',
            'cung' => [
                'ngay' => '26 âm lịch hàng tháng',
                'huong' => 'Chính Tây',
                'den' => '7 ngọn nến',
                'mau' => 'Vàng (Giấy vàng)',
                'chu' => 'Nguyệt Cung Thái Âm Hoàng Hậu Tinh Quân'
            ]
        ],
        'Moc Duc' => [
            'type' => 'Cát Tinh (Tốt)',
            'hanh' => 'Mộc',
            'desc' => 'Mộc Đức sao sáng mênh mông. Tốt cho cả Nam và Nữ. Công việc bình an, sinh trưởng, hôn nhân hòa hợp.',
            'tho' => 'Mộc Đức hạn ấy chân thành<br>Năm này cho biết loan phòng kết đôi<br>Tháng mười tháng chạp tiệc ngồi<br>Tháng tư coi chừng ăn ôi đau lòng.',
            'cung' => [
                'ngay' => '25 âm lịch hàng tháng',
                'huong' => 'Chính Đông',
                'den' => '20 ngọn nến',
                'mau' => 'Xanh (Giấy xanh)',
                'chu' => 'Đông Phương Giáp Ất Mộc Đức Tinh Quân'
            ]
        ],
        'Van Hon' => [
            'type' => 'Trung Tinh (Bình)',
            'hanh' => 'Hỏa',
            'desc' => 'Vân Hớn là sao hung tinh hành Hỏa. Đề phòng khẩu thiệt, nóng nảy, kiện tụng, tai nạn lửa điện. Nam nữ đều nên giữ gìn.',
            'tho' => 'Vân Hớn tháng hai tháng năm<br>Lời qua tiếng lại ăn nằm nào yên<br>Tháng tám rắc rối đảo điên<br>Giữ mình kẻo bị plụy phiền ngục hình.',
            'cung' => [
                'ngay' => '29 âm lịch hàng tháng',
                'huong' => 'Chính Nam',
                'den' => '15 ngọn nến',
                'mau' => 'Đỏ (Giấy đỏ)',
                'chu' => 'Nam Phương Bính Đinh Hỏa Đức Tinh Quân'
            ]
        ],
        'Tho Tu' => [
            'type' => 'Trung Tinh (Xấu nhẹ)',
            'hanh' => 'Thổ',
            'desc' => 'Thổ Tú là sao ách tinh. Dễ gặp tiểu nhân, xuất hành không thuận, gia đạo bất hòa, chăn nuôi thua lỗ.',
            'tho' => 'Thổ Tú sao ấy phải lo<br>Tuy rằng không nặng nhưng mà chẳng yên<br>Tháng tư tháng tám đảo điên<br>Gia đình xào xáo chẳng yên trong ngoài.',
            'cung' => [
                'ngay' => '19 âm lịch hàng tháng',
                'huong' => 'Chính Tây',
                'den' => '5 ngọn nến',
                'mau' => 'Vàng (Giấy vàng)',
                'chu' => 'Trung Ương Mậu Kỷ Thổ Đức Tinh Quân'
            ]
        ],
        'Thai Bach' => [
            'type' => 'Hung Tinh (Rất Xấu)',
            'hanh' => 'Kim',
            'desc' => 'Thái Bạch quét sạch cửa nhà. Xấu nhất trong các sao. Hao tài tốn của, dễ gặp quan sự, tiểu nhân hãm hại, sức khỏe kém. Kỵ màu trắng.',
            'tho' => 'Thái Bạch hạn ấy nặng thay<br>Nam nữ máu huyết kỵ rày gươm đao<br>Kim tinh bạch hổ vì sao<br>Cứ mặc áo trắng chiếu vào phương tây.',
            'cung' => [
                'ngay' => '15 âm lịch hàng tháng',
                'huong' => 'Chính Tây',
                'den' => '8 ngọn nến',
                'mau' => 'Trắng (Giấy trắng)',
                'chu' => 'Tây Phương Canh Tân Kim Đức Tinh Quân'
            ]
        ],
        'Thuy Dieu' => [
            'type' => 'Trung Tinh (Tốt/Xấu)',
            'hanh' => 'Thủy',
            'desc' => 'Thủy Diệu vừa hung vừa cát. Tốt cho tài lộc, đi xa. Nhưng kỵ tháng 4, tháng 8. Nữ giới cẩn thận sông nước và lời ăn tiếng nói.',
            'tho' => 'Thủy Diệu thuộc về thủy tinh<br>Trong năm nhịn nhục nhớ kiềm hiểm nguy<br>Đạo tặc mất của một khi<br>Thổ tinh chiếu mệnh lâm nguy hình hình.',
            'cung' => [
                'ngay' => '21 âm lịch hàng tháng',
                'huong' => 'Chính Bắc',
                'den' => '7 ngọn nến',
                'mau' => 'Đen (Giấy đen/xanh)',
                'chu' => 'Bắc Phương Nhâm Quý Thủy Đức Tinh Quân'
            ]
        ]
    ];

    // --- CẤU HÌNH HẠN NIÊN ---
    const HAN_INFO = [
        'Huynh Tuyen' => ['name' => 'Huỳnh Tuyền', 'loai' => 'Đại Hạn', 'desc' => 'Bệnh nặng, nguy vong. Kỵ đường thủy, sông nước. Không nên bảo lãnh cho người khác.'],
        'Tam Kheo' => ['name' => 'Tam Kheo', 'loai' => 'Tiểu Hạn', 'desc' => 'Đau chân tay, phong thấp, nhức mỏi. Đi lại cẩn thận xe cộ, tránh nơi đông người xô xát.'],
        'Ngu Mo' => ['name' => 'Ngũ Mộ', 'loai' => 'Tiểu Hạn', 'desc' => 'Hao tài, mất của. Đề phòng trộm cắp, mua hàng kém chất lượng, không nên cho ai ngủ nhờ.'],
        'Thien Tinh' => ['name' => 'Thiên Tinh', 'loai' => 'Xấu', 'desc' => 'Rối rắm, tranh chấp. Dễ bị ngộ độc thực phẩm, kiện cáo, thị phi. Phụ nữ có thai cần cẩn thận.'],
        'Tan Tan' => ['name' => 'Tán Tận', 'loai' => 'Đại Hạn', 'desc' => 'Hao tài, tai nạn bất ngờ. Nam giới kỵ nhất, dễ bị tai nạn xe cộ hoặc bị cướp giật.'],
        'Thien La' => ['name' => 'Thiên La', 'loai' => 'Xấu', 'desc' => 'Lưới trời lồng lộng. Tâm lý bất an, hay lo âu, ma quỷ quấy phá, gia đạo bất hòa.'],
        'Dia Vong' => ['name' => 'Địa Võng', 'loai' => 'Xấu', 'desc' => 'Rắc rối, thị phi. Kỵ đi chơi xa vào giờ Tuất, ngày Tuất. Dễ bị hiểu lầm, mang tiếng xấu.'],
        'Diem Vuong' => ['name' => 'Diêm Vương', 'loai' => 'Xấu', 'desc' => 'Kỵ người đau ốm, người già. Nhưng tốt cho người làm ăn buôn bán (có tài lộc).']
    ];

    public function __construct($birthYear, $gender, $viewYear) {
        $this->birthYear = $birthYear;
        $this->gender = $gender; // 1 Nam, 0 Nữ
        $this->viewYear = $viewYear;
    }

    // --- 1. TÍNH SAO CỬU DIỆU ---

    public function getSao() {
        $tuoiAm = $this->viewYear - $this->birthYear + 1;
        $du = $tuoiAm % 9;
        if ($du == 0) $du = 9;

        // Bảng tra sao theo số dư (Cần mapping lại cho chuẩn với bảng cửu diệu nam/nữ)
        // Cách tính chuẩn:
        // Nam: 1-La Hầu, 2-Thổ Tú, 3-Thủy Diệu, 4-Thái Bạch, 5-Thái Dương, 6-Vân Hớn, 7-Kế Đô, 8-Thái Âm, 9-Mộc Đức
        // Nữ: 1-Kế Đô, 2-Vân Hớn, 3-Mộc Đức, 4-Thái Âm, 5-Thổ Tú, 6-La Hầu, 7-Thái Dương, 8-Thái Bạch, 9-Thủy Diệu

        $saoNam = [1=>'La Hầu', 2=>'Tho Tu', 3=>'Thuy Dieu', 4=>'Thai Bach', 5=>'Thai Duong', 6=>'Van Hon', 7=>'Ke Do', 8=>'Thai Am', 9=>'Moc Duc'];
        $saoNu = [1=>'Ke Do', 2=>'Van Hon', 3=>'Moc Duc', 4=>'Thai Am', 5=>'Tho Tu', 6=>'La Hầu', 7=>'Thai Duong', 8=>'Thai Bach', 9=>'Thuy Dieu'];

        $key = ($this->gender == 1) ? $saoNam[$du] : $saoNu[$du];
        return array_merge(['key' => $key], self::SAO_INFO[$key]);
    }

    // --- 2. TÍNH HẠN NIÊN ---

    public function getHan() {
        $tuoiAm = $this->viewYear - $this->birthYear + 1;
        $du = $tuoiAm % 8;
        if ($du == 0) $du = 8;

        // Bảng tra hạn:
        // Nam: 1-Huỳnh Tuyền, 2-Tam Kheo, 3-Ngũ Mộ, 4-Thiên Tinh, 5-Tán Tận, 6-Thiên La, 7-Địa Võng, 8-Diêm Vương
        // Nữ: 1-Tán Tận, 2-Thiên Tinh, 3-Ngũ Mộ, 4-Tam Kheo, 5-Huỳnh Tuyền, 6-Thiên La, 7-Địa Võng, 8-Diêm Vương

        $hanNam = [1=>'Huynh Tuyen', 2=>'Tam Kheo', 3=>'Ngu Mo', 4=>'Thien Tinh', 5=>'Tan Tan', 6=>'Thien La', 7=>'Dia Vong', 8=>'Diem Vuong'];
        $hanNu = [1=>'Tan Tan', 2=>'Thien Tinh', 3=>'Ngu Mo', 4=>'Tam Kheo', 5=>'Huynh Tuyen', 6=>'Thien La', 7=>'Dia Vong', 8=>'Diem Vuong'];

        $key = ($this->gender == 1) ? $hanNam[$du] : $hanNu[$du];
        return self::HAN_INFO[$key];
    }

    // --- 3. TÍNH CÁC HẠN KHÁC (TAM TAI, KIM LÂU, HOANG ỐC) ---

    public function checkTamTai() {
        $chiSinh = ($this->birthYear - 4) % 12; // 0=Tý
        $chiNam = ($this->viewYear - 4) % 12;
        if ($chiSinh < 0) $chiSinh += 12;
        if ($chiNam < 0) $chiNam += 12;

        // Nhóm Tam Hợp -> Năm Tam Tai
        // Thân Tý Thìn (8,0,4) -> Dần Mão Thìn (2,3,4)
        // Dần Ngọ Tuất (2,6,10) -> Thân Dậu Tuất (8,9,10)
        // Tỵ Dậu Sửu (5,9,1) -> Hợi Tý Sửu (11,0,1)
        // Hợi Mão Mùi (11,3,7) -> Tỵ Ngọ Mùi (5,6,7)

        $isTamTai = false;
        if (in_array($chiSinh, [8,0,4]) && in_array($chiNam, [2,3,4])) $isTamTai = true;
        if (in_array($chiSinh, [2,6,10]) && in_array($chiNam, [8,9,10])) $isTamTai = true;
        if (in_array($chiSinh, [5,9,1]) && in_array($chiNam, [11,0,1])) $isTamTai = true;
        if (in_array($chiSinh, [11,3,7]) && in_array($chiNam, [5,6,7])) $isTamTai = true;

        if ($isTamTai) return "Phạm Tam Tai (Năm {$this->viewYear}). Tránh khởi công lớn, cưới hỏi, đầu tư mạo hiểm.";
        return "Không phạm Tam Tai.";
    }

    public function checkKimLau() {
        $tuoiAm = $this->viewYear - $this->birthYear + 1;
        $du = $tuoiAm % 9;
        // Dư 1, 3, 6, 8 là Kim Lâu
        if ($du == 1) return "Phạm Kim Lâu Thân (Hại bản thân). Kỵ xây nhà, cưới hỏi.";
        if ($du == 3) return "Phạm Kim Lâu Thê (Hại vợ/chồng). Kỵ xây nhà, cưới hỏi.";
        if ($du == 6) return "Phạm Kim Lâu Tử (Hại con cái). Kỵ xây nhà, cưới hỏi.";
        if ($du == 8) return "Phạm Kim Lâu Súc (Hại vật nuôi/kinh tế). Kỵ chăn nuôi lớn.";
        return "Không phạm Kim Lâu.";
    }

    public function checkHoangOc() {
        $tuoiAm = $this->viewYear - $this->birthYear + 1;
        // Logic tính Hoang Ốc:
        // 10: Nhất Cát (Tốt), 20: Nhì Nghi (Tốt), 30: Tam Địa Sát (Xấu),
        // 40: Tứ Tấn Tài (Tốt), 50: Ngũ Thọ Tử (Xấu), 60: Lục Hoang Ốc (Xấu).
        // Thuật toán đếm tay:
        // Bước 1: Lấy hàng chục làm mốc (10=1, 20=2, 30=3, 40=4, 50=5, 60=6).
        // Bước 2: Từ mốc đó đếm tiếp hàng đơn vị theo chiều thuận.
        // Quy về logic số học:

        $chuc = floor($tuoiAm / 10);
        $dv = $tuoiAm % 10;

        if ($chuc == 0) $chuc = 1; // Dưới 10 tuổi coi như khởi 10

        // Vị trí khởi: 1->Cát, 2->Nghi, 3->Sát, 4->Tài, 5->Tử, 6->Ốc
        // 10 -> 1, 20 -> 2, 30 -> 3, 40 -> 4, 50 -> 5, 60 -> 6, 70 -> 1...
        $startPos = $chuc;
        while($startPos > 6) $startPos -= 6;

        // Cộng thêm hàng đơn vị: (start + donvi - 1)
        // Nhưng lưu ý 10 tuổi là 1, 11 tuổi là 2... nên công thức phải chuẩn.
        // Chuẩn nhất: Dùng mảng map tuổi -> cung.
        // Để code gọn, dùng logic:
        $currentPos = $startPos;
        for ($i = 0; $i < $dv; $i++) {
            $currentPos++;
            if ($currentPos > 6) $currentPos = 1;
        }

        $map = [
            1 => ['name' => 'Nhất Cát', 'type' => 'Tốt', 'msg' => 'Làm nhà tốt, an cư lạc nghiệp.'],
            2 => ['name' => 'Nhì Nghi', 'type' => 'Tốt', 'msg' => 'Làm nhà tốt, giàu sang hưng thịnh.'],
            3 => ['name' => 'Tam Địa Sát', 'type' => 'Xấu', 'msg' => 'Làm nhà xấu, gia chủ mắc bệnh.'],
            4 => ['name' => 'Tứ Tấn Tài', 'type' => 'Tốt', 'msg' => 'Làm nhà tốt, phúc lộc dồi dào.'],
            5 => ['name' => 'Ngũ Thọ Tử', 'type' => 'Xấu', 'msg' => 'Làm nhà xấu, gia đạo ly biệt.'],
            6 => ['name' => 'Lục Hoang Ốc', 'type' => 'Xấu', 'msg' => 'Làm nhà xấu, khó thành đạt.']
        ];

        return $map[$currentPos];
    }

    // --- 4. TỔNG HỢP BÁO CÁO ---

    public function execute() {
        $sao = $this->getSao();
        $han = $this->getHan();
        $tamTai = $this->checkTamTai();
        $kimLau = $this->checkKimLau();
        $hoangOc = $this->checkHoangOc();
        $tuoiAm = $this->viewYear - $this->birthYear + 1;

        $html = "<div class='sao-han-report'>";
        $html .= "<h3>Tra Cứu Sao Hạn Năm {$this->viewYear}</h3>";
        $html .= "<p>Gia chủ: " . ($this->gender == 1 ? "Nam" : "Nữ") . " - Sinh năm: {$this->birthYear} ($tuoiAm tuổi âm)</p>";

        // 1. Sao Chiếu Mệnh
        $classSao = (strpos($sao['type'], 'Tốt') !== false) ? 'good' : ((strpos($sao['type'], 'Xấu') !== false) ? 'bad' : 'neutral');
        $html .= "<div class='box-sao $classSao'>";
        $html .= "<h4>1. Sao Chiếu Mệnh: <strong>{$sao['key']}</strong> ({$sao['type']})</h4>";
        $html .= "<p><em>Tính chất:</em> {$sao['desc']}</p>";
        $html .= "<div class='poem'>{$sao['tho']}</div>";
        $html .= "<div class='ritual'><strong>⚔ Cách Cúng Giải Hạn:</strong><br>";
        $html .= "<ul>";
        $html .= "<li>Ngày cúng: {$sao['cung']['ngay']}</li>";
        $html .= "<li>Hướng lạy: {$sao['cung']['huong']}</li>";
        $html .= "<li>Lễ vật: {$sao['cung']['den']} - Màu {$sao['cung']['mau']}</li>";
        $html .= "<li>Bài vị: Viết chữ '{$sao['cung']['chu']}'</li>";
        $html .= "</ul></div>";
        $html .= "</div>";

        // 2. Hạn Niên
        $classHan = (strpos($han['loai'], 'Đại') !== false) ? 'bad' : 'neutral';
        $html .= "<div class='box-han $classHan'>";
        $html .= "<h4>2. Hạn Niên: <strong>{$han['name']}</strong> ({$han['loai']})</h4>";
        $html .= "<p>{$han['desc']}</p>";
        $html .= "</div>";

        // 3. Các Hạn Khác
        $html .= "<div class='box-other'>";
        $html .= "<h4>3. Các Hạn Khác (Xây nhà, Cưới hỏi)</h4>";
        $html .= "<ul>";
        $html .= "<li><strong>Tam Tai:</strong> $tamTai</li>";
        $html .= "<li><strong>Kim Lâu:</strong> $kimLau</li>";
        $html .= "<li><strong>Hoang Ốc:</strong> {$hoangOc['name']} ({$hoangOc['type']}) - {$hoangOc['msg']}</li>";
        $html .= "</ul>";
        $html .= "</div>";

        $html .= "</div>";
        return $html;
    }
}

// --- EXAMPLE ---
/*
$app = new TuViSaoHan(1990, 1, 2026); // Nam 1990 xem năm 2026
echo $app->execute();
*/
?>
