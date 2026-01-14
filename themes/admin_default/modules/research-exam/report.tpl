<!-- BEGIN: main -->
<div class="well">
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <label>{LANG.exam_list}: </label>
        <select class="form-control" name="examid" onchange="this.form.submit()">
            <!-- BEGIN: filter_exam -->
            <option value="{EXAM.id}" {EXAM.selected}>{EXAM.title}</option>
            <!-- END: filter_exam -->
        </select>
    </form>
    <div class="pull-right">
        <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&examid={EXAM_ID}" method="post" target="_blank">
            <input type="hidden" name="export" value="1">
            <button type="submit" name="export_type" value="individual" class="btn btn-success"><i class="fa fa-file-excel-o"></i> {LANG.export_excel} (Cá nhân)</button>
            <button type="submit" name="export_type" value="collective" class="btn btn-info"><i class="fa fa-file-excel-o"></i> {LANG.export_excel} (Tập thể)</button>
        </form>
    </div>
    <div class="clearfix"></div>
</div>

<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.report_individual}
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center">ID</th>
                        <th>{LANG.fullname}</th>
                        <th>{LANG.unit}</th>
                        <th class="text-center">{LANG.correct_count}</th>
                        <th class="text-center">{LANG.score}</th>
                        <th class="text-center">{LANG.essay_score}</th>
                        <th class="text-center">{LANG.total_score}</th>
                        <th class="text-center">Time</th>
                        <th class="text-center"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: individual -->
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center">{ROW.id}</td>
                        <td>{ROW.fullname}</td>
                        <td>{ROW.unit_title}</td>
                        <td class="text-center">{ROW.correct_count}</td>
                        <td class="text-center">{ROW.score}</td>
                        <td class="text-center">{ROW.essay_score}</td>
                        <td class="text-center"><strong>{ROW.total_score}</strong></td>
                        <td class="text-center">{ROW.time_submit}</td>
                        <td class="text-center">
                            <button class="btn btn-xs btn-primary" onclick="open_grade({ROW.id}, {ROW.essay_score})">{LANG.grading}</button>
                        </td>
                    </tr>
                    <!-- END: row -->
                    <!-- END: individual -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Grade Modal -->
<div id="gradeModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">{LANG.grading}</h4>
      </div>
      <div class="modal-body">
          <input type="hidden" name="save_grade" value="1">
          <input type="hidden" name="result_id" id="modal_rid">
          <div id="essay_content"></div>
          <hr>
          <div class="form-group">
              <label>{LANG.essay_score}</label>
              <input type="text" class="form-control" name="essay_score" id="modal_score">
          </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">{LANG.save}</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
      </form>
    </div>
  </div>
</div>

<script>
    var essayData = {ESSAY_DATA_JSON};

    function open_grade(id, currentScore) {
        $('#modal_rid').val(id);
        $('#modal_score').val(currentScore);
        $('#essay_content').html('');

        if (essayData[id]) {
            var html = '';
            $.each(essayData[id], function(i, item) {
                html += '<div class="alert alert-info"><strong>' + item.question_title + ' (Max: ' + item.max_score + ')</strong><br>' + item.user_answer + '</div>';
            });
            $('#essay_content').html(html);
        } else {
            $('#essay_content').html('<p class="text-warning">Không có câu hỏi tự luận nào.</p>');
        }

        $('#gradeModal').modal('show');
    }
</script>
<!-- END: main -->
