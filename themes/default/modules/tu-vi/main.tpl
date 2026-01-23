<!-- BEGIN: main -->
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="text-center mb-20" style="margin-bottom: 20px;">
                <a href="{URL_DATE}" class="btn btn-info">Xem Ngày Tốt</a>
                <a href="{URL_COMPATIBILITY}" class="btn btn-success">Xem Tuổi</a>
            </div>
            <h1 class="text-center">Lập Lá Số Tử Vi</h1>
            <div class="well">
                <form action="{ACTION}" method="post" class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Họ tên</label>
                        <div class="col-sm-9">
                            <input type="text" name="fullname" class="form-control" placeholder="Nhập họ tên..." required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Ngày sinh (Dương lịch)</label>
                        <div class="col-sm-3">
                            <select name="day" class="form-control">
                                <!-- BEGIN: day -->
                                <option value="{DAY.val}">{DAY.title}</option>
                                <!-- END: day -->
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="month" class="form-control">
                                <!-- BEGIN: month -->
                                <option value="{MONTH.val}">Tháng {MONTH.title}</option>
                                <!-- END: month -->
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <select name="year" class="form-control">
                                <!-- BEGIN: year -->
                                <option value="{YEAR.val}" {YEAR.selected}>{YEAR.title}</option>
                                <!-- END: year -->
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Giờ sinh</label>
                        <div class="col-sm-4">
                            <select name="hour" class="form-control">
                                <!-- BEGIN: hour -->
                                <option value="{HOUR.val}">{HOUR.title}</option>
                                <!-- END: hour -->
                            </select>
                        </div>
                         <div class="col-sm-4">
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="1" checked> Nam
                            </label>
                            <label class="radio-inline">
                                <input type="radio" name="gender" value="0"> Nữ
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-3 col-sm-9">
                            <button type="submit" name="submit" class="btn btn-primary btn-lg">Lập Lá Số</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- END: main -->
