<!-- BEGIN: main -->
<div class="xem-tuoi-result p-4 border rounded shadow-sm bg-white">
    <h2 class="text-center text-success mb-4">{LANG.result_title}</h2>

    <div class="row text-center mb-5">
        <div class="col-md-5">
            <div class="card border-info mb-3">
                <div class="card-header bg-info text-white">{LANG.info_1}</div>
                <div class="card-body">
                    <h4>{Y1} ({G1})</h4>
                    <p class="mb-1">Can Chi: <strong>{RESULT.info1.can_chi}</strong></p>
                    <p class="mb-1">Ngũ Hành: <strong>{RESULT.info1.ngu_hanh}</strong></p>
                    <p class="mb-0">Cung Phi: <strong>{RESULT.info1.cung_phi}</strong></p>
                </div>
            </div>
        </div>
        <div class="col-md-2 align-self-center">
            <h1 class="text-muted"><i class="bi bi-arrow-left-right"></i></h1>
        </div>
        <div class="col-md-5">
            <div class="card border-success mb-3">
                <div class="card-header bg-success text-white">{LANG.info_2}</div>
                <div class="card-body">
                    <h4>{Y2} ({G2})</h4>
                    <p class="mb-1">Can Chi: <strong>{RESULT.info2.can_chi}</strong></p>
                    <p class="mb-1">Ngũ Hành: <strong>{RESULT.info2.ngu_hanh}</strong></p>
                    <p class="mb-0">Cung Phi: <strong>{RESULT.info2.cung_phi}</strong></p>
                </div>
            </div>
        </div>
    </div>

    <div class="text-center mb-4">
        <div class="display-1 fw-bold text-danger">{RESULT.total}/10</div>
        <p class="lead">{LANG.score_total}</p>
    </div>

    <div class="row justify-content-center mb-4">
        <div class="col-md-8">
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Tiêu chí</th>
                        <th class="text-center">Điểm số</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{LANG.detail_can}</td>
                        <td class="text-center fw-bold">{SCORE_CAN}/2</td>
                    </tr>
                    <tr>
                        <td>{LANG.detail_chi}</td>
                        <td class="text-center fw-bold">{SCORE_CHI}/2</td>
                    </tr>
                    <tr>
                        <td>{LANG.detail_nguhanh}</td>
                        <td class="text-center fw-bold">{SCORE_NGUHANH}/3</td>
                    </tr>
                    <tr>
                        <td>{LANG.detail_cungphi}</td>
                        <td class="text-center fw-bold">{SCORE_CUNGPHI}/3</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="alert alert-warning" role="alert">
        <h4 class="alert-heading"><i class="bi bi-lightbulb"></i> {LANG.advice}:</h4>
        <hr>
        <div class="mb-0">{ADVICE}</div>
    </div>

    <div class="text-center mt-4">
        <a href="{BACK_LINK}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Tra cứu lại</a>
    </div>
</div>
<!-- END: main -->
