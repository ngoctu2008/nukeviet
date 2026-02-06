<!-- BEGIN: main -->
<div class="xem-tuoi-container p-4 border rounded shadow-sm bg-white">
    <h2 class="text-center text-primary mb-4">{LANG.main_title}</h2>
    <form action="{ACTION}" method="post">
        <div class="row g-3">
            <!-- Chủ sự -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-info text-white">
                        <i class="bi bi-person-fill"></i> {LANG.info_1}
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{LANG.year_1}</label>
                            <select name="year_1" class="form-select" required>
                                <option value="">--- Chọn năm sinh ---</option>
                                <!-- BEGIN: loop_year1 -->
                                <option value="{YEAR}">{YEAR}</option>
                                <!-- END: loop_year1 -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{LANG.gender}</label>
                            <select name="gender_1" class="form-select">
                                <option value="1">{LANG.male}</option>
                                <option value="0">{LANG.female}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Đối tác -->
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header bg-success text-white">
                        <i class="bi bi-people-fill"></i> {LANG.info_2}
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">{LANG.year_2}</label>
                            <select name="year_2" class="form-select" required>
                                <option value="">--- Chọn năm sinh ---</option>
                                <!-- BEGIN: loop_year2 -->
                                <option value="{YEAR}">{YEAR}</option>
                                <!-- END: loop_year2 -->
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">{LANG.gender}</label>
                            <select name="gender_2" class="form-select">
                                <option value="0">{LANG.female}</option>
                                <option value="1">{LANG.male}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="mb-3">
                    <label class="form-label fw-bold">{LANG.purpose}</label>
                    <select name="purpose" class="form-select">
                        <option value="business">{LANG.purpose_business}</option>
                        <option value="marriage">{LANG.purpose_marriage}</option>
                        <option value="friends">{LANG.purpose_friends}</option>
                    </select>
                </div>
            </div>

            <div class="col-12 text-center">
                <button type="submit" class="btn btn-primary btn-lg px-5">
                    <i class="bi bi-search"></i> {LANG.submit}
                </button>
            </div>
        </div>
    </form>
</div>
<!-- END: main -->
