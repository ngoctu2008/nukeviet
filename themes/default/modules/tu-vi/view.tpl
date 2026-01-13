<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/tu-vi.css">

<div class="tu-vi-container">
    <div class="tu-vi-chart">
        <!-- Center Box (Thien Ban) -->
        <div class="center-box">
            <h3>{INFO.fullname}</h3>
            <p>Dương Lịch: {INFO.solar_date}</p>
            <p>Âm Lịch: {INFO.lunar_date}</p>
            <p>Giới tính: {INFO.gender}</p>
            <p>Cục: {INFO.cuc}</p>
        </div>

        <!-- Loop 12 Cung -->
        <!-- BEGIN: loop -->
        <div class="cung-box cung-{CUNG.css_class}">
            <div class="cung-header">{CUNG.cung_chuc}</div>
            <div class="star-list">
                <!-- BEGIN: chinh_tinh -->
                <span class="chinh-tinh">{STAR_NAME}</span>
                <!-- END: chinh_tinh -->

                <!-- BEGIN: phu_tinh -->
                <span class="phu_tinh">{STAR_NAME}</span>
                <!-- END: phu_tinh -->
            </div>
            <div class="cung-footer">{CUNG.name}</div>
        </div>
        <!-- END: loop -->
    </div>

    <!-- Interpretations Section -->
    <div class="tu-vi-interpretations" style="margin-top: 20px;">
        <h3>{LANG.interpretations}</h3>

        <!-- General Interpretations -->
        <!-- BEGIN: interpretations -->
        <div class="panel-group">
            <!-- BEGIN: loop -->
            <div class="panel panel-info">
                <div class="panel-heading"><strong>{INTERP.star_key}</strong> - <strong>{INTERP.palace_key}</strong></div>
                <div class="panel-body">
                    {INTERP.content}
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: interpretations -->

        <!-- Yearly Detail Interpretations -->
        <!-- BEGIN: year_detail -->
        <h3>Bình giải chi tiết trong năm</h3>
        <div class="panel-group">
            <!-- BEGIN: loop -->
            <div class="panel panel-success">
                <div class="panel-heading"><strong>{DETAIL.palace_key}</strong></div>
                <div class="panel-body">
                    {DETAIL.content}
                </div>
            </div>
            <!-- END: loop -->
        </div>
        <!-- END: year_detail -->
    </div>

</div>
<!-- END: main -->
