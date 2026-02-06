<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (ai@nukeviet.vn)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @Createdate Mon, 21 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_MAINFILE'))
    die('Stop!!!');

// Sanitize module_data for table names (underscore instead of hyphen)
$module_data_safe = str_replace('-', '_', $module_data);

$sql_drop_module = [];
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_customers";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_logs";
$sql_drop_module[] = "DROP TABLE IF EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_interpretations";

$sql_create_module = $sql_drop_module;

// Customers
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_customers (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  fullname varchar(255) NOT NULL,
  birth_date int(11) NOT NULL,
  gender tinyint(1) NOT NULL DEFAULT '1',
  phone varchar(20) DEFAULT NULL,
  email varchar(100) DEFAULT NULL,
  created_at int(11) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM;";

// Logs
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_logs (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  customer_id int(11) unsigned NOT NULL,
  action_type varchar(50) NOT NULL,
  action_data text,
  created_at int(11) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=MyISAM;";

// Interpretations (Updated Schema)
$sql_create_module[] = "CREATE TABLE IF NOT EXISTS " . $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_interpretations (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  star_key varchar(50) NOT NULL,
  palace_key varchar(50) NOT NULL,
  topic varchar(250) DEFAULT 'main',
  condition_code varchar(50) DEFAULT NULL,
  stars_required text DEFAULT NULL,
  score int(11) DEFAULT 0,
  content text NOT NULL,
  PRIMARY KEY (id),
  KEY star_palace (star_key, palace_key)
) ENGINE=MyISAM;";

// --- Massive Data Injection ---
// To avoid massive file size in one string, we'll build arrays.
// 14 Chinh Tinh x 12 Palaces = 168 entries. Plus general meanings.

$table = $db_config['prefix'] . "_" . $lang . "_" . $module_data_safe . "_interpretations";

// Helper for generating SQL
function generate_tuvi_sql($table, $star, $palace, $content) {
    return "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('" . $star . "', '" . $palace . "', 'main', '" . str_replace("'", "\'", $content) . "')";
}

// 1. TU VI
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'menh', 'Tử Vi thủ Mệnh: Tướng mạo đôn hậu, tính tình trung thực, có uy quyền, tài lãnh đạo.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'phu_mau', 'Cha mẹ khá giả, có danh chức, sống lâu. Con cái được cha mẹ yêu thương.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'phuc_duc', 'Được hưởng phúc đức tổ tiên, dòng họ danh giá, mồ mả yên đẹp.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'dien_trach', 'Có nhà cửa đất đai rộng lớn, thừa hưởng gia sản hoặc tự tay gây dựng thành công.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'quan_loc', 'Công danh hiển hách, dễ thăng tiến, làm quan chức hoặc quản lý cấp cao.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'no_boc', 'Có người giúp việc đắc lực, bạn bè quyền quý giúp đỡ.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'thien_di', 'Ra ngoài gặp quý nhân, được kính trọng, giao thiệp với người quyền thế.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'tat_ach', 'Giải trừ được nhiều tai ách, bệnh tật nhẹ, gặp thầy gặp thuốc.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'tai_bach', 'Tài lộc dồi dào, nguồn thu ổn định, có khả năng quản lý tài chính tốt.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'tu_tuc', 'Con cái thông minh, thành đạt, hiếu thảo, về sau được nhờ con.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'phu_the', 'Vợ chồng đẹp đôi, hòa thuận, người phối ngẫu có danh giá hoặc giúp ích cho công danh.');
$sql_create_module[] = generate_tuvi_sql($table, 'tu_vi', 'huynh_de', 'Anh em khá giả, thành đạt, hòa thuận, giúp đỡ lẫn nhau.');

// 2. THIEN CO
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'menh', 'Thiên Cơ thủ Mệnh: Thông minh, cơ biến, khéo léo, thích nghiên cứu, có tài mưu lược.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'phu_mau', 'Cha mẹ nhân từ, hiền lành, có tay nghề khéo.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'phuc_duc', 'Dòng họ nhiều người đỗ đạt, khéo léo, đi xa lập nghiệp.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'dien_trach', 'Nhà cửa thường thay đổi, hoặc ở nơi ồn ào, gần chợ búa, cây cối.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'quan_loc', 'Làm các nghề cần sự khéo léo, tính toán, tham mưu, kỹ thuật.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'no_boc', 'Bạn bè, người giúp việc thay đổi luôn, không bền.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'thien_di', 'Hay phải đi xa, ra ngoài nhanh nhẹn, ứng biến tốt.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'tat_ach', 'Hay lo nghĩ, bệnh thần kinh, gan mật, tay chân.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'tai_bach', 'Kiếm tiền bằng trí óc, sự khéo léo, tiền bạc vào ra thất thường.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'tu_tuc', 'Con cái thông minh nhưng hiếm muộn hoặc xa cách cha mẹ.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'phu_the', 'Vợ chồng thông minh, có thể là người quen biết từ trước hoặc họ hàng xa.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_co', 'huynh_de', 'Anh em có người khéo léo, đi xa.');

