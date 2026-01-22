<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">{LANG.add}</div>
    <div class="panel-body">
        <form action="{ACTION_URL}" method="post">
            <div class="form-group row">
                <label class="col-sm-4 control-label">{LANG.month} (*)</label>
                <div class="col-sm-20">
                    <input type="number" name="month" class="form-control" min="1" max="12" required>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">{LANG.type}</label>
                <div class="col-sm-20">
                    <select name="type" class="form-control">
                        <!-- BEGIN: type_option -->
                        <option value="{TYPE.key}">{TYPE.title}</option>
                        <!-- END: type_option -->
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">{LANG.day_chi} (0-11)</label>
                <div class="col-sm-20">
                    <select name="day_chi" class="form-control">
                        <option value="-1">-- Không chọn --</option>
                        <!-- BEGIN: chi_option -->
                        <option value="{CHI.key}">{CHI.title}</option>
                        <!-- END: chi_option -->
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">{LANG.day_lunar} (1-30)</label>
                <div class="col-sm-20">
                    <input type="number" name="day_lunar" class="form-control" min="1" max="30">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">{LANG.description}</label>
                <div class="col-sm-20">
                    <input type="text" name="description" class="form-control" required>
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="save" value="1" class="btn btn-primary">{LANG.save}</button>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.month}</th>
                <th>{LANG.type}</th>
                <th>{LANG.description}</th>
                <th>{LANG.day_chi} / {LANG.day_lunar}</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td>{ROW.id}</td>
                <td>{ROW.month}</td>
                <td>{ROW.type}</td>
                <td>{ROW.description}</td>
                <td>
                    <!-- BEGIN: chi -->{ROW.day_chi_name}<!-- END: chi -->
                    <!-- BEGIN: lunar -->Ngày {ROW.day_lunar}<!-- END: lunar -->
                </td>
                <td>
                    <a href="{ROW.delete_url}" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
