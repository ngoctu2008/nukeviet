<!-- BEGIN: main -->
<div class="row">
    <div class="col-md-16">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>{LANG.title}</th>
                        <th class="text-center">{LANG.feature}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: list -->
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center">{ROW.catid}</td>
                        <td><a href="{ROW.link_edit}">{ROW.title}</a></td>
                        <td class="text-center">
                            <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.link_edit}">{LANG.edit}</a> -
                            <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="javascript:void(0);" onclick="{ROW.onclick_delete}">{LANG.delete}</a>
                        </td>
                    </tr>
                    <!-- END: row -->
                    <!-- END: list -->
                </tbody>
            </table>
        </div>
    </div>
    <div class="col-md-8">
        <!-- BEGIN: error -->
        <div class="alert alert-danger">{ERROR}</div>
        <!-- END: error -->
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="catid" value="{DATA.catid}" />
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.add_cat}</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>{LANG.title}</label>
                        <input class="form-control" type="text" name="title" value="{DATA.title}" required="required" />
                    </div>
                    <div class="form-group">
                        <label>{LANG.alias}</label>
                        <input class="form-control" type="text" name="alias" value="{DATA.alias}" />
                    </div>
                    <div class="form-group">
                        <label>{LANG.parent}</label>
                        <select class="form-control" name="parentid">
                            <option value="0">-- {LANG.root} --</option>
                            <!-- BEGIN: cat_list -->
                            <option value="{CAT.catid}" {CAT.selected}>{CAT.title}</option>
                            <!-- END: cat_list -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{LANG.description}</label>
                        <textarea class="form-control" name="description">{DATA.description}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{LANG.image}</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="image" value="{DATA.image}" id="image" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=image&path={MODULE_NAME}&type=image', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no'); return false;">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{LANG.viewcat}</label>
                        <select class="form-control" name="viewcat">
                            <!-- BEGIN: layout -->
                            <option value="{LAYOUT.key}" {LAYOUT.selected}>{LAYOUT.val}</option>
                            <!-- END: layout -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{LANG.groups_view}</label>
                        <div class="row">
                            <!-- BEGIN: groups_view -->
                            <div class="col-xs-6">
                                <label><input type="checkbox" name="groups_view[]" value="{GROUP_VIEW.value}" {GROUP_VIEW.checked} /> {GROUP_VIEW.title}</label>
                            </div>
                            <!-- END: groups_view -->
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{LANG.groups_use}</label>
                        <div class="row">
                            <!-- BEGIN: groups_use -->
                            <div class="col-xs-6">
                                <label><input type="checkbox" name="groups_use[]" value="{GROUP_USE.value}" {GROUP_USE.checked} /> {GROUP_USE.title}</label>
                            </div>
                            <!-- END: groups_use -->
                        </div>
                    </div>
                    <div class="text-center">
                        <input class="btn btn-primary" name="save" type="submit" value="{LANG.save}" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<script type="text/javascript">
function nv_del_cat(catid) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}', {
            delete: 1,
            catid: catid
        }, function(res) {
            if (res == 'OK') {
                window.location.href = window.location.href;
            } else {
                alert(res);
            }
        });
    }
}
</script>
<!-- END: main -->
