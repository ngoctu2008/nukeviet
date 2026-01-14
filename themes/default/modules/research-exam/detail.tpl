<!-- BEGIN: main -->
<div class="research-exam-detail">
    <h2 class="mb-3">{ROW.title}</h2>

    <div class="alert alert-info">
        {ROW.description}
        <hr>
        <ul class="list-unstyled mb-0">
            <li><strong>{LANG.exam_time_start}:</strong> {ROW.time_start}</li>
            <li><strong>{LANG.exam_time_end}:</strong> {ROW.time_end}</li>
            <li><strong>{LANG.exam_duration}:</strong> {ROW.duration} phút</li>
        </ul>
    </div>

    <!-- BEGIN: not_started -->
    <div class="alert alert-warning">{LANG.exam_not_started}</div>
    <!-- END: not_started -->

    <!-- BEGIN: ended -->
    <div class="alert alert-danger">{LANG.exam_ended}</div>
    <!-- END: ended -->

    <!-- BEGIN: form -->
    <div class="card">
        <div class="card-header bg-primary text-white">
            {LANG.detail}
        </div>
        <div class="card-body">
            <form action="{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}={OP}&id={ROW.id}" method="post">
                <input type="hidden" name="start_exam" value="1">
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{LANG.fullname} (*)</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="fullname" value="{FULLNAME}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{LANG.phone} (*)</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="phone" value="{PHONE}" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{LANG.address}</label>
                    <div class="col-md-9">
                        <input type="text" class="form-control" name="address">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-md-3 col-form-label">{LANG.unit} (*)</label>
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
                    <button type="submit" class="btn btn-lg btn-success">{LANG.start_exam} <i class="fa fa-arrow-right"></i></button>
                </div>
            </form>
        </div>
    </div>
    <!-- END: form -->
</div>
<!-- END: main -->
