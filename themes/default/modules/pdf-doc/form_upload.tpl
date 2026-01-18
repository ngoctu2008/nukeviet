<!-- BEGIN: main -->
<div class="pdf-doc-tool">
    <div class="row">
        <div class="col-md-24 text-center">
             <h2>{LANG.upload}</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-16 col-md-offset-4 col-xs-24">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form id="pdf-doc-form" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
                        <div class="form-group text-center">
                            <label for="upload_file" class="btn btn-primary btn-lg">
                                <i class="fa fa-cloud-upload"></i> {LANG.select_file}
                                <input type="file" class="form-control-file" id="upload_file" name="upload_file" accept="{ACCEPT_EXT}" required style="display: none;">
                            </label>
                            <div id="file-name-display" class="help-block"></div>
                        </div>

                        <div class="progress mt-3 hidden" id="upload-progress">
                            <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                        </div>

                        <div id="result-area" class="mt-3 text-center"></div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-success btn-lg" id="btn-submit">{LANG.upload}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
(function() {
    var form = document.getElementById('pdf-doc-form');
    var fileInput = document.getElementById('upload_file');
    var fileNameDisplay = document.getElementById('file-name-display');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            if (this.files && this.files.length > 0) {
                fileNameDisplay.textContent = this.files[0].name;
            } else {
                fileNameDisplay.textContent = '';
            }
        });
    }

    if (form) {
        form.onsubmit = function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            formData.append('ajax', 1);

            var progressBar = document.querySelector('#upload-progress .progress-bar');
            var progressContainer = document.getElementById('upload-progress');
            var resultArea = document.getElementById('result-area');
            var btnSubmit = document.getElementById('btn-submit');

            if (progressContainer) progressContainer.classList.remove('hidden');
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', 0);
            }
            if (resultArea) resultArea.innerHTML = '';
            if (btnSubmit) btnSubmit.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable && progressBar) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressBar.setAttribute('aria-valuenow', percentComplete);
                }
            };

            xhr.onload = function() {
                if (btnSubmit) btnSubmit.disabled = false;
                if (xhr.status == 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status == 'ok') {
                             if (resultArea) resultArea.innerHTML = '<div class="alert alert-success">' + response.mess + '<br><a href="' + response.link + '" class="btn btn-primary mt-2">{LANG.download}</a></div>';
                        } else {
                            if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">' + response.mess + '</div>';
                        }
                    } catch (e) {
                        if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">Error parsing response</div>';
                        console.error(xhr.responseText);
                    }
                } else {
                    if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">Upload failed. Status: ' + xhr.status + '</div>';
                }
            };

            xhr.onerror = function() {
                if (btnSubmit) btnSubmit.disabled = false;
                if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">Network error.</div>';
            };

            xhr.send(formData);
        };
    } else {
        console.error('Form pdf-doc-form not found');
    }
})();
</script>
<!-- END: main -->
