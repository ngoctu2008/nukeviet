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

// Map module name 'tu-vi' to table prefix 'tu_vi'
$module_table_prefix = str_replace('-', '_', $module_data);
$table_interpretations = $db_config['prefix'] . "_" . $lang . "_" . $module_table_prefix . "_interpretations";

// Insert Sample Data
// Stars: Tử Vi, Thiên Cơ, Thái Dương, Vũ Khúc, Thiên Đồng, Liêm Trinh, Thiên Phủ, Thái Âm, Tham Lang, Cự Môn, Thiên Tướng, Thiên Lương, Thất Sát, Phá Quân
// Palaces: Tý, Sửu, Dần, Mão, Thìn, Tỵ, Ngọ, Mùi, Thân, Dậu, Tuất, Hợi

$sql = "INSERT INTO " . $table_interpretations . " (star_key, palace_key, topic, content, weight) VALUES ";
$values = [];

// Main Stars
$values[] = "('Tử Vi', 'Tý', 'tong_quan', '<p><strong>Tử Vi tại Tý:</strong> Bình hòa. Chủ về người khoan dung, nhân hậu nhưng thiếu quyết đoán nếu không có Tả Hữu hội chiếu. Ưa làm việc công chức, giáo dục.</p>', 0)";
$values[] = "('Tử Vi', 'Ngọ', 'tong_quan', '<p><strong>Tử Vi tại Ngọ:</strong> Miếu địa (Cực hướng Ly minh). Rất tốt. Chủ về uy quyền, tài năng lãnh đạo, phú quý song toàn. Thích hợp làm chính trị, kinh doanh lớn.</p>', 0)";
$values[] = "('Thiên Cơ', 'Tỵ', 'tong_quan', '<p><strong>Thiên Cơ tại Tỵ:</strong> Đắc địa. Người thông minh, khéo léo, giỏi tính toán, thích hợp các nghề kỹ thuật, thiết kế, mưu sĩ.</p>', 0)";
$values[] = "('Thái Dương', 'Dần', 'cong_danh', '<p><strong>Thái Dương tại Dần:</strong> Vượng địa (Nhật mọc). Chủ về sự nghiệp thăng tiến như mặt trời mọc, danh tiếng lẫy lừng, thông minh bác học.</p>', 0)";
$values[] = "('Thái Dương', 'Thân', 'cong_danh', '<p><strong>Thái Dương tại Thân:</strong> Hãm địa. Mặt trời lặn, chủ về vất vả buổi đầu, mắt kém, hay lo âu, về già mới an nhàn.</p>', 0)";
$values[] = "('Vũ Khúc', 'Thìn', 'tai_loc', '<p><strong>Vũ Khúc tại Thìn:</strong> Miếu địa. Chủ về tài lộc dồi dào, kinh doanh phát đạt, tính tình cương trực, quả quyết.</p>', 0)";
$values[] = "('Thiên Đồng', 'Tuất', 'tinh_cach', '<p><strong>Thiên Đồng tại Tuất:</strong> Hãm địa. Chủ về thay đổi, nay đây mai đó, hay gặp thị phi, cần tu tâm dưỡng tính mới bền.</p>', 0)";
$values[] = "('Liêm Trinh', 'Dần', 'cong_danh', '<p><strong>Liêm Trinh tại Dần:</strong> Vượng địa. Chủ về uy quyền, liêm khiết, làm việc trong ngành luật pháp, công an rất tốt.</p>', 0)";
$values[] = "('Thiên Phủ', 'Tuất', 'tai_san', '<p><strong>Thiên Phủ tại Tuất:</strong> Miếu địa. Kho lộc trời, chủ về giàu có, sung túc, tính tình ôn hòa, cẩn trọng, giỏi quản lý tài chính.</p>', 0)";
$values[] = "('Thái Âm', 'Hợi', 'tinh_duyen', '<p><strong>Thái Âm tại Hợi:</strong> Miếu địa (Nguyệt lãng thiên môn). Rất đẹp. Chủ về phú quý, văn chương, nghệ thuật, tình cảm phong phú.</p>', 0)";
$values[] = "('Tham Lang', 'Tý', 'tinh_duyen', '<p><strong>Tham Lang tại Tý:</strong> Vượng địa (Phiếm thủy đào hoa). Chủ về tài hoa, nghệ sĩ nhưng đa tình, dễ vướng vào rắc rối tình cảm nếu không có sao giải.</p>', 0)";
$values[] = "('Cự Môn', 'Ngọ', 'cong_danh', '<p><strong>Cự Môn tại Ngọ:</strong> Vượng địa (Thạch trung ẩn ngọc). Ngọc trong đá, cần mài dũa mới sáng. Chủ về tài năng ẩn giấu, thành công muộn nhưng bền vững.</p>', 0)";
$values[] = "('Thiên Tướng', 'Mão', 'quan_he', '<p><strong>Thiên Tướng tại Mão:</strong> Hãm địa. Chủ về vất vả, hay bị lấn át, nhưng nếu có Tuần Triệt án ngữ thì lại trở nên tốt đẹp.</p>', 0)";
$values[] = "('Thiên Lương', 'Ngọ', 'tinh_cach', '<p><strong>Thiên Lương tại Ngọ:</strong> Miếu địa. Chủ về thọ trường, phúc đức, được người đời kính trọng, thích hợp làm thầy thuốc, giáo viên.</p>', 0)";
$values[] = "('Thất Sát', 'Dần', 'cong_danh', '<p><strong>Thất Sát tại Dần:</strong> Miếu địa (Thất Sát triều đẩu). Chủ về uy dũng, quyền biến, làm tướng soái, lãnh đạo rất hợp.</p>', 0)";
$values[] = "('Phá Quân', 'Tý', 'cong_danh', '<p><strong>Phá Quân tại Tý:</strong> Miếu địa (Anh tinh nhập miếu). Chủ về khai phá, sáng tạo, dám nghĩ dám làm, thành công trong biến động.</p>', 0)";

