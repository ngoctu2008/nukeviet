<!-- BEGIN: main -->
<div class="research-exam-test">
    <form id="examForm" action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=save" method="post">
        <input type="hidden" name="exam_id" value="{EXAM.id}">
        <input type="hidden" name="checkss" value="{NV_CHECK_SESSION}">

        <div class="row">
            <!-- Sidebar: Info & Grid -->
            <div class="col-md-6 col-lg-6 mb-4">
                <div class="sticky-sidebar">
                    <div class="card border-primary mb-3">
                        <div class="card-header bg-primary text-white text-center">
                            <div style="font-size: 0.9rem;">{LANG.time_remaining}</div>
                            <div class="font-weight-bold" style="font-size: 1.8rem;" id="countdown_timer">--:--</div>
                            <div style="font-size: 0.9rem;">{LANG.questions_done}: <span id="count_done">0</span>/{EXAM.num_questions_actual}</div>
                        </div>
                        <div class="card-body p-2">
                            <div class="text-center font-weight-bold mb-2">{LANG.list_question}</div>
                            <div class="d-flex flex-wrap justify-content-center" id="question-grid">
                                <!-- BEGIN: question_grid -->
                                <a href="#question_{Q_GRID.id}" id="grid_btn_{Q_GRID.id}" class="btn btn-outline-secondary btn-sm m-1 d-flex align-items-center justify-content-center grid-btn">
                                    {Q_GRID.index}
                                </a>
                                <!-- END: question_grid -->
                            </div>
                        </div>
                        <div class="card-footer p-2">
                             <button type="submit" class="btn btn-primary btn-block w-100 font-weight-bold">{LANG.submit_exam}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content: Questions -->
            <div class="col-md-18 col-lg-18">
                <h2 class="text-primary border-bottom pb-2 mb-4">{EXAM.title}</h2>

                <!-- BEGIN: loop -->
                <div class="card mb-4 question-card shadow-sm" id="question_{Q.id}">
                    <div class="card-header bg-light">
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
                            <input class="form-check-input q-input" type="radio" name="answer[{Q.id}]" id="ans_{ANS.id}" value="{ANS.id}" data-qid="{Q.id}">
                            <label class="form-check-label" for="ans_{ANS.id}">
                                {ANS.title}
                            </label>
                        </div>
                        <!-- END: loop -->
                        <!-- END: type_1 -->

                        <!-- BEGIN: type_2 -->
                        <!-- BEGIN: loop -->
                        <div class="form-check mb-2">
                            <input class="form-check-input q-input" type="checkbox" name="answer[{Q.id}][]" id="ans_{ANS.id}" value="{ANS.id}" data-qid="{Q.id}">
                            <label class="form-check-label" for="ans_{ANS.id}">
                                {ANS.title}
                            </label>
                        </div>
                        <!-- END: loop -->
                        <!-- END: type_2 -->

                        <!-- BEGIN: type_3 -->
                        <!-- Handled via JS listener on text inputs inside content_parsed -->
                        <!-- END: type_3 -->

                        <!-- BEGIN: type_4 -->
                        <textarea class="form-control q-input-text" name="answer[{Q.id}]" rows="5" placeholder="{LANG.essay_placeholder}" data-qid="{Q.id}"></textarea>
                        <!-- END: type_4 -->

                    </div>
                </div>
                <!-- END: loop -->

                <!-- BEGIN: prediction -->
                <div class="card mb-4 border-warning shadow-sm">
                    <div class="card-header bg-warning text-dark">
                        <strong>{LANG.prediction_question}</strong>
                    </div>
                    <div class="card-body">
                        <input type="number" class="form-control" name="prediction" required>
                    </div>
                </div>
                <!-- END: prediction -->

                <div class="alert alert-info">
                    {LANG.note_review}
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .sticky-sidebar {
        position: -webkit-sticky;
        position: sticky;
        top: 20px;
        z-index: 1000;
    }
    .grid-btn {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        font-weight: bold;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #495057;
    }
    .grid-btn:hover {
        background-color: #e2e6ea;
    }
    .btn-answered {
        background-color: #28a745 !important;
        color: white !important;
        border-color: #28a745 !important;
    }
    html {
        scroll-behavior: smooth;
    }
</style>

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

    document.addEventListener("DOMContentLoaded", function() {
        // Handle Radio and Checkbox
        var inputs = document.querySelectorAll('.q-input');
        inputs.forEach(function(input) {
            input.addEventListener('change', function() {
                updateStatus(this.dataset.qid);
            });
        });

        // Handle Essay and Text inputs
        var textInputs = document.querySelectorAll('.q-input-text, input[type="text"]');
        textInputs.forEach(function(input) {
            // For fill-in inputs inside type_3, we need to find the parent question ID
            // Since they are generated inside content, they might not have data-qid.
            // We can find closest .question-card
            input.addEventListener('input', function() {
                var card = this.closest('.question-card');
                if (card) {
                    var qid = card.id.replace('question_', '');
                    updateStatus(qid);
                }
            });
        });

        // Add listener for dynamically generated inputs (Type 3) specifically if they don't have classes yet
        // The PHP replaces [[input]] with inputs having name="answer[qid][index]"
        // We can select them by name attribute pattern if needed, but the closest() check above works if we attach to all inputs
        var allInputs = document.querySelectorAll('#examForm input[type="text"]');
        allInputs.forEach(function(input) {
             input.addEventListener('input', function() {
                var card = this.closest('.question-card');
                if (card) {
                    var qid = card.id.replace('question_', '');
                    updateStatus(qid);
                }
            });
        });
    });

    function updateStatus(qid) {
        // Check if question is answered
        var card = document.getElementById('question_' + qid);
        if (!card) return;

        var isAnswered = false;

        // Check Radio/Checkbox
        var checks = card.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked');
        if (checks.length > 0) isAnswered = true;

        // Check Text inputs (Essay or Fill)
        if (!isAnswered) {
            var texts = card.querySelectorAll('input[type="text"], textarea');
            if (texts.length > 0) {
                 // For fill in blank, all inputs must be filled? Or at least one? Let's say at least one for "active" status
                 // Usually for status tracking, "started" is enough.
                 for (var i = 0; i < texts.length; i++) {
                     if (texts[i].value.trim() !== '') {
                         isAnswered = true; break;
                     }
                 }
            }
        }

        var btn = document.getElementById('grid_btn_' + qid);
        if (isAnswered) {
            if (btn) btn.classList.add('btn-answered');
        } else {
            if (btn) btn.classList.remove('btn-answered');
        }

        updateCount();
    }

    function updateCount() {
        var count = document.querySelectorAll('.btn-answered').length;
        document.getElementById('count_done').innerText = count;
    }
</script>
<!-- END: main -->
