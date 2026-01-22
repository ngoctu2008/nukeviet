<!-- BEGIN: main -->
<div class="xem-ngay-funeral">
    <h2 class="text-center mb-4">{LANG.funeral_title}</h2>

    <form action="{ACTION_URL}" method="post" class="mb-5">
        <div class="panel panel-primary">
            <div class="panel-heading">{LANG.deceased_info}</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">{LANG.birth_year} (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="deceased_year" class="form-control" value="{DATA.deceased_year}" required placeholder="VD: 1952">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">{LANG.gender} (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <select name="gender" class="form-control">
                            <option value="1" {SELECTED_MALE}>{LANG.male}</option>
                            <option value="0" {SELECTED_FEMALE}>{LANG.female}</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">{LANG.death_time} (*)</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="datetime-local" name="death_time" class="form-control" value="{DATA.death_time}" required>
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-success">
            <div class="panel-heading">{LANG.chief_mourner}</div>
            <div class="panel-body">
                <div class="form-group row">
                    <label class="col-sm-6 col-md-6 control-label">{LANG.birth_year}</label>
                    <div class="col-sm-18 col-md-18">
                        <input type="number" name="chief_year" class="form-control" value="{DATA.chief_year}" placeholder="VD: 1975">
                    </div>
                </div>
            </div>
        </div>

        <div class="panel panel-info">
            <div class="panel-heading">{LANG.relatives}</div>
            <div class="panel-body">
                <div class="form-group">
                    <label>{LANG.relatives} (Năm sinh, cách nhau bằng dấu phẩy)</label>
                    <input type="text" name="relatives" class="form-control" value="{DATA.relatives_str}" placeholder="1980, 1990, 2000...">
                </div>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" name="submit" value="1" class="btn btn-primary btn-lg">{LANG.submit}</button>
        </div>
    </form>

    <!-- BEGIN: result -->
    <div id="result-section">
        <h3>{LANG.check_result}</h3>

        <!-- BEGIN: trung_tang -->
        <div class="panel panel-danger" style="border-color: #d9534f;">
            <div class="panel-heading" style="background-color: #d9534f; color: white;">{LANG.trung_tang_status}</div>
            <div class="panel-body">
                <ul>
                    <li><strong>Tuổi ({AGE_CHI}):</strong> <span class="{AGE_CLASS}">{AGE_STATUS}</span></li>
                    <li><strong>Tháng ({MONTH_CHI}):</strong> <span class="{MONTH_CLASS}">{MONTH_STATUS}</span></li>
                    <li><strong>Ngày ({DAY_CHI}):</strong> <span class="{DAY_CLASS}">{DAY_STATUS}</span></li>
                    <li><strong>Giờ ({HOUR_CHI}):</strong> <span class="{HOUR_CLASS}">{HOUR_STATUS}</span></li>
                </ul>
            </div>
        </div>
        <!-- END: trung_tang -->

        <h4>{LANG.date_list}</h4>
        <div class="table-responsive xemngay-result-table">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{LANG.date}</th>
                        <th>{LANG.lunar_date}</th>
                        <th>{LANG.can_chi}</th>
                        <th>{LANG.hoang_dao}</th>
                        <th>{LANG.truc} / {LANG.sao}</th>
                        <th>{LANG.score}</th>
                        <th>{LANG.warnings}</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- BEGIN: date_row -->
                    <tr>
                        <td>{ROW.date}</td>
                        <td>{ROW.lunar_date}</td>
                        <td>{ROW.day_can_chi}</td>
                        <td>{ROW.hoang_dao}</td>
                        <td>{ROW.truc} <br> {ROW.sao}</td>
                        <td><span class="score-{ROW.score_class}">{ROW.score}</span></td>
                        <td class="warning-text">
                            <!-- BEGIN: warning -->
                            <div>- {WARNING}</div>
                            <!-- END: warning -->
                        </td>
                    </tr>
                    <!-- END: date_row -->
                </tbody>
            </table>
        </div>
    </div>
    <!-- END: result -->
</div>
<!-- END: main -->
