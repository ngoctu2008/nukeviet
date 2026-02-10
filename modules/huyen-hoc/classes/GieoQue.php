<?php

/**
 * Class GieoQue
 * Chức năng: Gieo quẻ Kinh Dịch theo phương pháp Mai Hoa Dịch Số.
 * Bao gồm: Quẻ Chủ, Quẻ Hỗ, Quẻ Biến.
 */

namespace NukeViet\Module\HuyenHoc;

class GieoQue {

    // --- CẤU HÌNH BÁT QUÁI ---
    // Thứ tự Tiên Thiên Bát Quái: 1=Càn, 2=Đoài, 3=Ly, 4=Chấn, 5=Tốn, 6=Khảm, 7=Cấn, 8=Khôn
    const BAT_QUAI = [
        1 => ['name' => 'Càn', 'hanh' => 'Kim', 'tuong' => 'Thiên'],
        2 => ['name' => 'Đoài', 'hanh' => 'Kim', 'tuong' => 'Trạch'],
        3 => ['name' => 'Ly', 'hanh' => 'Hỏa', 'tuong' => 'Hỏa'],
        4 => ['name' => 'Chấn', 'hanh' => 'Mộc', 'tuong' => 'Lôi'],
        5 => ['name' => 'Tốn', 'hanh' => 'Mộc', 'tuong' => 'Phong'],
        6 => ['name' => 'Khảm', 'hanh' => 'Thủy', 'tuong' => 'Thủy'],
        7 => ['name' => 'Cấn', 'hanh' => 'Thổ', 'tuong' => 'Sơn'],
        8 => ['name' => 'Khôn', 'hanh' => 'Thổ', 'tuong' => 'Địa']
    ];

