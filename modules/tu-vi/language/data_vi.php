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

// STAR DEFINITIONS (New Section for Tooltips)
// Format: star_key = [StarName], palace_key = '', topic = 'star_info'
$values[] = "('Liêm Trinh', '', 'star_info', 'Sao Liêm Trinh trong Tử Vi là một chính tinh thuộc hành Hỏa, có tính chất đối lập, vừa biểu trưng sự quyết đoán, tài năng, quyền lực, nhưng cũng mang đến thử thách tình cảm và tài chính, dễ gặp lận đận nếu gặp sát tinh như Hóa Kỵ, Kình Dương, Đà La. Khi đắc địa, Liêm Trinh chủ công danh, sự nghiệp thăng tiến, có khả năng quản lý; gặp Xương Khúc thì văn chương, nghệ thuật; nhưng hãm địa (Tỵ, Hợi) hoặc gặp sát tinh thì dễ gặp tai họa, tranh chấp, sức khỏe kém, tình cảm bất hòa', 0)";

// Placeholder for other main stars to avoid empty tooltips (Generic)
$generic_stars = ['Tử Vi', 'Thiên Cơ', 'Thái Dương', 'Vũ Khúc', 'Thiên Đồng', 'Thiên Phủ', 'Thái Âm', 'Tham Lang', 'Cự Môn', 'Thiên Tướng', 'Thiên Lương', 'Thất Sát', 'Phá Quân'];
foreach ($generic_stars as $star) {
    $values[] = "('" . $star . "', '', 'star_info', '<strong>" . $star . "</strong>: Là một trong 14 chính tinh quan trọng. Tính chất cụ thể phụ thuộc vào vị trí cung và các sao đi kèm.', 0)";
}

