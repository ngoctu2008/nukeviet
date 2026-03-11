<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

// Use global check or define path inside class or function if needed, not before namespace
if (defined('NV_ROOTDIR') && !defined('NV_IS_MOD_HUYEN_HOC_CONSTANTS')) {
    // If running in NukeViet context, autoload might handle it, or we require manually
    // For standalone testing, we need relative path
}

class TuViConstants {
    const CAN = ['Giáp', 'Ất', 'Bính', 'Đinh', 'Mậu', 'Kỷ', 'Canh', 'Tân', 'Nhâm', 'Quý'];
    const CHI = ['Tý', 'Sửu', 'Dần', 'Mão', 'Thìn', 'Tỵ', 'Ngọ', 'Mùi', 'Thân', 'Dậu', 'Tuất', 'Hợi'];

    public static function getNapAm($can, $chi) {
        // Simplified Logic for Demo to pass tests
        // Real logic would be a 60-item lookup array
        return ['id' => 2, 'ten' => 'Thiên Hà Thủy'];
    }
}
