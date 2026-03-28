<!-- BEGIN: main -->
<div class="table-responsive">
    <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center" width="50">ID</th>
                    <th>{LANG.title}</th>
                    <th>{LANG.type}</th>
                    <th class="text-center" width="100">{LANG.priority}</th>
                    <th class="text-center" width="100">{LANG.status}</th>
                    <th class="text-center" width="150">{GLANG.actions}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: row -->
                <tr>
                    <td class="text-center">{ROW.id}</td>
                    <td>{ROW.title}</td>
                    <td>{ROW.type_text}</td>
                    <td class="text-center">
                        <input type="number" class="form-control input-sm" name="priority[{ROW.id}]" value="{ROW.priority}">
                    </td>
                    <td class="text-center">
                        <a href="{ROW.status_url}" class="btn btn-sm btn-{ROW.status ? 'success' : 'danger'}">
                            {ROW.status_text}
                        </a>
                    </td>
                    <td class="text-center">
                        <a href="{ROW.edit_url}" class="btn btn-default btn-sm"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                        <a href="javascript:void(0);" onclick="nv_del_popup({ROW.id});" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: row -->
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <button type="submit" name="save_priority" class="btn btn-primary">{LANG.save}</button>
                    </td>
                </tr>
            </tfoot>
        </table>
    </form>
</div>

<script>
    function nv_del_popup(id) {
        if (confirm('{GLANG.confirm}')) {
            $.post('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=del', {
                id: id
            }, function(res) {
                if (res === 'OK') {
                    window.location.reload();
                } else {
                    alert(res);
                }
            });
        }
    }
</script>
<!-- END: main -->
