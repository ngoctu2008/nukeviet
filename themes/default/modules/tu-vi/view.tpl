<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/modules/{MODULE_NAME}/css/tu-vi.css">

<div class="tuvi-chart-container">
    <div class="tuvi-grid">

        <!-- Center Block (Heaven Plate) -->
        <div class="tuvi-center">
            <img src="{NV_BASE_SITEURL}themes/default/modules/{MODULE_NAME}/images/yin_yang.png" class="yin-yang-logo" alt="Yin Yang">
            <div class="user-info">
                <h3>{USER.fullname}</h3>
                <p><strong>Ngày sinh:</strong> {USER.birth_solar}</p>
                <p><strong>Âm lịch:</strong> {USER.birth_lunar}</p>
                <p><strong>Giới tính:</strong> {USER.gender_txt}</p>
            </div>
            <div class="copyright">
                <small>&copy; {USER.year_view} TraCuuTuVi</small>
            </div>
        </div>

        <!-- Loop 12 Palaces -->
        <!-- BEGIN: loop -->
        <div class="tuvi-palace palace-{PALACE.index} {PALACE.css_class}" style="grid-area: {PALACE.grid_area}">
            <div class="palace-header">
                <span class="palace-name">{PALACE.name}</span>
                <span class="palace-zodiac">{PALACE.zodiac}</span>
            </div>

            <div class="palace-body">
                <ul class="star-list">
                    <!-- BEGIN: stars -->
                    <li class="star-item {STAR.css}" data-toggle="tooltip" data-html="true" title="{STAR.tooltip}">
                        {STAR.name} <span class="brightness">({STAR.brightness})</span>
                    </li>
                    <!-- END: stars -->
                </ul>
            </div>

            <!-- Additional indicators (Loc Ton, Trang Sinh...) -->
             <!-- {PALACE.bottom_info} -->
        </div>
        <!-- END: loop -->

    </div>
</div>

<!-- Interpretation Section -->
<div class="container" style="margin-top: 20px;">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#lifetime" aria-controls="lifetime" role="tab" data-toggle="tab">Lá Số Trọn Đời</a></li>
        <li role="presentation"><a href="#yearly" aria-controls="yearly" role="tab" data-toggle="tab">Luận Giải Năm Xem ({USER.year_view})</a></li>
    </ul>

    <div class="tab-content" style="margin-top: 15px;">
        <div role="tabpanel" class="tab-pane active" id="lifetime">
             {INTERPRETATION}
        </div>
        <div role="tabpanel" class="tab-pane" id="yearly">
             {INTERPRETATION_YEAR}
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
<!-- END: main -->
