<!-- BEGIN: main -->
<!-- BEGIN: message -->
<div class="alert alert-success">{MESSAGE}</div>
<!-- END: message -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{MESSAGE}</div>
<!-- END: error -->

<form action="{ACTION}" method="post" enctype="multipart/form-data">
    <div class="panel panel-default">
        <div class="panel-heading">{LANG.import_json}</div>
        <div class="panel-body">
            <div class="form-group">
                <label>Select JSON File</label>
                <input type="file" name="import_file" class="form-control" required accept=".json">
            </div>
            <div class="text-center">
                <button class="btn btn-primary" name="import" type="submit">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: main -->
