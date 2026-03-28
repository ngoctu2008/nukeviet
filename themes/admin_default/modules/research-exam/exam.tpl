<!-- BEGIN: main -->
<link type="text/css" href="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.css" rel="stylesheet" />

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.exam_list}
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th>{LANG.exam_title}</th>
                        <th class="text-center">{LANG.exam_time_start}</th>
                        <th class="text-center">{LANG.exam_time_end}</th>
                        <th class="text-center">{LANG.exam_duration}</th>
                        <th class="text-center">{LANG.status}</th>
                        <th class="text-center" width="150"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: list -->
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center">{ROW.id}</td>
                        <td>{ROW.title}</td>
                        <td class="text-center">{ROW.time_start}</td>
                        <td class="text-center">{ROW.time_end}</td>
                        <td class="text-center">{ROW.duration}'</td>
                        <td class="text-center">{ROW.status}</td>
                        <td class="text-center">
                            <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" class="btn btn-xs btn-default"><em class="fa fa-edit"></em> {GLANG.edit}</a>
                            <a href="javascript:void(0);" onclick="nv_del_exam({ROW.id})" class="btn btn-xs btn-danger"><em class="fa fa-trash-o"></em> {GLANG.delete}</a>
                        </td>
                    </tr>
                    <!-- END: row -->
                    <!-- END: list -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.exam_add} / {LANG.exam_edit}
    </div>
    <div class="panel-body">
        <!-- BEGIN: form -->
        <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="id" value="{DATA.id}" />
            <input type="hidden" name="save" value="1" />
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_title} <span class="text-danger">(*)</span></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" name="title" value="{DATA.title}" required />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{GLANG.description}</label>
                <div class="col-sm-20">
                    {DATA.description}
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_time_start}</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" name="time_start" value="{DATA.time_start}" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <label class="col-sm-4 control-label">{LANG.exam_time_end}</label>
                <div class="col-sm-8">
                    <div class="input-group">
                        <input type="text" class="form-control datepicker" name="time_end" value="{DATA.time_end}" />
                        <span class="input-group-btn">
                            <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_duration}</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="duration" value="{DATA.duration}" />
                </div>
                <label class="col-sm-4 control-label">{LANG.exam_num_questions}</label>
                <div class="col-sm-8">
                    <input type="number" class="form-control" name="num_questions" value="{DATA.num_questions}" />
                    <span class="help-block text-warning">(Chỉ dùng nếu không cấu hình theo Chuyên đề)</span>
                </div>
            </div>

            <!-- BEGIN: topics -->
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_structure}</label>
                <div class="col-sm-20">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>{LANG.topic_title}</th>
                                <th width="150">{LANG.quantity}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- BEGIN: loop -->
                            <tr>
                                <td>{TOPIC.title}</td>
                                <td>
                                    <input type="number" class="form-control input-sm" name="structure[{TOPIC.id}]" value="{TOPIC.quantity}" />
                                </td>
                            </tr>
                            <!-- END: loop -->
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- END: topics -->

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_has_prediction}</label>
                <div class="col-sm-8">
                    <label><input type="checkbox" name="has_prediction" value="1" {PREDICTION_CHECKED} /> {LANG.active}</label>
                </div>
                <label class="col-sm-4 control-label">{LANG.status}</label>
                <div class="col-sm-8">
                    <label><input type="checkbox" name="status" value="1" {STATUS_CHECKED} /> {LANG.active}</label>
                </div>
            </div>
            <div class="form-group text-center">
                <button type="submit" class="btn btn-primary">{LANG.save}</button>
            </div>
        </form>
        <!-- END: form -->
    </div>
</div>

<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/jquery-ui/jquery-ui.min.js"></script>
<script type="text/javascript" src="{NV_BASE_SITEURL}{NV_ASSETS_DIR}/js/language/jquery.ui.datepicker-{NV_LANG_INTERFACE}.js"></script>
<script>
    $(".datepicker").datepicker({
        dateFormat: "dd/mm/yy",
        changeMonth: true,
        changeYear: true
    });

    function nv_del_exam(id) {
        if (confirm('{LANG.delete_confirm}')) {
            $.post('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}', {
                delete: id
            }, function(res) {
                if (res == 'OK') {
                    window.location.reload();
                } else {
                    alert('{GLANG.error_save}');
                }
            });
        }
    }
</script>
<!-- END: main -->