// General Topics
$values[] = "('Tổng Quan', 'Mệnh', 'tong_quan', '<p><strong>Tổng quan trọn đời:</strong> Người này có tính cách mạnh mẽ, cương trực. Cuộc đời có nhiều thăng trầm nhưng hậu vận tốt đẹp. Cần chú ý tu dưỡng đạo đức để giữ gìn phúc lộc.</p>', 0)";
$values[] = "('Vận Hạn', 'Tiểu Vận', 'van_han', '<p><strong>Vận hạn năm nay:</strong> Năm nay công việc có nhiều biến động, cần cẩn trọng trong đầu tư. Sức khỏe cần chú ý các bệnh về đường tiêu hóa.</p>', 0)";

// Sao Han Definitions
$values[] = "('Sao Chiếu Mệnh', 'La Hầu', 'van_han', '<p><strong>La Hầu (Khẩu thiệt tinh):</strong> Là sao chính thất kiến hung tai, cho nên năm nào người có sao này chiếu mệnh thì dễ bị tao ráng, tranh chấp rồi đưa đến cò bót nếu nặng, làm ăn mọi việc đều hắc ám.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Kế Đô', 'van_han', '<p><strong>Kế Đô (Hung tinh):</strong> Là sao tam cửu khóc bi ai, cho nên năm nào người có sao này chiếu mệnh thì dễ bị tai nạn, người âm phá rối, hay ốm đau bệnh tật, hao tài tốn của, dễ bị chuyện buồn rầu.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Thái Bạch', 'van_han', '<p><strong>Thái Bạch (Kim tinh):</strong> Là sao bạch triều, cho nên năm nào người có sao này chiếu mệnh thì dễ bị hao tài tốn của, ốm đau, làm ăn lận đận, đề phòng tiểu nhân.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Thái Dương', 'van_han', '<p><strong>Thái Dương (Nhật tinh):</strong> Tốt cho nam giới, rực rỡ như mặt trời, thăng quan tiến chức. Nữ giới thì vất vả, hay đau ốm.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Thái Âm', 'van_han', '<p><strong>Thái Âm (Nguyệt tinh):</strong> Tốt cho nữ giới, chủ về danh lợi, hỉ sự. Nam giới thì cũng tốt nhưng không bằng nữ, đi xa có lợi.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Mộc Đức', 'van_han', '<p><strong>Mộc Đức (Mộc tinh):</strong> Sao tốt, chủ về sự an vui, hòa hợp, có quý nhân giúp đỡ. Tuy nhiên nữ giới đề phòng bệnh máu huyết.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Vân Hớn', 'van_han', '<p><strong>Vân Hớn (Hỏa tinh):</strong> Sao trung tính, chủ về thủ cựu, đề phòng khẩu thiệt, kiện tụng, nóng nảy.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Thổ Tú', 'van_han', '<p><strong>Thổ Tú (Thổ tinh):</strong> Sao trung tính, chủ về tiểu nhân, xuất hành không thuận, gia đạo bất an, chăn nuôi thua lỗ.</p>', 0)";
$values[] = "('Sao Chiếu Mệnh', 'Thủy Diệu', 'van_han', '<p><strong>Thủy Diệu (Thủy tinh):</strong> Sao tốt nhưng kỵ tháng 4, tháng 8. Chủ về tài lộc, hỉ sự, nhưng đi sông nước cần cẩn thận.</p>', 0)";