    // Dữ liệu 64 Quẻ (Key = ThượngQuái_HạQuái)
    // 1=Càn, 2=Đoài, 3=Ly, 4=Chấn, 5=Tốn, 6=Khảm, 7=Cấn, 8=Khôn
    protected $dbQue = [
        // --- CUNG CÀN (1) ---
        '1_1' => ['name' => 'Thuần Càn', 'nghia' => 'Đại cát. Cương kiện, vững chắc, hanh thông. Như rồng gặp mây, mọi việc thuận lợi.'],
        '1_2' => ['name' => 'Thiên Trạch Lý', 'nghia' => 'Bình. Dẫm lên đuôi hổ. Cẩn trọng trong lời nói hành động thì mới thoát tai ương.'],
        '1_3' => ['name' => 'Thiên Hỏa Đồng Nhân', 'nghia' => 'Cát. Cùng người, thân thiện. Có quý nhân phù trợ, hợp tác làm ăn tốt.'],
        '1_4' => ['name' => 'Thiên Lôi Vô Vọng', 'nghia' => 'Hung. Không được vọng động. Tai bay vạ gió, nên an phận thủ thường.'],
        '1_5' => ['name' => 'Thiên Phong Cấu', 'nghia' => 'Bình. Gặp gỡ bất ngờ. Đàn bà nắm quyền, cẩn thận tiểu nhân hoặc cám dỗ.'],
        '1_6' => ['name' => 'Thiên Thủy Tụng', 'nghia' => 'Hung. Kiện tụng, tranh cãi. Bất hòa, ý kiến trái ngược, nên nhẫn nhịn.'],
        '1_7' => ['name' => 'Thiên Sơn Độn', 'nghia' => 'Xấu. Ẩn trốn, thoái lui. Tiểu nhân đạo trưởng, quân tử đạo tiêu, nên rút lui.'],
        '1_8' => ['name' => 'Thiên Địa Bĩ', 'nghia' => 'Đại hung. Bế tắc, không thông. Trời đất cách xa, mọi việc đình trệ.'],

        // --- CUNG ĐOÀI (2) ---
        '2_1' => ['name' => 'Trạch Thiên Quải', 'nghia' => 'Cát. Quyết liệt, dứt khoát. Trừ bỏ kẻ tiểu nhân, nhưng cần làm công khai, chính đại.'],
        '2_2' => ['name' => 'Thuần Đoài', 'nghia' => 'Bình. Vui vẻ, đẹp lòng. Cẩn thận miệng tiếng, coi chừng khẩu phật tâm xà.'],
        '2_3' => ['name' => 'Trạch Hỏa Cách', 'nghia' => 'Cát. Cải cách, đổi mới. Thay cũ đổi mới, thời cơ để làm cách mạng bản thân.'],
        '2_4' => ['name' => 'Trạch Lôi Tùy', 'nghia' => 'Cát. Đi theo, thuận tòng. Tùy cơ ứng biến, nhập gia tùy tục thì an lành.'],
        '2_5' => ['name' => 'Trạch Phong Đại Quá', 'nghia' => 'Xấu. Quá mức, gãy đổ. Việc lớn quá sức, như cây cột yếu chống mái nhà nặng.'],
        '2_6' => ['name' => 'Trạch Thủy Khốn', 'nghia' => 'Đại hung. Khốn cùng, cạn kiệt. Như cá nằm trong vũng nước cạn, tiến thoái lưỡng nan.'],
        '2_7' => ['name' => 'Trạch Sơn Hàm', 'nghia' => 'Cát. Cảm ứng, trai gái yêu nhau. Tình cảm thuận lợi, tâm đầu ý hợp.'],
        '2_8' => ['name' => 'Trạch Địa Tụy', 'nghia' => 'Cát. Tụ họp, nhóm lại. Đất lành chim đậu, nhân tài vật lực hội tụ.'],

        // --- CUNG LY (3) ---
        '3_1' => ['name' => 'Hỏa Thiên Đại Hữu', 'nghia' => 'Đại cát. Có lớn, phong phú. Như mặt trời giữa trưa, tài lộc dồi dào, sự nghiệp đỉnh cao.'],
        '3_2' => ['name' => 'Hỏa Trạch Khuê', 'nghia' => 'Xấu. Chống đối, trái lìa. Hai người hai ý, gia đạo bất hòa, việc làm không thành.'],
        '3_3' => ['name' => 'Thuần Ly', 'nghia' => 'Trung bình. Sáng sủa, văn minh. Như lửa bám vào củi, cần nương tựa người chính trực.'],
        '3_4' => ['name' => 'Hỏa Lôi Phệ Hạp', 'nghia' => 'Bình. Cắn hợp, trừng phạt. Phải dùng biện pháp mạnh để giải quyết trở ngại.'],
        '3_5' => ['name' => 'Hỏa Phong Đỉnh', 'nghia' => 'Cát. Vững vàng, nung đúc. Như vạc dầu sôi, biến đổi cái cũ thành cái mới tốt đẹp.'],
        '3_6' => ['name' => 'Hỏa Thủy Vị Tế', 'nghia' => 'Xấu. Chưa xong, dang dở. Việc chưa thành, nam nữ chưa hợp, cần kiên nhẫn.'],
        '3_7' => ['name' => 'Hỏa Sơn Lữ', 'nghia' => 'Bình. Lữ khách, đi xa. Cô độc nơi đất khách, không an định, nên giữ gìn.'],
        '3_8' => ['name' => 'Hỏa Địa Tấn', 'nghia' => 'Cát. Tiến lên, thăng chức. Mặt trời mọc trên mặt đất, công danh hiển đạt.'],

        // --- CUNG CHẤN (4) ---
        '4_1' => ['name' => 'Lôi Thiên Đại Tráng', 'nghia' => 'Cát. Lớn mạnh, thịnh vượng. Khí thế mạnh mẽ, nhưng chớ nên hung hăng quá đà.'],
        '4_2' => ['name' => 'Lôi Trạch Quy Muội', 'nghia' => 'Xấu. Rối ren, tai họa. Lấy nhầm chồng, làm sai quy tắc, kết quả không tốt.'],
        '4_3' => ['name' => 'Lôi Hỏa Phong', 'nghia' => 'Đại cát. Phong thịnh, dồi dào. Thành công rực rỡ, nhưng cần đề phòng lúc thịnh cực tất suy.'],
        '4_4' => ['name' => 'Thuần Chấn', 'nghia' => 'Cát. Sấm động, phấn phát. Ban đầu sợ hãi sau cười vui, chấn chỉnh lại kỷ cương.'],
        '4_5' => ['name' => 'Lôi Phong Hằng', 'nghia' => 'Cát. Lâu dài, bền vững. Đạo vợ chồng, sự nghiệp bền bỉ, giữ nguyên định hướng.'],
        '4_6' => ['name' => 'Lôi Thủy Giải', 'nghia' => 'Cát. Giải tỏa, tan đi. Khó khăn qua đi, mâu thuẫn được giải quyết, nên tha thứ.'],
        '4_7' => ['name' => 'Lôi Sơn Tiểu Quá', 'nghia' => 'Hung. Quá mức nhỏ, lỗi lầm. Chim bay quá cao, làm việc vượt quá khả năng.'],
        '4_8' => ['name' => 'Lôi Địa Dự', 'nghia' => 'Cát. Vui vẻ, dự bị. Thuận buồm xuôi gió, chuẩn bị kỹ lưỡng thì sẽ thành công.'],

        // --- CUNG TỐN (5) ---
        '5_1' => ['name' => 'Phong Thiên Tiểu Súc', 'nghia' => 'Bình. Ngăn chặn nhỏ, tích lũy. Mây dày mà chưa mưa, nên chờ đợi thời cơ.'],
        '5_2' => ['name' => 'Phong Trạch Trung Phu', 'nghia' => 'Cát. Tin tưởng, tín nghĩa. Lòng thành cảm động trời đất, việc gì cũng thông.'],
        '5_3' => ['name' => 'Phong Hỏa Gia Nhân', 'nghia' => 'Cát. Người nhà, gia đạo. Vợ chồng hòa thuận, nhà cửa yên vui, lợi cho phụ nữ.'],
        '5_4' => ['name' => 'Phong Lôi Ích', 'nghia' => 'Đại cát. Tăng thêm, lợi ích. Gió sấm giao nhau, vượt qua sóng gió để thành công lớn.'],
        '5_5' => ['name' => 'Thuần Tốn', 'nghia' => 'Bình. Thuận nhập, do dự. Như gió lùa, không quyết đoán, cần người chỉ dẫn.'],
        '5_6' => ['name' => 'Phong Thủy Hoán', 'nghia' => 'Cát. Tản ra, đổi mới. Gió thổi trên nước, giải tán sự u sầu, đi xa có lợi.'],
        '5_7' => ['name' => 'Phong Sơn Tiệm', 'nghia' => 'Cát. Tiến dần, từ từ. Như cây trên núi cao, phát triển chậm nhưng chắc chắn.'],
        '5_8' => ['name' => 'Phong Địa Quan', 'nghia' => 'Bình. Quan sát, chiêm nghiệm. Gió thổi trên đất, xem xét thời thế trước khi hành động.'],

        // --- CUNG KHẢM (6) ---
        '6_1' => ['name' => 'Thủy Thiên Nhu', 'nghia' => 'Bình. Chờ đợi, ăn uống. Mây bay trên trời, mưa chưa xuống, cần kiên nhẫn chờ thời.'],
        '6_2' => ['name' => 'Thủy Trạch Tiết', 'nghia' => 'Cát. Tiết chế, chừng mực. Biết dừng đúng lúc, không tham lam thì không có lỗi.'],
        '6_3' => ['name' => 'Thủy Hỏa Ký Tế', 'nghia' => 'Cát. Đã xong, hoàn thành. Mọi việc đã định, thành công, nhưng cẩn thận lúc đầu tốt sau xấu.'],
        '6_4' => ['name' => 'Thủy Lôi Truân', 'nghia' => 'Đại hung. Gian nan, vất vả. Vạn sự khởi đầu nan, đầy rẫy khó khăn, chớ vội tiến.'],
        '6_5' => ['name' => 'Thủy Phong Tỉnh', 'nghia' => 'Bình. Cái giếng, tịnh dưỡng. Công lao cóp nhặt, nuôi dưỡng nhân tài, bình ổn.'],
        '6_6' => ['name' => 'Thuần Khảm', 'nghia' => 'Đại hung. Hiểm trở, lao khổ. Nước chảy xiết, trùng trùng nguy hiểm, nên giữ mình.'],
        '6_7' => ['name' => 'Thủy Sơn Kiển', 'nghia' => 'Hung. Trở ngại, què chân. Đường đi khó khăn, núi cao sông sâu, nên quay lại hoặc tìm quý nhân.'],
        '6_8' => ['name' => 'Thủy Địa Tỷ', 'nghia' => 'Cát. Thân mật, giúp đỡ. Nước thấm xuống đất, tình cảm gắn bó, được quý nhân phù trợ.'],

        // --- CUNG CẤN (7) ---
        '7_1' => ['name' => 'Sơn Thiên Đại Súc', 'nghia' => 'Cát. Tích lũy lớn. Núi chứa vàng bạc, tài lộc dồi dào, ăn uống no đủ.'],
        '7_2' => ['name' => 'Sơn Trạch Tổn', 'nghia' => 'Bình. Tổn thất, bớt đi. Hy sinh cái nhỏ để được cái lớn, trước mất sau được.'],
        '7_3' => ['name' => 'Sơn Hỏa Bí', 'nghia' => 'Cát. Trang trí, vẻ đẹp. Văn vẻ sáng sủa, chỉ lợi cho việc nhỏ, bề ngoài đẹp đẽ.'],
        '7_4' => ['name' => 'Sơn Lôi Di', 'nghia' => 'Cát. Nuôi dưỡng, ăn uống. Họa tòng khẩu xuất, bệnh tòng khẩu nhập, cẩn thận lời nói.'],
        '7_5' => ['name' => 'Sơn Phong Cổ', 'nghia' => 'Hung. Đổ nát, sửa lại. Vật bị sâu mọt, cha làm con chịu, cần chấn chỉnh lại.'],
        '7_6' => ['name' => 'Sơn Thủy Mông', 'nghia' => 'Xấu. Mờ mịt, ngu tối. Như trẻ thơ chưa biết gì, cần thầy giỏi hướng dẫn mới thông.'],
        '7_7' => ['name' => 'Thuần Cấn', 'nghia' => 'Bình. Ngưng nghỉ, núi đứng. Nên giữ nguyên hiện trạng, không nên vọng động.'],
        '7_8' => ['name' => 'Sơn Địa Bác', 'nghia' => 'Đại hung. Bóc mòn, sụp đổ. Tiểu nhân lấn át quân tử, nền tảng lung lay.'],

        // --- CUNG KHÔN (8) ---
        '8_1' => ['name' => 'Địa Thiên Thái', 'nghia' => 'Đại cát. Hanh thông, thái bình. Trời đất giao hòa, âm dương kết hợp, mọi việc như ý.'],
        '8_2' => ['name' => 'Địa Trạch Lâm', 'nghia' => 'Cát. Bao trùm, lớn mạnh. Quân tử đạo trưởng, việc tốt đang đến, nên tiến hành.'],
        '8_3' => ['name' => 'Địa Hỏa Minh Di', 'nghia' => 'Hung. Hại, đau thương. Mặt trời lặn xuống đất, tối tăm, người hiền bị hại.'],
        '8_4' => ['name' => 'Địa Lôi Phục', 'nghia' => 'Cát. Phục hồi, quay lại. Đông qua xuân tới, dương khí sinh sôi, vận may trở lại.'],
        '8_5' => ['name' => 'Địa Phong Thăng', 'nghia' => 'Đại cát. Thăng tiến, mọc lên. Cây mọc trong đất, thăng quan tiến chức, danh lợi đều có.'],
        '8_6' => ['name' => 'Địa Thủy Sư', 'nghia' => 'Bình. Quân đội, đám đông. Cần người lãnh đạo tài ba, có sự tranh chấp, dùng binh.'],
        '8_7' => ['name' => 'Địa Sơn Khiêm', 'nghia' => 'Cát. Khiêm tốn, nhún nhường. Núi cao nằm dưới đất, càng khiêm tốn càng được lợi lớn.'],
        '8_8' => ['name' => 'Thuần Khôn', 'nghia' => 'Cát. Nhu thuận, bao dung. Đất dày chở vật, nên đi theo người khác, chủ về hậu vận tốt.']
    ];

