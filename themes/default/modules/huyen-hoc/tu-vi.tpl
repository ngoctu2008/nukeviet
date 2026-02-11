<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/css/tu-vi.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/font-awesome.min.css">

<div class="tu-vi-container-wrapper">
    <div class="d-flex justify-content-between align-items-center mb-3 no-print">
        <h2 class="text-uppercase m-0">{LANG.tu_vi}</h2>
        <button onclick="window.print()" class="btn btn-secondary"><i class="fa fa-print"></i> {LANG.print_laso}</button>
    </div>

    <div class="card mb-4 no-print">
        <div class="card-body">
            <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
                <div class="row">
                    <div class="col-xs-24 col-sm-12 col-md-12 form-group">
                        <label>{LANG.full_name}</label>
                        <input type="text" name="name" value="{INPUT.name}" class="form-control" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="col-xs-24 col-sm-12 col-md-12 form-group">
                        <label>{LANG.gender}</label>
                        <div>
                            <label class="radio-inline"><input type="radio" name="gender" value="1" {SELECTED_G_1}> {LANG.male}</label>
                            <label class="radio-inline"><input type="radio" name="gender" value="0" {SELECTED_G_0}> {LANG.female}</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-24 col-sm-12 col-md-6 form-group">
                        <label>{LANG.birth_day}</label>
                        <input type="number" name="day" value="{INPUT.d}" class="form-control" required>
                    </div>
                    <div class="col-xs-24 col-sm-12 col-md-6 form-group">
                        <label>{LANG.birth_month}</label>
                        <input type="number" name="month" value="{INPUT.m}" class="form-control" required>
                    </div>
                    <div class="col-xs-24 col-sm-12 col-md-6 form-group">
                        <label>{LANG.birth_year}</label>
                        <input type="number" name="year" value="{INPUT.y}" class="form-control" required>
                    </div>
                    <div class="col-xs-24 col-sm-12 col-md-6 form-group">
                        <label>{LANG.birth_hour}</label>
                        <select name="hour" class="form-control">
                            <option value="0" {SELECTED_0}>{LANG.hour_ty}</option>
                            <option value="1" {SELECTED_1}>{LANG.hour_suu}</option>
                            <option value="2" {SELECTED_2}>{LANG.hour_dan}</option>
                            <option value="3" {SELECTED_3}>{LANG.hour_mao}</option>
                            <option value="4" {SELECTED_4}>{LANG.hour_thin}</option>
                            <option value="5" {SELECTED_5}>{LANG.hour_ty_nho}</option>
                            <option value="6" {SELECTED_6}>{LANG.hour_ngo}</option>
                            <option value="7" {SELECTED_7}>{LANG.hour_mui}</option>
                            <option value="8" {SELECTED_8}>{LANG.hour_than}</option>
                            <option value="9" {SELECTED_9}>{LANG.hour_dau}</option>
                            <option value="10" {SELECTED_10}>{LANG.hour_tuat}</option>
                            <option value="11" {SELECTED_11}>{LANG.hour_hoi}</option>
                        </select>
                    </div>
                    <div class="col-xs-24 col-sm-24 col-md-24 form-group text-center">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" value="{LANG.btn_create_laso}" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BEGIN: result -->
    <hr class="no-print">

    <!-- La So Chart -->
    <div class="la-so-tu-vi-grid">
        <!-- Thien Ban (Center) -->
        <div class="cung-thien-ban">
            <div class="info-user">
                <h3 class="text-danger text-uppercase">{THIEN_BAN.ho_ten}</h3>
                <p>Năm sinh: <b>{THIEN_BAN.nam_sinh}</b></p>
                <p>Mệnh: <span class="text-{THIEN_BAN.menh_color} font-weight-bold text-uppercase">{THIEN_BAN.menh_ngu_hanh}</span></p>
                <p>Cục: <b>{THIEN_BAN.cuc}</b></p>
                <p>{THIEN_BAN.am_duong}</p>
                <p class="small text-muted">{THIEN_BAN.am_duong_ly}</p>
                <p class="small text-muted">{THIEN_BAN.cuc_menh_ly}</p>
                <!-- Hidden inputs for AJAX -->
                <input type="hidden" id="meta_chiYear" value="{META.chiYear}">
                <input type="hidden" id="meta_gender" value="{META.gender}">
                <input type="hidden" id="meta_birthDay" value="{INPUT.d}">
                <input type="hidden" id="meta_birthMonth" value="{INPUT.m}">
                <input type="hidden" id="meta_birthYear" value="{INPUT.y}">
                <input type="hidden" id="meta_birthHour" value="{INPUT.h}">
            </div>
        </div>

        <!-- 12 Palaces -->
        <!-- BEGIN: palace -->
        <div class="cung-so cung-{PALACE.key}">
            <div class="header-cung">
                <span class="cung-name">{PALACE.palace_name}</span>
                <!-- BEGIN: tieu_van --><span class="cung-tieu-van text-muted small">({PALACE.tieu_van})</span><!-- END: tieu_van -->
            </div>

            <div class="tuan-triet-container">
                <!-- BEGIN: tuan --><div class="label-tuan">TUẦN</div><!-- END: tuan -->
                <!-- BEGIN: triet --><div class="label-triet">TRIỆT</div><!-- END: triet -->
            </div>

            <div class="stars-list pt-1 pl-1 pr-1">
                <!-- BEGIN: chinh_tinh -->
                <div class="sao-chinh color-{STAR.color}" data-toggle="tooltip" data-html="true" title="<b>{STAR.tooltip_name}</b><br>{STAR.tooltip_tinh_chat}">{STAR.name} <sup class="star-dacs font-weight-normal">({STAR.dacs})</sup></div>
                <!-- END: chinh_tinh -->

                <!-- BEGIN: phu_tinh_tot -->
                <span class="sao-tot color-{STAR.color}" data-toggle="tooltip" title="{STAR.tooltip_name}">{STAR.name}</span>
                <!-- END: phu_tinh_tot -->

                <br>

                <!-- BEGIN: phu_tinh_xau -->
                <span class="sao-xau color-{STAR.color}" data-toggle="tooltip" title="{STAR.tooltip_name}">{STAR.name}</span>
                <!-- END: phu_tinh_xau -->

                <div class="mt-1 text-secondary small font-italic">{PALACE.vong_trang_sinh}</div>
            </div>

            <div class="footer-cung">
                <span class="dai-van">{PALACE.dai_van}</span>
            </div>
        </div>
        <!-- END: palace -->
    </div>

    <!-- Tabs Functionality -->
    <div class="row mt-2 no-print">
        <div class="col-xs-24 col-sm-24 col-md-24">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tongquan-tab" data-toggle="tab" href="#tongquan" role="tab">
                        <i class="fa fa-info-circle"></i> {LANG.tab_overview}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="luangiai-tab" data-toggle="tab" href="#luangiai" role="tab">
                        <i class="fa fa-book"></i> {LANG.tab_detail}
                    </a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" id="vanhan-tab" data-toggle="tab" href="#vanhan" role="tab">
                        <i class="fa fa-history"></i> {LANG.tab_limit}
                    </a>
                </li>
            </ul>

            <div class="tab-content p-2 border border-top-0 bg-white" id="myTabContent">

                <!-- Tab Tong Quan -->
                <div class="tab-pane fade show active" id="tongquan" role="tabpanel">
                    <h4>{LANG.tab_overview}</h4>
                    <div class="row">
                        <div class="col-xs-24 col-sm-16 col-md-16">
                            <p>{LANG.menh}: <b class="text-{THIEN_BAN.menh_color}">{THIEN_BAN.menh_ngu_hanh}</b> - {LANG.cuc}: <b>{THIEN_BAN.cuc}</b></p>
                            <p>Âm Dương: <b>{THIEN_BAN.am_duong_ly}</b>.</p>
                            <p>Ngũ Hành: <b>{THIEN_BAN.cuc_menh_ly}</b>.</p>
                        </div>
                        <!-- BEGIN: score_box -->
                        <div class="col-xs-24 col-sm-8 col-md-8 text-center">
                            <div class="alert alert-info p-2">
                                <h5 class="m-0">{LANG.score}</h5>
                                <h2 class="text-primary m-0">{SCORE}</h2>
                                <small>/ 100</small>
                            </div>
                        </div>
                        <!-- END: score_box -->
                    </div>
                    <hr>
                    <!-- BEGIN: overview -->
                    <div class="mt-3">
                        <h5 class="text-primary"><i class="fa fa-star"></i> {OVERVIEW.star}</h5>
                        <p class="text-justify">{OVERVIEW.content}</p>
                    </div>
                    <!-- END: overview -->
                </div>

                <!-- Tab Luan Giai Chi Tiet -->
                <div class="tab-pane fade" id="luangiai" role="tabpanel">
                    <!-- BEGIN: report_detail -->
                    <!-- BEGIN: sec1 -->
                    <h4 class="section-header">{LANG.sec1_title}</h4>
                    <div class="row mb-3">
                        <div class="col-md-24">
                             <p><b>{LANG.full_name}:</b> {SEC1.info}</p>
                             <p class="ml-4">{SEC1.am_duong}</p>
                             <p class="ml-4">{SEC1.cuc_menh}</p>
                             <p><b>Mệnh:</b> {SEC1.menh_text}</p>
                             <p><b>Thân:</b> {SEC1.than_text}</p>
                        </div>
                    </div>
                    <!-- END: sec1 -->

                    <h4 class="section-header">{LANG.sec2_title}</h4>
                    <!-- BEGIN: sec2 -->
                    <div class="palace-reading mb-4">
                         <h5 class="palace-title">{SEC2_NAME}</h5>

                         <!-- BEGIN: chinh_tinh -->
                         <p class="star-line"><i class="fa fa-star text-warning"></i> <b>{READING.star}:</b> {READING.content}</p>
                         <!-- END: chinh_tinh -->

                         <!-- BEGIN: phu_tinh -->
                         <p class="star-line"><i class="fa fa-star-o text-muted"></i> <b>{READING.star}:</b> {READING.content}</p>
                         <!-- END: phu_tinh -->

                         <!-- BEGIN: general -->
                         <p class="star-line"><i class="fa fa-asterisk text-info"></i> <b>{READING.star}:</b> {READING.content}</p>
                         <!-- END: general -->

                         <!-- BEGIN: evaluation -->
                         <div class="evaluation-block">
                            <i class="fa fa-commenting-o"></i> <b>Đánh giá:</b> {SEC2_EVAL.text}
                         </div>
                         <!-- END: evaluation -->
                    </div>
                    <hr>
                    <!-- END: sec2 -->
                    <!-- END: report_detail -->
                </div>

                <!-- Tab Van Han -->
                <div class="tab-pane fade" id="vanhan" role="tabpanel">
                     <form id="form-xem-han" class="form-inline mb-3">
                        <label>{LANG.year_view}:</label>
                        <select class="form-control mx-2" id="select-year-han">
                            <option value="2024">2024</option>
                            <option value="2025">2025</option>
                            <option value="2026">2026</option>
                            <option value="2027">2027</option>
                            <option value="2028">2028</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-success" id="btn-view-han">{LANG.btn_view_limit}</button>
                     </form>

                     <div id="ket-qua-han">
                         <!-- BEGIN: report_limit -->
                         <!-- BEGIN: sec3 -->
                         <h4 class="section-header">III. VẬN HẠN (ĐẠI VẬN & TIỂU VẬN)</h4>

                             <!-- BEGIN: dai_van -->
                             <div class="limit-box mb-3">
                                 <h5 class="text-primary">{LIMIT_NAME}</h5>
                                 <!-- BEGIN: reading -->
                                 <p><i class="fa fa-star-half-o"></i> {READING.content}</p>
                                 <!-- END: reading -->
                                 <div class="evaluation-block mt-2">
                                    <i class="fa fa-commenting-o"></i> <b>Đánh giá:</b> {LIMIT_EVAL.text}
                                 </div>
                             </div>
                             <!-- END: dai_van -->

                             <!-- BEGIN: tieu_van -->
                             <div class="limit-box mb-3">
                                 <h5 class="text-success">{LIMIT_NAME}</h5>
                                 <!-- BEGIN: reading -->
                                 <p><i class="fa fa-star-half-o"></i> {READING.content}</p>
                                 <!-- END: reading -->
                                 <div class="evaluation-block mt-2">
                                    <i class="fa fa-commenting-o"></i> <b>Đánh giá:</b> {LIMIT_EVAL.text}
                                 </div>
                             </div>
                             <!-- END: tieu_van -->

                         <!-- END: sec3 -->
                         <!-- END: report_limit -->
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END: result -->
</div>
<script>
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();

        // Handle Xem Han AJAX
        $('#btn-view-han').on('click', function() {
            var targetYear = $('#select-year-han').val();
            var chiYear = $('#meta_chiYear').val();
            var gender = $('#meta_gender').val();

            var birthDay = $('#meta_birthDay').val();
            var birthMonth = $('#meta_birthMonth').val();
            var birthYear = $('#meta_birthYear').val();
            var birthHour = $('#meta_birthHour').val();

            $('#ket-qua-han').html('<p><i class="fa fa-spinner fa-spin"></i> {LANG.loading}</p>');

             $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=ajax&action=xem_han&nv_ajax=1',
            {
                targetYear: targetYear,
                chiYear: chiYear,
                gender: gender,
                birthDay: birthDay,
                birthMonth: birthMonth,
                birthYear: birthYear,
                birthHour: birthHour
            }, function(res) {
                if(res.status == 'success') {
                    $('#ket-qua-han').html(res.html);
                } else {
                    $('#ket-qua-han').html('<p class="text-danger">{LANG.error}</p>');
                }
            }, 'json');
        });
    });
</script>
<!-- END: main -->
