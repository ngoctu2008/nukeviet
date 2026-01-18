<?php

/**
 * @Project NUKEVIET 4.x
 * @Author Jules (jules@google.com)
 * @Copyright (C) 2024 Jules. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 22-10-2024
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

// Sample Units
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_units (id, title, note, weight, status) VALUES
(1, 'Phòng Tổ chức - Hành chính', 'Khối Văn phòng', 1, 1),
(2, 'Phòng Tài chính - Kế toán', 'Khối Văn phòng', 2, 1),
(3, 'Khoa Cơ bản', 'Khối Đào tạo', 3, 1),
(4, 'Khoa Kỹ thuật', 'Khối Đào tạo', 4, 1),
(5, 'Trung tâm Tuyển sinh', 'Khối Hỗ trợ', 5, 1)");

// Sample Topics
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_topics (id, title, note) VALUES
(1, 'Kiến thức chung về Luật', 'Các câu hỏi về Hiến pháp, Luật chung'),
(2, 'Nghị quyết Đại hội Đảng', 'Các câu hỏi về Nghị quyết ĐH Đảng các cấp'),
(3, 'Văn bản pháp luật chuyên ngành', 'Luật Giáo dục, Luật Lao động...')");

// Sample Exams
$time_start = time();
$time_end = $time_start + (7 * 86400); // +7 days
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exams (id, title, alias, description, time_start, time_end, duration, num_questions, status, has_prediction) VALUES
(1, 'Cuộc thi tìm hiểu Pháp luật 2024', 'cuoc-thi-tim-hieu-phap-luat-2024', '<p>Cuộc thi nhằm nâng cao nhận thức pháp luật cho cán bộ, viên chức.</p>', " . $time_start . ", " . $time_end . ", 30, 20, 1, 1)");

// Sample Exam Structure (Exam 1: 5 from Topic 1, 5 from Topic 2, 10 from Topic 3)
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_exam_structure (exam_id, topic_id, quantity) VALUES
(1, 1, 5),
(1, 2, 5),
(1, 3, 10)");

// Sample Questions
// Topic 1: General Law
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions (id, exam_id, topic_id, title, type, score, weight) VALUES
(1, 1, 1, 'Hiến pháp nước CHXHCN Việt Nam năm 2013 có hiệu lực từ ngày nào?', 1, 1, 1),
(2, 1, 1, 'Quốc kỳ nước Cộng hòa xã hội chủ nghĩa Việt Nam hình chữ nhật, chiều rộng bằng hai phần ba chiều dài, nền đỏ, ở giữa có [[input]].', 3, 1, 2),
(3, 1, 1, 'Các quyền cơ bản của công dân được quy định trong Hiến pháp bao gồm những quyền nào?', 2, 1, 3),
(4, 1, 1, 'Cơ quan quyền lực nhà nước cao nhất của nước Cộng hòa xã hội chủ nghĩa Việt Nam là cơ quan nào?', 1, 1, 4),
(5, 1, 1, 'Trình bày cảm nghĩ của anh/chị về vai trò của pháp luật trong đời sống.', 4, 5, 5)");

// Topic 2: Party Resolution
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions (id, exam_id, topic_id, title, type, score, weight) VALUES
(6, 1, 2, 'Đại hội đại biểu toàn quốc lần thứ XIII của Đảng diễn ra vào thời gian nào?', 1, 1, 1),
(7, 1, 2, 'Mục tiêu phát triển đất nước đến năm 2030 là gì?', 1, 1, 2),
(8, 1, 2, 'Những nhiệm vụ trọng tâm trong nhiệm kỳ Đại hội XIII là gì?', 2, 1, 3),
(9, 1, 2, 'Khâu đột phá chiến lược về nguồn nhân lực chú trọng vào yếu tố nào?', 1, 1, 4),
(10, 1, 2, 'Việt Nam phấn đấu trở thành nước phát triển, thu nhập cao vào năm [[input]].', 3, 1, 5)");

// Topic 3: Specialized Law (Adding just a few for demo)
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_questions (id, exam_id, topic_id, title, type, score, weight) VALUES
(11, 1, 3, 'Luật Giáo dục năm 2019 có hiệu lực thi hành từ ngày nào?', 1, 1, 1),
(12, 1, 3, 'Giáo dục tiểu học là giáo dục bắt buộc? (Đúng/Sai)', 1, 1, 2),
(13, 1, 3, 'Nhà giáo có vai trò quyết định trong việc đảm bảo chất lượng giáo dục. (Đúng/Sai)', 1, 1, 3),
(14, 1, 3, 'Các hành vi bị nghiêm cấm trong cơ sở giáo dục bao gồm?', 2, 1, 4),
(15, 1, 3, 'Người học có quyền được tôn trọng, bình đẳng về cơ hội giáo dục? (Đúng/Sai)', 1, 1, 5)");

// Sample Answers
// Q1: Radio
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(1, '01/01/2014', 1),
(1, '28/11/2013', 0),
(1, '02/09/2013', 0),
(1, '30/04/2014', 0)");

// Q2: Fill - "ngôi sao vàng năm cánh"
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(2, 'ngôi sao vàng năm cánh', 1)");

// Q3: Checkbox
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(3, 'Quyền bầu cử và ứng cử', 1),
(3, 'Quyền tự do kinh doanh', 1),
(3, 'Quyền xâm phạm thân thể người khác', 0),
(3, 'Quyền được học tập', 1)");

// Q4: Radio
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(4, 'Chính phủ', 0),
(4, 'Quốc hội', 1),
(4, 'Chủ tịch nước', 0),
(4, 'Tòa án nhân dân tối cao', 0)");

// Q6: Radio
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(6, 'Từ 25/01 đến 01/02/2021', 1),
(6, 'Từ 20/01 đến 28/01/2021', 0),
(6, 'Từ 01/02 đến 08/02/2021', 0)");

// Q10: Fill - "2045"
$db->query("INSERT INTO " . $db_config['prefix'] . "_" . $lang . "_" . $module_data . "_answers (question_id, title, is_correct) VALUES
(10, '2045', 1)");
