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
                    <option value="0">Tý (23-1)</option>
                    <option value="1">Sửu (1-3)</option>
                    <option value="2">Dần (3-5)</option>
                    <option value="3">Mão (5-7)</option>
                    <option value="4">Thìn (7-9)</option>
                    <option value="5">Tỵ (9-11)</option>
                    <option value="6">Ngọ (11-13)</option>
                    <option value="7">Mùi (13-15)</option>
                    <option value="8">Thân (15-17)</option>
                    <option value="9">Dậu (17-19)</option>
                    <option value="10">Tuất (19-21)</option>
                    <option value="11">Hợi (21-23)</option>
                </select>
            </div>
        </div>
        <div class="text-center mt-3">
            <input type="submit" name="submit" value="{LANG.submit}" class="btn btn-success">
        </div>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <h3>{LANG.result}</h3>
    <pre>{DEBUG_DATA}</pre>
    <!-- END: result -->
</div>
<!-- END: main -->
