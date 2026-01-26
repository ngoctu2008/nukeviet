<!-- BEGIN: main -->
<div class="lo-ban-container">
    <h2 class="text-center">{LANG.lo_ban}</h2>

    <div class="text-center" style="margin-bottom: 30px;">
        <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline">
            <div class="form-group">
                <input type="number" step="0.1" name="length" value="{LENGTH}" class="form-control input-lg text-center" style="width: 150px; font-weight: bold; color: red; font-size: 24px;" placeholder="0" required>
                <span style="font-size: 18px; margin-left: 10px;">cm (nhập số)</span>
            </div>
            <button type="submit" class="btn btn-primary btn-lg">{LANG.submit}</button>
        </form>
        <p class="help-block"><i class="fa fa-info-circle"></i> Nhập kích thước để xem chi tiết các cung.</p>
    </div>

    <!-- BEGIN: result -->
    <div class="lo-ban-ruler-box">
        <!-- BEGIN: ruler -->
        <div class="ruler-group" style="margin-bottom: 20px; border: 1px solid #ddd; padding: 10px; position: relative;">
            <h4><strong>Thước Lỗ Ban {RULER.id}cm:</strong> {RULER.scope}</h4>

            <!-- Visual Representation -->
            <div class="ruler-visual" style="position: relative; height: 80px; background: #f9f9f9; border: 1px solid #999; overflow: hidden; white-space: nowrap;">
                <!-- Ruler Ticks Background (Simplified CSS pattern or image) -->
                <div class="ticks" style="height: 20px; border-bottom: 1px solid #ccc; background-image: linear-gradient(90deg, #333 1px, transparent 1px); background-size: 10px 100%;"></div>

                <!-- Main Segment -->
                <div class="segment-info text-center" style="margin-top: 10px;">
                    <span style="font-size: 18px; font-weight: bold; color: {RULER.color}; display: block;">{RULER.name}</span>
                    <span style="font-size: 12px; color: #666;">({RULER.desc})</span>
                </div>

                <!-- Sub Segment -->
                <div class="sub-segment-info text-center" style="margin-top: 5px; border-top: 1px solid #eee; padding-top: 5px;">
                    <span style="font-weight: bold; color: {RULER.color};">{RULER.sub_name}</span>
                </div>

                <!-- The Orange Line Indicator -->
                <div class="indicator-line" style="position: absolute; top: 0; bottom: 0; left: 50%; width: 2px; background: orange; z-index: 10;"></div>
            </div>

            <!-- Detail Text -->
            <div class="row text-center mt-2">
                <div class="col-xs-12">
                     <span class="label label-{RULER.result_class}" style="font-size: 100%;">{RULER.result_text}</span>
                </div>
            </div>
        </div>
        <!-- END: ruler -->
    </div>
    <!-- END: result -->
</div>

<style>
.lo-ban-ruler-box {
    max-width: 800px;
    margin: 0 auto;
}
.label-red { background-color: #d9534f; }
.label-black { background-color: #333; }
</style>
<!-- END: main -->
