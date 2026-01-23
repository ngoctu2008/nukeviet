<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License: GNU/GPL version 2 or any later version
 * @Createdate: 2023-10-27
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

// Sample Star Definitions for Tooltips
// In a real app, this might come from DB or a separate file.
$star_definitions = [
    'tu_vi' => [
        'name' => 'Tử Vi',
        'element' => 'Thổ',
        'nature' => 'Đế Tinh (Vua)',
        'info' => 'Chủ về quyền uy, phúc đức, tài lộc. Miếu địa ở Ngọ, Dần, Thân. Hãm địa ở Mão, Dậu.'
    ],
    'liem_trinh' => [
        'name' => 'Liêm Trinh',
        'element' => 'Hỏa',
        'nature' => 'Đào Hoa, Tù Tinh',
        'info' => 'Chủ về sự nóng nảy, tranh đấu, nhưng cũng là sao đào hoa thứ hai. Hãm địa dễ vướng lao lý.'
    ],
    'thien_dong' => [
        'name' => 'Thiên Đồng',
        'element' => 'Thủy',
        'nature' => 'Phúc Tinh',
        'info' => 'Chủ về sự thay đổi, hưởng thụ, hiền lành. Thích hợp làm việc thiện, dịch vụ.'
    ],
    'vu_khuc' => [
        'name' => 'Vũ Khúc',
        'element' => 'Kim',
        'nature' => 'Tài Tinh',
        'info' => 'Chủ về tài chính, tiền bạc, sự cô độc. Rất tốt cho kinh doanh.'
    ],
    'thai_duong' => [
        'name' => 'Thái Dương',
        'element' => 'Hỏa',
        'nature' => 'Quý Tinh (Mặt trời)',
        'info' => 'Chủ về quan lộc, danh tiếng, đàn ông, cha/chồng. Tốt nhất khi ở cung ban ngày (Dần đến Ngọ).'
    ],
    'thien_co' => [
        'name' => 'Thiên Cơ',
        'element' => 'Mộc',
        'nature' => 'Thiện Tinh',
        'info' => 'Chủ về trí tuệ, mưu lược, huynh đệ, xe cộ. Rất thông minh, khéo léo.'
    ],
    'thien_phu' => [
        'name' => 'Thiên Phủ',
        'element' => 'Thổ',
        'nature' => 'Tài Tinh, Kho lẫm',
        'info' => 'Chủ về tiền bạc, sự che chở, bao dung. Là kho trời, rất tốt cho tài lộc.'
    ],
    'thai_am' => [
        'name' => 'Thái Âm',
        'element' => 'Thủy',
        'nature' => 'Phú Tinh (Mặt trăng)',
        'info' => 'Chủ về bất động sản, tiền của, đàn bà, mẹ/vợ. Tốt nhất khi ở cung ban đêm (Thân đến Tý).'
    ],
    'tham_lang' => [
        'name' => 'Tham Lang',
        'element' => 'Thủy/Mộc',
        'nature' => 'Đào Hoa, Dục Tinh',
        'info' => 'Chủ về ham muốn, hưởng thụ, tửu sắc, nhưng cũng chủ về tu hành nếu gặp Tuần/Triệt.'
    ],
    'cu_mon' => [
        'name' => 'Cự Môn',
        'element' => 'Thủy',
        'nature' => 'Ám Tinh',
        'info' => 'Chủ về lời nói, thị phi, nghi ngờ. Cần có Tuần/Triệt hoặc Hóa Quyền để tốt đẹp.'
    ],
    'thien_tuong' => [
        'name' => 'Thiên Tướng',
        'element' => 'Thủy',
        'nature' => 'Quyền Tinh (Tướng quân)',
        'info' => 'Chủ về uy quyền, sự trung thành, giúp đỡ người khác. Mặt mày uy nghi.'
    ],
    'thien_luong' => [
        'name' => 'Thiên Lương',
        'element' => 'Mộc',
        'nature' => 'Ấm Tinh',
        'info' => 'Chủ về sự che chở, thọ trường, giáo dục, y dược. Rất hiền lành, lương thiện.'
    ],
    'that_sat' => [
        'name' => 'Thất Sát',
        'element' => 'Kim',
        'nature' => 'Sát Tinh (Tướng võ)',
        'info' => 'Chủ về quyền lực, sát phạt, cô khắc. Cần gặp Tử Vi hoặc Hóa Quyền để phục vụ việc lớn.'
    ],
    'pha_quan' => [
        'name' => 'Phá Quân',
        'element' => 'Thủy',
        'nature' => 'Hao Tinh',
        'info' => 'Chủ về sự phá bỏ cái cũ, sáng tạo cái mới, hao tán tiền bạc, phu thê trắc trở.'
    ]
];

// Add Aux Stars definitions if needed...
$star_definitions['loc_ton'] = ['name'=>'Lộc Tồn', 'element'=>'Thổ', 'nature'=>'Tài Tinh', 'info'=>'Chủ về tài lộc trời cho, sự chậm trễ, cô đơn.'];
$star_definitions['da_la'] = ['name'=>'Đà La', 'element'=>'Kim', 'nature'=>'Sát Tinh', 'info'=>'Chủ về ám muội, thị phi, chậm trễ.'];
$star_definitions['kinh_duong'] = ['name'=>'Kình Dương', 'element'=>'Kim', 'nature'=>'Sát Tinh', 'info'=>'Chủ về hình thương, tai nạn, sự quyết liệt.'];