// Main Stars (Position specific)
$values[] = "('Tử Vi', 'Tý', 'tong_quan', '<p><strong>Tử Vi tại Tý:</strong> Bình hòa. Chủ về người khoan dung, nhân hậu nhưng thiếu quyết đoán nếu không có Tả Hữu hội chiếu. Ưa làm việc công chức, giáo dục.</p>', 0)";
$values[] = "('Tử Vi', 'Ngọ', 'tong_quan', '<p><strong>Tử Vi tại Ngọ:</strong> Miếu địa (Cực hướng Ly minh). Rất tốt. Chủ về uy quyền, tài năng lãnh đạo, phú quý song toàn. <br><br><strong>Về Công Danh:</strong> Đường công danh rộng mở, dễ đạt được vị trí cao trong xã hội, được người đời kính trọng. Thích hợp làm chính trị, quản lý doanh nghiệp lớn.<br><br><strong>Về Tài Lộc:</strong> Tài vận hanh thông, cả đời không lo thiếu thốn tiền bạc. Có khả năng quản lý tài chính xuất sắc, tiền đẻ ra tiền.<br><br><strong>Về Gia Đạo:</strong> Gia đình êm ấm, vợ chồng hòa thuận, con cái thành đạt, hiếu thảo. Tuy nhiên cần chú ý không nên quá độc đoán trong gia đình.<br><br><strong>Về Sức Khỏe:</strong> Sức khỏe dồi dào, ít bệnh tật. Cần chú ý các bệnh về tiêu hóa do ăn uống tiệc tùng nhiều.</p>', 0)";
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
$values[] = "('Tổng Quan', 'Mệnh', 'tong_quan', '<p><strong>I. TỔNG QUAN BẢN MỆNH VÀ TÍNH CÁCH</strong><br>Người sở hữu lá số này thường có tính cách mạnh mẽ, cương trực và giàu lòng tự trọng. Bản mệnh vững vàng giúp đương số dễ dàng vượt qua những sóng gió, thử thách trong cuộc đời. Tuy nhiên, đôi khi sự cứng rắn quá mức có thể dẫn đến bảo thủ, thiếu linh hoạt trong giao tiếp xã hội. Lời khuyên là nên rèn luyện sự mềm mỏng, lắng nghe ý kiến người khác để hoàn thiện bản thân.<br>Về mặt tâm tính, đây là người sống có trách nhiệm, coi trọng chữ tín và luôn nỗ lực hết mình vì mục tiêu đã đề ra. Dù trong hoàn cảnh nào cũng giữ được khí tiết, không dễ bị cám dỗ bởi lợi danh bất chính.</p><p><strong>II. CÔNG DANH VÀ SỰ NGHIỆP</strong><br>Đường công danh của đương số trải qua nhiều thăng trầm, đặc biệt là trong giai đoạn tiền vận (trước 30 tuổi). Ban đầu có thể gặp nhiều khó khăn, thay đổi công việc hoặc định hướng chưa rõ ràng. Tuy nhiên, nhờ sự kiên trì và bản lĩnh, từ trung vận trở đi (sau 35 tuổi), sự nghiệp bắt đầu khởi sắc và đi vào ổn định.<br>Những ngành nghề phù hợp bao gồm: Quản lý, kinh doanh, hoặc các công việc đòi hỏi chuyên môn kỹ thuật cao. Nếu làm trong nhà nước thì thăng tiến chậm nhưng chắc; nếu làm tư nhân thì có cơ hội bứt phá nhưng áp lực lớn.</p><p><strong>III. TÀI BẠCH (TÀI CHÍNH - TIỀN BẠC)</strong><br>Về phương diện tài lộc, lá số này cho thấy đương số không phải lo lắng quá nhiều về cơm áo gạo tiền. Tiền bạc thường đến từ nỗ lực làm việc chân chính (Chính Tài) hơn là may mắn bất ngờ (Hoạch Tài).<br>Giai đoạn tuổi trẻ có thể tiêu pha nhiều, khó tích lũy. Nhưng càng về già, khả năng quản lý tài chính càng tốt, hậu vận có của ăn của để, điền sản sung túc. Cần lưu ý tránh đầu tư mạo hiểm hoặc cho vay mượn không rõ ràng để tránh thất thoát tài sản.</p><p><strong>IV. TÌNH DUYÊN VÀ GIA ĐẠO</strong><br>Chuyện tình cảm có đôi chút trắc trở ban đầu. Có thể lập gia đình muộn hoặc phải trải qua một vài mối tình mới tìm được ý trung nhân. Tuy nhiên, khi đã kết hôn, gia đạo nhìn chung êm ấm, vợ chồng biết nhường nhịn, chia sẻ.<br>Đối với con cái, đương số là người nghiêm khắc nhưng rất mực yêu thương, lo lắng cho tương lai của con. Con cái sau này thường thành đạt, hiếu thuận, là chỗ dựa tinh thần vững chắc cho cha mẹ.</p><p><strong>V. SỨC KHỎE VÀ HẬU VẬN</strong><br>Sức khỏe nhìn chung tốt, nhưng cần chú ý các bệnh liên quan đến đường tiêu hóa hoặc xương khớp khi lớn tuổi. Nên duy trì chế độ ăn uống lành mạnh và tập thể dục thường xuyên.<br>Hậu vận an nhàn, được hưởng phúc lộc từ con cháu. Cuộc sống về già thanh thản, ít lo âu phiền muộn, được mọi người xung quanh kính trọng.</p>', 0)";
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

