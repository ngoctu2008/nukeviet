<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/modules/tu-vi/css/tu-vi.css">

<div class="tu-vi-container">
    <div class="tu-vi-chart">
        <!-- Center Box (Thien Ban) -->
        <div class="center-box">
            <h3>{INFO.fullname}</h3>
            <p><strong>Dương Lịch:</strong> {INFO.solar_date}</p>
            <p><strong>Âm Lịch:</strong> {INFO.lunar_date}</p>
            <p><strong>Giới tính:</strong> {INFO.gender} | <strong>Cục:</strong> {INFO.cuc}</p>
            <p><strong>Tuổi Âm:</strong> {INFO.age} | <strong>Năm xem:</strong> {INFO.current_year}</p>
            <p>Sao chủ: <strong>{INFO.sao}</strong> - Hạn: <strong>{INFO.han}</strong></p>
            <p style="color: #c62828; font-weight: bold; margin-top: 5px;">{INFO.bad_luck}</p>
        </div>

        <!-- Loop 12 Cung -->
        <!-- BEGIN: loop -->
        <div class="cung-box cung-{CUNG.css_class}" data-zodiac-char="{CUNG.kanji}">
            <!-- BEGIN: daivan_label -->
            <span class="cung-label daivan-label">{LABEL_DAIVAN}</span>
            <!-- END: daivan_label -->
            <!-- BEGIN: tieuvan_label -->
            <span class="cung-label tieuvan-label">{LABEL_TIEUVAN}</span>
            <!-- END: tieuvan_label -->

            <div class="cung-header"
                 data-toggle="tooltip"
                 data-placement="top"
                 title="{CUNG.cung_desc}">{CUNG.cung_chuc}</div>

            <div class="star-list">
                <!-- BEGIN: chinh_tinh -->
                <span class="chinh-tinh"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="{STAR_INFO}">{STAR_NAME}</span>
                <!-- END: chinh_tinh -->

                <div style="margin-top: 5px;">
                <!-- BEGIN: phu_tinh -->
                <span class="phu-tinh"
                      data-toggle="tooltip"
                      data-placement="top"
                      title="{STAR_INFO}">{STAR_NAME}</span>
                <!-- END: phu_tinh -->
                </div>
            </div>

            <div class="cung-footer">{CUNG.name}</div>
        </div>
        <!-- END: loop -->
    </div>

    <!-- Interpretations Section -->
    <div class="tu-vi-interpretations">
        <h3>{LANG.interpretations}</h3>

        <!-- General Interpretations -->
        <!-- BEGIN: interpretations -->
        <div class="interpretation-group">
            <!-- BEGIN: loop -->
            <div class="tv-panel">
                <div class="tv-panel-heading">{INTERP.star_key} - {INTERP.palace_key}</div>
                <div class="tv-panel-body">
                    {INTERP.content}
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: interpretations -->

        <!-- Sao Han Detail -->
        <!-- BEGIN: sao_han_detail -->
        <h3>Bình giải Sao Hạn năm nay</h3>
        <div class="interpretation-group">
            <!-- BEGIN: loop -->
            <div class="tv-panel highlight-bad">
                <div class="tv-panel-heading">{DETAIL.star_key} : {DETAIL.palace_key}</div>
                <div class="tv-panel-body">
                    {DETAIL.content}
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: sao_han_detail -->

        <!-- Yearly Detail Interpretations -->
        <!-- BEGIN: year_detail -->
        <h3>Bình giải chi tiết trong năm</h3>
        <div class="interpretation-group">
            <!-- BEGIN: loop -->
            <div class="tv-panel highlight-luck">
                <div class="tv-panel-heading">{DETAIL.palace_key}</div>
                <div class="tv-panel-body">
                    {DETAIL.content}
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: year_detail -->
    </div>

</div>

<script type="text/javascript">
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip({html: true});
});
</script>
<!-- END: main -->
