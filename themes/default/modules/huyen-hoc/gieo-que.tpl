<!-- BEGIN: main -->
<div class="gieo-que-container text-center">
    <h2>{LANG.gieo_que}</h2>
    <p>Hãy tịnh tâm và bấm nút để gieo quẻ</p>

    <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
        <button type="submit" name="submit" value="1" class="btn btn-warning btn-lg">Gieo Quẻ</button>
    </form>

    <!-- BEGIN: result -->
    <hr>
    <div class="alert alert-success">
        <h3>{RESULT.name}</h3>
        <p><strong>{RESULT.meaning}</strong></p>
        <!-- Image placeholder -->
        <!-- <img src="{RESULT.image}" alt="{RESULT.name}" /> -->
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
