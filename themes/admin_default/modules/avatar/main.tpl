<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="w100">{LANG.weight}</th>
                <th class="text-center">{LANG.image}</th>
                <th>{LANG.title}</th>
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
                    <img src="{VIEW.image}" width="50" />
                    <!-- END: image_small -->
                </td>
                <td><a href="{VIEW.link_edit}">{VIEW.title}</a></td>
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
<!-- END: main -->
