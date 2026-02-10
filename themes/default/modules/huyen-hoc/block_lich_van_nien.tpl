<!-- BEGIN: main -->
<div class="panel panel-default block-lich-van-nien" style="cursor: pointer;" onclick="window.location.href='{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=xem-ngay&date={SOLAR.day}-{SOLAR.month}-{SOLAR.year}'">
    <div class="panel-body" style="padding: 0; position: relative; overflow: hidden; background: url('{NV_BASE_SITEURL}themes/{TEMPLATE}/images/bg-calendar.jpg') no-repeat center center; background-size: cover; color: #fff; min-height: 300px;">
        <!-- Fallback background if image missing -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: linear-gradient(to bottom, rgba(58, 123, 213, 0.8), rgba(0, 210, 255, 0.6)); z-index: 1;"></div>

        <div style="position: relative; z-index: 2; padding: 15px; text-align: center;">
            <!-- Header: Solar Month/Year -->
            <div class="calendar-header" style="border: 1px solid rgba(255,255,255,0.5); border-radius: 20px; display: inline-block; padding: 5px 20px; background: rgba(0,0,0,0.2);">
                <span class="pointer" style="margin-right: 10px;">&lt;</span>
                <span style="font-size: 16px; font-weight: bold;">Tháng {SOLAR.month} Năm {SOLAR.year}</span>
                <span class="pointer" style="margin-left: 10px;">&gt;</span>
            </div>

            <!-- Body: Solar Day -->
            <div class="calendar-body" style="margin-top: 20px;">
                <div style="font-size: 80px; font-weight: bold; line-height: 1; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">{SOLAR.day}</div>
                <div style="font-size: 18px; margin-top: 5px; text-transform: uppercase;">{SOLAR.day_name}</div>
            </div>

            <!-- Quote -->
            <div class="calendar-quote" style="margin-top: 20px; font-style: italic; font-size: 13px; opacity: 0.9; padding: 0 10px;">
                <i class="fa fa-quote-left"></i> {QUOTE} <i class="fa fa-quote-right"></i>
            </div>
        </div>
    </div>

    <!-- Footer: Lunar Info -->
    <div class="panel-footer" style="background: #f9f9f9; color: #333; padding: 10px;">
        <div class="row">
            <div class="col-xs-8 text-center" style="border-right: 1px solid #ddd;">
                <div style="font-size: 12px; color: #666;">Tháng {LUNAR.month} (Âm)</div>
                <div style="font-size: 30px; font-weight: bold; color: #009688;">{LUNAR.day}</div>
            </div>
            <div class="col-xs-16">
                <div style="font-size: 12px; line-height: 1.6;">
                    <div><strong>Ngày:</strong> {LUNAR.can_chi_day}</div>
                    <div><strong>Tháng:</strong> {LUNAR.can_chi_month}</div>
                    <div><strong>Năm:</strong> {LUNAR.can_chi_year}</div>
                    <div style="margin-top: 5px; border-top: 1px dashed #ccc; padding-top: 5px;">
                        <div><span class="text-info">{LUNAR.tiet_khi}</span></div>
                        <div><span class="text-success">{LUNAR.truc}</span></div>
                        <div><span style="color: #e91e63;">{LUNAR.hoang_dao}</span></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
