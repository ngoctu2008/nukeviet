# Hướng Dẫn Sử Dụng Chức Năng Tạo Mục Lục Tự Động (TOC) Cho Bài Viết Module News

Tính năng này cho phép quản trị viên lựa chọn tạo một "Mục lục" tự động cho từng bài viết của module News trên nền tảng NukeViet. Hệ thống sẽ tự động quét các thẻ tiêu đề (`<h1>`, `<h2>`, `<h3>`) trong nội dung chi tiết bài viết (Bodytext) để tạo thành một bảng danh sách phân cấp với các liên kết neo (anchor links). Khi người dùng nhấp vào một mục trong danh sách, trang web sẽ cuộn mượt mà đến phần nội dung tương ứng.

---

## 1. Nâng Cấp Cấu Trúc Cơ Sở Dữ Liệu (Dành cho Website NukeViet đã cài sẵn module News)

**Quan trọng:** Vì tính năng này yêu cầu một cột mới `toc` trong cơ sở dữ liệu để lưu trữ trạng thái hiển thị mục lục cho từng bài viết, nếu bạn đang cập nhật mã nguồn (update code) cho một website đã hoạt động, bạn phải chạy lệnh SQL sau trong phpMyAdmin hoặc các công cụ quản lý MySQL:

```sql
-- Thay [prefix] và [lang] bằng tiền tố CSDL và ngôn ngữ tương ứng của website, ví dụ: nv_vi
ALTER TABLE `nv_vi_news_rows` ADD `toc` tinyint(1) unsigned NOT NULL DEFAULT '0';
```

**Lưu ý bổ sung:** Trong kiến trúc NukeViet, khi một bài viết được lưu, nó có thể được sao chép vào bảng dữ liệu của chuyên mục tương ứng. Do đó, bạn cần chạy lệnh ALTER tương tự cho tất cả các bảng dữ liệu chuyên mục (ví dụ: `nv_vi_news_1`, `nv_vi_news_2`, v.v.). Bạn có thể dễ dàng lấy danh sách chuyên mục thông qua bảng `nv_vi_news_cat` và thực thi:
```sql
ALTER TABLE `nv_vi_news_ID` ADD `toc` tinyint(1) unsigned NOT NULL DEFAULT '0';
```

*(Đối với trường hợp cài đặt mới hoàn toàn module News, mã nguồn đã được cập nhật `action_mysql.php` để tự động tạo cột này)*.

---

## 2. Hướng Dẫn Sử Dụng Trong Khu Vực Quản Trị (Admin)

1. Đăng nhập vào khu vực **Quản trị viên (Admin Control Panel)**.
2. Chọn module **Tin tức (News)** từ menu bên trái.
3. Nhấp vào **Thêm bài viết** hoặc **Sửa** một bài viết hiện có.
4. Tìm đến khu vực soạn thảo **Nội dung chi tiết**.
5. Ngay phía trên khung soạn thảo sẽ xuất hiện một hộp kiểm (Checkbox) với nhãn:
   **☑ Tạo mục lục bài viết**
6. Để bật tính năng mục lục cho bài viết này, hãy đánh dấu tick (chọn) vào hộp kiểm đó.
7. **Viết nội dung bài viết và định dạng đúng:** Hệ thống chỉ tự động sinh mục lục nếu bạn sử dụng các thẻ tiêu đề (Heading) khi viết bài. Sử dụng thanh công cụ của trình soạn thảo để chọn **Định dạng (Format) -> Tiêu đề 1 (H1), Tiêu đề 2 (H2), Tiêu đề 3 (H3)** cho các phần chính của bài viết.
8. Bấm **Đăng bài viết** hoặc **Lưu nháp** để hoàn thành.

*(Theo mặc định, khi đăng bài viết mới, tính năng tạo mục lục được TẮT (bỏ chọn) để tránh phá vỡ giao diện đối với các tin tức ngắn không cần cấu trúc phức tạp).*

---

## 3. Hiển Thị Và Cơ Chế Hoạt Động Ngoài Trang Chủ (Front-end)

- Khi tính năng được bật cho một bài viết, cấu trúc HTML của Mục Lục sẽ tự động xuất hiện ở giữa phần **Giới thiệu ngắn gọn** (Hometext) và phần **Nội dung chi tiết** (Bodytext).
- Mục lục được hiển thị trong một khung dạng danh sách (Bootstrap Panel) có tiêu đề rõ ràng là **"Nội dung bài viết"**.
- Cấu trúc danh sách sẽ lùi lề (indent) dựa trên cấp độ thẻ (H1, H2, H3).
- **Thu gọn / Mở rộng:** Phía bên phải thanh tiêu đề Mục lục có nút `[+/-]`. Người đọc có thể nhấp chuột vào thanh này để thu gọn toàn bộ khung danh sách mục lục lại (nếu bài viết quá dài), hoặc nhấp lần nữa để mở rộng ra.
- Tính năng hoạt động tự động thông qua việc chèn thuộc tính `id` (ví dụ: `id="toc-heading-1"`) vào các thẻ `<h1>...<h3>` của nội dung chi tiết. Người viết bài không cần phải biết code hay tự chèn các điểm neo (anchor) này bằng tay.