// Ke Do (Bad, Hung tinh)
$values[] = "('Kế Đô', 'Tháng 1', 'van_han', 'Tháng Giêng (Kế Đô): Đầu năm gặp chuyện không may, tinh thần bất an.', 0)";
$values[] = "('Kế Đô', 'Tháng 2', 'van_han', 'Tháng Hai (Kế Đô): Tiền bạc hao tán, cẩn thận mất mát.', 0)";
$values[] = "('Kế Đô', 'Tháng 3', 'van_han', 'Tháng Ba (Kế Đô): Kỵ nhất tháng này (đặc biệt nữ giới), đề phòng thị phi, bệnh tật.', 0)";
$values[] = "('Kế Đô', 'Tháng 4', 'van_han', 'Tháng Tư (Kế Đô): Gia đạo có xáo trộn, cần nhẫn nại.', 0)";
$values[] = "('Kế Đô', 'Tháng 5', 'van_han', 'Tháng Năm (Kế Đô): Tránh đi xa, cẩn thận tai nạn nhỏ.', 0)";
$values[] = "('Kế Đô', 'Tháng 6', 'van_han', 'Tháng Sáu (Kế Đô): Công việc trì trệ, không nên khởi sự mới.', 0)";
$values[] = "('Kế Đô', 'Tháng 7', 'van_han', 'Tháng Bảy (Kế Đô): Gặp quý nhân giúp đỡ nhưng vẫn cần thận trọng.', 0)";
$values[] = "('Kế Đô', 'Tháng 8', 'van_han', 'Tháng Tám (Kế Đô): Tài lộc trung bình, chi tiêu hợp lý.', 0)";
$values[] = "('Kế Đô', 'Tháng 9', 'van_han', 'Tháng Chín (Kế Đô): Kỵ tháng này (như tháng 3), không nên lo chuyện bao đồng.', 0)";
$values[] = "('Kế Đô', 'Tháng 10', 'van_han', 'Tháng Mười (Kế Đô): Sức khỏe có vấn đề, chú ý đường tiêu hóa.', 0)";
$values[] = "('Kế Đô', 'Tháng 11', 'van_han', 'Tháng Mười Một (Kế Đô): Tinh thần thoải mái hơn, công việc dần ổn định.', 0)";
$values[] = "('Kế Đô', 'Tháng 12', 'van_han', 'Tháng Chạp (Kế Đô): Cuối năm cẩn thận lời ăn tiếng nói.', 0)";

// Thai Am (Good, Nguyet tinh)
$values[] = "('Thái Âm', 'Tháng 1', 'van_han', 'Tháng Giêng (Thái Âm): Đầu năm an vui, gia đình hạnh phúc.', 0)";
$values[] = "('Thái Âm', 'Tháng 2', 'van_han', 'Tháng Hai (Thái Âm): Có tin vui từ phương xa, hoặc gặp lại người xưa.', 0)";
$values[] = "('Thái Âm', 'Tháng 3', 'van_han', 'Tháng Ba (Thái Âm): Tài lộc dồi dào, công việc thuận lợi.', 0)";
$values[] = "('Thái Âm', 'Tháng 4', 'van_han', 'Tháng Tư (Thái Âm): Cẩn thận sức khỏe khi giao mùa.', 0)";
$values[] = "('Thái Âm', 'Tháng 5', 'van_han', 'Tháng Năm (Thái Âm): Tình cảm thăng hoa, có thể tính chuyện hỷ sự.', 0)";
$values[] = "('Thái Âm', 'Tháng 6', 'van_han', 'Tháng Sáu (Thái Âm): Công danh có bước tiến mới.', 0)";
$values[] = "('Thái Âm', 'Tháng 7', 'van_han', 'Tháng Bảy (Thái Âm): Làm việc thiện tích đức, mọi sự hanh thông.', 0)";
$values[] = "('Thái Âm', 'Tháng 8', 'van_han', 'Tháng Tám (Thái Âm): Tiền bạc vào như nước, kinh doanh phát đạt.', 0)";
$values[] = "('Thái Âm', 'Tháng 9', 'van_han', 'Tháng Chín (Thái Âm): Tháng đại cát (đặc biệt nữ giới), mọi mong cầu đều toại nguyện.', 0)";
$values[] = "('Thái Âm', 'Tháng 10', 'van_han', 'Tháng Mười (Thái Âm): Gia đạo yên ấm, con cái ngoan ngoãn.', 0)";
$values[] = "('Thái Âm', 'Tháng 11', 'van_han', 'Tháng Mười Một (Thái Âm): Cẩn thận tiểu nhân đố kỵ, nhưng không đáng ngại.', 0)";
$values[] = "('Thái Âm', 'Tháng 12', 'van_han', 'Tháng Chạp (Thái Âm): Kết thúc năm viên mãn, chuẩn bị đón xuân.', 0)";

