<?php

/**
 * @Dự án module Huyền học cho NukeViet 4.5.07
 * @Người lập trình: Phạm Ngọc Tú (ngoctu.dnkd@gmail.com)
 * @Ngày triển khai: 01/01/2026
 * @Ngày hoàn thành: 11/02/2026
 */

namespace NukeViet\Module\HuyenHoc;

class TuViHuongNghiep {

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
