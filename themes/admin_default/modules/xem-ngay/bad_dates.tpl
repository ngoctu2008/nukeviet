<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>ID</th>
                <th>{LANG.month}</th>
                <th>{LANG.type}</th>
                <th>{LANG.description}</th>
                <th>{LANG.day_chi} / {LANG.day_lunar}</th>
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
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
