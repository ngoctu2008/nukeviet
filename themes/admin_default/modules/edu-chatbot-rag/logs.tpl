<!-- BEGIN: main -->
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover">
        <thead>
            <tr>
                <th class="text-center" style="width: 50px;">ID</th>
                <th style="width: 150px;">{LANG.session_id}</th>
                <th style="width: 250px;">{LANG.user_question}</th>
                <th style="width: 300px;">{LANG.ai_answer}</th>
                <th>{LANG.reference_data}</th>
                <th class="text-center" style="width: 150px;">{LANG.time}</th>
            </tr>
        </thead>
        <tbody>
            <!-- BEGIN: row -->
            <tr>
                <td class="text-center">{ROW.id}</td>
                <td>{ROW.session_id}</td>
                <td>{ROW.user_question}</td>
                <td>{ROW.ai_answer}</td>
                <td>
                    <button class="btn btn-xs btn-info" type="button" data-toggle="collapse" data-target="#ref_{ROW.id}" aria-expanded="false" aria-controls="ref_{ROW.id}">
                        Xem Context
                    </button>
                    <div class="collapse" id="ref_{ROW.id}">
                        <div class="well well-sm mt-2" style="font-size: 12px; margin-top: 5px;">
                            {ROW.reference_data}
                        </div>
                    </div>
                </td>
                <td class="text-center">{ROW.add_time}</td>
            </tr>
            <!-- END: row -->
        </tbody>
    </table>
</div>

<!-- BEGIN: generate_page -->
<div class="text-center">
    {GENERATE_PAGE}
</div>
<!-- END: generate_page -->
<!-- END: main -->