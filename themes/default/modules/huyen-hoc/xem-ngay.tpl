<!-- BEGIN: main -->
<div class="xem-ngay-container">
    <h2 class="text-center">{LANG.xem_ngay}</h2>
    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline text-center">
        <input type="number" name="d" value="{INPUT.d}" class="form-control" placeholder="Ngày" style="width: 70px" required>
        <input type="number" name="m" value="{INPUT.m}" class="form-control" placeholder="Tháng" style="width: 70px" required>
        <input type="number" name="y" value="{INPUT.y}" class="form-control" placeholder="Năm" style="width: 100px" required>
        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
    </form>

    <hr>
    <div class="text-center">
        <h3>Dương lịch: {INPUT.d}/{INPUT.m}/{INPUT.y}</h3>
        <h4>Âm lịch: {LUNAR.day}/{LUNAR.month}/{LUNAR.year}</h4>
        <div class="alert alert-info">
            <strong>{INFO.comment}</strong><br>
            Trực: {INFO.truc} - Sao: {INFO.sao}
        </div>

        <h5>Giờ Hoàng Đạo:</h5>
        <ul class="list-inline">
            <!-- BEGIN: gio -->
            <li>{GIO.name}h</li>
            <!-- END: gio -->
        </ul>
    </div>
</div>
<!-- END: main -->
