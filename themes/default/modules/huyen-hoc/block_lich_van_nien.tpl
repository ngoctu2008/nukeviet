<!-- BEGIN: main -->
<div class="calendar-block-wrapper text-center" id="block-calendar-{BLOCK_ID}">
    <div class="calendar-nav d-flex justify-content-between align-items-center mb-3">
        <button class="btn btn-sm btn-light btn-prev-day" type="button" title="Ngày trước"><i class="fa fa-chevron-left"></i></button>
        <div class="current-date-display font-weight-bold text-uppercase" style="cursor:pointer;" id="datepicker-trigger-{BLOCK_ID}">
            THÁNG <span class="lbl-month">{DATA.solar_month}</span> NĂM <span class="lbl-year">{DATA.solar_year}</span>
        </div>
        <button class="btn btn-sm btn-light btn-next-day" type="button" title="Ngày sau"><i class="fa fa-chevron-right"></i></button>
    </div>

    <input type="hidden" id="cal-day-{BLOCK_ID}" value="{DATA.solar_day}">
    <input type="hidden" id="cal-month-{BLOCK_ID}" value="{DATA.solar_month}">
    <input type="hidden" id="cal-year-{BLOCK_ID}" value="{DATA.solar_year}">

    <div class="calendar-top">
        <div class="solar-day lbl-day">{DATA.solar_day}</div>
        <div class="day-of-week lbl-wday">{DATA.day_of_week}</div>
    </div>

    <div class="calendar-bottom-card text-left">
        <div class="row no-gutters">
            <div class="col-xs-12 col-sm-12 col-md-12 border-right pr-2">
                <div class="lunar-info">
                    <div class="lunar-date-row">
                        <span class="lbl-lunar-day display-4 text-danger font-weight-bold">{DATA.lunar_day}</span>
                        <span class="lbl-lunar-month-year small">/ {DATA.lunar_month} ({DATA.lunar_year})</span>
                    </div>
                    <div class="can-chi-info small text-muted">
                        <div class="lbl-cc-day">{DATA.can_chi_day}</div>
                        <div class="lbl-cc-month">{DATA.can_chi_month}</div>
                        <div class="lbl-cc-year">{DATA.can_chi_year}</div>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-12 pl-2 d-flex flex-column justify-content-center">
                <div class="solar-term small mb-1">
                    <i class="fa fa-leaf text-success"></i> <span class="lbl-tiet-khi">{DATA.tiet_khi}</span>
                </div>
                <div class="hoang-dao small">
                    <i class="fa fa-star text-warning"></i> <span class="lbl-hoang-dao" style="color: {DATA.ngay_hoang_dao_type_color|default:'green'}">{DATA.ngay_hoang_dao}</span>
                </div>
            </div>
        </div>

        <div class="extra-info mt-3 pt-2 border-top small">
            <div class="mb-1">
                <strong><i class="fa fa-ban text-danger"></i> Tuổi xung:</strong> <span class="lbl-tuoi-xung">{DATA.tuoi_xung}</span>
            </div>
            <div class="mb-1">
                <strong><i class="fa fa-compass text-primary"></i> Xuất hành:</strong> <span class="lbl-xuat-hanh">{DATA.huong_xuat_hanh}</span>
            </div>
        </div>

        <!-- BEGIN: show_zodiac -->
        <div class="zodiac-hours mt-2 pt-2 border-top small">
            <strong>Giờ hoàng đạo:</strong>
            <span class="lbl-zodiac-hours">
            <!-- BEGIN: loop -->
            <span class="zodiac-item">{GIO.name} ({GIO.range})</span><span class="comma">, </span>
            <!-- END: loop -->
            </span>
        </div>
        <!-- END: show_zodiac -->

        <div class="ly-thuan-phong mt-2 pt-2 border-top small">
            <a href="javascript:void(0);" class="btn-toggle-ltp text-info">
                <i class="fa fa-clock-o"></i> Giờ Lý Thuần Phong <i class="fa fa-angle-down"></i>
            </a>
            <div class="ltp-content mt-1" style="display:none; max-height: 150px; overflow-y:auto;">
                <ul class="list-unstyled mb-0 lbl-ltp-list">
                    <!-- BEGIN: ltp_loop -->
                    <li><strong>{LTP.hour}</strong>: {LTP.name}</li>
                    <!-- END: ltp_loop -->
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
.calendar-block-wrapper {
    background-color: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    border: 1px solid #e9ecef;
    font-family: Arial, sans-serif;
}
.calendar-nav .btn {
    border-radius: 50%;
    width: 30px;
    height: 30px;
    padding: 0;
    line-height: 30px;
}
.solar-day {
    font-size: 60px;
    font-weight: bold;
    color: #007bff;
    line-height: 1;
}
.day-of-week {
    font-size: 16px;
    text-transform: uppercase;
    color: #6c757d;
    margin-bottom: 10px;
}
.calendar-bottom-card {
    background: #fff;
    border-radius: 6px;
    padding: 10px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}
