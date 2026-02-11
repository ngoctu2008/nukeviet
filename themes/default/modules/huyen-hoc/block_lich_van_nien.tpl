<!-- BEGIN: main -->
<div class="card shadow-sm border-primary mb-3 text-center lich-vannien shadow-none border-1">
    <div class="card-header bg-primary text-white py-1">
        <span class="fw-bold small text-uppercase">Tháng {DATA.lunar_month} Âm Lịch</span>
    </div>
    <div class="card-body p-3">
        <div class="display-4 fw-bold text-primary mb-0" style="font-size: 3rem; font-weight: bold;">{DATA.lunar_day}</div>
        <div class="text-muted small mb-2">Ngày {DATA.can_chi_day}</div>

        <hr class="my-2">

        <div class="row g-0 align-items-center">
            <div class="col-xs-12 col-12 border-right border-end">
                <p class="mb-0 small text-muted text-uppercase">Dương lịch</p>
                <p class="fw-bold mb-0 text-dark">{DATA.solar}</p>
            </div>
            <div class="col-xs-12 col-12">
                <p class="mb-0 small text-muted text-uppercase">Năm</p>
                <p class="fw-bold mb-0 text-dark">{DATA.can_chi_year}</p>
            </div>
        </div>

        <!-- BEGIN: zodiac -->
        <div class="mt-2 pt-2 border-top">
            <p class="mb-1 small fw-bold text-danger"><i class="fa fa-clock-o me-1"></i> Giờ Hoàng Đạo</p>
            <marquee behavior="scroll" direction="left" scrollamount="3" class="small text-secondary">
                Tý (23-1), Dần (3-5), Mão (5-7), Ngọ (11-13), Mùi (13-15), Dậu (17-19)
            </marquee>
        </div>
        <!-- END: zodiac -->
    </div>
    <div class="card-footer bg-light p-1">
        <a href="{MODULE_URL}" class="btn btn-link btn-sm p-0 text-decoration-none small">Xem chi tiết</a>
    </div>
</div>
<!-- END: main -->
