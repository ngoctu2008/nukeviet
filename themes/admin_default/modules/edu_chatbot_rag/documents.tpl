<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-body">
        <!-- BEGIN: error -->
        <div class="alert alert-danger">{ERROR}</div>
        <!-- END: error -->
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&amp;{NV_NAME_VARIABLE}={MODULE_NAME}&amp;{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
            <input type="hidden" name="checksess" value="{NV_CHECK_SESSION}" />
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>{LANG.doc_title}</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="text" name="title" value="" required />
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label"><strong>{LANG.doc_file}</strong></label>
                <div class="col-sm-20">
                    <input class="form-control" type="file" name="doc_file" accept=".txt,.pdf" required />
                </div>
            </div>
            <div class="text-center">
                <input type="submit" name="save" class="btn btn-primary" value="{LANG.upload_doc}" />
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center">ID</th>
                <th>{LANG.doc_title}</th>
                <th>File Path</th>
                <th class="text-center">{LANG.doc_status}</th>
                <th class="text-center">{LANG.time}</th>
                <th class="text-center">{LANG.doc_action}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td class="text-center">{ROW.id}</td>
                <td>{ROW.title}</td>
                <td>{ROW.file_path}</td>
                <td class="text-center">{ROW.status_text}</td>
                <td class="text-center">{ROW.add_time}</td>
                <td class="text-center">
                    <!-- BEGIN: vectorize -->
                    <a href="{ROW.vectorize_url}" class="btn btn-sm btn-success" onclick="return confirm('Bạn có chắc chắn muốn vector hóa tài liệu này?');">{LANG.vectorize}</a>
                    <!-- END: vectorize -->
                    <a href="{ROW.delete_url}" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa tài liệu này?');">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->