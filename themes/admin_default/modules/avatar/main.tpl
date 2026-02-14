<!-- BEGIN: main -->
<div class="well">
    <div class="row">
        <div class="col-md-12">
            <form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
                <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
                <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
                <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
                <div class="form-group">
                    <input class="form-control" type="text" value="{Q}" name="q" placeholder="{LANG.search_title}" />
                </div>
                <input class="btn btn-primary" type="submit" value="{LANG.search_submit}" />
            </form>
        </div>
        <div class="col-md-12 text-right">
             <a href="{ADD_NEW}" class="btn btn-success"><i class="fa fa-plus-circle"></i> {LANG.add_template}</a>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="w100">{LANG.weight}</th>
                <th class="text-center">{LANG.image}</th>
                <th>{LANG.title}</th>
                <th class="text-center">{LANG.views}</th>
                <th class="text-center">{LANG.downloads}</th>
                <th class="w150 text-center">{LANG.status}</th>
                <th class="w150 text-center">{LANG.feature}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: view -->
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">
                    <select class="form-control" id="id_weight_{VIEW.id}" onchange="nv_chang_weight('{VIEW.id}');">
                    <!-- BEGIN: weight_loop -->
                        <option value="{WEIGHT.key}"{WEIGHT.selected}>{WEIGHT.title}</option>
                    <!-- END: weight_loop -->
                    </select>
                </td>
                <td class="text-center">
                    <!-- BEGIN: image_small -->
                    <!-- Logic for image display if needed, currently VIEW.image is path -->
                    <!-- END: image_small -->
                    <img src="{VIEW.image}" width="50" style="max-height: 50px;" />
                </td>
                <td><a href="{VIEW.link_edit}">{VIEW.title}</a></td>
                <td class="text-center">{VIEW.views}</td>
                <td class="text-center">{VIEW.downloads}</td>
                <td class="text-center"><input type="checkbox" name="status" id="change_status_{VIEW.id}" value="{VIEW.id}" {CHECK} onclick="nv_change_status({VIEW.id});" /></td>
                <td class="text-center">
                    <em class="fa fa-edit fa-lg">&nbsp;</em> <a href="{VIEW.link_edit}">{LANG.edit}</a> -
                    <em class="fa fa-trash-o fa-lg">&nbsp;</em> <a href="{VIEW.link_delete}" onclick="return confirm(nv_is_del_confirm[0]);">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
            <!-- END: view -->
        </tbody>
    </table>
</div>
<div class="text-center">
    {NV_GENERATE_PAGE}
</div>

<script type="text/javascript">
function nv_change_status(id) {
    var new_status = document.getElementById('change_status_' + id).checked ? 1 : 0;
    if (confirm(nv_is_change_act_confirm[0])) {
        var nv_timer = nv_settimeout_disable('change_status_' + id, 5000);
        $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&change_status=1&id=' + id, function(res) {
            nv_clear_timeout_disable(nv_timer);
            var r_split = res.split('_');
            if (r_split[0] != 'OK') {
                alert(nv_is_change_act_confirm[2]);
            }
        });
    }
    else{
        document.getElementById('change_status_' + id).checked = !document.getElementById('change_status_' + id).checked;
    }
    return;
}

function nv_chang_weight(id) {
    var nv_timer = nv_settimeout_disable('id_weight_' + id, 5000);
    var new_vid = $('#id_weight_' + id).val();
    $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=main&ajax_action=1&id=' + id + '&new_vid=' + new_vid, function(res) {
        nv_clear_timeout_disable(nv_timer);
        var r_split = res.split('_');
        if (r_split[0] != 'OK') {
            alert(nv_is_change_act_confirm[2]);
        } else {
            window.location.href = window.location.href;
        }
    });
    return;
}
</script>
<!-- END: main -->
