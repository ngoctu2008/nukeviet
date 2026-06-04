<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: list -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.star}</th>
                <th>{LANG.palace}</th>
                <th>{LANG.topic}</th>
                <th width="50">Weight</th>
                <th class="text-center">{LANG.content}</th>
                <th class="text-center" width="150">{GLANG.actions}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td>{ITEM.id}</td>
                <td>{ITEM.star_key}</td>
                <td>{ITEM.palace_key}</td>
                <td>{ITEM.topic}</td>
                <td>{ITEM.weight}</td>
                <td>{ITEM.content}</td>
                <td class="text-center">
                    <a href="{ITEM.link_edit}" class="btn btn-default btn-xs"><i class="fa fa-edit"></i> {GLANG.edit}</a>
                    <a href="{ITEM.link_delete}" class="btn btn-danger btn-xs" onclick="return confirm(nv_is_del_confirm[0]);"><i class="fa fa-trash-o"></i> {GLANG.delete}</a>
                </td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<div class="text-center" style="margin-bottom: 20px;">
    <a href="{ACTION}" class="btn btn-success"><i class="fa fa-plus"></i> {LANG.add_interpretation}</a>
</div>
<!-- END: list -->

<!-- BEGIN: form -->
<form action="{ACTION}" method="post">
    <div class="panel panel-primary">
        <div class="panel-heading">{LANG.interpretations}</div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label>{LANG.star}</label>
                        <input class="form-control" type="text" name="star_key" value="{ROW.star_key}" required />
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>{LANG.palace}</label>
                        <input class="form-control" type="text" name="palace_key" value="{ROW.palace_key}" required />
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="form-group">
                        <label>{LANG.topic}</label>
                        <select class="form-control" name="topic">
                            <!-- BEGIN: topic -->
                            <option value="{TOPIC.key}" {TOPIC.selected}>{TOPIC.title}</option>
                            <!-- END: topic -->
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label>Weight</label>
                <input class="form-control" type="number" name="weight" value="{ROW.weight}" />
            </div>

            <div class="form-group">
                <label>{LANG.content}</label>
                {CONTENT}
            </div>
            <div class="text-center">
                <button class="btn btn-primary" name="submit" type="submit">{LANG.save}</button>
            </div>
        </div>
    </div>
</form>
<!-- END: form -->
<!-- END: main -->