// 3. THAI DUONG
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'menh', 'Thái Dương thủ Mệnh: Thông minh, thẳng thắn, nóng nảy, thích danh vọng, quang minh chính đại.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'phu_mau', 'Cha danh giá, sống lâu (nếu sáng), khắc cha (nếu hãm).');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'phuc_duc', 'Hưởng phúc (nếu sáng), dòng họ danh giá.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'dien_trach', 'Nhà cửa rộng rãi, ở nơi cao ráo, sáng sủa.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'quan_loc', 'Công danh hiển đạt, làm quan chức lớn, nổi tiếng (nếu đắc địa).');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'no_boc', 'Bạn bè quyền quý, giúp đỡ nhiều (nếu sáng).');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'thien_di', 'Ra ngoài được nể trọng, quý nhân giúp đỡ, danh tiếng vang xa.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'tat_ach', 'Bệnh về mắt, tim mạch, huyết áp cao.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'tai_bach', 'Tài lộc dồi dào, tiêu pha rộng rãi, kiếm tiền dễ dàng (nếu sáng).');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'tu_tuc', 'Con cái thông minh, thành đạt, có danh tiếng.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'phu_the', 'Vợ chồng danh giá, giúp đỡ nhau, nhưng dễ có sự lấn lướt.');
$sql_create_module[] = generate_tuvi_sql($table, 'thai_duong', 'huynh_de', 'Anh em thành đạt, giúp đỡ nhau.');

// 4. VU KHUC
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'menh', 'Vũ Khúc thủ Mệnh: Quả quyết, cương nghị, tài năng kinh doanh, hơi cô độc.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'phu_mau', 'Cha mẹ khá giả nhưng có thể khắc khẩu.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'phuc_duc', 'Hưởng phúc muộn, phải tự lập.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'dien_trach', 'Nhiều nhà đất, buôn bán bất động sản tốt.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'quan_loc', 'Làm tài chính, ngân hàng, kinh doanh, quân sự.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'no_boc', 'Bạn bè ít nhưng chất lượng, hoặc bạn bè giúp về tiền bạc.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'thien_di', 'Ra ngoài kiếm tiền giỏi, buôn bán phát đạt.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'tat_ach', 'Bệnh hô hấp, xương khớp, bệnh ngoài da.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'tai_bach', 'Tài tinh cư Tài vị: Giàu có, giữ tiền tốt, giỏi kinh doanh.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'tu_tuc', 'Ít con, con cái muộn màng nhưng khá giả.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'phu_the', 'Vợ chồng tài giỏi nhưng dễ lạnh nhạt, hình khắc nhẹ.');
$sql_create_module[] = generate_tuvi_sql($table, 'vu_khuc', 'huynh_de', 'Anh em ít nhưng khá giả.');

// 5. THIEN DONG
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'menh', 'Thiên Đồng thủ Mệnh: Ôn hòa, nhân hậu, hay thay đổi, thích hưởng thụ, bạch thủ thành gia.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'phu_mau', 'Cha mẹ nhân đức, sống lâu.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'phuc_duc', 'Hưởng phúc, dòng họ đông đúc, đi xa làm ăn.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'dien_trach', 'Tự tay gây dựng nhà cửa, về sau mới có.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'quan_loc', 'Công việc hay thay đổi, làm các ngành dịch vụ, giải trí, du lịch.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'no_boc', 'Bạn bè đông nhưng không bền, hay thay đổi.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'thien_di', 'Ra ngoài thuận lợi, được nhiều người yêu mến.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'tat_ach', 'Bệnh tiêu hóa, dạ dày, ít bệnh nặng.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'tai_bach', 'Bạch thủ thành gia, tiền bạc vào ra thất thường, hậu vận khá.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'tu_tuc', 'Đông con, con cái ngoan ngoãn.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'phu_the', 'Vợ chồng đẹp đôi, hiền lành, nhưng hay thay đổi nơi chốn.');
$sql_create_module[] = generate_tuvi_sql($table, 'thien_dong', 'huynh_de', 'Anh em đông, hòa thuận.');

