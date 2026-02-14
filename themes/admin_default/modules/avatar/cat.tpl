<!-- BEGIN: main -->
<div id="module_show_list">
    <!-- BEGIN: view -->
    <div class="table-responsive">
        <table class="table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px">{LANG.weight}</th>
                    <th>{LANG.title}</th>
                    <th class="text-center" style="width: 100px">{LANG.status}</th>
                    <th class="text-center">{LANG.image}</th>
                    <th class="text-center">{LANG.groups_view}</th>
                    <th class="text-center">{LANG.groups_use}</th>
                    <th class="text-center" style="width: 150px">{LANG.func}</th>
                </tr>
            </thead>
            <tbody>
                <!-- BEGIN: loop -->
                <tr>
                    <td class="text-center">
                        <select class="form-control input-sm" id="id_weight_{ROW.catid}" onchange="nv_change_weight('{ROW.catid}');">
                            <!-- BEGIN: weight_loop -->
                            <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                            <!-- END: weight_loop -->
                        </select>
                    </td>
                    <td>
                        <a href="{ROW.link_edit}"><strong>{ROW.title}</strong></a>
                    </td>
                    <td class="text-center">
                        <input type="checkbox" name="status" id="change_status_{ROW.catid}" value="{ROW.catid}" {ROW.check_status} onclick="nv_change_status({ROW.catid});" />
                    </td>
                    <td class="text-center">
                        <!-- IF {ROW.image} --><img src="{ROW.image}" width="50" /><!-- ENDIF -->
                    </td>
                    <td>{ROW.groups_view_str}</td>
                    <td>{ROW.groups_use_str}</td>
                    <td class="text-center">
                        <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{ROW.link_edit}">{GLANG.edit}</a> -
                        <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{ROW.link_delete}" onclick="{ROW.onclick_delete}">{GLANG.delete}</a>
                    </td>
                </tr>
                <!-- END: loop -->
            </tbody>
        </table>
    </div>
    <!-- BEGIN: generate_page -->
    <div class="text-center">
        {NV_GENERATE_PAGE}
    </div>
    <!-- END: generate_page -->
    <!-- END: view -->
</div>

<div id="module_show_form">
    <!-- BEGIN: error -->
    <div class="alert alert-danger">{ERROR}</div>
    <!-- END: error -->
    <!-- BEGIN: form -->
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.categories}</div>
        <div class="panel-body">
            <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
                <input type="hidden" name="catid" value="{DATA.catid}" />
                <input type="hidden" name="parentid_old" value="{DATA.parentid}" />
                <input type="hidden" name="save" value="1" />

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="title">{LANG.title} <span class="red">*</span></label>
                    <div class="col-sm-20">
                        <input type="text" class="form-control" name="title" id="title" value="{DATA.title}" required="required" onchange="get_alias('title', 'alias');" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label" for="alias">{LANG.alias}</label>
                    <div class="col-sm-20">
                        <input type="text" class="form-control" name="alias" id="alias" value="{DATA.alias}" />
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.cat_sub}</label>
                    <div class="col-sm-20">
                        <select class="form-control" name="parentid">
                            <option value="0">{LANG.cat_sub_sl}</option>
                            <!-- BEGIN: cat_list -->
                            <option value="{CAT.catid}" {CAT.selected}>{CAT.title}</option>
                            <!-- END: cat_list -->
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.image}</label>
                    <div class="col-sm-20">
                        <div class="input-group">
                            <input type="text" class="form-control" name="image" id="image" value="{DATA.image}" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=image&path={MODULE_UPLOAD}&type=image', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no'); return false;">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>{LANG.browse_image}
                                </button>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.description}</label>
                    <div class="col-sm-20">
                        <textarea class="form-control" name="description">{DATA.description}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.view_layout}</label>
                    <div class="col-sm-20">
                        <select class="form-control" name="viewcat">
                            <!-- BEGIN: layout -->
                            <option value="{LAYOUT.key}" {LAYOUT.selected}>{LAYOUT.val}</option>
                            <!-- END: layout -->
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.groups_view}</label>
                    <div class="col-sm-20">
                        <!-- BEGIN: groups_view -->
                        <div class="checkbox">
                            <label><input type="checkbox" name="groups_view[]" value="{GROUP_VIEW.value}" {GROUP_VIEW.checked} /> {GROUP_VIEW.title}</label>
                        </div>
                        <!-- END: groups_view -->
                    </div>
                </div>

                <div class="form-group">
                    <label class="col-sm-4 control-label">{LANG.groups_use}</label>
                    <div class="col-sm-20">
                        <!-- BEGIN: groups_use -->
                        <div class="checkbox">
                            <label><input type="checkbox" name="groups_use[]" value="{GROUP_USE.value}" {GROUP_USE.checked} /> {GROUP_USE.title}</label>
                        </div>
                        <!-- END: groups_use -->
                    </div>
                </div>

                <div class="form-group text-center">
                    <button class="btn btn-primary" type="submit">{LANG.save}</button>
                </div>
            </form>
        </div>
    </div>
    <!-- END: form -->
</div>

<script type="text/javascript">
function nv_change_weight(catid) {
    var nv_timer = nv_settimeout_disable('id_weight_' + catid, 5000);
    var new_vid = $('#id_weight_' + catid).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'ajax_action=1&catid=' + catid + '&new_vid=' + new_vid, function(res) {
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        }
        window.location.href = window.location.href;
    });
    return;
}

function nv_change_status(catid) {
    var new_status = $('#change_status_' + catid).is(':checked') ? true : false;
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_status_' + catid, 5000);
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'change_status=1&catid=' + catid, function(res) {
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
        });
    }
    else{
        $('#change_status_' + catid).prop('checked', new_status ? false : true);
    }
    return;
}

function nv_del_cat(catid, checkss) {
    if (confirm(nv_is_del_confirm[0])) {
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=cat&nocache=' + new Date().getTime(), 'delete=1&catid=' + catid + '&checkss=' + checkss, function(res) {
            if (res == 'OK') {
                window.location.href = window.location.href;
            } else if (res == 'ERR_PARENT') {
                alert('{LANG.error_del_parent}');
            } else if (res == 'ERR_ROWS') {
                alert('{LANG.error_del_rows}');
            } else {
                alert(nv_is_del_confirm[2]);
            }
        });
    }
    return false;
}
</script>
<!-- END: main -->
