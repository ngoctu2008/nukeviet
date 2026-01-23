<!-- BEGIN: main -->
<div class="alert alert-info">{ERROR}</div>

<div class="panel panel-default">
    <div class="panel-heading">Add Interpretation</div>
    <div class="panel-body">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <div class="form-group">
                <label>Star Key (e.g. tu_vi, pha_quan)</label>
                <input type="text" name="star_key" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Palace Key (e.g. menh, phu_mau, 1, 2...)</label>
                <input type="text" name="palace_key" class="form-control" required>
            </div>
             <div class="form-group">
                <label>Topic</label>
                <input type="text" name="topic" class="form-control" value="tong_quan">
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control" rows="5"></textarea>
            </div>
            <button type="submit" name="save" class="btn btn-primary">Save</button>
        </form>
    </div>
</div>

<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Star</th>
                <th>Palace</th>
                <th>Topic</th>
                <th>Content</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td>{ITEM.id}</td>
                <td>{ITEM.star_key}</td>
                <td>{ITEM.palace_key}</td>
                <td>{ITEM.topic}</td>
                <td>{ITEM.content}</td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: list -->

<!-- END: main -->