    // Thuộc tính lưu trữ kết quả
    public $queChu;   // [thuong, ha, hao_dong]
    public $queHo;    // [thuong, ha]
    public $queBien;  // [thuong, ha]

    public function __construct() {
        // Init
    }

    // --- PHẦN 1: CÁC PHƯƠNG PHÁP LẬP QUẺ ---

    /**
     * Phương pháp 1: Gieo theo Thời Gian (Mai Hoa Dịch Số)
     * Dùng cho người xem trực tiếp.
     * @param int $nam (Chi năm: 1=Tý... 12=Hợi)
     * @param int $thang (Âm lịch)
     * @param int $ngay (Âm lịch)
     * @param int $gio (Chi giờ: 1=Tý... 12=Hợi)
     */
    public function gieoTheoThoiGian($nam, $thang, $ngay, $gio) {
        // 1. Tính Thượng Quái = (Năm + Tháng + Ngày) % 8
        $tongThuong = $nam + $thang + $ngay;
        $thuong = $tongThuong % 8;
        if ($thuong == 0) $thuong = 8;

        // 2. Tính Hạ Quái = (Năm + Tháng + Ngày + Giờ) % 8
        $tongHa = $tongThuong + $gio;
        $ha = $tongHa % 8;
        if ($ha == 0) $ha = 8;

        // 3. Tính Hào Động = (Năm + Tháng + Ngày + Giờ) % 6
        $haoDong = $tongHa % 6;
        if ($haoDong == 0) $haoDong = 6;

        $this->xuLyQue($thuong, $ha, $haoDong);
    }

