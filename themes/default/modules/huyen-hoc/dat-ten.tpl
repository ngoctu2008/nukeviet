<!-- BEGIN: main -->
<div class="dat-ten-container">
    <h2 class="text-center">{LANG.dat_ten}</h2>
    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-horizontal">
        <div class="form-group text-center">
            <input type="text" name="ho" value="{INPUT.ho}" class="form-control" placeholder="Họ (Ví dụ: Nguyễn)" style="display:inline-block; width: 150px" required>
            <input type="text" name="ten" value="{INPUT.ten}" class="form-control" placeholder="Tên (Ví dụ: Văn A)" style="display:inline-block; width: 150px" required>
            <input type="number" name="year" value="{INPUT.year}" class="form-control" placeholder="Năm sinh" style="display:inline-block; width: 100px" required>
            <button type="submit" class="btn btn-primary">{LANG.submit}</button>
        </div>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <div class="text-center">
        <h3>Kết quả phân tích: {RESULT.input}</h3>

        <!-- Han-Viet Breakdown Table -->
        <div class="table-responsive" style="margin-bottom: 20px;">
            <table class="table table-bordered table-striped" style="width: auto; margin: 0 auto; min-width: 50%;">
                <thead>
                    <tr class="active">
                        <th class="text-center">Chữ</th>
                        <th class="text-center">Hán Tự</th>
                        <th class="text-center">Số Nét</th>
                        <th class="text-center">Ý Nghĩa</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: part -->
                    <tr>
                        <td><strong>{PART.word}</strong></td>
                        <td class="text-danger" style="font-size: 1.2em;">{PART.han}</td>
                        <td>{PART.strokes}</td>
                        <td class="text-left">{PART.meaning}</td>
                    </tr>
                    <!-- END: part -->
                </tbody>
            </table>
        </div>

        <div class="row">
            <div class="col-md-16 col-md-offset-4">
                <table class="table table-bordered">
                    <thead>
                        <tr class="active">
                            <th class="text-center">Cách Cục</th>
                            <th class="text-center">Số Lý</th>
                            <th class="text-center">Ngũ Hành</th>
                            <th class="text-center">Luận Giải</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><strong>Thiên Cách</strong> (Tổ tiên)</td>
                            <td>{CACH.thien.val}</td>
                            <td>{CACH.thien.element}</td>
                            <td class="{CACH.thien.class}">{CACH.thien.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Nhân Cách</strong> (Chủ vận)</td>
                            <td>{CACH.nhan.val}</td>
                            <td>{CACH.nhan.element}</td>
                            <td class="{CACH.nhan.class}">{CACH.nhan.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Địa Cách</strong> (Tiền vận)</td>
                            <td>{CACH.dia.val}</td>
                            <td>{CACH.dia.element}</td>
                            <td class="{CACH.dia.class}">{CACH.dia.meaning}</td>
                        </tr>
                        <tr>
                            <td><strong>Ngoại Cách</strong> (Xã giao)</td>
                            <td>{CACH.ngoai.val}</td>
                            <td>{CACH.ngoai.element}</td>
                            <td class="{CACH.ngoai.class}">{CACH.ngoai.meaning}</td>
                        </tr>
                        <tr class="info">
                            <td><strong>Tổng Cách</strong> (Hậu vận)</td>
                            <td><strong>{CACH.tong.val}</strong></td>
                            <td><strong>{CACH.tong.element}</strong></td>
                            <td class="{CACH.tong.class}"><strong>{CACH.tong.meaning}</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
