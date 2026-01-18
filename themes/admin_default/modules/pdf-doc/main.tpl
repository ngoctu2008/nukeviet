<!-- BEGIN: main -->
<!-- BEGIN: install_result -->
<div class="alert {INSTALL_CLASS}">
    {INSTALL_MESSAGE}
</div>
<!-- END: install_result -->

<form action="{ACTION_URL}" method="post" class="form-horizontal">
    <input type="hidden" name="checkss" value="{CHECKSS}" />

    <!-- BEGIN: error_dependency -->
    <div class="alert alert-danger">
        {ERROR_DEPENDENCY}
        <div class="margin-top">
            <button type="submit" name="install_composer" value="1" class="btn btn-warning btn-xs" onclick="this.innerHTML='Installing... Please wait (this can take 1-2 minutes)'; this.disabled=true; this.form.submit();">{LANG.install_composer}</button>
        </div>
    </div>
    <!-- END: error_dependency -->

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
