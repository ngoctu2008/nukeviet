<!-- BEGIN: main -->
<div class="tu-vi-form">
    <h2 class="text-center">{LANG.enter_info}</h2>
    <form action="{ACTION}" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.fullname}</label>
            <div class="col-sm-18">
                <input type="text" name="fullname" class="form-control" required />
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.birth_date}</label>
            <div class="col-sm-18 form-inline">
                <select name="day" class="form-control">
                    <!-- BEGIN: day -->
                    <option value="{DAY.value}">{DAY.title}</option>
                    <!-- END: day -->
                </select> /
                <select name="month" class="form-control">
                    <!-- BEGIN: month -->
                    <option value="{MONTH.value}">{MONTH.title}</option>
                    <!-- END: month -->
                </select> /
                <select name="year" class="form-control">
                    <!-- BEGIN: year -->
                    <option value="{YEAR.value}" {YEAR.selected}>{YEAR.title}</option>
                    <!-- END: year -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.birth_time}</label>
            <div class="col-sm-18">
                <select name="hour" class="form-control">
                    <!-- BEGIN: hour -->
                    <option value="{HOUR.value}">{HOUR.title}</option>
                    <!-- END: hour -->
                </select>
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-6 control-label">{LANG.gender}</label>
            <div class="col-sm-18">
                <label class="radio-inline"><input type="radio" name="gender" value="1" checked> {LANG.male}</label>
                <label class="radio-inline"><input type="radio" name="gender" value="0"> {LANG.female}</label>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-offset-6 col-sm-18">
                <button type="submit" class="btn btn-primary">{LANG.submit_view}</button>
            </div>
        </div>
    </form>
</div>
<!-- END: main -->