.lbl-lunar-day {
    font-size: 28px;
    line-height: 1;
}
.comma:last-child { display: none; }
</style>

<script>
$(document).ready(function() {
    var blockId = '{BLOCK_ID}';
    var ajaxUrl = '{AJAX_URL}';
    var $block = $('#block-calendar-' + blockId);

    function updateCalendar(d, m, y) {
        $block.css('opacity', '0.6');
        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: { day: d, month: m, year: y },
            dataType: 'json',
            success: function(res) {
                $block.css('opacity', '1');
                if (res.status == 'success') {
                    var data = res.data;
                    // Update inputs
                    $('#cal-day-' + blockId).val(data.solar_day);
                    $('#cal-month-' + blockId).val(data.solar_month);
                    $('#cal-year-' + blockId).val(data.solar_year);

                    // Update labels
                    $block.find('.lbl-day').text(data.solar_day);
                    $block.find('.lbl-month').text(data.solar_month);
                    $block.find('.lbl-year').text(data.solar_year);
                    $block.find('.lbl-wday').text(data.day_of_week);

                    $block.find('.lbl-lunar-day').text(data.lunar_day);
                    $block.find('.lbl-lunar-month-year').text('/ ' + data.lunar_month + ' (' + data.lunar_year + ')' + (data.is_leap ? ' Nhuận' : ''));

                    $block.find('.lbl-cc-day').text(data.can_chi_day);
                    $block.find('.lbl-cc-month').text(data.can_chi_month);
                    $block.find('.lbl-cc-year').text(data.can_chi_year);

                    $block.find('.lbl-tiet-khi').text(data.tiet_khi);
                    $block.find('.lbl-hoang-dao').text(data.ngay_hoang_dao).css('color', data.ngay_hoang_dao_type == 1 ? 'green' : '#d9534f');

                    $block.find('.lbl-tuoi-xung').text(data.tuoi_xung);
                    $block.find('.lbl-xuat-hanh').text(data.huong_xuat_hanh);

                    // Update Zodiac Hours
                    var zHtml = '';
                    if (data.gio_hoang_dao && data.gio_hoang_dao.length > 0) {
                        $.each(data.gio_hoang_dao, function(i, v) {
                            zHtml += '<span class="zodiac-item">' + v.name + ' (' + v.range + ')</span><span class="comma">, </span>';
                        });
                    }
                    $block.find('.lbl-zodiac-hours').html(zHtml);

                    // Update LTP
                    var ltpHtml = '';
                    if (data.ly_thuan_phong && data.ly_thuan_phong.length > 0) {
                        $.each(data.ly_thuan_phong, function(i, v) {
                            ltpHtml += '<li><strong>' + v.hour + '</strong>: ' + v.name + '</li>';
                        });
                    }
                    $block.find('.lbl-ltp-list').html(ltpHtml);
                } else {
                    console.error(res.message);
                }
            },
            error: function() {
                $block.css('opacity', '1');
                alert('Lỗi kết nối!');
            }
        });
    }

    $block.find('.btn-prev-day').click(function() {
        var d = parseInt($('#cal-day-' + blockId).val());
        var m = parseInt($('#cal-month-' + blockId).val());
        var y = parseInt($('#cal-year-' + blockId).val());
        var date = new Date(y, m - 1, d);
        date.setDate(date.getDate() - 1);
        updateCalendar(date.getDate(), date.getMonth() + 1, date.getFullYear());
    });

    $block.find('.btn-next-day').click(function() {
        var d = parseInt($('#cal-day-' + blockId).val());
        var m = parseInt($('#cal-month-' + blockId).val());
        var y = parseInt($('#cal-year-' + blockId).val());
        var date = new Date(y, m - 1, d);
        date.setDate(date.getDate() + 1);
        updateCalendar(date.getDate(), date.getMonth() + 1, date.getFullYear());
    });

    $block.find('.btn-toggle-ltp').click(function() {
        $(this).next('.ltp-content').slideToggle();
        $(this).find('i.fa-angle-down').toggleClass('fa-angle-up');
    });
});
</script>
<!-- END: main -->