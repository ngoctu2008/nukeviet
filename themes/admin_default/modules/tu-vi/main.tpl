<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.star}</th>
                <th>{LANG.palace}</th>
                <th>{LANG.topic}</th>
                <th class="text-center">{LANG.content}</th>
                <th class="text-center" width="150">{GLANG.actions}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ROW.id}</td>
                <td>{ROW.star_key}</td>
                <td>{ROW.palace_key}</td>
                <td>{ROW.topic}</td>
                <td>{ROW.content}</td>
                <td class="text-center">
                    <a href="{ROW.link_edit}" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                    <a href="{ROW.link_delete}" class="btn btn-danger btn-xs" onclick="return confirm(nv_is_del_confirm[0]);"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<div class="text-center">
    <a href="{LINK_ADD}" class="btn btn-primary">{LANG.add_interpretation}</a>
</div>
<!-- END: main -->
