<!-- BEGIN: main -->
<div class="xem-tuoi-content">
    <div class="page-header text-center">
        <h1 class="text-uppercase text-danger">Xem Tuổi Hợp Khắc</h1>
        <p class="text-muted">Phân tích Thiên Can, Địa Chi, Ngũ Hành, Cung Phi Bát Trạch</p>
    </div>

    <div class="panel panel-default">
        <div class="panel-body">
            <form action="{NV_BASE_SITEURL}index.php" method="get" class="form-horizontal">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
                <input type="hidden" name="{NV_OP_VARIABLE}" value="xem-tuoi" />

                <div class="row">
                    <!-- Person 1 -->
                    <div class="col-xs-24 col-sm-12 col-md-12">
                        <div class="panel panel-primary">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-user"></i> Người 1 (Chồng/Chủ)</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-8 col-md-8 control-label">Năm sinh</label>
                                    <div class="col-sm-16 col-md-16">
                                        <input type="number" name="year1" value="{YEAR1}" class="form-control" placeholder="Ví dụ: 1990" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-8 col-md-8 control-label">Giới tính</label>
                                    <div class="col-sm-16 col-md-16">
                                        <label class="radio-inline"><input type="radio" name="gender1" value="1" {G1_M_CHECKED}> Nam</label>
                                        <label class="radio-inline"><input type="radio" name="gender1" value="0" {G1_F_CHECKED}> Nữ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Person 2 -->
                    <div class="col-xs-24 col-sm-12 col-md-12">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                <h3 class="panel-title"><i class="fa fa-female"></i> Người 2 (Vợ/Đối tác)</h3>
                            </div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label class="col-sm-8 col-md-8 control-label">Năm sinh</label>
                                    <div class="col-sm-16 col-md-16">
                                        <input type="number" name="year2" value="{YEAR2}" class="form-control" placeholder="Ví dụ: 1995" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-8 col-md-8 control-label">Giới tính</label>
                                    <div class="col-sm-16 col-md-16">
                                        <label class="radio-inline"><input type="radio" name="gender2" value="1" {G2_M_CHECKED}> Nam</label>
                                        <label class="radio-inline"><input type="radio" name="gender2" value="0" {G2_F_CHECKED}> Nữ</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-danger btn-lg"><i class="fa fa-search"></i> Xem Kết Quả Ngay</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BEGIN: result -->
    <div class="panel panel-info" id="ket-qua-xem-tuoi">
        <div class="panel-heading">
            <h3 class="panel-title text-center text-uppercase"><i class="fa fa-star"></i> Kết Quả Bình Giải</h3>
        </div>
        <div class="panel-body">

            <div class="alert alert-success text-center">
                <h3>{CONCLUSION}</h3>
                <p>Tổng điểm đánh giá: <strong>{TOTAL_SCORE}</strong></p>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="active">
                            <th class="text-center" style="width: 20%">Yếu Tố</th>
                            <th class="text-center" style="width: 40%">Người 1 ({N1_YEAR})</th>
                            <th class="text-center" style="width: 40%">Người 2 ({N2_YEAR})</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Can Chi</strong></td>
                            <td class="text-center text-primary"><strong>{N1_NAME}</strong></td>
                            <td class="text-center text-danger"><strong>{N2_NAME}</strong></td>
                        </tr>
                        <tr>
                            <td><strong>Mệnh Ngũ Hành</strong></td>
                            <td class="text-center">{N1_MENH}</td>
                            <td class="text-center">{N2_MENH}</td>
                        </tr>
                        <tr>
                            <td><strong>Cung Phi</strong></td>
                            <td class="text-center">{N1_CUNG}</td>
                            <td class="text-center">{N2_CUNG}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <hr />

            <h4 class="text-primary"><i class="fa fa-list-alt"></i> Chi Tiết Phân Tích</h4>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead>
                        <tr class="info">
                            <th class="text-center" style="width: 20%">Tiêu Chí</th>
                            <th class="text-center" style="width: 15%">Đánh Giá</th>
                            <th class="text-center" style="width: 10%">Điểm</th>
                            <th class="text-center">Luận Giải Chi Tiết</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Thien Can -->
                        <tr>
                            <td><strong>Thiên Can</strong><br><small>(Hợp/Xung/Hình)</small></td>
                            <td class="text-center"><span class="label label-default">{CAN_MSG}</span></td>
                            <td class="text-center"><strong>{CAN_SCORE}</strong></td>
                            <td>{CAN_DETAIL}</td>
                        </tr>
                        <!-- Dia Chi -->
                        <tr>
                            <td><strong>Địa Chi</strong><br><small>(Tam Hợp/Lục Hợp/Xung)</small></td>
                            <td class="text-center"><span class="label label-default">{CHI_MSG}</span></td>
                            <td class="text-center"><strong>{CHI_SCORE}</strong></td>
                            <td>{CHI_DETAIL}</td>
                        </tr>
                        <!-- Ngu Hanh -->
                        <tr>
                            <td><strong>Ngũ Hành</strong><br><small>(Tương Sinh/Tương Khắc)</small></td>
                            <td class="text-center"><span class="label label-default">{HANH_MSG}</span></td>
                            <td class="text-center"><strong>{HANH_SCORE}</strong></td>
                            <td>{HANH_DETAIL}</td>
                        </tr>
                        <!-- Cung Phi -->
                        <tr>
                            <td><strong>Cung Phi Bát Trạch</strong><br><small>(Hôn Nhân/Nhà Cửa)</small></td>
                            <td class="text-center"><span class="label label-default">{CUNG_MSG}</span></td>
                            <td class="text-center"><strong>{CUNG_SCORE}</strong></td>
                            <td>{CUNG_DETAIL}</td>
                        </tr>
                        <tr class="success">
                            <td class="text-right"><strong>TỔNG KẾT</strong></td>
                            <td colspan="2" class="text-center text-danger" style="font-size: 1.2em; font-weight: bold;">{TOTAL_SCORE}</td>
                            <td class="text-danger" style="font-weight: bold;">{CONCLUSION}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="text-center" style="margin-top: 20px;">
                <a href="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=xem-tuoi" class="btn btn-default"><i class="fa fa-refresh"></i> Xem Tuổi Khác</a>
            </div>

        </div>
    </div>
    <!-- END: result -->
</div>

<script>
    // Simple verification helper or scroll to result
    $(document).ready(function(){
        if($('#ket-qua-xem-tuoi').length > 0) {
            $('html, body').animate({
                scrollTop: $("#ket-qua-xem-tuoi").offset().top - 50
            }, 1000);
        }
    });
</script>
<!-- END: main -->
