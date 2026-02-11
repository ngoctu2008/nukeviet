<!-- BEGIN: main -->
<div class="calendar-block-wrapper text-center">
    <div class="calendar-top">
        <div class="month-year">THÁNG {DATA.solar_month} NĂM {DATA.solar_year}</div>
        <div class="solar-day">{DATA.solar_day}</div>
        <div class="day-of-week">{DATA.day_of_week}</div>
    </div>
    <div class="calendar-bottom-card">
        <div class="row" style="margin-left: -5px; margin-right: -5px;">
            <div class="col-xs-6 col-md-6 col-6 lunar-col" style="padding-left: 5px; padding-right: 5px;">
                <div class="lunar-text">Tháng {DATA.lunar_month}</div>
                <div class="lunar-day">{DATA.lunar_day}</div>
                <div class="lunar-text">Năm {DATA.lunar_year}</div>
                <div class="can-chi-small text-muted">{DATA.can_chi_year}</div>
            </div>
            <div class="col-xs-6 col-md-6 col-6 text-center solar-term-container" style="padding-left: 5px; padding-right: 5px;">
                <div class="solar-term-val">{DATA.tiet_khi}</div>
            </div>
        </div>

        <!-- BEGIN: show_zodiac -->
        <div class="zodiac-hours" style="text-align: left;">
            <strong>Giờ hoàng đạo:</strong>
            <!-- BEGIN: loop -->
            <span class="zodiac-item">{GIO.name} ({GIO.range})</span><span class="comma">, </span>
            <!-- END: loop -->
        </div>
        <!-- END: show_zodiac -->
    </div>
</div>

<style>
.calendar-block-wrapper {
    background-color: #f4f6f9;
    padding: 15px;
    border-radius: 8px;
    font-family: Arial, Helvetica, sans-serif;
}
.calendar-block-wrapper .calendar-top {
    margin-bottom: 15px;
    color: #4a90e2;
}
.calendar-block-wrapper .month-year {
    font-size: 16px;
    font-weight: bold;
    text-transform: uppercase;
}
.calendar-block-wrapper .solar-day {
    font-size: 80px;
    font-weight: bold;
    line-height: 1;
    color: #4a90e2;
    margin: 5px 0;
}
.calendar-block-wrapper .day-of-week {
    font-size: 20px;
    font-weight: bold;
    text-transform: uppercase;
    color: #4a90e2;
}
.calendar-block-wrapper .calendar-bottom-card {
    background: #fff;
    border-radius: 8px;
    padding: 15px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
}
.calendar-block-wrapper .lunar-col {
    border-right: 1px solid #eee;
}
.calendar-block-wrapper .lunar-day {
    font-size: 40px;
    color: #d9534f;
    font-weight: bold;
    line-height: 1.2;
}
.calendar-block-wrapper .lunar-text,
.calendar-block-wrapper .solar-term-label {
    font-size: 14px;
    color: #333;
}
.calendar-block-wrapper .solar-term-val {
    font-size: 16px;
    font-weight: bold;
    color: #333;
    margin-top: 5px;
}
.calendar-block-wrapper .can-chi-small {
    font-size: 12px;
    color: #777;
}
.calendar-block-wrapper .zodiac-hours {
    border-top: 1px solid #eee;
    margin-top: 15px;
    padding-top: 10px;
    font-size: 13px;
    line-height: 1.5;
    color: #333;
}
.calendar-block-wrapper .zodiac-item {
    color: #4a90e2;
}
.calendar-block-wrapper .comma:last-child {
    display: none;
}
.calendar-block-wrapper .solar-term-container {
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    min-height: 100px;
}
/* Scoped overrides if needed */
.calendar-block-wrapper .col-xs-6 {
    /* Only apply if the theme doesn't handle it well, but better to rely on theme grid */
}
</style>
<!-- END: main -->