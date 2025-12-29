<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">{LANG.config}</div>
    <div class="panel-body">
        <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="save" value="1" />

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_per_page_cat}</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="per_page_cat" value="{DATA.per_page_cat}" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.config_per_page_row}</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="per_page_row" value="{DATA.per_page_row}" />
                </div>
            </div>

            <div class="form-group">
                <div class="col-sm-offset-4 col-sm-8">
                    <button type="submit" class="btn btn-primary">{LANG.save}</button>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- END: main -->
