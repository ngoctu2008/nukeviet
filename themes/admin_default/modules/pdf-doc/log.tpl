<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.log_action_type}</th>
                <th>{LANG.log_file_name}</th>
                <th>{LANG.log_action_time}</th>
                <th>{LANG.log_ip}</th>
                <th>{LANG.log_user}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td>{ROW.id}</td>
                <td>{ROW.action_type}</td>
                <td>{ROW.file_name}</td>
                <td>{ROW.action_time}</td>
                <td>{ROW.ip}</td>
                <td>{ROW.user_id}</td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