    /**
     * Phương pháp 2: Gieo theo Số ngẫu nhiên (Hoặc Seri tiền, Số ĐT)
     * @param int $so1 Số thứ nhất (Tâm động 1)
     * @param int $so2 Số thứ hai (Tâm động 2)
     */
    public function gieoTheoSo($so1, $so2) {
        // Thượng quái lấy số 1
        $thuong = $so1 % 8;
        if ($thuong == 0) $thuong = 8;

        // Hạ quái lấy số 2
        $ha = $so2 % 8;
        if ($ha == 0) $ha = 8;

        // Hào động: Tổng 2 số % 6
        $haoDong = ($so1 + $so2) % 6;
        if ($haoDong == 0) $haoDong = 6;

        $this->xuLyQue($thuong, $ha, $haoDong);
    }

    /**
     * Phương pháp 3: Gieo ngẫu nhiên (Máy tính tự gieo)
     */
    public function gieoNgauNhien() {
        $so1 = rand(1, 100);
        $so2 = rand(1, 100);
        $this->gieoTheoSo($so1, $so2);
    }

    // --- PHẦN 2: XỬ LÝ QUẺ (CHỦ - HỖ - BIẾN) ---

    private function xuLyQue($thuong, $ha, $haoDong) {
        // 1. Quẻ Chủ
        $this->queChu = [
            'thuong' => $thuong,
            'ha' => $ha,
            'hao_dong' => $haoDong,
            'info' => $this->traCuuQue($thuong, $ha)
        ];

        // 2. Quẻ Biến (Biến đổi âm dương tại hào động)
        // Logic: Xác định quẻ Thượng/Hạ bị biến
        // Hào 1,2,3 thuộc Hạ Quái. Hào 4,5,6 thuộc Thượng Quái.

        $thuongBien = $thuong;
        $haBien = $ha;

        if ($haoDong <= 3) {
            // Biến ở Hạ Quái (1,2,3)
            $haBien = $this->bienQuai($ha, $haoDong);
        } else {
            // Biến ở Thượng Quái (4->1, 5->2, 6->3 của quái đơn)
            $thuongBien = $this->bienQuai($thuong, $haoDong - 3);
        }

        $this->queBien = [
            'thuong' => $thuongBien,
            'ha' => $haBien,
            'info' => $this->traCuuQue($thuongBien, $haBien)
        ];

        // 3. Quẻ Hỗ (Phức tạp hơn - Tạo từ các hào 234 và 345 của quẻ chủ)
        // Hỗ Thượng: Lấy hào 3,4,5 của Quẻ chủ.
        // Hỗ Hạ: Lấy hào 2,3,4 của Quẻ chủ.

        $binThuong = $this->quaiToBin($thuong); // VD Càn -> [1,1,1]
        $binHa = $this->quaiToBin($ha);         // VD Ly  -> [1,0,1]
        // Gộp lại: [Hào 6, 5, 4, 3, 2, 1]
        // Mảng full: [T1, T2, T3, H1, H2, H3]
        $fullHex = array_merge($binThuong, $binHa);

        // Hỗ Thượng (Lấy hào 5,4,3) -> Index 1, 2, 3
        $hoThuongBin = [$fullHex[1], $fullHex[2], $fullHex[3]];
        // Hỗ Hạ (Lấy hào 4,3,2) -> Index 2, 3, 4
        $hoHaBin = [$fullHex[2], $fullHex[3], $fullHex[4]];

        $this->queHo = [
            'thuong' => $this->binToQuai($hoThuongBin),
            'ha' => $this->binToQuai($hoHaBin),
            'info' => $this->traCuuQue($this->binToQuai($hoThuongBin), $this->binToQuai($hoHaBin))
        ];
    }

