/**
 * PDF-Doc Module JS
 * Handles file selection, drag & drop, and AJAX uploads.
 */

var PdfDoc = (function() {

    var init = function() {
        var form = document.getElementById('pdf-doc-form');
        if (!form) return;

        var fileInput = document.getElementById('upload_file');
        var dropZone = document.querySelector('.pdf-upload-zone');
        var fileListContainer = document.getElementById('file-list-container');
        var fileListUl = document.getElementById('file-list-ul');
        var fileCountBadge = document.getElementById('file-count');
        var fileNameDisplay = document.getElementById('file-name-display');

        // Drag & Drop Events
        if (dropZone) {
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, preventDefaults, false);
            });

            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, unhighlight, false);
            });

            dropZone.addEventListener('drop', handleDrop, false);
        }

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        function highlight(e) {
            dropZone.classList.add('dragover');
        }

        function unhighlight(e) {
            dropZone.classList.remove('dragover');
        }

        function handleDrop(e) {
            var dt = e.dataTransfer;
            var files = dt.files;
            fileInput.files = files; // Assign dropped files to input
            handleFiles(files);
        }

        // File Input Change Event
        if (fileInput) {
            fileInput.addEventListener('change', function() {
                handleFiles(this.files);
            });
        }

        function handleFiles(files) {
            // Logic for Merge (List View)
            if (fileListUl) {
                fileListUl.innerHTML = '';
                if (files.length > 0) {
                    fileListContainer.classList.remove('hidden');
                    if(fileCountBadge) fileCountBadge.textContent = files.length;

                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        var li = document.createElement('li');
                        li.className = 'list-group-item d-flex justify-content-between align-items-center';
                        li.innerHTML = '<div>' +
                                '<i class="fa fa-file-pdf-o text-danger pdf-file-icon"></i> ' + file.name +
                                '<small class="text-muted ml-2">(' + formatBytes(file.size) + ')</small>' +
                            '</div>' +
                            '<span class="status-icon text-muted"><i class="fa fa-circle-o"></i></span>';
                        fileListUl.appendChild(li);
                    }
                } else {
                    fileListContainer.classList.add('hidden');
                }
            }
            // Logic for Single File (Display Name)
            else if (fileNameDisplay) {
                if (files.length > 0) {
                    fileNameDisplay.innerHTML = '<i class="fa fa-check text-success"></i> ' + files[0].name + ' (' + formatBytes(files[0].size) + ')';
                } else {
                    fileNameDisplay.textContent = '';
                }
            }
        }

        // Form Submit
        form.onsubmit = function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            formData.append('ajax', 1);

            var progressBar = document.querySelector('#upload-progress .progress-bar');
            var progressContainer = document.getElementById('upload-progress');
            var resultArea = document.getElementById('result-area');
            var btnSubmit = document.getElementById('btn-submit');
            var statusIcons = document.querySelectorAll('#file-list-ul .status-icon');

            if (progressContainer) progressContainer.classList.remove('hidden');
            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', 0);
            }
            if (resultArea) resultArea.innerHTML = '';
            if (btnSubmit) btnSubmit.disabled = true;

            if (statusIcons.length > 0) {
                statusIcons.forEach(function(icon) {
                    icon.innerHTML = '<i class="fa fa-spinner fa-spin text-primary"></i>';
                });
            }

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
                             if (resultArea) resultArea.innerHTML = '<div class="alert alert-success">' + response.mess + '<br><a href="' + response.link + '" class="btn btn-primary btn-lg-custom mt-2"><i class="fa fa-download"></i> ' + (window.lang_download || 'Download') + '</a></div>';

                             if (statusIcons.length > 0) {
                                statusIcons.forEach(function(icon) {
                                    icon.innerHTML = '<i class="fa fa-check-circle text-success"></i>';
                                });
                             }
                        } else {
                            if (resultArea) resultArea.innerHTML = '<div class="alert alert-danger">' + response.mess + '</div>';

                            if (statusIcons.length > 0) {
                                statusIcons.forEach(function(icon) {
                                    icon.innerHTML = '<i class="fa fa-exclamation-triangle text-warning"></i>';
                                });
                            }
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
    };

    function formatBytes(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    return {
        init: init
    };

})();

document.addEventListener('DOMContentLoaded', function() {
    PdfDoc.init();
});
