<!-- BEGIN: main -->
<div class="pdf-doc-tool">
    <div class="row">
        <div class="col-md-24 text-center">
             <h2>{LANG.merge}</h2>
             <p class="text-muted">{LANG.merge_guide}</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-16 col-md-offset-4 col-xs-24">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="pdf-doc-form" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">

                        <div class="form-group">
                            <label class="pdf-upload-zone btn-block" for="upload_file">
                                <input type="file" class="form-control-file" id="upload_file" name="upload_file[]" accept="{ACCEPT_EXT}" multiple required>
                                <div class="pdf-upload-icon"><i class="fa fa-cloud-upload"></i></div>
                                <div class="pdf-upload-text">{LANG.select_file}</div>
                                <div class="pdf-upload-subtext">or Drag & Drop files here</div>
                            </label>
                        </div>

                        <!-- File List Container -->
                        <div id="file-list-container" class="hidden">
                             <div class="pdf-file-list">
                                <ul class="list-group" id="file-list-ul" style="max-height: 300px; overflow-y: auto;">
                                    <!-- List items will be injected here -->
                                </ul>
                             </div>
                        </div>

                        <div class="progress hidden" id="upload-progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>

                        <div id="result-area" class="text-center"></div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg btn-lg-custom" id="btn-submit">{LANG.upload}</button>
                        </div>
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
