<!-- BEGIN: main -->
<div class="alert alert-info">{LANG.info}</div>
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post">
    <input type="hidden" name="id" value="{ROW.id}" />
    <div class="row">
        <div class="col-md-18">
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.content}</div>
                <div class="panel-body">
                    <div class="form-group">
                        <label>{LANG.title} <span class="red">(*)</span></label>
                        <input class="form-control" type="text" name="title" value="{ROW.title}" required="required" id="id_title" />
                    </div>
                    <div class="form-group">
                        <label>{LANG.alias}</label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="alias" value="{ROW.alias}" id="id_alias" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="return nv_get_alias('id_alias');">
                                    <i class="fa fa-refresh fa-lg fa-pointer"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{LANG.category}</label>
                        <select class="form-control" name="catid">
                            <option value="0">-- {LANG.root} --</option>
                            <!-- BEGIN: cat_list -->
                            <option value="{CAT.catid}" {CAT.selected}>{CAT.title}</option>
                            <!-- END: cat_list -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{LANG.image} <span class="red">(*)</span></label>
                        <div class="input-group">
                            <input class="form-control" type="text" name="image" value="{ROW.image}" id="image" onchange="nv_avatar_preview();" />
                            <span class="input-group-btn">
                                <button class="btn btn-default" type="button" onclick="nv_open_browse('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}=upload&popup=1&area=image&path={MODULE_NAME}&type=image', 'NVImg', 850, 420, 'resizable=no,scrollbars=no,toolbar=no,location=no,status=no'); return false;">
                                    <em class="fa fa-folder-open-o fa-fix">&nbsp;</em>
                                </button>
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>{LANG.description}</label>
                        <textarea class="form-control" name="description" rows="5">{ROW.description}</textarea>
                    </div>
                    <div class="form-group">
                        <label>{LANG.body}</label>
                        {ROW.body}
                    </div>
                </div>
                <div class="panel-footer text-center">
                    <input class="btn btn-primary" name="submit" type="submit" value="{LANG.save}" />
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="panel panel-default">
                <div class="panel-heading">{LANG.image}</div>
                <div class="panel-body text-center">
                    <img id="image_preview" src="{ROW.image}" class="img-responsive img-thumbnail" style="max-height: 300px;" />
                </div>
            </div>
        </div>
    </div>
</form>

<script type="text/javascript">
    function nv_avatar_preview() {
        var src = $('#image').val();
        if (src != '') {
            $('#image_preview').attr('src', src);
        } else {
             $('#image_preview').attr('src', '');
        }
    }

    function nv_get_alias(id) {
        var title = strip_tags(document.getElementById('id_title').value);
        if (title != '') {
            $.post(script_name + '?' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=content&get_alias_title=' + encodeURIComponent(title), function(res) {
                document.getElementById(id).value = res;
            });
        }
        return false;
    }

    // Monitor the input for changes (e.g. from popup)
    $(document).ready(function() {
        var current_image = $('#image').val();
        setInterval(function() {
            var new_image = $('#image').val();
            if (new_image != current_image) {
                current_image = new_image;
                nv_avatar_preview();
            }
        }, 1000);

        nv_avatar_preview();
    });
</script>
<!-- END: main -->