    // --- PHẦN 3: HÀM HỖ TRỢ TÍNH TOÁN ---

    /**
     * Biến quái: Đổi hào Âm/Dương tại vị trí động
     * @param int $quaiID (1-8)
     * @param int $hao (1,2,3)
     */
    private function bienQuai($quaiID, $hao) {
        // Lấy nhị phân: VD Càn(1) -> [1,1,1] (Hào 3,2,1)
        $bin = $this->quaiToBin($quaiID);

        // Đảo trạng thái hào
        $index = 3 - $hao;
        $bin[$index] = ($bin[$index] == 1) ? 0 : 1;

        return $this->binToQuai($bin);
    }

    // Map Quái số sang Nhị phân (1=Dương, 0=Âm) - Thứ tự: Hào 3, Hào 2, Hào 1 (Trên xuống)
    private function quaiToBin($id) {
        $map = [
            1 => [1,1,1], // Càn (Thiên)
            2 => [0,1,1], // Đoài (Trạch) - Trên đứt, dưới liền
            3 => [1,0,1], // Ly (Hỏa)
            4 => [0,0,1], // Chấn (Lôi)
            5 => [1,1,0], // Tốn (Phong)
            6 => [0,1,0], // Khảm (Thủy)
            7 => [1,0,0], // Cấn (Sơn)
            8 => [0,0,0]  // Khôn (Địa)
        ];
        return isset($map[$id]) ? $map[$id] : [0,0,0];
    }

