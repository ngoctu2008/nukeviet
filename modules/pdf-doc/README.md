# Cài đặt module PDF Doc

Module này yêu cầu các thư viện PHP bên ngoài để xử lý file PDF và Word. Bạn cần cài đặt chúng thông qua Composer.

## Bước 1: Cài đặt Composer
Nếu máy chủ của bạn chưa có Composer, hãy cài đặt nó. Xem hướng dẫn tại [getcomposer.org](https://getcomposer.org/).

## Bước 2: Cài đặt thư viện
1.  Truy cập vào thư mục của module trên máy chủ bằng dòng lệnh (Terminal/CMD):
    ```bash
    cd E:\webs\htdocs\daotao\modules\pdf-doc
    ```
    *(Thay đổi đường dẫn trên cho phù hợp với thực tế)*

2.  Chạy lệnh cài đặt:
    ```bash
    composer install
    ```

3.  Sau khi lệnh chạy xong, một thư mục `vendor` sẽ được tạo ra trong `modules/pdf-doc`.

## Bước 3: Kiểm tra
Truy cập lại công cụ trên website. Nếu thông báo lỗi biến mất, module đã sẵn sàng sử dụng.

Lưu ý: Nếu bạn đang sử dụng Hosting chia sẻ (Shared Hosting) không có quyền truy cập SSH/Terminal, bạn có thể chạy `composer install` trên máy cá nhân (Localhost), sau đó upload toàn bộ thư mục `vendor` lên thư mục module trên Hosting.
