<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/css/lo-ban.css">

<div class="lo-ban-container">
    <h2 class="text-center text-uppercase" style="margin-bottom: 30px;">{LANG.lo_ban}</h2>

    <div class="text-center">
        <p class="help-block"><i class="fa fa-hand-pointer-o"></i> Kéo thước hoặc nhập kích thước bên dưới.</p>
        <div class="lo-ban-input-group" style="position: relative;">
            <input type="number" id="lo-ban-input" step="0.1" name="length" value="{LENGTH}" placeholder="0">
            <span style="font-size: 18px;">cm</span>
            <div class="lo-ban-cursor-line"></div>
        </div>
    </div>

    <!-- Interactive Canvas Ruler -->
    <div class="lo-ban-canvas-container" style="min-height: 450px;">
        <canvas id="lo-ban-canvas"></canvas>
    </div>

    <div class="alert alert-info text-center">
        Kéo thước sang trái/phải để tra cứu các cung Lỗ Ban 52.2cm, 42.9cm và 38.8cm.
    </div>
</div>

<script src="{NV_BASE_SITEURL}themes/{TEMPLATE}/modules/{MODULE_FILE}/js/lo-ban.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        LoBanRuler.init('lo-ban-canvas', 'lo-ban-input');
    });
</script>
<!-- END: main -->
