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
            <!-- Background handled by CSS -->
        </div>
        <div class="shake-prompt">
            <span class="d-block d-md-none">LẮC ĐIỆN THOẠI ĐỂ GIEO QUẺ</span>
            <span class="d-none d-md-block desktop-prompt">Bấm và Giữ chuột vào ống xăm để gieo</span>
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
    <div id="step-4" class="step-container" style="display: none;">
        <h2 class="step-title">KẾT QUẢ GIEO QUẺ</h2>

        <!-- Hexagram Visuals -->
        <div class="row">
            <!-- Que Chu -->
            <div class="col-xs-24 col-sm-8 mb-3">
                <div class="hex-box">
                    <div class="hex-title">Quẻ Chủ (Hiện Tại)</div>
                    <div id="hex-vis-chu" class="hex-visual">
                        <!-- Lines populated by JS -->
                    </div>
                    <div id="res-chu-name" class="hex-name-display"></div>
                    <p id="res-chu-nghia" class="small text-muted"></p>
                    <p id="res-chu-dong" class="small text-danger font-italic"></p>
                </div>
            </div>

            <!-- Que Ho -->
            <div class="col-xs-24 col-sm-8 mb-3">
                 <div class="hex-box">
                    <div class="hex-title">Quẻ Hỗ (Diễn Biến)</div>
                    <div id="hex-vis-ho" class="hex-visual"></div>
                    <div id="res-ho-name" class="hex-name-display"></div>
                    <p id="res-ho-nghia" class="small text-muted"></p>
                </div>
            </div>

            <!-- Que Bien -->
            <div class="col-xs-24 col-sm-8 mb-3">
                 <div class="hex-box">
                    <div class="hex-title">Quẻ Biến (Kết Quả)</div>
                    <div id="hex-vis-bien" class="hex-visual"></div>
                    <div id="res-bien-name" class="hex-name-display"></div>
                    <p id="res-bien-nghia" class="small text-muted"></p>
                </div>
            </div>
        </div>

        <!-- Detailed Interpretation -->
        <div class="interp-section">
            <h3 class="text-uppercase text-center text-warning mb-4" style="border-bottom: 2px solid #8b0000; display: inline-block; padding-bottom: 5px;">Tổng Luận Chi Tiết</h3>

            <div class="interp-card context">
                <h4><i class="fa fa-map-marker"></i> 1. Bối Cảnh (Hiện Tại)</h4>
                <div id="interp-context"></div>
            </div>

            <div class="interp-card process">
                <h4><i class="fa fa-road"></i> 2. Diễn Biến (Quá Trình)</h4>
                <div id="interp-process"></div>
            </div>

            <div class="interp-card outcome">
                <h4><i class="fa fa-flag-checkered"></i> 3. Kết Quả (Tương Lai)</h4>
                <div id="interp-outcome"></div>
            </div>

            <div class="interp-card advice">
                <h4><i class="fa fa-lightbulb-o"></i> 4. Lời Khuyên</h4>
                <div id="interp-advice" class="font-weight-bold text-danger" style="font-size: 1.1em;"></div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button onclick="location.reload()" class="btn btn-warning btn-lg"><i class="fa fa-refresh"></i> Gieo quẻ khác</button>
        </div>
    </div>

</div>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/js/gieo-que.js"></script>
<!-- END: main -->