    // Map Nhị phân sang Quái số
    private function binToQuai($arr) {
        // So khớp mảng
        foreach ([1,2,3,4,5,6,7,8] as $id) {
            if ($this->quaiToBin($id) === $arr) return $id;
        }
        return 0;
    }

    // Tra cứu tên và ý nghĩa Quẻ
    private function traCuuQue($thuong, $ha) {
        $key = "{$thuong}_{$ha}";

        // Tên Tượng
        $tenThuong = isset(self::BAT_QUAI[$thuong]['tuong']) ? self::BAT_QUAI[$thuong]['tuong'] : '';
        $tenHa = isset(self::BAT_QUAI[$ha]['tuong']) ? self::BAT_QUAI[$ha]['tuong'] : '';

        if (isset($this->dbQue[$key])) {
            return $this->dbQue[$key];
        } else {
            // Fallback
            return [
                'name' => "$tenThuong $tenHa (Chưa có dữ liệu)",
                'nghia' => "Quẻ này được kết hợp bởi Tượng $tenThuong ở trên và Tượng $tenHa ở dưới."
            ];
        }
    }

    // --- PHẦN 4: XUẤT KẾT QUẢ ---

    public function layKetQua() {
        if (empty($this->queChu)) return "Chưa gieo quẻ.";

        $tongLuan = $this->getDetailedInterpretation($this->queChu, $this->queHo, $this->queBien);

        return [
            'chu' => [
                'name' => $this->queChu['info']['name'],
                'nghia' => $this->queChu['info']['nghia'],
                'image' => '',
                'dong' => "Hào động: " . $this->queChu['hao_dong']
            ],
            'ho' => [
                'name' => $this->queHo['info']['name'],
                'nghia' => $this->queHo['info']['nghia'],
                'desc' => "Quẻ Hỗ thể hiện quá trình diễn biến của sự việc."
            ],
            'bien' => [
                'name' => $this->queBien['info']['name'],
                'nghia' => $this->queBien['info']['nghia'],
                'desc' => "Quẻ Biến thể hiện kết quả cuối cùng."
            ],
            'tong_luan' => $tongLuan
        ];
    }

