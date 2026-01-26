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
        <div id="ong-xam" class="ong-xam-container"></div>
        <div class="shake-prompt">
            <span class="d-block d-md-none">LẮC ĐIỆN THOẠI ĐỂ GIEO QUẺ</span>
            <span class="d-none d-md-block desktop-prompt">Bấm và Giữ chuột vào ống xăm để lắc</span>
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
    <div id="step-4" class="step-container" style="display: none; max-width: 900px;">
        <h2 class="step-title">KẾT QUẢ</h2>

        <div class="result-card">
            <div class="result-card-inner">
                <!-- Hexagram Name -->
                <div id="res-name" class="hex-name"></div>

                <div class="row">
                    <!-- Chinese Poem -->
                    <div class="col-md-12 col-sm-24 mb-3">
                        <div class="poem-label">Thơ chữ Hán:</div>
                        <div class="poem-content" id="res-poem-han"></div>
                    </div>

                    <!-- Vietnamese Poem -->
                    <div class="col-md-12 col-sm-24 mb-3">
                        <div class="poem-label">Thơ dịch:</div>
                        <div class="poem-content" id="res-poem-viet"></div>
                    </div>
                </div>

                <!-- Meaning -->
                <div class="meaning-box">
                    <h4>Lời bàn:</h4>
                    <p id="res-meaning"></p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <button id="btn-retry" class="btn-mystic"><i class="fa fa-refresh"></i> Gieo quẻ khác</button>
        </div>
    </div>

</div>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/js/gieo-que.js"></script>
<!-- END: main -->
