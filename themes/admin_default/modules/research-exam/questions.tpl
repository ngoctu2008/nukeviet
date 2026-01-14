<!-- BEGIN: main -->
<div class="well">
    <form class="form-inline" action="{NV_BASE_ADMINURL}index.php" method="get">
        <input type="hidden" name="{NV_LANG_VARIABLE}" value="{NV_LANG_DATA}" />
        <input type="hidden" name="{NV_NAME_VARIABLE}" value="{MODULE_NAME}" />
        <input type="hidden" name="{NV_OP_VARIABLE}" value="{OP}" />
        <label>{LANG.exam_list}: </label>
        <select class="form-control" name="examid" onchange="this.form.submit()">
            <option value="0">--- {LANG.exam_list} ---</option>
            <!-- BEGIN: filter_exam -->
            <option value="{EXAM.id}" {EXAM.selected}>{EXAM.title}</option>
            <!-- END: filter_exam -->
        </select>
    </form>
</div>

<!-- BEGIN: error -->
<div class="alert alert-danger">{ERROR}</div>
<!-- END: error -->

<!-- BEGIN: list -->
<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.question_list}
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th class="text-center" width="50">ID</th>
                        <th>{LANG.question_title}</th>
                        <th class="text-center">{LANG.question_type}</th>
                        <th class="text-center">{LANG.question_score}</th>
                        <th class="text-center" width="150"></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: row -->
                    <tr>
                        <td class="text-center">{ROW.id}</td>
                        <td>{ROW.title}</td>
                        <td class="text-center">{ROW.type_text}</td>
                        <td class="text-center">{ROW.score}</td>
                        <td class="text-center">
                            <a href="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}&examid={ROW.exam_id}" class="btn btn-xs btn-default"><em class="fa fa-edit"></em> {GLANG.edit}</a>
                            <a href="javascript:void(0);" onclick="nv_del_question({ROW.id})" class="btn btn-xs btn-danger"><em class="fa fa-trash-o"></em> {GLANG.delete}</a>
                        </td>
                    </tr>
                    <!-- END: row -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- END: list -->

<!-- BEGIN: form -->
<div class="panel panel-default">
    <div class="panel-heading">
        {LANG.question_add} / {LANG.question_edit}
    </div>
    <div class="panel-body">
        <form class="form-horizontal" action="{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}" method="post">
            <input type="hidden" name="id" value="{DATA.id}" />
            <input type="hidden" name="save" value="1" />

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.exam_title} <span class="text-danger">(*)</span></label>
                <div class="col-sm-20">
                    <select class="form-control" name="exam_id">
                        <option value="0">--- {LANG.exam_list} ---</option>
                        <!-- BEGIN: form_exam -->
                        <option value="{EXAM.id}" {EXAM.selected}>{EXAM.title}</option>
                        <!-- END: form_exam -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.question_type}</label>
                <div class="col-sm-20">
                    <select class="form-control" name="type" id="q_type" onchange="change_type()">
                        <!-- BEGIN: type_loop -->
                        <option value="{TYPE.id}" {TYPE.selected}>{TYPE.title}</option>
                        <!-- END: type_loop -->
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.question_title} <span class="text-danger">(*)</span></label>
                <div class="col-sm-20">
                    {DATA.title}
                    <div id="hint_fill" class="help-block text-info" style="display:none">Sử dụng <code>[[input]]</code> để đặt chỗ trống cần điền.</div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-4 control-label">{LANG.question_score}</label>
                <div class="col-sm-20">
                    <input type="text" class="form-control" name="score" value="{DATA.score}" style="width:100px" />
                </div>
            </div>

            <!-- Area for Answers -->
            <div id="area_answers">

                <!-- BEGIN: answers_radio -->
                <div id="type_1_wrapper">
                    <h4>{LANG.question_answers}</h4>
                    <table class="table table-bordered">
                        <thead><tr><th width="50" class="text-center">{LANG.is_correct}</th><th>{LANG.answer_content}</th></tr></thead>
                        <tbody id="container_radio">
                             <!-- BEGIN: loop -->
                             <tr>
                                 <td class="text-center"><input type="radio" name="correct_radio" value="{ANS.index}" {ANS.checked}></td>
                                 <td><input type="text" class="form-control" name="answers[{ANS.index}]" value="{ANS.title}"></td>
                             </tr>
                             <!-- END: loop -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-success" onclick="add_radio()"><i class="fa fa-plus"></i> {LANG.add_answer}</button>
                </div>
                <!-- END: answers_radio -->

                <!-- BEGIN: answers_checkbox -->
                <div id="type_2_wrapper">
                    <h4>{LANG.question_answers}</h4>
                    <table class="table table-bordered">
                        <thead><tr><th width="50" class="text-center">{LANG.is_correct}</th><th>{LANG.answer_content}</th></tr></thead>
                        <tbody id="container_checkbox">
                             <!-- BEGIN: loop -->
                             <tr>
                                 <td class="text-center"><input type="checkbox" name="correct[{ANS.index}]" value="1" {ANS.checked}></td>
                                 <td><input type="text" class="form-control" name="answers[{ANS.index}]" value="{ANS.title}"></td>
                             </tr>
                             <!-- END: loop -->
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-success" onclick="add_checkbox()"><i class="fa fa-plus"></i> {LANG.add_answer}</button>
                </div>
                <!-- END: answers_checkbox -->

                <!-- BEGIN: answers_fill -->
                <div id="type_3_wrapper">
                    <h4>{LANG.fill_keyword}</h4>
                    <p class="help-block">Nhập từ khóa đúng cho từng ô trống theo thứ tự xuất hiện của <code>[[input]]</code>.</p>
                    <div id="container_fill">
                         <!-- BEGIN: loop -->
                         <div class="input-group" style="margin-bottom:5px">
                             <span class="input-group-addon">#{ANS.index}</span>
                             <input type="text" class="form-control" name="answers[]" value="{ANS.title}">
                         </div>
                         <!-- END: loop -->
                    </div>
                    <button type="button" class="btn btn-sm btn-success" onclick="add_fill()"><i class="fa fa-plus"></i> {LANG.add_answer}</button>
                </div>
                <!-- END: answers_fill -->

            </div>
            <!-- Type 4 Essay has no answers to configure -->

            <div class="form-group text-center" style="margin-top:20px">
                <button type="submit" class="btn btn-primary">{LANG.save}</button>
            </div>
        </form>
    </div>