    private function getDetailedInterpretation($chu, $ho, $bien) {
        // Data for Analysis
        $tChu = self::BAT_QUAI[$chu['thuong']];
        $hChu = self::BAT_QUAI[$chu['ha']];

        $tBien = self::BAT_QUAI[$bien['thuong']];
        $hBien = self::BAT_QUAI[$bien['ha']];

        // 1. Analyze Main Hexagram (Context)
        // Relationship between Thuong (Ngoai) and Ha (Noi)
        // Thuong: Object/Situation. Ha: Subject/Self.
        // Compare Elements: Sinh, Khac, Hoa
        $relChu = $this->compareElements($hChu['hanh'], $tChu['hanh']); // Subject vs Object

        $context = "";
        switch ($relChu['type']) {
            case 'sinh_xuat': // Subject generates Object (Weakening self)
                $context = "Bối cảnh hiện tại cho thấy bạn đang phải nỗ lực, hao tâm tổn trí vì sự việc (Ta sinh Người). Sự khởi đầu có thể vất vả nhưng thể hiện sự chủ động của bạn.";
                break;
            case 'sinh_nhap': // Object generates Subject (Strengthening self)
                $context = "Bối cảnh rất thuận lợi, bạn nhận được sự hỗ trợ từ hoàn cảnh hoặc quý nhân (Người sinh Ta). Mọi việc khởi đầu suôn sẻ, có lợi thế.";
                break;
            case 'khac_xuat': // Subject controls Object (Hard work but control)
                $context = "Bạn đang ở thế chủ động kiểm soát tình hình (Ta khắc Người). Tuy nhiên, để đạt được mục tiêu cần phải đấu tranh và nỗ lực vượt qua trở ngại.";
                break;
            case 'khac_nhap': // Object controls Subject (Pressure)
                $context = "Hoàn cảnh hiện tại đang gây bất lợi hoặc áp lực lên bạn (Người khắc Ta). Có nhiều trở ngại khách quan kìm hãm, cần cẩn trọng và kiên nhẫn.";
                break;
            case 'ty_hoa': // Same element (Harmony/Competition)
                $context = "Bối cảnh hiện tại là sự tương hòa, bình đẳng (Tỷ Hòa). Bạn và đối tác/hoàn cảnh có sự tương đồng, dễ dàng hợp tác hoặc cạnh tranh công bằng.";
                break;
        }

        // 2. Analyze Process (Ho Hexagram)
        // Ho shows hidden factors/process
        $process = "Trong quá trình diễn biến, quẻ Hỗ là **{$ho['info']['name']}**. Điều này ám chỉ giai đoạn giữa sẽ mang tính chất: {$ho['info']['nghia']}.";

        // 3. Analyze Outcome (Bien Hexagram)
        // Rel between Subject (Ha Bien) and Object (Thuong Bien) if transformed?
        // Usually analyze the meaning of the Hexagram itself.
        $outcome = "Kết quả cuối cùng dự báo bởi quẻ **{$bien['info']['name']}**. Ý nghĩa: {$bien['info']['nghia']}.";

        // 4. Synthesis / Advice
        // Compare Chu vs Bien rating (if available) or basic logic
        // Simple Logic: Check keywords in meaning
        $keywordsGood = ['Cát', 'Hanh thông', 'Thuận lợi', 'Tốt'];
        $keywordsBad = ['Hung', 'Xấu', 'Bế tắc', 'Khốn'];

        $isChuGood = $this->checkKeywords($chu['info']['nghia'], $keywordsGood, $keywordsBad);
        $isBienGood = $this->checkKeywords($bien['info']['nghia'], $keywordsGood, $keywordsBad);

        $advice = "";
        if ($isChuGood && $isBienGood) {
            $advice = "Đại Cát: Sự việc bắt đầu thuận lợi và kết thúc viên mãn. Bạn nên tự tin tiến hành theo kế hoạch.";
        } elseif (!$isChuGood && !$isBienGood) {
            $advice = "Đại Hung: Cả khởi đầu và kết thúc đều gặp khó khăn. Lời khuyên là nên dừng lại, xem xét kỹ lưỡng hoặc chờ thời cơ khác.";
        } elseif ($isChuGood && !$isBienGood) {
            $advice = "Đầu Xuôi Đuôi Lọt (Tiền Cát Hậu Hung): Ban đầu có vẻ thuận lợi nhưng về sau sẽ gặp trắc trở. Cần chuẩn bị phương án dự phòng cho những khó khăn phát sinh.";
        } else {
            $advice = "Khổ Tận Cam Lai (Tiền Hung Hậu Cát): Khởi đầu gian nan nhưng kiên trì sẽ gặt hái quả ngọt. Đừng nản lòng trước khó khăn trước mắt.";
        }

        return [
            'context' => $context,
            'process' => $process,
            'outcome' => $outcome,
            'advice' => $advice,
            'full_text' => "<strong>1. Bối Cảnh:</strong> $context<br><br><strong>2. Diễn Biến:</strong> $process<br><br><strong>3. Kết Quả:</strong> $outcome<br><br><strong>4. Lời Khuyên:</strong> $advice"
        ];
    }

