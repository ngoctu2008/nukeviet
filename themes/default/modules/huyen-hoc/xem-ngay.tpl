<!-- BEGIN: main -->
<div class="xem-ngay-container">
    <h2 class="text-center text-uppercase" style="margin-bottom: 20px;">{LANG.xem_ngay}</h2>

    <div class="well">
        <form action="{NV_BASE_SITEURL}index.php" method="get" class="form-inline text-center">
            <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
            <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
            <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">

            <div class="form-group">
                <label class="sr-only">Ngày</label>
                <input type="number" name="d" value="{INPUT.d}" class="form-control" placeholder="Ngày" style="width: 70px" required>
            </div>
            <div class="form-group">
                <label class="sr-only">Tháng</label>
                <input type="number" name="m" value="{INPUT.m}" class="form-control" placeholder="Tháng" style="width: 70px" required>
            </div>
            <div class="form-group">
                <label class="sr-only">Năm</label>
                <input type="number" name="y" value="{INPUT.y}" class="form-control" placeholder="Năm" style="width: 80px" required>
            </div>

            <div class="form-group" style="margin-left: 10px;">
                <label>Mục đích:</label>
                <select name="purpose" class="form-control">
                    <!-- BEGIN: purpose_option -->
                    <option value="{PURPOSE.key}" {PURPOSE.selected}>{PURPOSE.title}</option>
                    <!-- END: purpose_option -->
                </select>
            </div>

            <div class="form-group" style="margin-left: 10px;">
                <label>Năm sinh (Gia chủ):</label>
                <input type="number" name="birth_year" value="{INPUT.birth_year}" class="form-control" placeholder="19xx" style="width: 80px">
            </div>

            <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Tra cứu</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading text-center">
                    <h3 class="panel-title">Thông tin ngày: {INPUT.d}/{INPUT.m}/{INPUT.y}</h3>
                </div>
                <div class="panel-body text-center">
                    <div class="row">
                        <div class="col-md-6 col-sm-12">
                            <h4>Dương lịch</h4>
                            <p class="lead">{INPUT.d}/{INPUT.m}/{INPUT.y}</p>
                        </div>
                        <div class="col-md-6 col-sm-12">
                            <h4>Âm lịch</h4>
                            <p class="lead">{LUNAR.day}/{LUNAR.month}/{LUNAR.year} {LUNAR.leap_msg}</p>
                        </div>
                    </div>

                    <div class="alert alert-{INFO.alert_type} alert-info">
                        <strong>Đánh giá: {INFO.comment}</strong><br>
                        Trực: {INFO.truc} <br>
                        <em>{INFO.details_text}</em>
                    </div>

                    <h5>Giờ Hoàng Đạo (Tốt):</h5>
                    <ul class="list-inline">
                        <!-- BEGIN: gio -->
                        <li><span class="label label-success">{GIO.name}</span></li>
                        <!-- END: gio -->
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- BEGIN: good_days -->
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Các ngày tốt trong tháng {INPUT.m}/{INPUT.y} cho việc: {INPUT.purpose_title}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">Ngày Dương</th>
                        <th class="text-center">Ngày Âm</th>
                        <th class="text-center">Trực</th>
                        <th class="text-center">Giờ Tốt</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center"><strong>{GD.day}/{INPUT.m}</strong></td>
                        <td class="text-center">{GD.lunar_day}/{GD.lunar_month}</td>
                        <td class="text-center">{GD.truc}</td>
                        <td>{GD.hours}</td>
                    </tr>
                    <!-- END: row -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- END: good_days -->

</div>
<!-- END: main -->
