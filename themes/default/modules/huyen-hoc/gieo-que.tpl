<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/css/gieo-que.css">

<div class="gieo-que-container">

    <!-- Step 1: Cleansing -->
    <div id="step-1" class="step-container">
        <h2 class="step-title">TẨY TRẦN</h2>
        <p class="step-desc">
            "Xin hãy tĩnh tâm, hít thở sâu, gạt bỏ mọi tạp niệm.<br>
            Tập trung suy nghĩ về điều muốn hỏi trước khi gieo quẻ."
        </p>
        <div class="py-4">
            <i class="fa fa-om fa-3x" style="color: #d4af37;"></i>
        </div>
        <button id="btn-start" class="btn-mystic">TÔI ĐÃ SẴN SÀNG</button>
    </div>

    <!-- Step 2: Interaction -->
    <div id="step-2" class="step-container" style="display: none;">
        <h2 class="step-title">GIEO QUẺ</h2>
        <div id="ong-xam" class="ong-xam-container">
            <div class="bat-quai-bg"></div>
        </div>
        <div class="shake-prompt">
            <span class="d-block d-md-none">LẮC ĐIỆN THOẠI ĐỂ GIEO QUẺ</span>
            <span class="d-none d-md-block desktop-prompt">Bấm và Giữ chuột vào hình bát quái để gieo</span>
        </div>
    </div>

    <!-- Step 3: Animation Process -->
    <div id="step-3" class="step-container" style="display: none;">
        <h2 class="step-title">ĐANG LUẬN GIẢI...</h2>
        <div class="py-5">
            <i class="fa fa-spinner fa-spin fa-3x" style="color: #d4af37;"></i>
        </div>
        <p class="step-desc">Thần cơ diệu toán, vạn sự tùy duyên...</p>
    </div>

    <!-- Step 4: Result -->
    <div id="step-4" class="step-container" style="display: none; max-width: 1100px;">
        <h2 class="step-title">KẾT QUẢ GIEO QUẺ</h2>

        <div class="result-card">
            <div class="result-card-inner">
                <div class="row">
                    <!-- Que Chu -->
                    <div class="col-xs-24 col-sm-24 col-md-8 mb-3">
                        <div class="hex-box hex-chu p-3 border rounded h-100">
                            <h4 class="text-uppercase text-danger text-center border-bottom pb-2">Quẻ Chủ (Hiện Tại)</h4>
                            <div id="res-chu-name" class="font-weight-bold h5 text-primary text-center mt-2"></div>
                            <p id="res-chu-nghia" class="small text-justify mt-2"></p>
                            <p id="res-chu-dong" class="font-italic text-muted text-center"></p>
                        </div>
                    </div>

                    <!-- Que Ho -->
                    <div class="col-xs-24 col-sm-24 col-md-8 mb-3">
                         <div class="hex-box hex-ho p-3 border rounded h-100">
                            <h4 class="text-uppercase text-info text-center border-bottom pb-2">Quẻ Hỗ (Diễn Biến)</h4>
                            <div id="res-ho-name" class="font-weight-bold h5 text-primary text-center mt-2"></div>
                            <p id="res-ho-nghia" class="small text-justify mt-2"></p>
                            <p class="text-muted small text-center mt-2">(Quá trình diễn biến sự việc)</p>
                        </div>
                    </div>

                    <!-- Que Bien -->
                    <div class="col-xs-24 col-sm-24 col-md-8 mb-3">
                         <div class="hex-box hex-bien p-3 border rounded h-100">
                            <h4 class="text-uppercase text-success text-center border-bottom pb-2">Quẻ Biến (Kết Quả)</h4>
                            <div id="res-bien-name" class="font-weight-bold h5 text-primary text-center mt-2"></div>
                            <p id="res-bien-nghia" class="small text-justify mt-2"></p>
                            <p class="text-muted small text-center mt-2">(Kết quả cuối cùng)</p>
                        </div>
                    </div>
                </div>

                <!-- Summary/Detail -->
                <div class="meaning-box mt-4 p-4 bg-light border rounded">
                    <h4 class="border-bottom pb-2 text-uppercase"><i class="fa fa-commenting-o"></i> Tổng Luận:</h4>
                    <div id="res-summary">
                        <p>Quẻ này cho thấy sự việc khởi đầu bởi <b id="sum-chu" class="text-danger"></b>, trải qua quá trình <b id="sum-ho" class="text-info"></b>, và sẽ kết thúc ở <b id="sum-bien" class="text-success"></b>.</p>
                        <p class="font-italic text-muted mt-3">"Hãy suy ngẫm kỹ về ý nghĩa của từng quẻ, kết hợp với hoàn cảnh thực tế để tìm ra hướng đi đúng đắn nhất."</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button onclick="location.reload()" class="btn btn-warning btn-lg"><i class="fa fa-refresh"></i> Gieo quẻ khác</button>
        </div>
    </div>

</div>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/js/gieo-que.js"></script>
<!-- END: main -->