$values[] = "('Hạn', 'Huỳnh Tuyền', 'van_han', '<p><strong>Hạn Huỳnh Tuyền:</strong> Chủ về sức khỏe, bệnh nặng, nguy hiểm. Không nên bảo chứng cho người khác, dễ nảy sinh chuyện bất lợi.</p>', 0)";
$values[] = "('Hạn', 'Tam Kheo', 'van_han', '<p><strong>Hạn Tam Kheo:</strong> Chủ về chân tay, đau mắt. Đề phòng té ngã, xe cộ, các bệnh xương khớp.</p>', 0)";
$values[] = "('Hạn', 'Ngũ Mộ', 'van_han', '<p><strong>Hạn Ngũ Mộ:</strong> Chủ về hao tài, mất của. Cẩn thận tiền bạc, không nên cho vay mượn, đề phòng trộm cắp.</p>', 0)";
$values[] = "('Hạn', 'Thiên Tinh', 'van_han', '<p><strong>Hạn Thiên Tinh:</strong> Chủ về thị phi, kiện tụng, ngộ độc. Cẩn thận ăn uống, lời ăn tiếng nói.</p>', 0)";
$values[] = "('Hạn', 'Toán Tận', 'van_han', '<p><strong>Hạn Toán Tận:</strong> Chủ về hao tài, ngộ nạn thình lình. Nam giới kỵ hơn nữ. Cẩn thận khi mang tiền bạc đi đường.</p>', 0)";
$values[] = "('Hạn', 'Thiên La', 'van_han', '<p><strong>Hạn Thiên La:</strong> Chủ về tâm linh, ma quỷ quấy phá, bệnh kỳ quái. Tâm trạng hay lo âu, bồn chồn.</p>', 0)";
$values[] = "('Hạn', 'Địa Võng', 'van_han', '<p><strong>Hạn Địa Võng:</strong> Chủ về rắc rối, thị phi, cấm kỵ đi xa vào giờ Tuất, ngày Tuất. Gia đạo có chuyện buồn.</p>', 0)";
$values[] = "('Hạn', 'Diêm Vương', 'van_han', '<p><strong>Hạn Diêm Vương:</strong> Kỵ người già, bệnh nặng khó qua. Nhưng tốt cho việc cầu tài lộc, kinh doanh.</p>', 0)";

// Detailed Monthly Interpretations by Star
// La Hau (Bad)
$values[] = "('La Hầu', 'Tháng 1', 'van_han', 'Tháng Giêng (La Hầu): Đầu năm hao tài, có chuyện buồn phiền, lo âu.', 0)";
$values[] = "('La Hầu', 'Tháng 2', 'van_han', 'Tháng Hai (La Hầu): Cẩn thận bệnh tật, gia đạo bất an.', 0)";
$values[] = "('La Hầu', 'Tháng 3', 'van_han', 'Tháng Ba (La Hầu): Công việc trắc trở, tiểu nhân quấy phá.', 0)";
$values[] = "('La Hầu', 'Tháng 4', 'van_han', 'Tháng Tư (La Hầu): Đề phòng tai nạn sông nước, đi lại cẩn thận.', 0)";
$values[] = "('La Hầu', 'Tháng 5', 'van_han', 'Tháng Năm (La Hầu): Có tin vui từ xa nhưng không trọn vẹn.', 0)";
$values[] = "('La Hầu', 'Tháng 6', 'van_han', 'Tháng Sáu (La Hầu): Tiền bạc hao hụt, chớ nên đầu tư lớn.', 0)";
$values[] = "('La Hầu', 'Tháng 7', 'van_han', 'Tháng Bảy (La Hầu): Kỵ tháng này nhất, cẩn thận thị phi, tranh chấp.', 0)";
$values[] = "('La Hầu', 'Tháng 8', 'van_han', 'Tháng Tám (La Hầu): Sức khỏe kém, đề phòng bệnh cũ tái phát.', 0)";
$values[] = "('La Hầu', 'Tháng 9', 'van_han', 'Tháng Chín (La Hầu): Gia đạo có hỷ sự, cứu vãn vận hạn.', 0)";
$values[] = "('La Hầu', 'Tháng 10', 'van_han', 'Tháng Mười (La Hầu): Công việc ổn định, nhưng vẫn cần đề phòng.', 0)";
$values[] = "('La Hầu', 'Tháng 11', 'van_han', 'Tháng Mười Một (La Hầu): Có lộc nhỏ, tinh thần thoải mái hơn.', 0)";
$values[] = "('La Hầu', 'Tháng 12', 'van_han', 'Tháng Chạp (La Hầu): Tổng kết cuối năm, mọi sự bình an.', 0)";