</div>

<script>
    var current_idx = 100; // start high to avoid conflict if editing

    function change_type() {
        var t = $('#q_type').val();
        $('#area_answers > div').hide(); // Hide all wrappers
        $('#hint_fill').hide();

        if (t == 1) {
            if ($('#type_1_wrapper').length == 0) create_wrapper(1);
            $('#type_1_wrapper').show();
        } else if (t == 2) {
            if ($('#type_2_wrapper').length == 0) create_wrapper(2);
            $('#type_2_wrapper').show();
        } else if (t == 3) {
            if ($('#type_3_wrapper').length == 0) create_wrapper(3);
            $('#type_3_wrapper').show();
            $('#hint_fill').show();
        }
        // Type 4: nothing
    }

    function create_wrapper(type) {
        var html = '';
        if (type == 1) {
            html = '<div id="type_1_wrapper"><h4>{LANG.question_answers}</h4><table class="table table-bordered"><thead><tr><th width="50" class="text-center">{LANG.is_correct}</th><th>{LANG.answer_content}</th></tr></thead><tbody id="container_radio"></tbody></table><button type="button" class="btn btn-sm btn-success" onclick="add_radio()"><i class="fa fa-plus"></i> {LANG.add_answer}</button></div>';
            $('#area_answers').append(html);
            // Add default 4 rows
            add_radio(); add_radio(); add_radio(); add_radio();
        } else if (type == 2) {
            html = '<div id="type_2_wrapper"><h4>{LANG.question_answers}</h4><table class="table table-bordered"><thead><tr><th width="50" class="text-center">{LANG.is_correct}</th><th>{LANG.answer_content}</th></tr></thead><tbody id="container_checkbox"></tbody></table><button type="button" class="btn btn-sm btn-success" onclick="add_checkbox()"><i class="fa fa-plus"></i> {LANG.add_answer}</button></div>';
            $('#area_answers').append(html);
            add_checkbox(); add_checkbox(); add_checkbox(); add_checkbox();
        } else if (type == 3) {
            html = '<div id="type_3_wrapper"><h4>{LANG.fill_keyword}</h4><p class="help-block">Nhập từ khóa đúng cho từng ô trống.</p><div id="container_fill"></div><button type="button" class="btn btn-sm btn-success" onclick="add_fill()"><i class="fa fa-plus"></i> {LANG.add_answer}</button></div>';
            $('#area_answers').append(html);
            add_fill();
        }
    }

    function add_radio() {
        current_idx++;
        var html = '<tr><td class="text-center"><input type="radio" name="correct_radio" value="'+current_idx+'"></td><td><input type="text" class="form-control" name="answers['+current_idx+']"></td></tr>';
        $('#container_radio').append(html);
    }

    function add_checkbox() {
        current_idx++;
        var html = '<tr><td class="text-center"><input type="checkbox" name="correct['+current_idx+']" value="1"></td><td><input type="text" class="form-control" name="answers['+current_idx+']"></td></tr>';
        $('#container_checkbox').append(html);
    }

    function add_fill() {
        var html = '<div class="input-group" style="margin-bottom:5px"><span class="input-group-addon">Key</span><input type="text" class="form-control" name="answers[]" placeholder="{LANG.fill_keyword}"></div>';
        $('#container_fill').append(html);
    }

    function nv_del_question(id) {
        if (confirm('{LANG.delete_confirm}')) {
            $.post('{NV_BASE_ADMINURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}', {
                delete: id
            }, function(res) {
                if (res == 'OK') {
                    window.location.reload();
                } else {
                    alert('{GLANG.error_save}');
                }
            });
        }
    }

    // Init state
    $(document).ready(function() {
        if ($('#area_answers > div:visible').length == 0 && $('#q_type').val() != 4) {
             change_type();
        }
    });
</script>
<!-- END: form -->

<!-- END: main -->
