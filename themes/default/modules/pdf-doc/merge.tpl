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

                <!-- File List Container -->
                <div id="file-list-container" class="mt-3 mb-3 d-none">
                     <div class="card">
                        <div class="card-header bg-light">
                            <strong>Selected Files</strong> <span class="badge badge-secondary" id="file-count">0</span>
                        </div>
                        <ul class="list-group list-group-flush" id="file-list-ul" style="max-height: 300px; overflow-y: auto;">
                            <!-- List items will be injected here -->
                        </ul>
                     </div>
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
    var fileInput = document.getElementById('upload_file');
    var fileListContainer = document.getElementById('file-list-container');
    var fileListUl = document.getElementById('file-list-ul');
    var fileCountBadge = document.getElementById('file-count');

    if (fileInput) {
        fileInput.addEventListener('change', function() {
            fileListUl.innerHTML = '';
            var files = this.files;

            if (files.length > 0) {
                fileListContainer.classList.remove('d-none');
                fileCountBadge.textContent = files.length;

                for (var i = 0; i < files.length; i++) {
                    var file = files[i];
                    var li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.innerHTML = `
                        <div>
                            <i class="fa fa-file-pdf-o text-danger mr-2"></i> ${file.name}
                            <small class="text-muted ml-2">(${formatBytes(file.size)})</small>
                        </div>
                        <span class="status-icon text-muted"><i class="fa fa-circle-o"></i></span>
                    `;
                    fileListUl.appendChild(li);
                }
            } else {
                fileListContainer.classList.add('d-none');
            }
        });
    }

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
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
            var statusIcons = document.querySelectorAll('#file-list-ul .status-icon');

            if (progressContainer) progressContainer.classList.remove('d-none');
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', 0);
            }
            if (resultArea) resultArea.innerHTML = '';
            if (btnSubmit) btnSubmit.disabled = true;

            // Set all icons to spinner
            statusIcons.forEach(function(icon) {
                icon.innerHTML = '<i class="fa fa-spinner fa-spin text-primary"></i>';
            });

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
                             // Set all icons to check
                             statusIcons.forEach(function(icon) {
                                icon.innerHTML = '<i class="fa fa-check-circle text-success"></i>';
                             });
                        } else {
                            if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">' + response.mess + '</div>';
                            // Set all icons to warning
                             statusIcons.forEach(function(icon) {
                                icon.innerHTML = '<i class="fa fa-exclamation-triangle text-warning"></i>';
                             });
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
