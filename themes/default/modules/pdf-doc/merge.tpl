<!-- BEGIN: main -->
<div class="pdf-doc-tool">
    <div class="row">
        <div class="col-md-24">
             <h2 class="text-center mb-4">{LANG.merge}</h2>
             <p class="text-center text-muted">{LANG.merge_guide}</p>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="pdf-doc-form" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="upload_file">{LANG.select_file}</label>
                    <input type="file" class="form-control-file" id="upload_file" name="upload_file[]" accept="{ACCEPT_EXT}" multiple required>
                </div>

                <div class="progress mt-3 d-none" id="upload-progress">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>

                <div id="result-area" class="mt-3 text-center"></div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-success" id="btn-submit">{LANG.upload}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{NV_BASE_SITEURL}themes/default/js/pdf-doc.js"></script>
<!-- END: main -->