// Moc Duc (Good, Moc tinh)
$values[] = "('Mộc Đức', 'Tháng 1', 'van_han', 'Tháng Giêng (Mộc Đức): Vui vẻ đón xuân, may mắn gõ cửa.', 0)";
$values[] = "('Mộc Đức', 'Tháng 2', 'van_han', 'Tháng Hai (Mộc Đức): Công việc hanh thông, được cấp trên tin tưởng.', 0)";
$values[] = "('Mộc Đức', 'Tháng 3', 'van_han', 'Tháng Ba (Mộc Đức): Có lộc nhỏ, tinh thần phấn chấn.', 0)";
$values[] = "('Mộc Đức', 'Tháng 4', 'van_han', 'Tháng Tư (Mộc Đức): Gia đạo hòa thuận, sức khỏe tốt.', 0)";
$values[] = "('Mộc Đức', 'Tháng 5', 'van_han', 'Tháng Năm (Mộc Đức): Cẩn thận các bệnh về mắt, nhưng mau khỏi.', 0)";
$values[] = "('Mộc Đức', 'Tháng 6', 'van_han', 'Tháng Sáu (Mộc Đức): Tài chính ổn định, có thể đầu tư.', 0)";
$values[] = "('Mộc Đức', 'Tháng 7', 'van_han', 'Tháng Bảy (Mộc Đức): Tránh tranh chấp không cần thiết.', 0)";
$values[] = "('Mộc Đức', 'Tháng 8', 'van_han', 'Tháng Tám (Mộc Đức): Công danh sáng lạn, gặp cơ hội tốt.', 0)";
$values[] = "('Mộc Đức', 'Tháng 9', 'van_han', 'Tháng Chín (Mộc Đức): Đi xa có lợi, gặp quý nhân.', 0)";
$values[] = "('Mộc Đức', 'Tháng 10', 'van_han', 'Tháng Mười (Mộc Đức): Tháng tốt nhất trong năm, mọi việc như ý.', 0)";
$values[] = "('Mộc Đức', 'Tháng 11', 'van_han', 'Tháng Mười Một (Mộc Đức): Cẩn thận dao kéo, vật sắc nhọn.', 0)";
$values[] = "('Mộc Đức', 'Tháng 12', 'van_han', 'Tháng Chạp (Mộc Đức): Tổng kết năm thành công, tài lộc dồi dào.', 0)";

// Van Hon (Average/Bad, Hoa tinh)
$values[] = "('Vân Hớn', 'Tháng 1', 'van_han', 'Tháng Giêng (Vân Hớn): Bình thường, không có biến động lớn.', 0)";
$values[] = "('Vân Hớn', 'Tháng 2', 'van_han', 'Tháng Hai (Vân Hớn): Kỵ tháng này, cẩn thận thị phi, nóng nảy hỏng việc.', 0)";
$values[] = "('Vân Hớn', 'Tháng 3', 'van_han', 'Tháng Ba (Vân Hớn): Công việc ở mức trung bình, nên giữ nguyên hiện trạng.', 0)";
$values[] = "('Vân Hớn', 'Tháng 4', 'van_han', 'Tháng Tư (Vân Hớn): Đề phòng khẩu thiệt, tai tiếng.', 0)";
$values[] = "('Vân Hớn', 'Tháng 5', 'van_han', 'Tháng Năm (Vân Hớn): Sức khỏe ổn định, tránh làm việc quá sức.', 0)";
$values[] = "('Vân Hớn', 'Tháng 6', 'van_han', 'Tháng Sáu (Vân Hớn): Tài lộc kém, chi tiêu cần tiết kiệm.', 0)";
$values[] = "('Vân Hớn', 'Tháng 7', 'van_han', 'Tháng Bảy (Vân Hớn): Gia đạo có chút bất hòa, cần nhường nhịn.', 0)";
$values[] = "('Vân Hớn', 'Tháng 8', 'van_han', 'Tháng Tám (Vân Hớn): Kỵ tháng này, cẩn thận xe cộ, đi lại.', 0)";
$values[] = "('Vân Hớn', 'Tháng 9', 'van_han', 'Tháng Chín (Vân Hớn): Mọi việc dần trở lại bình thường.', 0)";
$values[] = "('Vân Hớn', 'Tháng 10', 'van_han', 'Tháng Mười (Vân Hớn): Có tin vui từ con cái, học hành.', 0)";
$values[] = "('Vân Hớn', 'Tháng 11', 'van_han', 'Tháng Mười Một (Vân Hớn): Công việc bận rộn nhưng hiệu quả chưa cao.', 0)";
$values[] = "('Vân Hớn', 'Tháng 12', 'van_han', 'Tháng Chạp (Vân Hớn): Cuối năm bình an, tránh kiện tụng.', 0)";

