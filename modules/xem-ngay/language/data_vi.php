<?php

/**
 * @Project NUKEVIET 4.x
 * @Author YOURNAME (email@domain.com)
 * @Copyright (C) 2024 YOURNAME. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Sat, 26 Oct 2024 00:00:00 GMT
 */

if (!defined('NV_ADMIN')) {
    die('Stop!!!');
}

// Data for Bad Dates (Sat Chu, Tho Tu, Duong Cong Ky Nhat)
// Using placeholder {TABLE} to avoid variable scope issues
$sql_insert_bad_dates = "INSERT INTO {TABLE} (month, day_chi, day_lunar, type, description) VALUES
(1, 5, NULL, 'sat_chu', 'Sát Chủ - Tỵ'),
(2, 0, NULL, 'sat_chu', 'Sát Chủ - Tý'),
(3, 7, NULL, 'sat_chu', 'Sát Chủ - Mùi'),
(4, 3, NULL, 'sat_chu', 'Sát Chủ - Mão'),
(5, 8, NULL, 'sat_chu', 'Sát Chủ - Thân'),
(6, 10, NULL, 'sat_chu', 'Sát Chủ - Tuất'),
(7, 11, NULL, 'sat_chu', 'Sát Chủ - Hợi'),
(8, 1, NULL, 'sat_chu', 'Sát Chủ - Sửu'),
(9, 6, NULL, 'sat_chu', 'Sát Chủ - Ngọ'),
(10, 1, NULL, 'sat_chu', 'Sát Chủ - Sửu'),
(11, 0, NULL, 'sat_chu', 'Sát Chủ - Tý'),
(12, 4, NULL, 'sat_chu', 'Sát Chủ - Thìn'),
(1, 10, NULL, 'tho_tu', 'Thọ Tử - Tuất'),
(2, 4, NULL, 'tho_tu', 'Thọ Tử - Thìn'),
(3, 11, NULL, 'tho_tu', 'Thọ Tử - Hợi'),
(4, 5, NULL, 'tho_tu', 'Thọ Tử - Tỵ'),
(5, 0, NULL, 'tho_tu', 'Thọ Tử - Tý'),
(6, 6, NULL, 'tho_tu', 'Thọ Tử - Ngọ'),
(7, 1, NULL, 'tho_tu', 'Thọ Tử - Sửu'),
(8, 7, NULL, 'tho_tu', 'Thọ Tử - Mùi'),
(9, 2, NULL, 'tho_tu', 'Thọ Tử - Dần'),
(10, 8, NULL, 'tho_tu', 'Thọ Tử - Thân'),
(11, 3, NULL, 'tho_tu', 'Thọ Tử - Mão'),
(12, 9, NULL, 'tho_tu', 'Thọ Tử - Dậu'),
(1, NULL, 13, 'duong_cong', 'Dương Công Kỵ Nhật'),
(2, NULL, 11, 'duong_cong', 'Dương Công Kỵ Nhật'),
(3, NULL, 9, 'duong_cong', 'Dương Công Kỵ Nhật'),
(4, NULL, 7, 'duong_cong', 'Dương Công Kỵ Nhật'),
(5, NULL, 5, 'duong_cong', 'Dương Công Kỵ Nhật'),
(6, NULL, 3, 'duong_cong', 'Dương Công Kỵ Nhật'),
(7, NULL, 8, 'duong_cong', 'Dương Công Kỵ Nhật'),
(7, NULL, 29, 'duong_cong', 'Dương Công Kỵ Nhật'),
(8, NULL, 27, 'duong_cong', 'Dương Công Kỵ Nhật'),
(9, NULL, 25, 'duong_cong', 'Dương Công Kỵ Nhật'),
(10, NULL, 23, 'duong_cong', 'Dương Công Kỵ Nhật'),
(11, NULL, 21, 'duong_cong', 'Dương Công Kỵ Nhật'),
(12, NULL, 19, 'duong_cong', 'Dương Công Kỵ Nhật')";
