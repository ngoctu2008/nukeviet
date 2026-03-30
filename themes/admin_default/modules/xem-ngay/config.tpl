<!-- BEGIN: main -->
<form action="{ACTION_URL}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config}</div>
        <div class="panel-body">
            <div class="form-group row">
                <label class="col-sm-6 control-label">{LANG.groups_view}</label>
                <div class="col-sm-18">
                    <!-- BEGIN: group -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="groups_view[]" value="{GROUP.id}" {GROUP.checked}> {GROUP.title}
                        </label>
                    </div>
                    <!-- END: group -->
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="save_config" value="1" class="btn btn-primary">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
