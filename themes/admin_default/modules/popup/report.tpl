<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th>{LANG.title}</th>
                <th class="text-center">{LANG.report_views}</th>
                <th class="text-center">{LANG.report_clicks}</th>
                <th class="text-center">{LANG.report_closes}</th>
                <th class="text-center">{LANG.report_ctr} (%)</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td>{ROW.title}</td>
                <td class="text-center">{ROW.total_views}</td>
                <td class="text-center">{ROW.total_clicks}</td>
                <td class="text-center">{ROW.total_closes}</td>
                <td class="text-center"><strong>{ROW.ctr}%</strong></td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>
<!-- END: main -->
