<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_ADMIN')) {
    exit('Stop!!!');
}

// We need to map module name 'tu-vi' to table prefix 'tu_vi'
// $module_data usually holds 'tu-vi' during install if directory is 'tu-vi'
// But we need to be safe.
$module_table_prefix = str_replace('-', '_', $module_data);
$table_interpretations = $db_config['prefix'] . "_" . $lang . "_" . $module_table_prefix . "_interpretations";

// Insert Sample Data
// Stars: Tử Vi, Thiên Cơ, Thái Dương, Vũ Khúc, Thiên Đồng, Liêm Trinh, Thiên Phủ, Thái Âm, Tham Lang, Cự Môn, Thiên Tướng, Thiên Lương, Thất Sát, Phá Quân
// Palaces: Tý, Sửu, Dần, Mão, Thìn, Tỵ, Ngọ, Mùi, Thân, Dậu, Tuất, Hợi

$db->query("INSERT INTO " . $table_interpretations . " (star_key, palace_key, content) VALUES
('Tử Vi', 'Tý', '<p><strong>Tử Vi tại Tý:</strong> Bình hòa. Chủ về người khoan dung, nhân hậu nhưng thiếu quyết đoán nếu không có Tả Hữu hội chiếu. Ưa làm việc công chức, giáo dục.</p>'),
('Tử Vi', 'Ngọ', '<p><strong>Tử Vi tại Ngọ:</strong> Miếu địa (Cực hướng Ly minh). Rất tốt. Chủ về uy quyền, tài năng lãnh đạo, phú quý song toàn. Thích hợp làm chính trị, kinh doanh lớn.</p>'),
('Thiên Cơ', 'Tỵ', '<p><strong>Thiên Cơ tại Tỵ:</strong> Đắc địa. Người thông minh, khéo léo, giỏi tính toán, thích hợp các nghề kỹ thuật, thiết kế, mưu sĩ.</p>'),
('Thái Dương', 'Dần', '<p><strong>Thái Dương tại Dần:</strong> Vượng địa (Nhật mọc). Chủ về sự nghiệp thăng tiến như mặt trời mọc, danh tiếng lẫy lừng, thông minh bác học.</p>'),
('Thái Dương', 'Thân', '<p><strong>Thái Dương tại Thân:</strong> Hãm địa. Mặt trời lặn, chủ về vất vả buổi đầu, mắt kém, hay lo âu, về già mới an nhàn.</p>'),
('Vũ Khúc', 'Thìn', '<p><strong>Vũ Khúc tại Thìn:</strong> Miếu địa. Chủ về tài lộc dồi dào, kinh doanh phát đạt, tính tình cương trực, quả quyết.</p>'),
('Thiên Đồng', 'Tuất', '<p><strong>Thiên Đồng tại Tuất:</strong> Hãm địa. Chủ về thay đổi, nay đây mai đó, hay gặp thị phi, cần tu tâm dưỡng tính mới bền.</p>'),
('Liêm Trinh', 'Dần', '<p><strong>Liêm Trinh tại Dần:</strong> Vượng địa. Chủ về uy quyền, liêm khiết, làm việc trong ngành luật pháp, công an rất tốt.</p>'),
('Thiên Phủ', 'Tuất', '<p><strong>Thiên Phủ tại Tuất:</strong> Miếu địa. Kho lộc trời, chủ về giàu có, sung túc, tính tình ôn hòa, cẩn trọng, giỏi quản lý tài chính.</p>'),
('Thái Âm', 'Hợi', '<p><strong>Thái Âm tại Hợi:</strong> Miếu địa (Nguyệt lãng thiên môn). Rất đẹp. Chủ về phú quý, văn chương, nghệ thuật, tình cảm phong phú.</p>'),
('Tham Lang', 'Tý', '<p><strong>Tham Lang tại Tý:</strong> Vượng địa (Phiếm thủy đào hoa). Chủ về tài hoa, nghệ sĩ nhưng đa tình, dễ vướng vào rắc rối tình cảm nếu không có sao giải.</p>'),
('Cự Môn', 'Ngọ', '<p><strong>Cự Môn tại Ngọ:</strong> Vượng địa (Thạch trung ẩn ngọc). Ngọc trong đá, cần mài dũa mới sáng. Chủ về tài năng ẩn giấu, thành công muộn nhưng bền vững.</p>'),
('Thiên Tướng', 'Mão', '<p><strong>Thiên Tướng tại Mão:</strong> Hãm địa. Chủ về vất vả, hay bị lấn át, nhưng nếu có Tuần Triệt án ngữ thì lại trở nên tốt đẹp.</p>'),
('Thiên Lương', 'Ngọ', '<p><strong>Thiên Lương tại Ngọ:</strong> Miếu địa. Chủ về thọ trường, phúc đức, được người đời kính trọng, thích hợp làm thầy thuốc, giáo viên.</p>'),
('Thất Sát', 'Dần', '<p><strong>Thất Sát tại Dần:</strong> Miếu địa (Thất Sát triều đẩu). Chủ về uy dũng, quyền biến, làm tướng soái, lãnh đạo rất hợp.</p>'),
('Phá Quân', 'Tý', '<p><strong>Phá Quân tại Tý:</strong> Miếu địa (Anh tinh nhập miếu). Chủ về khai phá, sáng tạo, dám nghĩ dám làm, thành công trong biến động.</p>'),
('Tổng Quan', 'Mệnh', '<p><strong>Tổng quan trọn đời:</strong> Người này có tính cách mạnh mẽ, cương trực. Cuộc đời có nhiều thăng trầm nhưng hậu vận tốt đẹp. Cần chú ý tu dưỡng đạo đức để giữ gìn phúc lộc.</p>'),
('Vận Hạn', 'Tiểu Vận', '<p><strong>Vận hạn năm nay:</strong> Năm nay công việc có nhiều biến động, cần cẩn trọng trong đầu tư. Sức khỏe cần chú ý các bệnh về đường tiêu hóa.</p>'),
('Bình Giải Năm', 'Tổng Quan', '<p><strong>Tổng quan năm:</strong> Một năm nhiều cơ hội nhưng cũng lắm thách thức. Cần kiên nhẫn chờ đợi thời cơ.</p>'),
('Bình Giải Năm', 'Tháng 1', '<p><strong>Tháng 1:</strong> Vui xuân đón tết, hao tốn tiền bạc nhưng tinh thần thoải mái.</p>'),
('Bình Giải Năm', 'Tháng 2', '<p><strong>Tháng 2:</strong> Công việc bắt đầu vào guồng, có quý nhân phù trợ.</p>'),
('Bình Giải Năm', 'Tháng 3', '<p><strong>Tháng 3:</strong> Cẩn thận lời ăn tiếng nói, đề phòng thị phi.</p>'),
('Bình Giải Năm', 'Tháng 4', '<p><strong>Tháng 4:</strong> Tài lộc có chút khởi sắc, có thể đầu tư nhỏ.</p>'),
('Bình Giải Năm', 'Tháng 5', '<p><strong>Tháng 5:</strong> Sức khỏe cần chú ý, tránh đi xa.</p>'),
('Bình Giải Năm', 'Tháng 6', '<p><strong>Tháng 6:</strong> Gia đạo bình an, có tin vui từ xa.</p>'),
('Bình Giải Năm', 'Tháng 7', '<p><strong>Tháng 7:</strong> Tháng cô hồn, làm việc thiện tích đức, tránh tranh chấp.</p>'),
('Bình Giải Năm', 'Tháng 8', '<p><strong>Tháng 8:</strong> Công việc thuận lợi, được cấp trên khen ngợi.</p>'),
('Bình Giải Năm', 'Tháng 9', '<p><strong>Tháng 9:</strong> Tài lộc dồi dào, kinh doanh phát đạt.</p>'),
('Bình Giải Năm', 'Tháng 10', '<p><strong>Tháng 10:</strong> Cẩn thận xe cộ đi lại, giữ gìn sức khỏe.</p>'),
('Bình Giải Năm', 'Tháng 11', '<p><strong>Tháng 11:</strong> Có lộc ăn uống, tiệc tùng.</p>'),
('Bình Giải Năm', 'Tháng 12', '<p><strong>Tháng 12:</strong> Tổng kết cuối năm, chuẩn bị cho kế hoạch mới, mọi sự hanh thông.</p>'),
('Bình Giải Năm', 'Sao Hạn', '<p><strong>Sao Hạn trong năm:</strong> Năm nay gặp sao Thái Bạch (nếu Nam) hoặc Thái Âm (nếu Nữ). Cần làm lễ dâng sao giải hạn đầu năm.</p>')
");
