<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/css/tu-vi.css">
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/font-awesome.min.css">

<div class="tu-vi-container-wrapper">
    <h2 class="text-center text-uppercase">{LANG.tu_vi}</h2>

    <div class="card mb-4">
        <div class="card-body">
            <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
                <div class="row">
                    <div class="col-md-12 form-group">
                        <label>{LANG.full_name}</label>
                        <input type="text" name="name" value="{INPUT.name}" class="form-control" placeholder="Nguyễn Văn A">
                    </div>
                    <div class="col-md-6 form-group">
                        <label>{LANG.gender}</label>
                        <div>
                            <label class="radio-inline"><input type="radio" name="gender" value="1" {SELECTED_G_1}> Nam</label>
                            <label class="radio-inline"><input type="radio" name="gender" value="0" {SELECTED_G_0}> Nữ</label>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label>Ngày (DL)</label>
                        <input type="number" name="day" value="{INPUT.d}" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Tháng (DL)</label>
                        <input type="number" name="month" value="{INPUT.m}" class="form-control" required>
                    </div>
                    <div class="col-md-4 form-group">
                        <label>Năm (DL)</label>
                        <input type="number" name="year" value="{INPUT.y}" class="form-control" required>
                    </div>
                    <div class="col-md-6 form-group">
                        <label>Giờ sinh</label>
                        <select name="hour" class="form-control">
                            <option value="0" {SELECTED_0}>Tý (23h-1h)</option>
                            <option value="1" {SELECTED_1}>Sửu (1h-3h)</option>
                            <option value="2" {SELECTED_2}>Dần (3h-5h)</option>
                            <option value="3" {SELECTED_3}>Mão (5h-7h)</option>
                            <option value="4" {SELECTED_4}>Thìn (7h-9h)</option>
                            <option value="5" {SELECTED_5}>Tỵ (9h-11h)</option>
                            <option value="6" {SELECTED_6}>Ngọ (11h-13h)</option>
                            <option value="7" {SELECTED_7}>Mùi (13h-15h)</option>
                            <option value="8" {SELECTED_8}>Thân (15h-17h)</option>
                            <option value="9" {SELECTED_9}>Dậu (17h-19h)</option>
                            <option value="10" {SELECTED_10}>Tuất (19h-21h)</option>
                            <option value="11" {SELECTED_11}>Hợi (21h-23h)</option>
                        </select>
                    </div>
                    <div class="col-md-6 form-group text-center">
                        <label>&nbsp;</label>
                        <input type="submit" name="submit" value="LẬP LÁ SỐ" class="btn btn-primary btn-block">
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- BEGIN: result -->
    <hr>

    <!-- La So Chart -->
    <div class="laso-container">
        <!-- Thien Ban (Center) -->
        <div class="thien-ban">
            <div class="info-user">
                <h3>{THIEN_BAN.ho_ten}</h3>
                <p>Năm sinh: <b>{THIEN_BAN.nam_sinh}</b></p>
                <p>Mệnh: <span class="text-{THIEN_BAN.menh_color} font-weight-bold">{THIEN_BAN.menh_ngu_hanh}</span></p>
                <p>Cục: <b>{THIEN_BAN.cuc}</b></p>
                <p>{THIEN_BAN.am_duong}</p>
            </div>
        </div>

        <!-- 12 Palaces -->
        <!-- BEGIN: palace -->
        <div class="cung cung-{PALACE.key}">
            <div class="header-cung">
                <span class="cung-name">{PALACE.palace_name}</span>
                <!-- BEGIN: tieu_van --><span class="cung-tieu-van">({PALACE.tieu_van})</span><!-- END: tieu_van -->
            </div>

            <div class="tuan-triet">
                <!-- BEGIN: tuan --><span class="label-tuan">TUẦN</span><!-- END: tuan -->
                <!-- BEGIN: triet --><span class="label-triet">TRIỆT</span><!-- END: triet -->
            </div>

            <div class="stars-list pt-2">
                <!-- BEGIN: chinh_tinh -->
                <span class="sao-chinh color-{STAR.color}" data-toggle="tooltip" data-html="true" title="<b>{STAR.name}</b><br>Hành: {STAR.element}<br>Đắc địa: {STAR.dacs}">{STAR.name} <sup class="star-dacs">{STAR.dacs}</sup></span>
                <!-- END: chinh_tinh -->
            </div>

            <div class="footer-cung">
                <span class="dai-van">{PALACE.dai_van}</span>
                <span class="trang-sinh">{PALACE.vong_trang_sinh}</span>
            </div>
        </div>
        <!-- END: palace -->
    </div>

    <!-- Tabs Functionality -->
    <div class="row mt-4">
        <div class="col-md-24">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tongquan-tab" data-toggle="tab" href="#tongquan" role="tab">
                        <i class="fa fa-info-circle"></i> Tổng Quan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="luangiai-tab" data-toggle="tab" href="#luangiai" role="tab">
                        <i class="fa fa-book"></i> Luận Giải Chi Tiết
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="vanhan-tab" data-toggle="tab" href="#vanhan" role="tab">
                        <i class="fa fa-history"></i> Vận Hạn
                    </a>
                </li>
            </ul>

            <div class="tab-content p-3 border border-top-0 bg-white" id="myTabContent">

                <!-- Tab Tong Quan -->
                <div class="tab-pane fade show active" id="tongquan" role="tabpanel">
                    <h4>Cân xương tính số</h4>
                    <p>Mệnh: <b class="text-{THIEN_BAN.menh_color}">{THIEN_BAN.menh_ngu_hanh}</b> - Cục: <b>{THIEN_BAN.cuc}</b></p>
                    <p>Đánh giá: Âm Dương Thuận Lý (Giúp cuộc đời hanh thông, gặp hung hóa cát).</p>
                    <p>Đánh giá: Cục Sinh Mệnh (Đắc thiên thời, dễ thành công).</p>
                    <hr>
                    <!-- BEGIN: overview -->
                    <div class="mt-3">
                        <h5 class="text-primary"><i class="fa fa-star"></i> {OVERVIEW.star} - Tổng Quan</h5>
                        <p class="text-justify">{OVERVIEW.content}</p>
                    </div>
                    <!-- END: overview -->
                </div>

                <!-- Tab Luan Giai -->
                <div class="tab-pane fade" id="luangiai" role="tabpanel">
                    <!-- BEGIN: palace_luan -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            Luận Cung {PALACE.palace_name}
                        </div>
                        <div class="card-body">
                            <!-- BEGIN: content -->
                            <p class="card-text">
                                <strong>{CONTENT.star}:</strong> {CONTENT.content}
                            </p>
                            <!-- END: content -->
                            <!-- BEGIN: empty -->
                            <p class="card-text text-muted">Đang cập nhật lời giải chi tiết...</p>
                            <!-- END: empty -->
                        </div>
                    </div>
                    <!-- END: palace_luan -->
                    <div class="alert alert-info">Chức năng luận giải chi tiết đang được xây dựng.</div>
                </div>

                <!-- Tab Van Han -->
                <div class="tab-pane fade" id="vanhan" role="tabpanel">
                     <form id="form-xem-han" class="form-inline mb-3">
                        <label>Chọn năm xem hạn:</label>
                        <select class="form-control mx-2">
                            <option>2024</option>
                            <option>2025</option>
                            <option>2026</option>
                        </select>
                        <button type="button" class="btn btn-sm btn-success">Xem ngay</button>
                     </form>
                     <div id="ket-qua-han">
                         <!-- BEGIN: limit -->
                         <div class="alert alert-warning mt-3">
                             <h5><i class="fa fa-exclamation-triangle"></i> Hành Hạn ({LIMIT.star})</h5>
                             <p class="text-justify">{LIMIT.content}</p>
                         </div>
                         <!-- END: limit -->
                     </div>
                </div>
            </div>
        </div>
    </div>

    <!-- END: result -->
</div>
<!-- END: main -->
