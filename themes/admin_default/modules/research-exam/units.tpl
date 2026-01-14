<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.unit_list}
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th>{LANG.unit_title}</th>
                        <th>{LANG.unit_note}</th>
                        <th class="text-center" width="100">{LANG.status}</th>
                        <th class="text-center" width="150"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: list -->
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center">{ROW.id}</td>
                        <td>{ROW.title}</td>
                        <td>{ROW.note}</td>
                        <td class="text-center">{ROW.status}</td>
                        <td class="text-center">
                            <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" class="btn btn-xs btn-default"><em class="fa fa-edit"></em> {GLANG.edit}</a>
                            <a href="javascript:void(0);" onclick="nv_del_unit({ROW.id})" class="btn btn-xs btn-danger"><em class="fa fa-trash-o"></em> {GLANG.delete}</a>
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
        {LANG.unit_add} / {LANG.unit_edit}
    </div>
    <div class="panel-body">
        <!-- BEGIN: form -->
        <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="id" value="{DATA.id}" />
            <input type="hidden" name="save" value="1" />
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.unit_title} <span class="text-danger">(*)</span></label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" name="title" value="{DATA.title}" required />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.unit_note}</label>
                <div class="col-sm-20">
                    <textarea class="form-control" name="note">{DATA.note}</textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.weight}</label>
                <div class="col-sm-20">
                    <input type="number" class="form-control" name="weight" value="{DATA.weight}" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.status}</label>
                <div class="col-sm-20">
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

<script>
    function nv_del_unit(id) {
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
