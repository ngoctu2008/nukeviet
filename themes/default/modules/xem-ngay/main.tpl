<!-- BEGIN: main -->
<div class="xem-ngay-main">
    <h1 class="text-center mb-4">{LANG.main_title}</h1>
    <div class="row display-flex">
        <div class="col-xs-24 col-sm-12 col-md-12 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-primary">{LANG.funeral_title}</h3>
                    <p class="card-text text-muted">Xem ngày giờ tẩm liệm, động quan, hạ huyệt, tránh Trùng Tang.</p>
                    <a href="{URL_FUNERAL}" class="btn btn-primary btn-lg mt-3">{LANG.submit}</a>
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-danger">{LANG.wedding_title}</h3>
                    <p class="card-text text-muted">Xem ngày cưới hỏi, nạp tài, rước dâu.</p>
                    <a href="{URL_WEDDING}" class="btn btn-danger btn-lg mt-3">{LANG.submit}</a>
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-success">{LANG.construction_title}</h3>
                    <p class="card-text text-muted">Xem ngày động thổ, làm nhà, tránh Kim Lâu, Hoang Ốc.</p>
                    <a href="{URL_CONSTRUCTION}" class="btn btn-success btn-lg mt-3">{LANG.submit}</a>
                </div>
            </div>
        </div>
        <div class="col-xs-24 col-sm-12 col-md-12 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-warning">{LANG.grand_opening_title}</h3>
                    <p class="card-text text-muted">Xem ngày khai trương, cầu tài lộc.</p>
                    <a href="{URL_GRAND_OPENING}" class="btn btn-warning btn-lg mt-3">{LANG.submit}</a>
                </div>
            </div>
        </div>
        <!-- BEGIN: event_loop -->
        <div class="col-xs-24 col-sm-12 col-md-12 mb-3">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center">
                    <h3 class="card-title text-info">{EVENT.title}</h3>
                    <p class="card-text text-muted">{EVENT.description}</p>
                    <a href="{EVENT.url}" class="btn btn-info btn-lg mt-3">{LANG.submit}</a>
                </div>
            </div>
        </div>
        <!-- END: event_loop -->
    </div>
</div>
<!-- END: main -->
