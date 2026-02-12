<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViHuongNghiep {

    private $quanLoc;
    private $menh;
    private $taiBach;

    public function __construct($calcData) {
        if (isset($calcData['dia_ban'])) {
            $diaBan = $calcData['dia_ban'];
            $meta = $calcData['meta'];

            // Find Quan Loc, Menh, Tai Bach indices
            $menhIdx = $meta['menh_idx'];
            $quanIdx = ($menhIdx + 4) % 12; // Quan Loc is Menh + 4 (clockwise) or -4?
            // Standard: Menh(1), Huynh(2), Phu(3), Tu(4), Tai(5), Tat(6), Thien(7), No(8), Quan(9).
            // So Quan is Menh - 4 (or +8).
            // But wait, my TuViLapSo index might be 0..11.
            // Check TuViLapSo: Menh, Phu Mau, Phuc Duc, Dien Trach, Quan Loc...
            // Menh=0, Phu=1, Phuc=2, Dien=3, Quan=4.
            // So Quan Loc is Menh + 4.
            // Tai Bach is Menh + 8.
            $quanIdx = ($menhIdx + 4) % 12;
            $taiIdx = ($menhIdx + 8) % 12;

            $this->menh = isset($diaBan[$menhIdx]) ? $diaBan[$menhIdx] : [];
            $this->quanLoc = isset($diaBan[$quanIdx]) ? $diaBan[$quanIdx] : [];
            $this->taiBach = isset($diaBan[$taiIdx]) ? $diaBan[$taiIdx] : [];
        }
    }

    public function renderReport() {
        if (empty($this->quanLoc)) return '<div class="alert alert-warning">Chưa có dữ liệu Quan Lộc để phân tích.</div>';

        $analysis = self::analyzeCareer($this->quanLoc, $this->menh, $this->taiBach);

        $html = '<div class="career-report">';
        $html .= '<h4><i class="fa fa-briefcase"></i> ĐỊNH HƯỚNG NGHỀ NGHIỆP (Cung Quan Lộc: ' . $this->quanLoc['palace_name'] . ')</h4>';

        if (!empty($analysis['jobs'])) {
            $html .= '<div class="alert alert-success"><strong><i class="fa fa-check-circle"></i> Ngành nghề phù hợp:</strong><br>';
            $html .= '<ul class="mb-0">';
            foreach ($analysis['jobs'] as $job) {
                $html .= '<li>' . $job . '</li>';
            }
            $html .= '</ul></div>';
        }

        if (!empty($analysis['advice'])) {
            $html .= '<div class="alert alert-info"><strong><i class="fa fa-lightbulb-o"></i> Lời khuyên:</strong> ' . $analysis['advice'] . '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    public static function analyzeCareer($quanLoc, $menh, $taiBach) {
        $advice = [];
        $suitableJobs = [];

        // Combine stars from Quan Loc, Menh, Tai Bach (Tam Hop)
        // Focus on Quan Loc

        $mainStars = $quanLoc['chinh_tinh'];
        if (empty($mainStars) && !empty($quanLoc['chinh_tinh_borrowed'])) {
            $mainStars = $quanLoc['chinh_tinh_borrowed'];
        }

        foreach ($mainStars as $s) {
            switch ($s['code']) {
                case 'tu_vi':
                    $suitableJobs[] = "Lãnh đạo, quản lý, công chức nhà nước, chủ doanh nghiệp.";
                    break;
                case 'thien_co':
                    $suitableJobs[] = "Tham mưu, kế hoạch, kỹ thuật, công nghệ thông tin, tôn giáo.";
                    break;
                case 'thai_duong':
                    $suitableJobs[] = "Chính trị, luật sư, giáo dục, công tác xã hội, điện lực.";
                    break;
                case 'vu_khuc':
                    $suitableJobs[] = "Tài chính, ngân hàng, kinh doanh kim khí, kế toán.";
                    break;
                case 'thien_dong':
                    $suitableJobs[] = "Du lịch, dịch vụ, bác sĩ tâm lý, công tác từ thiện, ẩm thực.";
                    break;
                case 'liem_trinh':
                    $suitableJobs[] = "Giám sát, kiểm tra, công nghệ, kỹ thuật chính xác, quân sự.";
                    break;
                case 'thien_phu':
                    $suitableJobs[] = "Ngân hàng, bảo hiểm, quản lý kho bãi, bất động sản, tài chính.";
                    break;
                case 'thai_am':
                    $suitableJobs[] = "Bất động sản, khách sạn, nghệ thuật, thẩm mỹ, y dược.";
                    break;
                case 'tham_lang':
                    $suitableJobs[] = "Giải trí, nghệ thuật, kinh doanh, ngoại giao, phong thủy.";
                    break;
                case 'cu_mon':
                    $suitableJobs[] = "Luật sư, giáo viên, diễn giả, nghiên cứu, y dược (cái miệng).";
                    break;
                case 'thien_tuong':
                    $suitableJobs[] = "Bác sĩ, y tá, hành chính, thư ký, quân sự, bảo hiểm.";
                    break;
                case 'thien_luong':
                    $suitableJobs[] = "Giáo dục, y tế, từ thiện, giám sát, tôn giáo.";
                    break;
                case 'that_sat':
                    $suitableJobs[] = "Quân đội, công an, công nghiệp nặng, quản lý dự án, kinh doanh mạo hiểm.";
                    break;
                case 'pha_quan':
                    $suitableJobs[] = "Kinh doanh tự do, phá dỡ, xây dựng, vận tải, hàng hải.";
                    break;
            }
        }

        // Check Phu Tinh
        $phuTinh = array_merge($quanLoc['phu_tinh_tot'], $quanLoc['phu_tinh_xau']);
        foreach ($phuTinh as $s) {
            if ($s['code'] == 'van_xuong' || $s['code'] == 'van_khuc') {
                $suitableJobs[] = "Văn hóa, nghệ thuật, viết lách, học thuật.";
            }
            if ($s['code'] == 'thien_khoi' || $s['code'] == 'thien_viet') {
                $suitableJobs[] = "Đứng đầu ngành, bằng cấp cao, được đề bạt.";
            }
            if ($s['code'] == 'hoa_loc' || $s['code'] == 'loc_ton') {
                $suitableJobs[] = "Kinh doanh, thương mại, đầu tư.";
            }
            if ($s['code'] == 'thien_ma') {
                $suitableJobs[] = "Vận tải, du lịch, xuất nhập khẩu, công việc hay di chuyển.";
            }
        }

        if (empty($suitableJobs)) {
            $suitableJobs[] = "Đa ngành nghề, tùy thuộc vào vận hạn và nỗ lực cá nhân.";
        }

        return [
            'jobs' => array_unique($suitableJobs),
            'advice' => "Nên chọn nghề phù hợp với tính cách và bộ sao chiếu mệnh để phát huy tối đa năng lực."
        ];
    }
}
