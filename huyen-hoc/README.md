// 1. Nhúng các file Class
require_once 'classes/TuViConstants.php';
require_once 'classes/TuViCalculator.php';
require_once 'classes/TuViInterpreter.php';

// 2. Giả lập dữ liệu đầu vào (Trong thực tế bạn lấy từ $_POST)
// Ví dụ: Nam, sinh 15/04/1984 (Âm lịch) giờ Thìn
$inputData = [
    'day'      => 15,    // Ngày Âm
    'month'    => 4,     // Tháng Âm
    'year'     => 1984,  // Năm Dương (để tính Can Chi)
    'hour'     => 4,     // Giờ Thìn (0=Tý, 1=Sửu... 4=Thìn)
    'gender'   => 1,     // 1 = Nam, 0 = Nữ
    
    // Các thông số Can Chi (Thường cần một hàm chuyển đổi từ năm 1984 -> Giáp Tý)
    // Giáp Tý: Can Giáp = 0, Chi Tý = 0
    'can_year' => 0,     // 0=Giáp, 1=Ất...
    'chi_year' => 0,     // 0=Tý, 1=Sửu...
];

// 3. Bước 1: Tính toán An Sao (Calculator)
try {
    $calculator = new TuViCalculator($inputData);
    $result = $calculator->execute();
    
    // $result lúc này chứa mảng toàn bộ 12 cung và vị trí các sao
    // Bạn có thể var_dump($result) để xem cấu trúc dữ liệu thô
    
} catch (Exception $e) {
    die("Lỗi tính toán: " . $e->getMessage());
}

// 4. Bước 2: Luận giải (Interpreter)
$interpreter = new TuViInterpreter($result);

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lá Số Tử Vi - <?php echo $inputData['year']; ?></title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; background: #f4f4f4; }
        .container { width: 80%; margin: 0 auto; background: white; padding: 20px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        .section-title { border-bottom: 2px solid #8b0000; color: #8b0000; padding-bottom: 5px; margin-top: 30px; }
        .cung-box { border: 1px solid #ddd; padding: 15px; margin-bottom: 15px; border-radius: 5px; background: #fafafa; }
        .highlight { color: #d35400; font-weight: bold; }
        .sao-tot { color: green; }
        .sao-xau { color: red; }
    </style>
</head>
<body>

<div class="container">
    <h1 style="text-align:center;">LÁ SỐ TỬ VI</h1>
    
    <h2 class="section-title">I. Tổng Quan Bản Mệnh</h2>
    <div>
        <?php echo $interpreter->luanGiaiTongQuan(); ?>
    </div>
    
    <h2 class="section-title">II. Cách Cục Nổi Bật</h2>
    <div>
        <?php echo $interpreter->nhanDienCachCuc(); ?>
    </div>

    <h2 class="section-title">III. Luận Giải Hậu Vận</h2>
    <div>
        <?php echo $interpreter->luanGiaiThanCu(); ?>
    </div>

    <h2 class="section-title">IV. Luận Giải Chi Tiết 12 Cung</h2>
    <?php 
    // Duyệt qua 12 cung từ Tý đến Hợi (hoặc sắp xếp theo Mệnh Viên)
    // Ở đây ta in theo thứ tự từ cung Mệnh đi thuận hoặc nghịch
    
    $menhID = $result['menh_pos'];
    echo $interpreter->luanGiaiChiTietCung($menhID); // Luận cung Mệnh trước
    
    // In các cung còn lại
    for ($i = 1; $i < 12; $i++) {
        // Logic tìm cung tiếp theo (tùy nhu cầu hiển thị)
        $nextCung = ($menhID - $i + 12) % 12; // Đi nghịch theo chiều an cung chức
        // echo $interpreter->luanGiaiChiTietCung($nextCung);
    }
    
    // Demo in cung Tài Bạch và Quan Lộc
    $quanLocPos = ($menhID + 4) % 12; // Quan Lộc luôn cách Mệnh 4 cung chiều thuận? Không, Quan Lộc cách Mệnh 4 cung chiều *nghịch*?
    // Cung Mệnh (1) -> Phụ (2) -> Phúc (3) -> Điền (4) -> Quan (5).
    // Vậy Quan Lộc cách Mệnh 4 bước nghịch.
    // Lưu ý: Trong code Calculator ta an cung chức đi NGƯỢC chiều kim đồng hồ.
    
    // Tìm ID cung Quan Lộc trong mảng kết quả
    foreach ($result['laso'] as $cung) {
        if ($cung['cung_chuc'] == 'Quan Lộc') {
            echo $interpreter->luanGiaiChiTietCung($cung['id']);
        }
        if ($cung['cung_chuc'] == 'Tài Bạch') {
            echo $interpreter->luanGiaiChiTietCung($cung['id']);
        }
    }
    ?>
    
</div>

</body>
</html>