<!-- BEGIN: main -->
<div class="pdf-doc-tool">
    <div class="row">
        <div class="col-md-24">
             <h2 class="text-center mb-4">{LANG.upload}</h2>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-12">
            <form id="pdf-doc-form" action="{FORM_ACTION}" method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <label for="upload_file">{LANG.select_file}</label>
                    <input type="file" class="form-control-file" id="upload_file" name="upload_file" accept="{ACCEPT_EXT}" required>
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
<script>
(function() {
    var form = document.getElementById('pdf-doc-form');
    if (form) {
        form.onsubmit = function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            formData.append('ajax', 1);

            var progressBar = document.querySelector('#upload-progress .progress-bar');
            var progressContainer = document.getElementById('upload-progress');
            var resultArea = document.getElementById('result-area');
            var btnSubmit = document.getElementById('btn-submit');

            if (progressContainer) progressContainer.classList.remove('d-none');
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
