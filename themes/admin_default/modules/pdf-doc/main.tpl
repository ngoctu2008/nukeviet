<!-- BEGIN: main -->
<form action="{ACTION_URL}" method="post" class="form-horizontal">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.config}</div>
        <div class="panel-body">
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.upload_max_size}</label>
                <div class="col-sm-18">
                    <input type="number" name="upload_max_size" value="{CONFIG.upload_max_size}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.cleanup_time}</label>
                <div class="col-sm-18">
                    <input type="number" name="cleanup_time" value="{CONFIG.cleanup_time}" class="form-control" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-6 control-label">{LANG.groups_use}</label>
                <div class="col-sm-18">
                    <!-- BEGIN: group -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="groups_use[]" value="{GROUP.id}" {GROUP.checked}> {GROUP.title}
                        </label>
                    </div>
                    <!-- END: group -->
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="save" class="btn btn-primary">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
