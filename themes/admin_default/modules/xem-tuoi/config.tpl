<!-- BEGIN: main -->
<div id="module_show_list">
    <!-- BEGIN: saved -->
    <div class="alert alert-success">{LANG.config_saved}</div>
    <!-- END: saved -->

    <form action="{ACTION}" method="post">
        <input type="hidden" name="save_config" value="1">
        <input type="hidden" name="checkss" value="{CHECKSS}">

        <div class="panel panel-default">
            <div class="panel-heading">{LANG.advice_high}</div>
            <div class="panel-body">
                {EDITOR_HIGH}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">{LANG.advice_medium}</div>
            <div class="panel-body">
                {EDITOR_MEDIUM}
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">{LANG.advice_low}</div>
            <div class="panel-body">
                {EDITOR_LOW}
            </div>
        </div>

        <div class="text-center">
            <input type="submit" value="{LANG.save}" class="btn btn-primary" />
        </div>
    </form>
</div>
<!-- END: main -->
