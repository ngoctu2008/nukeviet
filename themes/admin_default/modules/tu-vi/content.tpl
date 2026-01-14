<!-- BEGIN: main -->
<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->
<form action="{ACTION}" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="form-group">
                <label>{LANG.star}</label>
                <input class="form-control" type="text" name="star_key" value="{ROW.star_key}" required />
            </div>
            <div class="form-group">
                <label>{LANG.palace}</label>
                <input class="form-control" type="text" name="palace_key" value="{ROW.palace_key}" required />
            </div>
            <div class="form-group">
                <label>{LANG.topic}</label>
                <select class="form-control" name="topic">
                    <!-- BEGIN: topic -->
                    <option value="{TOPIC.key}" {TOPIC.selected}>{TOPIC.title}</option>
                    <!-- END: topic -->
                </select>
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
<!-- END: main -->
