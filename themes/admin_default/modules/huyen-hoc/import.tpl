<!-- BEGIN: main -->
<div class="alert alert-info">
    Công cụ này sẽ xóa toàn bộ dữ liệu hiện có trong bảng "Lời giải" và nhập lại từ file JSON mẫu.<br>
    Vui lòng sao lưu dữ liệu trước khi thực hiện.
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: success -->
<div class="alert alert-success">{SUCCESS}</div>
<!-- END: success -->

<form action="{NV_BASE_ADMINURL}index.php?nv={MODULE_NAME}&op=import" method="post">
    <div class="text-center">
        <input type="hidden" name="checkss" value="{CHECKSS}" />
        <button type="submit" name="import" class="btn btn-primary btn-lg">Bắt Đầu Nhập Dữ Liệu Mẫu</button>
    </div>
</form>
<!-- END: main -->
