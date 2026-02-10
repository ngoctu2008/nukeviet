<!-- BEGIN: main -->
<div class="xem-ngay-container">
    <h1 class="text-center text-uppercase" style="margin-bottom: 20px;">{LANG.xem_ngay}</h1>

    <div class="row">
        <!-- Left Column: Search Form -->
        <div class="col-md-8 col-sm-24">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-search"></i> Tra Cứu Ngày Tốt</h3>
                </div>
                <div class="panel-body">
                    <form action="{NV_BASE_SITEURL}index.php" method="get">
                        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">

                        <div class="form-group">
                            <label>Ngày Dương Lịch</label>
                            <div class="row">
                                <div class="col-xs-8">
                                    <input type="number" name="d" value="{INPUT.d}" class="form-control" placeholder="Ngày" required>
                                </div>
                                <div class="col-xs-8">
                                    <input type="number" name="m" value="{INPUT.m}" class="form-control" placeholder="Tháng" required>
                                </div>
                                <div class="col-xs-8">
                                    <input type="number" name="y" value="{INPUT.y}" class="form-control" placeholder="Năm" required>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Mục đích công việc</label>
                            <select name="purpose" class="form-control">
                                <!-- BEGIN: purpose_option -->
                                <option value="{PURPOSE.key}" {PURPOSE.selected}>{PURPOSE.title}</option>
                                <!-- END: purpose_option -->
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Năm sinh gia chủ (Âm lịch)</label>
                            <input type="number" name="birth_year" value="{INPUT.birth_year}" class="form-control" placeholder="Ví dụ: 1983">
                            <p class="help-block"><small>Nhập năm sinh để xem tuổi hợp/kỵ (Kim Lâu, Hoang Ốc, Tam Tai).</small></p>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block"><i class="fa fa-calendar-check-o"></i> Xem Kết Quả</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Results -->
        <div class="col-md-16 col-sm-24">
            <!-- Date Info Panel -->
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Thông tin ngày: {INPUT.d}/{INPUT.m}/{INPUT.y}</h3>
                </div>
                <div class="panel-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <tbody>
                                <tr>
                                    <th class="active" style="width: 20%">Dương Lịch</th>
                                    <td style="width: 30%"><strong>{INPUT.d}/{INPUT.m}/{INPUT.y}</strong></td>
                                    <th class="active" style="width: 20%">Âm Lịch</th>
                                    <td style="width: 30%"><strong>{LUNAR.day}/{LUNAR.month}/{LUNAR.year}</strong> {LUNAR.leap_msg}</td>
                                </tr>
                                <tr>
                                    <th class="active">Can Chi</th>
                                    <td colspan="3">
                                        Ngày <strong>{CANCHI_TEXT.day}</strong>,
                                        Tháng <strong>{CANCHI_TEXT.month}</strong>,
                                        Năm <strong>{CANCHI_TEXT.year}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="active">Giờ Hoàng Đạo</th>
                                    <td colspan="3">
                                        <ul class="list-inline" style="margin-bottom: 0;">
                                            <!-- BEGIN: gio -->
                                            <li><span class="label label-success">{GIO.name}</span></li>
                                            <!-- END: gio -->
                                        </ul>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Age Analysis Block -->
                    <!-- BEGIN: age_check -->
                    <div class="alert alert-{AGE_CHECK.status_class}" style="margin-bottom: 10px; padding: 10px;">
                        <i class="fa {AGE_CHECK.icon} fa-lg fa-fw"></i> <strong>{AGE_CHECK.msg}</strong>
                    </div>
                    <!-- END: age_check -->

                    <!-- Day Analysis Block -->
                    <div class="alert alert-{INFO.alert_type} fade in">
                        <h4><i class="fa fa-info-circle"></i> Bình Giải Ngày: {INFO.comment}</h4>
                        <hr style="margin: 10px 0;">
                        <ul class="fa-ul">
                            <li><i class="fa-li fa fa-check-square"></i>Trực: <strong>{INFO.truc}</strong></li>
                            <li><i class="fa-li fa fa-star"></i>Sao Tốt: {INFO.sao}</li>
                        </ul>
                        <div style="margin-top: 10px;">
                            <em>{INFO.details_text}</em>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Good Days List -->
    <!-- BEGIN: good_days -->
    <div class="panel panel-success">
        <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> Các ngày tốt trong tháng {INPUT.m}/{INPUT.y} cho việc: {INPUT.purpose_title}</h3>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr class="success">
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
