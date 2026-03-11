<!-- BEGIN: main -->
<div class="dat-ten-container">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title text-center" style="font-weight:bold; text-transform:uppercase">{LANG.dat_ten}</h3>
        </div>
        <div class="panel-body">
            <form action="{NV_BASE_SITEURL}index.php" method="get" class="form-horizontal">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}">
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}">
                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}">

                <div class="form-group text-center">
                    <div class="col-md-6 col-sm-6 col-xs-24">
                        <label class="sr-only">Họ</label>
                        <input type="text" name="ho" value="{INPUT.ho}" class="form-control input-lg" placeholder="Họ (Ví dụ: Nguyễn)" required>
                    </div>
                    <div class="col-md-8 col-sm-8 col-xs-24">
                         <label class="sr-only">Tên Đệm & Tên</label>
                        <input type="text" name="ten" value="{INPUT.ten}" class="form-control input-lg" placeholder="Tên Đệm & Tên (Ví dụ: Văn A)" required>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <label class="sr-only">Năm Sinh</label>
                        <input type="number" name="year" value="{INPUT.year}" class="form-control input-lg" placeholder="Năm sinh" required>
                    </div>
                     <div class="col-md-5 col-sm-5 col-xs-12">
                        <label class="sr-only">Giới Tính</label>
                        <select name="gender" class="form-control input-lg">
                            <option value="1" {INPUT.gender_male}>Nam</option>
                            <option value="0" {INPUT.gender_female}>Nữ</option>
                        </select>
                    </div>
                </div>
                 <div class="text-center">
                    <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-calculator"></i> Phân Tích Tên</button>
                </div>
            </form>
        </div>
    </div>

    <!-- BEGIN: result -->
    <div class="alert alert-info text-center" style="font-size: 1.2em;">
        Kết quả phân tích cho: <strong>{RESULT.input}</strong> ({INPUT.year} - {PHONG_THUY.user.gioi_tinh})
    </div>

    <div class="row">
        <!-- Cot Trai: Chi tiet Chu + Phong Thuy -->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">1. Phân Tích Hán Tự</h3></div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr class="active">
                                <th class="text-center">Chữ</th>
                                <th class="text-center">Hán Tự</th>
                                <th class="text-center">Nét</th>
                                <th class="text-center">Hành</th>
                                <th class="text-center">Ý Nghĩa</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: part -->
                            <tr>
                                <td class="text-center"><strong>{PART.word}</strong></td>
                                <td class="text-center text-danger" style="font-size: 1.5em; font-family: 'Times New Roman'">{PART.han}</td>
                                <td class="text-center">{PART.strokes}</td>
                                <td class="text-center">{PART.element}</td>
                                <td>{PART.meaning}</td>
                            </tr>
                            <!-- END: part -->
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">2. Cân Bằng Âm Dương</h3></div>
                <div class="panel-body text-center">
                    <div style="font-size: 1.5em; margin-bottom: 10px;">
                        {AM_DUONG.sequence}
                    </div>
                    <div class="{AM_DUONG.class}" style="font-weight: bold;">
                        {AM_DUONG.message}
                    </div>
                    <p class="help-block"><small>(Số nét chẵn là Âm, lẻ là Dương. Cân bằng là tốt)</small></p>
                </div>
            </div>

            <!-- Phong Thuy Bat Trach -->
            <div class="panel panel-info">
                <div class="panel-heading"><h3 class="panel-title">5. Phong Thủy Mệnh & Cung Phi</h3></div>
                <div class="panel-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Mệnh (Nạp Âm):</strong> <span style="color:{PHONG_THUY.user.color}">{PHONG_THUY.user.menh_text}</span> ({PHONG_THUY.user.can_chi})
                        </li>
                        <li class="list-group-item">
                            <strong>Cung Phi (Bát Trạch):</strong> {PHONG_THUY.user.cung_text}
                        </li>
                        <li class="list-group-item">
                            <strong>Hành của Tên (Tổng Cách):</strong> <span style="color:{PHONG_THUY.ten.color}">{PHONG_THUY.ten.hanh_ten}</span>
                        </li>
                    </ul>
                    <hr style="margin: 10px 0;">
                    <p><strong>So sánh Tên vs Mệnh:</strong> {PHONG_THUY.chi_tiet.vs_menh.msg}</p>
                    <p><strong>So sánh Tên vs Cung:</strong> {PHONG_THUY.chi_tiet.vs_cung.msg}</p>

                    <!-- BEGIN: warning_nu -->
                    <div class="alert alert-danger" style="margin-top: 10px;">
                        {PHONG_THUY.chi_tiet.canh_bao_nu.msg}
                    </div>
                    <!-- END: warning_nu -->

                    <div class="text-center" style="margin-top: 10px;">
                        <h4>KẾT LUẬN: <span class="label label-primary">{PHONG_THUY.ket_luan}</span></h4>
                    </div>
                </div>
            </div>

        </div>

        <!-- Cot Phai: Ngu Cach + Tam Tai -->
        <div class="col-md-14">
             <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">3. Ngũ Cách (Cát - Hung)</h3></div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th>Cách Cục</th>
                            <th class="text-center">Số</th>
                            <th class="text-center">Hành</th>
                            <th>Luận Giải</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Thiên Cách</strong> <br><small>(Tổ nghiệp)</small></td>
                            <td class="text-center">{CACH.thien.val}</td>
                            <td class="text-center">{CACH.thien.element}</td>
                            <td class="{CACH.thien.class}">{CACH.thien.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Nhân Cách</strong> <br><small>(Chủ vận - Quan trọng nhất)</small></td>
                            <td class="text-center">{CACH.nhan.val}</td>
                            <td class="text-center">{CACH.nhan.element}</td>
                            <td class="{CACH.nhan.class}">{CACH.nhan.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Địa Cách</strong> <br><small>(Tiền vận, Gia đạo)</small></td>
                            <td class="text-center">{CACH.dia.val}</td>
                            <td class="text-center">{CACH.dia.element}</td>
                            <td class="{CACH.dia.class}">{CACH.dia.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngoại Cách</strong> <br><small>(Xã giao)</small></td>
                            <td class="text-center">{CACH.ngoai.val}</td>
                            <td class="text-center">{CACH.ngoai.element}</td>
                            <td class="{CACH.ngoai.class}">{CACH.ngoai.meaning}</td>
                        </tr>
                        <tr class="warning">
                            <td><strong>Tổng Cách</strong> <br><small>(Hậu vận)</small></td>
                            <td class="text-center"><strong>{CACH.tong.val}</strong></td>
                            <td class="text-center"><strong>{CACH.tong.element}</strong></td>
                            <td class="{CACH.tong.class}"><strong>{CACH.tong.meaning}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><h3 class="panel-title">4. Tam Tài (Thiên - Nhân - Địa)</h3></div>
                <div class="panel-body">
                    <div class="row text-center" style="margin-bottom: 15px;">
                         <div class="col-xs-8">
                            <div class="well well-sm">
                                <strong>Thiên</strong><br>
                                {CACH.thien.element}
                            </div>
                        </div>
                        <div class="col-xs-8">
                            <div class="well well-sm">
                                <strong>Nhân</strong><br>
                                {CACH.nhan.element}
                            </div>
                        </div>
                        <div class="col-xs-8">
                            <div class="well well-sm">
                                <strong>Địa</strong><br>
                                {CACH.dia.element}
                            </div>
                        </div>
                    </div>
                    <ul class="list-group">
                        <li class="list-group-item">
                            <strong>Thiên - Nhân:</strong> {TAM_TAI.thien_nhan}
                        </li>
                        <li class="list-group-item">
                            <strong>Nhân - Địa:</strong> {TAM_TAI.nhan_dia}
                        </li>
                        <li class="list-group-item list-group-item-info text-center">
                            <strong>Đánh giá chung:</strong> <span class="{TAM_TAI.class}" style="font-size: 1.2em;">{TAM_TAI.final}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