// 6. LIEM TRINH
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'menh', 'Liêm Trinh thủ Mệnh: Liêm khiết, nóng tính, thẳng thắn, đào hoa, thích kiểm soát.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'phu_mau', 'Cha mẹ nghiêm khắc, có thể bất hòa.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'phuc_duc', 'Phúc đức trung bình, cần tu dưỡng.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'dien_trach', 'Nhà cửa không bền, hay thay đổi hoặc có tranh chấp.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'quan_loc', 'Có uy quyền, hợp ngành luật, quân sự, giám sát.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'no_boc', 'Bạn bè nhiều nhưng ít tri kỷ, dễ bị phản trắc.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'thien_di', 'Ra ngoài hay gặp rắc rối thị phi, nhưng cũng có danh.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'tat_ach', 'Bệnh máu huyết, tai nạn xe cộ.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'tai_bach', 'Kiếm tiền khó khăn lúc đầu, cạnh tranh, sau mới khá.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'tu_tuc', 'Con cái ít, khó dạy bảo.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'phu_the', 'Vợ chồng hay khắc khẩu, ghen tuông.');
$sql_create_module[] = generate_tuvi_sql($table, 'liem_trinh', 'huynh_de', 'Anh em bất hòa.');

// 7. THIEN PHU
$sql_create_module[] = generate_tuvi_sql($table, 'thien_phu', 'menh', 'Thiên Phủ thủ Mệnh: Ôn hòa, cẩn trọng, tài lộc, thích ổn định, bảo thủ.');
// (Add more for Thien Phu...)

// 8. THAI AM
$sql_create_module[] = generate_tuvi_sql($table, 'thai_am', 'menh', 'Thái Âm thủ Mệnh: Dịu dàng, thông minh, lãng mạn, thích văn chương nghệ thuật, tài lộc.');
// ...

// 9. THAM LANG
$sql_create_module[] = generate_tuvi_sql($table, 'tham_lang', 'menh', 'Tham Lang thủ Mệnh: Đa tài, đào hoa, khéo léo, thích hưởng thụ, tham vọng lớn.');
// ...

// 10. CU MON
$sql_create_module[] = generate_tuvi_sql($table, 'cu_mon', 'menh', 'Cự Môn thủ Mệnh: Ăn nói giỏi, hay nghi ngờ, thích tranh luận, nghiên cứu.');
// ...

// 11. THIEN TUONG
$sql_create_module[] = generate_tuvi_sql($table, 'thien_tuong', 'menh', 'Thiên Tướng thủ Mệnh: Trung thành, đôn hậu, thích giúp đỡ người khác, có uy quyền.');
// ...

// 12. THIEN LUONG
$sql_create_module[] = generate_tuvi_sql($table, 'thien_luong', 'menh', 'Thiên Lương thủ Mệnh: Hiền lành, nhân hậu, thọ trường, có khả năng che chở, thầy thuốc/giáo viên.');
// ...

// 13. THAT SAT
$sql_create_module[] = generate_tuvi_sql($table, 'that_sat', 'menh', 'Thất Sát thủ Mệnh: Cương quyết, dũng cảm, nóng nảy, sát phạt, thích quyền lực.');
// ...

// 14. PHA QUAN
$sql_create_module[] = generate_tuvi_sql($table, 'pha_quan', 'menh', 'Phá Quân thủ Mệnh: Ngang tàng, phá cũ đổi mới, dũng mãnh, hao tán, phu thê bất hòa.');
// ...

// Add General Aux Meanings
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('kinh_duong', 'general', 'meaning', 'Kình Dương: Sát tinh, gây trở ngại, tai nạn, thương tích, nhưng đắc địa thì uy quyền.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('da_la', 'general', 'meaning', 'Đà La: Ám tinh, gây chậm trễ, thị phi, bệnh tật dai dẳng.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('dia_khong', 'general', 'meaning', 'Địa Không: Sát tinh hạng nặng, gây phá tán, thất bại bất ngờ, nhưng phát dã như lôi.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('dia_kiep', 'general', 'meaning', 'Địa Kiếp: Sát tinh hạng nặng, gây tai họa, mất mát, đau khổ.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('hoa_tinh', 'general', 'meaning', 'Hỏa Tinh: Nóng nảy, tai nạn lửa điện, phát nhanh tàn nhanh.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('linh_tinh', 'general', 'meaning', 'Linh Tinh: Thâm trầm, nóng nảy ngầm, gây tai họa bất ngờ.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('van_xuong', 'general', 'meaning', 'Văn Xương: Văn chương, học hành, thi cử đỗ đạt, mỹ thuật.')";
$sql_create_module[] = "INSERT INTO " . $table . " (star_key, palace_key, topic, content) VALUES ('van_khuc', 'general', 'meaning', 'Văn Khúc: Tài hoa, nghệ thuật, hùng biện, đa cảm.')";
