<!-- BEGIN: main -->
<div class="xem-ngay-wedding">
    <h2 class="text-center mb-4 text-danger">{LANG.wedding_title}</h2>

    <form action="{ACTION_URL}" method="post" class="mb-5">
        <div class="panel panel-danger">
            <div class="panel-heading">{LANG.wedding_title}</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Năm sinh Chú rể (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="groom_year" class="form-control" value="{DATA.groom_year}" required placeholder="VD: 1995">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">Năm sinh Cô dâu (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="bride_year" class="form-control" value="{DATA.bride_year}" required placeholder="VD: 1997">
                    </div>
                </div>
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
            <button type="submit" name="submit" value="1" class="btn btn-danger btn-lg">{LANG.submit}</button>
        </div>
    </form>

    <!-- BEGIN: result -->
    <div id="result-section">
        <h3>Kết quả</h3>

        <!-- BEGIN: warning -->
        <div class="alert alert-warning">{WARNING_MSG}</div>
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
                        <th>Hoàng Đạo</th>
                        <th>Điểm</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: date_row -->
                    <tr>
                        <td>{ROW.date}</td>
                        <td>{ROW.lunar_date}</td>
                        <td>{ROW.day_can_chi}</td>
                        <td>{ROW.hoang_dao}</td>
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
