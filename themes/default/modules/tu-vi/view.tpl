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
                    <li class="star-item {STAR.css}">
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

<script>
// JS to position the grid cells correctly if CSS Grid Template Areas are hard to manage dynamically
// Or we use CSS classes.
// Mapping:
// 6(Ngo) 7(Mui) 8(Than) 9(Dau)
// 5(Ti)                 10(Tuat)
// 4(Thin)               11(Hoi)
// 3(Mao) 2(Dan) 1(Suu)  0(Ty)

// We need to inject style order or grid-area in the loop.
// Let's handle this in PHP or simple CSS classes.
</script>
<!-- END: main -->
