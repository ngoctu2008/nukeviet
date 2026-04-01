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
             <button type="button" class="btn btn-warning btn-xs" id="btn-install-composer">{LANG.install_composer}</button>
        </div>
    </div>

    <div class="panel panel-info" id="install-terminal-container" style="display:none;">
        <div class="panel-heading">Terminal Log</div>
        <div class="panel-body" style="padding:0;">
            <iframe id="install-frame" src="about:blank" style="width:100%; height:300px; border:none; background:#1e1e1e;"></iframe>
        </div>
        <div class="panel-footer text-right">
             <button type="button" class="btn btn-default btn-xs" onclick="$('#install-terminal-container').hide();">Close</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    var btn = document.getElementById('btn-install-composer');
    if (btn) {
        btn.addEventListener('click', function() {
            var container = document.getElementById('install-terminal-container');
            var frame = document.getElementById('install-frame');

            container.style.display = 'block';
            frame.src = '{STREAM_URL}';
            btn.disabled = true;
            btn.innerHTML = 'Installing...';
        });
    }
});

function installComplete(success) {
    var btn = document.getElementById('btn-install-composer');
    if (btn) {
        btn.disabled = false;
        if (success) {
            btn.innerHTML = 'Install Completed (Reload to apply)';
            btn.className = 'btn btn-success btn-xs';
            btn.onclick = function() { location.reload(); };
        } else {
            btn.innerHTML = 'Install Failed - Retry';
            btn.className = 'btn btn-danger btn-xs';
        }
    }
}
</script>
<!-- END: main -->