// Tho Tu (Average/Bad, Tho tinh)
$values[] = "('Thổ Tú', 'Tháng 1', 'van_han', 'Tháng Giêng (Thổ Tú): Đầu năm tâm trạng lo âu, chưa an tâm.', 0)";
$values[] = "('Thổ Tú', 'Tháng 2', 'van_han', 'Tháng Hai (Thổ Tú): Công việc gặp trở ngại, tiểu nhân quấy phá.', 0)";
$values[] = "('Thổ Tú', 'Tháng 3', 'van_han', 'Tháng Ba (Thổ Tú): Cẩn thận giấy tờ, ký kết hợp đồng.', 0)";
$values[] = "('Thổ Tú', 'Tháng 4', 'van_han', 'Tháng Tư (Thổ Tú): Kỵ tháng này, gia đạo bất an, chăn nuôi thất bát.', 0)";
$values[] = "('Thổ Tú', 'Tháng 5', 'van_han', 'Tháng Năm (Thổ Tú): Sức khỏe kém, đề phòng bệnh dạ dày.', 0)";
$values[] = "('Thổ Tú', 'Tháng 6', 'van_han', 'Tháng Sáu (Thổ Tú): Không nên đi xa, xuất hành bất lợi.', 0)";
$values[] = "('Thổ Tú', 'Tháng 7', 'van_han', 'Tháng Bảy (Thổ Tú): Cẩn thận lời nói, tránh hiểu lầm.', 0)";
$values[] = "('Thổ Tú', 'Tháng 8', 'van_han', 'Tháng Tám (Thổ Tú): Kỵ tháng này, đề phòng mất của.', 0)";
$values[] = "('Thổ Tú', 'Tháng 9', 'van_han', 'Tháng Chín (Thổ Tú): Tài lộc có chút khởi sắc, nhưng không nhiều.', 0)";
$values[] = "('Thổ Tú', 'Tháng 10', 'van_han', 'Tháng Mười (Thổ Tú): Gia đạo dần ổn định, bớt lo âu.', 0)";
$values[] = "('Thổ Tú', 'Tháng 11', 'van_han', 'Tháng Mười Một (Thổ Tú): Có người giúp đỡ trong công việc.', 0)";
$values[] = "('Thổ Tú', 'Tháng 12', 'van_han', 'Tháng Chạp (Thổ Tú): Tổng kết năm, mọi sự trung bình.', 0)";

// Thuy Dieu (Good/Bad mixed, Thuy tinh)
$values[] = "('Thủy Diệu', 'Tháng 1', 'van_han', 'Tháng Giêng (Thủy Diệu): Vui xuân, tài lộc vào nhà.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 2', 'van_han', 'Tháng Hai (Thủy Diệu): Công việc thuận lợi, đi xa có lợi.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 3', 'van_han', 'Tháng Ba (Thủy Diệu): Có hỷ sự hoặc tin vui từ người thân.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 4', 'van_han', 'Tháng Tư (Thủy Diệu): Kỵ tháng này, tránh sông nước, đi biển.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 5', 'van_han', 'Tháng Năm (Thủy Diệu): Cẩn thận lời ăn tiếng nói, tránh thị phi.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 6', 'van_han', 'Tháng Sáu (Thủy Diệu): Tài chính tốt, kinh doanh có lãi.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 7', 'van_han', 'Tháng Bảy (Thủy Diệu): Bình an, làm việc thiện tích đức.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 8', 'van_han', 'Tháng Tám (Thủy Diệu): Kỵ tháng này (đặc biệt nữ giới), đề phòng tai nạn.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 9', 'van_han', 'Tháng Chín (Thủy Diệu): Sức khỏe tốt, tinh thần sảng khoái.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 10', 'van_han', 'Tháng Mười (Thủy Diệu): Gia đạo hòa thuận, con cái vui vẻ.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 11', 'van_han', 'Tháng Mười Một (Thủy Diệu): Công việc phát triển, mở rộng quy mô.', 0)";
$values[] = "('Thủy Diệu', 'Tháng 12', 'van_han', 'Tháng Chạp (Thủy Diệu): Kết thúc năm viên mãn, đón nhiều tài lộc.', 0)";

$db->query($sql . implode(',', $values));
