# Module Tử Vi (tu-vi) cho NukeViet 4.5.07

Module Tử Vi giúp người dùng lập và bình giải lá số Tử Vi trọn đời dựa trên ngày giờ sinh.

## Tính năng

### 1. Khu vực Người dùng (Frontend)
*   **Lập lá số:** Form nhập thông tin ngày, tháng, năm, giờ sinh (Dương lịch/Âm lịch).
*   **Hiển thị lá số:** Giao diện lá số Tử Vi truyền thống, bố cục 12 cung + Thiên bàn. Responsive cho mobile.
*   **Bình giải tự động:**
    *   Tổng quan Mệnh/Thân.
    *   Bình giải các sao tại các cung.
    *   Tính hạn năm nay (Tiểu vận), Đại vận.
    *   Tính Sao Hạn (La Hầu, Kế Đô...), Hạn (Huỳnh Tuyền, Tam Kheo...).
    *   Tính Tam Tai, Kim Lâu, Hoang Ốc.
    *   Bình giải chi tiết 12 tháng trong năm (theo Sao Hạn).
    *   Hiển thị thông tin chi tiết sao khi di chuột (Tooltip).

### 2. Khu vực Quản trị (Admin)
*   **Dashboard:** Thống kê tổng quan số lượng hồ sơ, dữ liệu.
*   **Quản lý Dữ liệu Tử Vi (Data):**
    *   Thêm/Sửa/Xóa các bài bình giải.
    *   Phân loại dữ liệu theo: Sao, Cung, Chủ đề (Tổng quan, Tình duyên, Công danh...).
    *   Trình soạn thảo WYSIWYG (CKEditor) cho nội dung bình giải.
*   **Cấu hình:** Cài đặt hiển thị, SEO.

## Cài đặt

1.  Upload thư mục `tu-vi` vào thư mục `modules/` của website.
2.  Đăng nhập Admin Control Panel (ACP).
3.  Vào **Quản lý Modules** -> **Cài đặt Module**.
4.  Chọn module **Tử Vi** và nhấn **Cài đặt**.
5.  Sau khi cài đặt, vào **Cấu hình module** để thiết lập các thông số ban đầu (nếu cần).

## Dữ liệu mẫu

File `tuvi_data_sample.sql` đi kèm chứa dữ liệu mẫu cho bảng `nv4_vi_tu_vi_interpretations` (cấu trúc mới là `nv4_vi_tuvi_data` sẽ được cập nhật trong phiên bản tới). Bạn có thể import file này vào database (nhớ đổi prefix `nv4_vi` nếu cần) để có dữ liệu test ban đầu.

## Yêu cầu hệ thống

*   NukeViet 4.5.07 trở lên.
*   PHP 7.4 hoặc 8.0+.
*   MySQL/MariaDB.
*   Trình duyệt hỗ trợ CSS Grid (Chrome, Firefox, Safari, Edge mới).

## Cấu trúc Database

*   `nv4_vi_tu_vi_users`: Lưu lịch sử người dùng lập lá số.
*   `nv4_vi_tuvi_data`: Lưu dữ liệu bình giải (Thay thế bảng `interpretations` cũ).
*   `nv4_vi_tu_vi_config`: Lưu cấu hình module.

## Liên hệ & Hỗ trợ

Module được phát triển theo yêu cầu.
