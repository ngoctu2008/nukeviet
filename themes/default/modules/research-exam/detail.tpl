<!-- BEGIN: main -->
<div class="research-exam-detail">
    <!-- Exam Header & Description -->
    <div class="mb-4">
        <h3 class="text-primary border-bottom pb-2 mb-3" style="color: #337ab7 !important;">{ROW.title}</h3>
        <div class="mb-3 text-muted">
            {ROW.description}
        </div>
    </div>

    <!-- Info Box -->
    <div class="card mb-4 border shadow-sm">
        <div class="card-body p-0">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex align-items-center">
                    <i class="fa fa-clock-o text-primary mr-3" style="font-size: 1.2rem; width: 25px; text-align: center;"></i>
                    <div>
                        <strong>{LANG.exam_time_start}:</strong> {ROW.time_start}
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fa fa-clock-o text-danger mr-3" style="font-size: 1.2rem; width: 25px; text-align: center;"></i>
                    <div>
                        <strong>{LANG.exam_time_end}:</strong> {ROW.time_end}
                    </div>
                </li>
                <li class="list-group-item d-flex align-items-center">
                    <i class="fa fa-hourglass-half text-warning mr-3" style="font-size: 1.2rem; width: 25px; text-align: center;"></i>
                    <div>
                        <strong>{LANG.exam_duration}:</strong> {ROW.duration} {LANG.minutes}
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <!-- BEGIN: not_started -->
    <div class="alert alert-warning text-center shadow-sm">
        <h4><i class="fa fa-exclamation-triangle"></i> {LANG.exam_not_started}</h4>
    </div>
    <!-- END: not_started -->

    <!-- BEGIN: ended -->
    <div class="alert alert-danger text-center shadow-sm">
        <h4><i class="fa fa-times-circle"></i> {LANG.exam_ended}</h4>
    </div>
    <!-- END: ended -->

    <!-- BEGIN: form -->
    <div class="card shadow border-primary">
        <div class="card-header text-white" style="background-color: #337ab7;">
            <h5 class="m-0"><i class="fa fa-user-circle mr-2"></i> {LANG.detail}</h5>
        </div>
        <div class="card-body">
            <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" method="post">
                <input type="hidden" name="start_exam" value="1">

                <div class="form-group row align-items-center mb-3">
                    <label class="col-md-6 col-lg-5 col-form-label font-weight-bold">
                        {LANG.fullname} <span class="text-danger">(*)</span>
                    </label>
                    <div class="col-md-18 col-lg-19">
                        <input type="text" class="form-control" name="fullname" value="{FULLNAME}" placeholder="{LANG.fullname}" required>
                    </div>
                </div>

                <div class="form-group row align-items-center mb-3">
                    <label class="col-md-6 col-lg-5 col-form-label font-weight-bold">
                        {LANG.phone} <span class="text-danger">(*)</span>
                    </label>
                    <div class="col-md-18 col-lg-19">
                        <input type="text" class="form-control" name="phone" value="{PHONE}" placeholder="{LANG.phone}" required>
                    </div>
                </div>

                <div class="form-group row align-items-center mb-3">
                    <label class="col-md-6 col-lg-5 col-form-label font-weight-bold">
                        {LANG.address}
                    </label>
                    <div class="col-md-18 col-lg-19">
                        <input type="text" class="form-control" name="address" placeholder="{LANG.address}">
                    </div>
                </div>

                <div class="form-group row align-items-center mb-3">
                    <label class="col-md-6 col-lg-5 col-form-label font-weight-bold">
                        {LANG.unit} <span class="text-danger">(*)</span>
                    </label>
                    <div class="col-md-18 col-lg-19">
                        <select class="form-control" name="unit_id" required>
                            <option value="">--- {LANG.select_unit} ---</option>
                            <!-- BEGIN: unit_loop -->
                            <option value="{UNIT.id}">{UNIT.title}</option>
                            <!-- END: unit_loop -->
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-success pl-5 pr-5 shadow font-weight-bold" style="background-color: #5cb85c; border-color: #4cae4c;">
                        <i class="fa fa-play-circle mr-2"></i> {LANG.start_exam}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- END: form -->
</div>

<style>
    .research-exam-detail .card {
        border-radius: 4px;
    }
    .research-exam-detail .card-header {
        border-radius: 3px 3px 0 0;
    }
    /* Fix for FA icons if needed */
    .fa { display: inline-block; }
</style>
<!-- END: main -->