// Thai Bach (Bad Money)
$values[] = "('Thái Bạch', 'Tháng 1', 'van_han', 'Tháng Giêng (Thái Bạch): Hao tài tốn của, chi tiêu nhiều cho lễ hội.', 0)";
$values[] = "('Thái Bạch', 'Tháng 2', 'van_han', 'Tháng Hai (Thái Bạch): Công việc gặp khó khăn, tiền bạc thất thoát.', 0)";
$values[] = "('Thái Bạch', 'Tháng 3', 'van_han', 'Tháng Ba (Thái Bạch): Đề phòng trộm cắp, mất mát tài sản.', 0)";
$values[] = "('Thái Bạch', 'Tháng 4', 'van_han', 'Tháng Tư (Thái Bạch): Sức khỏe tốt, nhưng tinh thần lo âu.', 0)";
$values[] = "('Thái Bạch', 'Tháng 5', 'van_han', 'Tháng Năm (Thái Bạch): Kỵ tháng này, tuyệt đối không cho vay mượn.', 0)";
$values[] = "('Thái Bạch', 'Tháng 6', 'van_han', 'Tháng Sáu (Thái Bạch): Có quý nhân giúp đỡ, công việc hanh thông.', 0)";
$values[] = "('Thái Bạch', 'Tháng 7', 'van_han', 'Tháng Bảy (Thái Bạch): Tránh đi xa, cẩn thận xe cộ.', 0)";
$values[] = "('Thái Bạch', 'Tháng 8', 'van_han', 'Tháng Tám (Thái Bạch): Gia đạo có chuyện buồn, cần nhẫn nhịn.', 0)";
$values[] = "('Thái Bạch', 'Tháng 9', 'van_han', 'Tháng Chín (Thái Bạch): Tài lộc hồi phục, có thể thu hồi nợ.', 0)";
$values[] = "('Thái Bạch', 'Tháng 10', 'van_han', 'Tháng Mười (Thái Bạch): Sức khỏe kém, chú ý ăn uống.', 0)";
$values[] = "('Thái Bạch', 'Tháng 11', 'van_han', 'Tháng Mười Một (Thái Bạch): Công việc bận rộn, áp lực cao.', 0)";
$values[] = "('Thái Bạch', 'Tháng 12', 'van_han', 'Tháng Chạp (Thái Bạch): Cuối năm bình an, chuẩn bị cho năm mới.', 0)";

// Thai Duong (Good for Male)
$values[] = "('Thái Dương', 'Tháng 1', 'van_han', 'Tháng Giêng (Thái Dương): Khởi đầu năm mới rực rỡ, nhiều may mắn.', 0)";
$values[] = "('Thái Dương', 'Tháng 2', 'van_han', 'Tháng Hai (Thái Dương): Công danh thăng tiến, được cấp trên tin dùng.', 0)";
$values[] = "('Thái Dương', 'Tháng 3', 'van_han', 'Tháng Ba (Thái Dương): Tài lộc dồi dào, kinh doanh phát đạt.', 0)";
$values[] = "('Thái Dương', 'Tháng 4', 'van_han', 'Tháng Tư (Thái Dương): Gia đạo hạnh phúc, có tin vui.', 0)";
$values[] = "('Thái Dương', 'Tháng 5', 'van_han', 'Tháng Năm (Thái Dương): Sức khỏe dồi dào, tinh thần phấn chấn.', 0)";
$values[] = "('Thái Dương', 'Tháng 6', 'van_han', 'Tháng Sáu (Thái Dương): Cẩn thận thị phi nhỏ, không đáng ngại.', 0)";
$values[] = "('Thái Dương', 'Tháng 7', 'van_han', 'Tháng Bảy (Thái Dương): Đi xa có lợi, gặp gỡ đối tác tốt.', 0)";
$values[] = "('Thái Dương', 'Tháng 8', 'van_han', 'Tháng Tám (Thái Dương): Tài lộc tiếp tục tăng tiến.', 0)";
$values[] = "('Thái Dương', 'Tháng 9', 'van_han', 'Tháng Chín (Thái Dương): Gia đạo bình an, con cháu hiếu thảo.', 0)";
$values[] = "('Thái Dương', 'Tháng 10', 'van_han', 'Tháng Mười (Thái Dương): Cẩn thận thời tiết chuyển mùa, bệnh vặt.', 0)";
$values[] = "('Thái Dương', 'Tháng 11', 'van_han', 'Tháng Mười Một (Thái Dương): Tổng kết thành quả, nhiều niềm vui.', 0)";
$values[] = "('Thái Dương', 'Tháng 12', 'van_han', 'Tháng Chạp (Thái Dương): Mọi sự viên mãn, đón tết vui vẻ.', 0)";

$db->query($sql . implode(',', $values));
