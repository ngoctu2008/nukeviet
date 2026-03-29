<!-- BEGIN: main -->
<div class="panel panel-default">
    <div class="panel-heading">{FORM_TITLE}</div>
    <div class="panel-body">
        <form action="{ACTION_URL}" method="post">
            <input type="hidden" name="id" value="{DATA.id}">
            <div class="form-group row">
                <label class="col-sm-4 control-label">Tiêu đề sự kiện (*)</label>
                <div class="col-sm-20">
                    <input type="text" name="title" class="form-control" value="{DATA.title}" required placeholder="Ví dụ: Mua xe, Ký hợp đồng...">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">Mô tả</label>
                <div class="col-sm-20">
                    <textarea name="description" class="form-control" rows="3">{DATA.description}</textarea>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">Tùy chọn Logic</label>
                <div class="col-sm-20">
                    <!-- BEGIN: logic_option -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="logic_config[]" value="{LOGIC.key}" {LOGIC.checked}> {LOGIC.label}
                        </label>
                    </div>
                    <!-- END: logic_option -->
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-4 control-label">Tùy chọn Nhập liệu</label>
                <div class="col-sm-20">
                    <!-- BEGIN: input_option -->
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" name="input_config[]" value="{INPUT.key}" {INPUT.checked}> {INPUT.label}
                        </label>
                    </div>
                    <!-- END: input_option -->
                </div>
            </div>
            <div class="text-center">
                <button type="submit" name="save" value="1" class="btn btn-primary">{LANG.save}</button>
                <a href="{ACTION_URL}" class="btn btn-default">Hủy</a>
            </div>
        </form>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Mô tả</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td>{ROW.id}</td>
                <td>{ROW.title}</td>
                <td>{ROW.description}</td>
                <td>
                    <a href="{ROW.edit_url}" class="btn btn-xs btn-info">{LANG.edit}</a>
                    <a href="{ROW.delete_url}" class="btn btn-xs btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?');">{LANG.delete}</a>
                </td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
