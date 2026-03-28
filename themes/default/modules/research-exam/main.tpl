<!-- BEGIN: main -->
<div class="research-exam-list">
    <h1 class="mb-4">{LANG.exam_list}</h1>

    <!-- BEGIN: list -->
    <div class="row">
        <!-- BEGIN: row -->
        <div class="col-md-12 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <h3 class="card-title"><a href="{ROW.link}">{ROW.title}</a></h3>
                    <p class="card-text text-muted">
                        <i class="fa fa-clock-o"></i> {ROW.time_start} - {ROW.time_end} |
                        <i class="fa fa-hourglass-half"></i> {ROW.duration} phút
                    </p>
                    <p class="card-text">{ROW.description}</p>
                    <p>{ROW.status_text}</p>
                    <a href="{ROW.link}" class="btn {ROW.btn_class}">{LANG.detail}</a>
                </div>
            </div>
        </div>
        <!-- END: row -->
    </div>
    <!-- END: list -->
</div>
<!-- END: main -->
