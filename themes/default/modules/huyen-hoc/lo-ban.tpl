<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/css/lo-ban.css">

<div class="lo-ban-container">
    <h2 class="text-center">{LANG.lo_ban}</h2>

    <div class="text-center" style="margin-bottom: 20px;">
        <p class="help-block"><i class="fa fa-hand-pointer-o"></i> Kéo thước hoặc nhập số đo để xem kết quả.</p>
        <div class="form-inline">
            <div class="form-group">
                <input type="number" id="lo-ban-input" step="0.1" name="length" value="{LENGTH}" class="form-control input-lg text-center" style="width: 150px; font-weight: bold; color: red; font-size: 24px;" placeholder="0">
                <span style="font-size: 18px; margin-left: 10px;">cm</span>
            </div>
        </div>
    </div>

    <!-- Interactive Canvas Ruler -->
    <div class="lo-ban-canvas-container">
        <canvas id="lo-ban-canvas"></canvas>
    </div>

    <!-- Detailed Result from Backend (Optional fallback or detailed text) -->
    <!-- BEGIN: result -->
    <div class="lo-ban-ruler-box mt-4">
        <!-- BEGIN: ruler -->
        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Thước {RULER.id}cm ({RULER.scope}):</strong>
                <span class="label label-{RULER.result_class} pull-right">{RULER.result_text}</span>
            </div>
            <div class="panel-body text-center">
                <h3 style="margin: 5px 0; color: {RULER.color}">{RULER.name}</h3>
                <p class="text-muted">{RULER.desc}</p>
                <div style="border-top: 1px dashed #ccc; padding-top: 5px;">
                    Cung nhỏ: <strong>{RULER.sub_name}</strong>
                </div>
            </div>
        </div>
        <!-- END: ruler -->
    </div>
    <!-- END: result -->
</div>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/js/lo-ban.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        LoBanRuler.init('lo-ban-canvas', 'lo-ban-input');
    });
</script>
<!-- END: main -->
