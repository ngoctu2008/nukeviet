<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.add_knowledge}</div>
            <div class="panel-body">
                <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
                    <input type="hidden" name="id" value="{DATA.id}" />
                    <div class="form-group">
                        <label>{LANG.title}</label>
                        <input type="text" name="title" value="{DATA.title}" class="form-control" required />
                    </div>
                    <div class="form-group">
                        <label>{LANG.content}</label>
                        <textarea name="content" class="form-control" rows="5" required>{DATA.content}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{LANG.status}</label>
                        <select name="status" class="form-control">
                            <option value="1" {STATUS_ACTIVE}>{LANG.active}</option>
                            <option value="0" {STATUS_INACTIVE}>{LANG.inactive}</option>
                        </select>
                    </div>
                    <div class="text-center">
                        <input type="submit" name="save" value="{LANG.save}" class="btn btn-primary" />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">{LANG.knowledge}</div>
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th width="50">ID</th>
                        <th>{LANG.title}</th>
                        <th width="100">{LANG.status}</th>
                        <th width="100"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: loop -->
                    <tr>
                        <td>{ROW.id}</td>
                        <td>{ROW.title}</td>
                        <td>{ROW.status_text}</td>
                        <td>
                            <a href="{ROW.link_edit}" class="btn btn-xs btn-default"><i class="fa fa-edit"></i></a>
                            <a href="javascript:void(0);" onclick="nv_del_knowledge({ROW.id});" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                        </td>
                    </tr>
                    <!-- END: loop -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function nv_del_knowledge(id) {
    if (confirm('Are you sure you want to delete this item?')) {
        $.post('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}', {delete_id: id}, function(res) {
            if (res == 'OK') {
                window.location.reload();
            } else {
                alert('Error!');
            }
        });
    }
}
</script>
<!-- END: main -->