    private function compareElements($subjectEl, $objectEl) {
        // Elements: Kim, Moc, Thuy, Hoa, Tho
        // Sinh: Kim->Thuy->Moc->Hoa->Tho->Kim
        // Khac: Kim->Moc->Tho->Thuy->Hoa->Kim

        if ($subjectEl == $objectEl) return ['type' => 'ty_hoa'];

        $sinh = ['Kim'=>'Thủy', 'Thủy'=>'Mộc', 'Mộc'=>'Hỏa', 'Hỏa'=>'Thổ', 'Thổ'=>'Kim'];
        $khac = ['Kim'=>'Mộc', 'Mộc'=>'Thổ', 'Thổ'=>'Thủy', 'Thủy'=>'Hỏa', 'Hỏa'=>'Kim'];

        if ($sinh[$subjectEl] == $objectEl) return ['type' => 'sinh_xuat'];
        if ($sinh[$objectEl] == $subjectEl) return ['type' => 'sinh_nhap'];
        if ($khac[$subjectEl] == $objectEl) return ['type' => 'khac_xuat'];
        if ($khac[$objectEl] == $subjectEl) return ['type' => 'khac_nhap'];

        return ['type' => 'ty_hoa']; // Fallback
    }

    private function checkKeywords($text, $good, $bad) {
        foreach ($good as $k) if (stripos($text, $k) !== false) return true;
        foreach ($bad as $k) if (stripos($text, $k) !== false) return false;
        return true; // Default neutral/good
    }
}
