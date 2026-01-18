<!-- BEGIN: main -->
<div class="pdf-doc-tool">
    <div class="row">
        <div class="col-md-24 text-center">
             <h2>{LANG.upload}</h2>
        </div>
    </div>

    <!-- Stepper -->
    <div class="pdf-stepper">
        <div class="pdf-step active" id="step-1">
            <div class="pdf-step-circle">1</div>
            <div class="pdf-step-text">Select</div>
        </div>
        <div class="pdf-step" id="step-2">
            <div class="pdf-step-circle">2</div>
            <div class="pdf-step-text">Process</div>
        </div>
        <div class="pdf-step" id="step-3">
            <div class="pdf-step-circle">3</div>
            <div class="pdf-step-text">Result</div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-16 col-md-offset-4 col-xs-24">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="pdf-doc-form" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">

                        <div id="step-content-1">
                            <div class="form-group">
                                <label class="pdf-upload-zone btn-block" for="upload_file">
                                    <input type="file" class="form-control-file" id="upload_file" name="upload_file" accept="{ACCEPT_EXT}" required>
                                    <div class="pdf-upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                    <div class="pdf-upload-text">{LANG.select_file}</div>
                                    <div class="pdf-upload-subtext">or Drag & Drop file here</div>
                                </label>
                                <div id="file-name-display" class="help-block text-center mt-2" style="font-size: 1.1em;"></div>
                            </div>

                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success btn-lg btn-lg-custom hidden" id="btn-submit">{LANG.upload}</button>
                            </div>
                        </div>

                        <div id="step-content-2" class="hidden text-center">
                            <h3>{LANG.processing}</h3>
                            <div class="progress" id="upload-progress">
                                <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                            </div>
                        </div>

                        <div id="result-area" class="text-center hidden"></div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    var lang_download = "{LANG.download}";
</script>
<!-- END: main -->
