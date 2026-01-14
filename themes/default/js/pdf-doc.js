document.addEventListener('DOMContentLoaded', function() {
    var form = document.getElementById('pdf-doc-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            formData.append('ajax', 1);

            var progressBar = document.querySelector('#upload-progress .progress-bar');
            var progressContainer = document.getElementById('upload-progress');
            var resultArea = document.getElementById('result-area');
            var btnSubmit = document.getElementById('btn-submit');

            progressContainer.classList.remove('d-none');
            progressBar.style.width = '0%';
            progressBar.setAttribute('aria-valuenow', 0);
            resultArea.innerHTML = '';
            btnSubmit.disabled = true;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', form.action, true);

            xhr.upload.onprogress = function(e) {
                if (e.lengthComputable) {
                    var percentComplete = (e.loaded / e.total) * 100;
                    progressBar.style.width = percentComplete + '%';
                    progressBar.setAttribute('aria-valuenow', percentComplete);
                }
            };

            xhr.onload = function() {
                if (xhr.status == 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status == 'ok') {
                             resultArea.innerHTML = '<div class="alert alert-success">' + response.mess + '<br><a href="' + response.link + '" class="btn btn-primary mt-2">Download File</a></div>';
                        } else {
                            resultArea.innerHTML = '<div class="alert alert-danger">' + response.mess + '</div>';
                        }
                    } catch (e) {
                        resultArea.innerHTML = '<div class="alert alert-danger">Error parsing response</div>';
                        console.error(xhr.responseText);
                    }
                } else {
                    resultArea.innerHTML = '<div class="alert alert-danger">Upload failed.</div>';
                }
                btnSubmit.disabled = false;
            };

            xhr.onerror = function() {
                resultArea.innerHTML = '<div class="alert alert-danger">Network error.</div>';
                btnSubmit.disabled = false;
            };

            xhr.send(formData);
        });
    }
});
