<!-- BEGIN: main -->
<div class="research-exam-detail">
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h3 class="m-0">{ROW.title}</h3>
        </div>
        <div class="card-body bg-light">
            <div class="mb-3">
                {ROW.description}
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item bg-transparent pl-0"><i class="fa fa-clock-o text-primary"></i> <strong>{LANG.exam_time_start}:</strong> {ROW.time_start}</li>
                <li class="list-group-item bg-transparent pl-0"><i class="fa fa-clock-o text-danger"></i> <strong>{LANG.exam_time_end}:</strong> {ROW.time_end}</li>
                <li class="list-group-item bg-transparent pl-0"><i class="fa fa-hourglass-half text-warning"></i> <strong>{LANG.exam_duration}:</strong> {ROW.duration} {LANG.minutes}</li>
            </ul>
        </div>
    </div>

    <!-- BEGIN: not_started -->
    <div class="alert alert-warning text-center">
        <h4><i class="fa fa-exclamation-triangle"></i> {LANG.exam_not_started}</h4>
    </div>
    <!-- END: not_started -->

    <!-- BEGIN: ended -->
    <div class="alert alert-danger text-center">
        <h4><i class="fa fa-times-circle"></i> {LANG.exam_ended}</h4>
    </div>
    <!-- END: ended -->

    <!-- BEGIN: form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="m-0"><i class="fa fa-user-circle"></i> {LANG.detail}</h4>
        </div>
        <div class="card-body">
            <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" method="post">
                <input type="hidden" name="start_exam" value="1">

                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label font-weight-bold">{LANG.fullname} <span class="text-danger">(*)</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="fullname" value="{FULLNAME}" placeholder="{LANG.fullname}" required>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label font-weight-bold">{LANG.phone} <span class="text-danger">(*)</span></label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="phone" value="{PHONE}" placeholder="{LANG.phone}" required>
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label font-weight-bold">{LANG.address}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="address" placeholder="{LANG.address}">
                    </div>
                </div>

                <div class="form-group row mb-3">
                    <label class="col-md-3 col-form-label font-weight-bold">{LANG.unit} <span class="text-danger">(*)</span></label>
                    <div class="col-md-9">
                        <select class="form-control" name="unit_id" required>
                            <option value="">--- {LANG.select_unit} ---</option>
                            <!-- BEGIN: unit_loop -->
                            <option value="{UNIT.id}">{UNIT.title}</option>
                            <!-- END: unit_loop -->
                        </select>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-lg btn-success pl-5 pr-5 shadow">
                        <i class="fa fa-play-circle"></i> {LANG.start_exam}
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- END: form -->
</div>
<!-- END: main -->
