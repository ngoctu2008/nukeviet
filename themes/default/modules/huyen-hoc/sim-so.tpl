<!-- BEGIN: main -->
<div class="sim-so-container">
    <h2 class="text-center">{LANG.sim_so}</h2>
    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" class="form-inline text-center">
        <label>Nhập số điện thoại:</label>
        <input type="text" name="phone" value="{PHONE}" class="form-control" placeholder="0912345678" required>
        <button type="submit" class="btn btn-primary">{LANG.submit}</button>
    </form>

    <hr>
    <!-- BEGIN: error -->
    <div class="alert alert-danger text-center">{ERROR}</div>
    <!-- END: error -->

    <!-- BEGIN: result -->
    <div class="text-center">
        <h3>Kết quả cho số: {RESULT.phone}</h3>
        <p>4 số cuối: <strong>{RESULT.last4}</strong></p>
        <p>Điểm: <strong>{RESULT.score}/10</strong></p>
        <div class="alert alert-success">
            <strong>{RESULT.meaning}</strong>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
