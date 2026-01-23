<!-- BEGIN: main -->
<div class="xem-ngay-custom">
    <h2 class="text-center mb-4 text-primary">{EVENT.title}</h2>
    <p class="text-center">{EVENT.description}</p>

    <form action="{ACTION_URL}" method="post" class="mb-5">
        <div class="panel panel-primary">
            <div class="panel-heading">Thông tin</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Năm sinh Gia chủ (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="birth_year" class="form-control" value="{DATA.birth_year}" required placeholder="VD: 1985">
                    </div>
                </div>
                <!-- BEGIN: partner -->
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Năm sinh Đối tác / Vợ chồng</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="partner_year" class="form-control" value="{DATA.partner_year}" placeholder="VD: 1980">
                    </div>
                </div>
                <!-- END: partner -->
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Từ ngày</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="date" name="start_date" class="form-control" value="{DATA.start_date}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Đến ngày</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="date" name="end_date" class="form-control" value="{DATA.end_date}" required>
                    </div>
                </div>
            </div>
        </div>
        <div class="text-center">
            <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">{LANG.submit}</button>
        </div>
    </form>

    <!-- BEGIN: result -->
    <div id="result-section">
        <h3>Kết quả</h3>

        <!-- BEGIN: warning -->
        <div class="alert alert-warning">
            <strong>Lưu ý:</strong>
            <ul>
                <!-- BEGIN: loop -->
                <li>{WARNING}</li>
                <!-- END: loop -->
            </ul>
        </div>
        <!-- END: warning -->
        <!-- BEGIN: success -->
        <div class="alert alert-success">{SUCCESS_MSG}</div>
        <!-- END: success -->

        <h4>Ngày tốt đề xuất</h4>
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Ngày dương</th>
                        <th>Ngày âm</th>
                        <th>Can Chi</th>
                        <th>Trực</th>
                        <th>Hoàng Đạo</th>
                        <th>Giờ Tốt</th>
                        <th>Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: date_row -->
                    <tr>
                        <td>{ROW.date}</td>
                        <td>{ROW.lunar_date}</td>
                        <td>{ROW.day_can_chi}</td>
                        <td>{ROW.truc}</td>
                        <td>{ROW.hoang_dao}</td>
                        <td>{ROW.hours}</td>
                        <td>{ROW.score}</td>
                    </tr>
                    <!-- END: date_row -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
