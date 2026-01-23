<!-- BEGIN: main -->
<div class="alert alert-info">
    {MSG} {ERROR}
</div>

<div class="panel panel-default">
    <div class="panel-heading">Import JSON Data</div>
    <div class="panel-body">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label>Select JSON File</label>
                <input type="file" name="import_file" class="form-control" required accept=".json">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Import</button>
        </form>
    </div>
</div>
<!-- END: main -->
