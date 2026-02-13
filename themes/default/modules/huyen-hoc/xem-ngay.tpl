<!-- BEGIN: main -->
<div class="xem-ngay-container">
    <h1 class="text-center text-uppercase text-primary" style="margin-bottom: 20px;">{LANG.xem_ngay}</h1>

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="{ACTIVE_TAB_GENERAL}"><a href="#tab-general" aria-controls="tab-general" role="tab" data-toggle="tab"><i class="fa fa-calendar"></i> Xem Ngày Chung</a></li>
        <li role="presentation" class="{ACTIVE_TAB_KHAI_TRUONG}"><a href="#tab-khai-truong" aria-controls="tab-khai-truong" role="tab" data-toggle="tab"><i class="fa fa-briefcase"></i> Khai Trương</a></li>
        <li role="presentation" class="{ACTIVE_TAB_LAM_NHA}"><a href="#tab-lam-nha" aria-controls="tab-lam-nha" role="tab" data-toggle="tab"><i class="fa fa-home"></i> Động Thổ / Làm Nhà</a></li>
        <li role="presentation" class="{ACTIVE_TAB_CUOI_HOI}"><a href="#tab-cuoi-hoi" aria-controls="tab-cuoi-hoi" role="tab" data-toggle="tab"><i class="fa fa-heart"></i> Cưới Hỏi</a></li>
        <li role="presentation" class="{ACTIVE_TAB_TANG_LE}"><a href="#tab-tang-le" aria-controls="tab-tang-le" role="tab" data-toggle="tab"><i class="fa fa-user-times"></i> Tang Lễ (Trùng Tang)</a></li>
    </ul>

    <!-- Tab panes -->
    <div class="tab-content" style="padding-top: 20px;">

        <!-- TAB 1: GENERAL -->
        <div role="tabpanel" class="tab-pane {ACTIVE_TAB_GENERAL}" id="tab-general">
            <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-default">
                        <div class="panel-heading">Chọn ngày xem</div>
                        <div class="panel-body">
                            <form action="{NV_BASE_SITEURL}index.php" method="get">
                                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
                                <input type="hidden" name="tab" value="general">

                                <div class="form-group">
                                    <label>Ngày dương lịch</label>
                                    <div class="row">
                                        <div class="col-xs-8"><input type="number" name="d" value="{INPUT.d}" class="form-control" placeholder="Ngày" required></div>
                                        <div class="col-xs-8"><input type="number" name="m" value="{INPUT.m}" class="form-control" placeholder="Tháng" required></div>
                                        <div class="col-xs-8"><input type="number" name="y" value="{INPUT.y}" class="form-control" placeholder="Năm" required></div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-block">Xem Chi Tiết</button>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- BEGIN: general_result -->
                <div class="col-md-16">
                    <div class="panel panel-info">
                        <div class="panel-heading">Kết quả ngày {INPUT.d}/{INPUT.m}/{INPUT.y}</div>
                        <div class="panel-body">
                            <p><strong>Âm lịch:</strong> {LUNAR.day}/{LUNAR.month}/{LUNAR.year} {LUNAR.leap_msg}</p>
                            <p><strong>Can Chi:</strong> {CANCHI_TEXT.day}, Tháng {CANCHI_TEXT.month}, Năm {CANCHI_TEXT.year}</p>
                            <div class="alert alert-{INFO.alert_type}">
                                <strong>Kết luận:</strong> {INFO.comment}
                            </div>
                            <ul>
                                <!-- BEGIN: detail -->
                                <li>{DETAIL}</li>
                                <!-- END: detail -->
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- END: general_result -->
            </div>
        </div>

        <!-- TAB 2: KHAI TRUONG -->
        <div role="tabpanel" class="tab-pane {ACTIVE_TAB_KHAI_TRUONG}" id="tab-khai-truong">
            <form action="{NV_BASE_SITEURL}index.php" method="get" class="form-inline mb-3 well">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
                <input type="hidden" name="tab" value="khai_truong">

                <div class="form-group">
                    <label class="mr-2">Năm sinh chủ sự:</label>
                    <input type="number" name="birth_year" value="{INPUT.birth_year}" class="form-control" placeholder="1984">
                </div>
                <div class="form-group mx-2">
                    <label class="mr-2">Tháng/Năm xem:</label>
                    <input type="number" name="m" value="{INPUT.m}" class="form-control" style="width: 70px" placeholder="T"> /
                    <input type="number" name="y" value="{INPUT.y}" class="form-control" style="width: 80px" placeholder="N">
                </div>
                <button type="submit" class="btn btn-success">Tìm Ngày Tốt</button>
            </form>

            <!-- BEGIN: khai_truong_result -->
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr class="success">
                            <th>Ngày Dương</th>
                            <th>Ngày Âm</th>
                            <th>Can Chi</th>
                            <th>Trực</th>
                            <th>Hoàng Đạo</th>
                            <th>Giờ Tốt</th>
                            <th>Điểm</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- BEGIN: day -->
                        <tr>
                            <td>{DAY.day}/{INPUT.m}</td>
                            <td>{DAY.lunar_day}/{DAY.lunar_month}</td>
                            <td>{DAY.can_chi}</td>
                            <td>{DAY.truc}</td>
                            <td>{DAY.hoang_dao}</td>
                            <td>{DAY.gio_tot}</td>
                            <td><span class="badge">{DAY.diem}</span></td>
                        </tr>
                        <!-- END: day -->
                    </tbody>
                </table>
            </div>
            <!-- END: khai_truong_result -->
        </div>

        <!-- TAB 3: LAM NHA -->
        <div role="tabpanel" class="tab-pane {ACTIVE_TAB_LAM_NHA}" id="tab-lam-nha">
             <div class="row">
                <div class="col-md-8">
                    <div class="panel panel-warning">
                        <div class="panel-heading">Tra Cứu Tuổi Làm Nhà</div>
                        <div class="panel-body">
                            <form action="{NV_BASE_SITEURL}index.php" method="get">
                                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
                                <input type="hidden" name="tab" value="lam_nha">

                                <div class="form-group">
                                    <label>Năm dự định làm nhà:</label>
                                    <div class="row">
                                        <div class="col-xs-6"><input type="number" name="target_year" value="{TARGET_YEAR}" class="form-control" placeholder="Năm"></div>
                                        <div class="col-xs-6"><input type="number" name="target_month" value="{TARGET_MONTH}" class="form-control" placeholder="Tháng dự kiến"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label>Năm sinh gia chủ:</label>
                                    <input type="number" name="owner_year" value="{OWNER_YEAR}" class="form-control">
                                </div>
                                <button type="submit" class="btn btn-warning btn-block">Kiểm Tra & Tìm Ngày</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-16">
                    <!-- BEGIN: lam_nha_result -->

                    <!-- BEGIN: owner_check -->
                    <div class="alert alert-{OWNER_ALERT}">
                        <strong>Kết quả gia chủ ({OWNER_YEAR}):</strong> {OWNER_MSG}
                    </div>
                    <!-- END: owner_check -->

                    <!-- BEGIN: borrow_list -->
                    <div class="panel panel-info">
                        <div class="panel-heading">Danh sách tuổi mượn (Gợi ý)</div>
                        <div class="panel-body" style="max-height: 200px; overflow-y: auto;">
                            <!-- BEGIN: candidate -->
                            <div class="media border-bottom pb-2">
                                <div class="media-left">
                                    <span class="badge" style="background-color: #5cb85c;">{CANDIDATE.score}</span>
                                </div>
                                <div class="media-body">
                                    <h5 class="media-heading">Sinh năm {CANDIDATE.birth_year} ({CANDIDATE.can_chi})</h5>
                                    <small>{CANDIDATE.comment_str}</small>
                                </div>
                            </div>
                            <!-- END: candidate -->
                        </div>
                    </div>
                    <!-- END: borrow_list -->

                    <!-- BEGIN: good_days -->
                    <div class="panel panel-success">
                        <div class="panel-heading">Ngày tốt động thổ trong tháng {TARGET_MONTH}/{TARGET_YEAR}</div>
                        <div class="table-responsive">
                            <table class="table table-bordered table-condensed">
                                <thead>
                                    <tr>
                                        <th>Ngày Dương</th>
                                        <th>Ngày Âm</th>
                                        <th>Can Chi</th>
                                        <th>Trực</th>
                                        <th>Hoàng Đạo</th>
                                        <th>Giờ Tốt</th>
                                        <th>Điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- BEGIN: day -->
                                    <tr>
                                        <td>{DAY.day}</td>
                                        <td>{DAY.lunar_day}/{DAY.lunar_month}</td>
                                        <td>{DAY.can_chi}</td>
                                        <td>{DAY.truc}</td>
                                        <td>{DAY.hoang_dao}</td>
                                        <td>{DAY.gio_tot}</td>
                                        <td><span class="badge">{DAY.diem}</span></td>
                                    </tr>
                                    <!-- END: day -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- END: good_days -->

                    <!-- END: lam_nha_result -->
                </div>
             </div>
        </div>

        <!-- TAB 4: CUOI HOI -->
        <div role="tabpanel" class="tab-pane {ACTIVE_TAB_CUOI_HOI}" id="tab-cuoi-hoi">
            <div class="well">
                <form action="{NV_BASE_SITEURL}index.php" method="get" class="form-inline">
                    <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                    <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                    <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
                    <input type="hidden" name="tab" value="cuoi_hoi">

                    <div class="form-group">
                        <label>Năm sinh Chú rể:</label>
                        <input type="number" name="groom_year" value="{GROOM_YEAR}" class="form-control" style="width: 80px">
                    </div>
                    <div class="form-group">
                        <label>Năm sinh Cô dâu:</label>
                        <input type="number" name="bride_year" value="{BRIDE_YEAR}" class="form-control" style="width: 80px">
                    </div>
                    <div class="form-group">
                        <label>Tháng/Năm tổ chức:</label>
                        <input type="number" name="target_month" value="{TARGET_MONTH}" class="form-control" style="width: 60px"> /
                        <input type="number" name="target_year" value="{TARGET_YEAR}" class="form-control" style="width: 80px">
                    </div>
                    <button type="submit" class="btn btn-danger">Xem Ngày Cưới</button>
                </form>
            </div>

            <!-- BEGIN: cuoi_hoi_result -->

            <!-- BEGIN: bride_check -->
            <div class="alert alert-{BRIDE_ALERT}">
                <strong>Tuổi Cô dâu ({BRIDE_YEAR} - {BRIDE_AGE} tuổi):</strong> {BRIDE_MSG}
            </div>
            <!-- END: bride_check -->

            <!-- BEGIN: good_days -->
            <div class="panel panel-danger">
                <div class="panel-heading">Ngày tốt cưới hỏi trong tháng {TARGET_MONTH}/{TARGET_YEAR}</div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="danger">
                                <th>Ngày Dương</th>
                                <th>Ngày Âm</th>
                                <th>Can Chi</th>
                                <th>Trực</th>
                                <th>Hoàng Đạo</th>
                                <th>Giờ Tốt</th>
                                <th>Điểm</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: day -->
                            <tr>
                                <td>{DAY.day}</td>
                                <td>{DAY.lunar_day}/{DAY.lunar_month}</td>
                                <td>{DAY.can_chi}</td>
                                <td>{DAY.truc}</td>
                                <td>{DAY.hoang_dao}</td>
                                <td>{DAY.gio_tot}</td>
                                <td><span class="badge">{DAY.diem}</span></td>
                            </tr>
                            <!-- END: day -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END: good_days -->

            <!-- END: cuoi_hoi_result -->
        </div>

        <!-- TAB 5: TANG LE (TRUNG TANG) -->
        <div role="tabpanel" class="tab-pane {ACTIVE_TAB_TANG_LE}" id="tab-tang-le">
            <div class="panel panel-danger">
                <div class="panel-heading"><h3 class="panel-title">Xem Ngày Tang Lễ (Trùng Tang)</h3></div>
                <div class="panel-body">
                    <form action="{NV_BASE_SITEURL}index.php" method="post">
                        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">
                        <input type="hidden" name="tab" value="tang_le">

                        <div class="panel panel-default">
                            <div class="panel-heading">Thông tin người mất</div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-8 form-group">
                                        <label>Năm sinh (Dương lịch) (*)</label>
                                        <input type="number" name="deceased_year" class="form-control" required placeholder="19xx" value="{INPUT_TT.deceased_year}">
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <label>Giới tính (*)</label>
                                        <select name="deceased_gender" class="form-control">
                                            <option value="1">Nam</option>
                                            <option value="0">Nữ</option>
                                        </select>
                                    </div>
                                    <div class="col-md-8 form-group">
                                        <label>Thời gian mất (*)</label>
                                        <input type="datetime-local" name="death_time" class="form-control" required value="{INPUT_TT.death_time}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Trưởng nam / Chủ lễ</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Năm sinh (Dương lịch)</label>
                                    <input type="number" name="head_year" class="form-control" placeholder="19xx" value="{INPUT_TT.head_year}">
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">Người thân (Tam hợp / Tứ hành xung)</div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <label>Năm sinh người thân (Cách nhau dấu phẩy)</label>
                                    <input type="text" name="relatives_list" class="form-control" placeholder="1983, 1990, 2005..." value="{INPUT_TT.relatives_list}">
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger btn-block">Xem Kết Quả</button>
                    </form>
                </div>
            </div>

            <!-- BEGIN: tang_le_result -->
            <div class="panel panel-primary mt-3">
                <div class="panel-heading">Kết quả luận giải</div>
                <div class="panel-body">
                    <div class="alert alert-{TT_RESULT.alert_class}">
                        <h4>{TT_RESULT.main_conclusion}</h4>
                    </div>
                    <table class="table table-bordered">
                        <tr>
                            <th width="30%">Tuổi (Niên)</th>
                            <td>{TT_RESULT.tuoi_val} ({TT_RESULT.tuoi_text})</td>
                        </tr>
                        <tr>
                            <th>Tháng (Nguyệt)</th>
                            <td>{TT_RESULT.thang_val} ({TT_RESULT.thang_text})</td>
                        </tr>
                        <tr>
                            <th>Ngày (Nhật)</th>
                            <td>{TT_RESULT.ngay_val} ({TT_RESULT.ngay_text})</td>
                        </tr>
                        <tr>
                            <th>Giờ (Thời)</th>
                            <td>{TT_RESULT.gio_val} ({TT_RESULT.gio_text})</td>
                        </tr>
                    </table>

                    <!-- BEGIN: conflict -->
                    <div class="alert alert-warning">
                        <strong>Xung khắc:</strong> {CONFLICT}
                    </div>
                    <!-- END: conflict -->
                </div>
            </div>
            <!-- END: tang_le_result -->
        </div>

    </div>
</div>
<!-- END: main -->