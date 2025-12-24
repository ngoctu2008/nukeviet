<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.info}</div>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <tbody>
                <tr>
                    <td class="w200">{LANG.title}</td>
                    <td><input class="form-control" type="text" name="title" value="{ROW.title}" required="required" /></td>
                </tr>
                <tr>
                    <td>{LANG.alias}</td>
                    <td><input class="form-control" type="text" name="alias" value="{ROW.alias}" /></td>
                </tr>
                <tr>
                    <td>{LANG.category}</td>
                    <td>
                        <select class="form-control" name="catid">
                            <option value="0">-- {LANG.root} --</option>
                            <!-- BEGIN: cat_list -->
                            <option value="{CAT.catid}" {CAT.selected}>{CAT.title}</option>
                            <!-- END: cat_list -->
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.image}</td>
                    <td>
                        <div class="input-group">
                            <input class="form-control" type="text" name="image" value="{ROW.image}" id="image" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=image&path={MODULE_NAME}&type=image', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no'); return false;">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>{LANG.description}</td>
                    <td><textarea class="form-control" name="description">{ROW.description}</textarea></td>
                </tr>
                <tr>
                    <td>{LANG.body}</td>
                    <td>{ROW.body}</td>
                </tr>
                <tr>
                    <td class="text-center" colspan="2">
                        <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</form>
<!-- END: main -->
