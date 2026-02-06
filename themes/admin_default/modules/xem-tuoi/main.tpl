<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th width="50" class="text-center">ID</th>
                <th>{LANG.log_time}</th>
                <th class="text-center">{LANG.log_year1}</th>
                <th class="text-center">{LANG.log_year2}</th>
                <th class="text-center">{LANG.log_type}</th>
                <th class="text-center">{LANG.log_score}</th>
                <th>IP</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: loop -->
            <tr>
                <td class="text-center">{ROW.id}</td>
                <td>{ROW.add_time}</td>
                <td class="text-center">{ROW.year_1} (<!-- BEGIN: male1 -->{LANG.male}<!-- END: male1 --><!-- BEGIN: female1 -->{LANG.female}<!-- END: female1 -->)</td>
                <td class="text-center">{ROW.year_2} (<!-- BEGIN: male2 -->{LANG.male}<!-- END: male2 --><!-- BEGIN: female2 -->{LANG.female}<!-- END: female2 -->)</td>
                <td class="text-center">{ROW.type}</td>
                <td class="text-center fw-bold">{ROW.result_score}</td>
                <td>{ROW.ip}</td>
            </tr>
            <!-- END: loop -->
        </tbody>
    </table>
</div>
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: main -->
