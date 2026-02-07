<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}assets/js/jquery-ui/jquery-ui.min.css">
<script type="text/javascript" src="{NV_BASE_SITEURL}assets/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}assets/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script type="text/javascript" data-show="after" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.js"></script>
<link href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/select2/select2.min.css" type="text/css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" method="post">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.add_popup}</div>
        <div class="panel-body">

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.title} <span class="text-danger">(*)</span></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" name="title" value="{ROW.title}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.content}</label>
                <div class="col-sm-20">
                    {EDITOR}
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.type}</label>
                <div class="col-sm-20">
                    <select class="form-control" name="type">
                        <!-- BEGIN: type -->
                        <option value="{TYPE.key}" {TYPE.selected}>{TYPE.title}</option>
                        <!-- END: type -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.trigger_config}</label>
                <div class="col-sm-10">
                    <select class="form-control" name="trigger_type">
                        <!-- BEGIN: trigger -->
                        <option value="{TRIGGER.key}" {TRIGGER.selected}>{TRIGGER.title}</option>
                        <!-- END: trigger -->
                    </select>
                </div>
                <div class="col-sm-10">
                    <input type="number" class="form-control" name="trigger_value" value="{TRIGGER_VALUE}" placeholder="{LANG.trigger_value}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.display_pages}</label>
                <div class="col-sm-20">
                    <select class="form-control select2" name="display_pages[]" multiple="multiple">
                        <!-- BEGIN: module -->
                        <option value="{MOD.value}" {MOD.selected}>{MOD.title}</option>
                        <!-- END: module -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.user_groups}</label>
                <div class="col-sm-20">
                    <select class="form-control select2" name="user_groups[]" multiple="multiple">
                        <!-- BEGIN: group -->
                        <option value="{GROUP.value}" {GROUP.selected}>{GROUP.title}</option>
                        <!-- END: group -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.device_type}</label>
                <div class="col-sm-20">
                    <select class="form-control" name="device_type">
                        <!-- BEGIN: device -->
                        <option value="{DEVICE.value}" {DEVICE.selected}>{DEVICE.title}</option>
                        <!-- END: device -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.schedule}</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control datepicker" name="begin_time" value="{BEGIN_TIME}" placeholder="{LANG.begin_time}">
                </div>
                <div class="col-sm-10">
                    <input type="text" class="form-control datepicker" name="end_time" value="{END_TIME}" placeholder="{LANG.end_time}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.frequency}</label>
                <div class="col-sm-20">
                    <input type="number" class="form-control" name="frequency" value="{ROW.frequency}">
                    <span class="help-block">{LANG.frequency_note}</span>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.priority}</label>
                <div class="col-sm-20">
                    <input type="number" class="form-control" name="priority" value="{ROW.priority}">
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.status}</label>
                <div class="col-sm-20">
                    <label><input type="checkbox" name="status" value="1" {STATUS_CHECKED}> {LANG.status}</label>
                </div>
            </div>

            <div class="form-group text-center">
                <button type="submit" name="submit" class="btn btn-primary">{LANG.save}</button>
            </div>

        </div>
    </div>
</form>

<script>
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });

    $(document).ready(function() {
        $('.select2').select2();
    });
</script>
<!-- END: main -->
