<!-- BEGIN: main -->
<div class="dat-ten-container">
    <h2 class="text-center">{LANG.dat_ten}</h2>
    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
        <div class="form-group text-center">
            <input type="text" name="ho" value="{INPUT.ho}" class="form-control" placeholder="Họ (Ví dụ: Nguyễn)" style="display:inline-block; width: 150px" required>
            <input type="text" name="ten" value="{INPUT.ten}" class="form-control" placeholder="Tên (Ví dụ: Văn A)" style="display:inline-block; width: 150px" required>
            <input type="number" name="year" value="{INPUT.year}" class="form-control" placeholder="Năm sinh" style="display:inline-block; width: 100px" required>
            <button type="submit" class="btn btn-primary">{LANG.submit}</button>
        </div>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <div class="text-center">
        <h3>Kết quả phân tích: {RESULT.ho} {RESULT.ten}</h3>
        <p><strong>{RESULT.comment}</strong></p>
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <table class="table table-bordered">
                    <tr><td>Thiên Cách</td><td>{CACH.thien}</td></tr>
                    <tr><td>Địa Cách</td><td>{CACH.dia}</td></tr>
                    <tr><td>Nhân Cách</td><td>{CACH.nhan}</td></tr>
                    <tr><td>Ngoại Cách</td><td>{CACH.ngoai}</td></tr>
                    <tr><td><strong>Tổng Cách</strong></td><td><strong>{CACH.tong}</strong></td></tr>
                </table>
            </div>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
