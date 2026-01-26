<!-- BEGIN: main -->
<div class="tu-vi-container">
    <h2 class="text-center">{LANG.tu_vi}</h2>

    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <div class="row">
            <div class="col-md-3">
                <label>Ngày (DL)</label>
                <input type="number" name="day" value="{INPUT.d}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Tháng (DL)</label>
                <input type="number" name="month" value="{INPUT.m}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Năm (DL)</label>
                <input type="number" name="year" value="{INPUT.y}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label>Giờ (0-11)</label>
                <select name="hour" class="form-control">
                    <option value="0" {SELECTED_0}>Tý (23-1)</option>
                    <option value="1" {SELECTED_1}>Sửu (1-3)</option>
                    <option value="2" {SELECTED_2}>Dần (3-5)</option>
                    <option value="3" {SELECTED_3}>Mão (5-7)</option>
                    <option value="4" {SELECTED_4}>Thìn (7-9)</option>
                    <option value="5" {SELECTED_5}>Tỵ (9-11)</option>
                    <option value="6" {SELECTED_6}>Ngọ (11-13)</option>
                    <option value="7" {SELECTED_7}>Mùi (13-15)</option>
                    <option value="8" {SELECTED_8}>Thân (15-17)</option>
                    <option value="9" {SELECTED_9}>Dậu (17-19)</option>
                    <option value="10" {SELECTED_10}>Tuất (19-21)</option>
                    <option value="11" {SELECTED_11}>Hợi (21-23)</option>
                </select>
            </div>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-success">
        </div>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <h3>{LANG.result}: {INFO.cuc_name}</h3>

    <div class="laso-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; border: 1px solid #ccc; padding: 10px;">
        <!-- Loop through 12 palaces. Grid mapping:
             Ty (0) -> Suu (1) -> Dan (2) -> Mao (3)  (Top?)
             Wait, standard Tu Vi chart is a square with center.
             Ty(0) is usually bottom or top specific?
             Standard:
             Tỵ (5) - Ngọ (6) - Mùi (7) - Thân (8)  (Top row)
             Thìn (4)                        Dậu (9)
             Mão (3)                         Tuất (10)
             Dần (2) - Sửu (1) - Tý (0) - Hợi (11) (Bottom row)

             Let's render a simple list for Phase 1 Debug or a basic Grid if possible.
             For now, let's just loop and print.
        -->
        <!-- BEGIN: palace -->
        <div class="palace-box" style="border: 1px solid #ddd; padding: 5px; min-height: 150px;">
            <div class="text-center"><strong>{PALACE.name}</strong> <br> <span class="text-danger">{PALACE.palace_name}</span></div>
            <ul class="list-unstyled">
                <!-- BEGIN: star -->
                <li style="color: {STAR.color}">{STAR.name} ({STAR.type})</li>
                <!-- END: star -->
            </ul>
        </div>
        <!-- END: palace -->
    </div>

    <div class="mt-3">
        <h4>Chi tiết (Debug)</h4>
        <pre>{DEBUG_DATA}</pre>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
