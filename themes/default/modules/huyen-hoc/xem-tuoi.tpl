<!-- BEGIN: main -->
<div class="xem-tuoi-container">
    <h2 class="text-center">{LANG.xem_tuoi}</h2>
    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline text-center">
        <label>Năm sinh 1:</label>
        <input type="number" name="y1" value="{INPUT.y1}" class="form-control" style="width: 100px" required>
        <label>Năm sinh 2:</label>
        <input type="number" name="y2" value="{INPUT.y2}" class="form-control" style="width: 100px" required>
        <button type="submit" name="submit" value="1" class="btn btn-primary">{LANG.submit}</button>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <div class="text-center">
        <h3>Điểm hợp khắc: {RESULT.score}/100</h3>
        <ul class="list-group">
            <!-- BEGIN: detail -->
            <li class="list-group-item">{DETAIL}</li>
            <!-- END: detail -->
        </ul>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
