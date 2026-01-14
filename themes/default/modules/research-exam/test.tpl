<!-- BEGIN: main -->
<div class="research-exam-test">
    <div class="sticky-top bg-white border-bottom p-3 mb-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="m-0 text-primary">{EXAM.title}</h4>
            <div class="text-danger font-weight-bold" style="font-size: 1.2rem;">
                <i class="fa fa-clock-o"></i> <span id="countdown_timer">--:--</span>
            </div>
        </div>
    </div>

    <form id="examForm" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=save" method="post">
        <input type="hidden" name="exam_id" value="{EXAM.id}">
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}">

        <!-- BEGIN: loop -->
        <div class="card mb-4 question-card" id="question_{Q.id}">
            <div class="card-header">
                <strong>{LANG.question_title} {Q.index}:</strong>
                <!-- BEGIN: type_3 -->
                {Q_PARSED.content_parsed}
                <!-- END: type_3 -->
                <!-- BEGIN: default_title -->
                {Q.title}
                <!-- END: default_title -->
            </div>
            <div class="card-body">

                <!-- BEGIN: type_1 -->
                <!-- BEGIN: loop -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" name="answer[{Q.id}]" id="ans_{ANS.id}" value="{ANS.id}">
                    <label class="form-check-label" for="ans_{ANS.id}">
                        {ANS.title}
                    </label>
                </div>
                <!-- END: loop -->
                <!-- END: type_1 -->

                <!-- BEGIN: type_2 -->
                <!-- BEGIN: loop -->
                <div class="form-check mb-2">
                    <input class="form-check-input" type="checkbox" name="answer[{Q.id}][]" id="ans_{ANS.id}" value="{ANS.id}">
                    <label class="form-check-label" for="ans_{ANS.id}">
                        {ANS.title}
                    </label>
                </div>
                <!-- END: loop -->
                <!-- END: type_2 -->

                <!-- BEGIN: type_4 -->
                <textarea class="form-control" name="answer[{Q.id}]" rows="5" placeholder="{LANG.essay_placeholder}"></textarea>
                <!-- END: type_4 -->

            </div>
        </div>
        <!-- END: loop -->

        <!-- BEGIN: prediction -->
        <div class="card mb-4 border-warning">
            <div class="card-header bg-warning text-dark">
                <strong>{LANG.prediction_question}</strong>
            </div>
            <div class="card-body">
                <input type="number" class="form-control" name="prediction" required>
            </div>
        </div>
        <!-- END: prediction -->

        <div class="text-center mb-5">
            <button type="submit" class="btn btn-lg btn-primary pl-5 pr-5">{LANG.submit_exam}</button>
        </div>
    </form>
</div>

<script>
    var timeLeft = {REMAINING_SECONDS};
    var timerId = setInterval(countdown, 1000);

    function countdown() {
        if (timeLeft <= 0) {
            clearTimeout(timerId);
            document.getElementById("countdown_timer").innerHTML = "00:00";
            alert('{LANG.time_out}');
            document.getElementById("examForm").submit();
        } else {
            var minutes = Math.floor(timeLeft / 60);
            var seconds = timeLeft % 60;
            if (minutes < 10) minutes = "0" + minutes;
            if (seconds < 10) seconds = "0" + seconds;
            document.getElementById("countdown_timer").innerHTML = minutes + ":" + seconds;
            timeLeft--;
        }
    }
</script>
<!-- END: main -->
