/**
 * PDF-Doc Module JS
 * Handles file selection, drag & drop, and AJAX uploads with Stepper UI.
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
        var btnSubmit = document.getElementById('btn-submit');
        var splitRangeGroup = document.getElementById('split-range-group');

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
            if (files.length > 0) {
                // Show Submit Button
                if(btnSubmit) btnSubmit.classList.remove('hidden');
                if(splitRangeGroup) splitRangeGroup.classList.remove('hidden');

                // Logic for Merge (List View)
                if (fileListUl) {
                    fileListUl.innerHTML = '';
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
                }
                // Logic for Single File (Display Name)
                else if (fileNameDisplay) {
                    fileNameDisplay.innerHTML = '<i class="fa fa-check text-success"></i> ' + files[0].name + ' (' + formatBytes(files[0].size) + ')';
                }
            } else {
                if(btnSubmit) btnSubmit.classList.add('hidden');
                if(splitRangeGroup) splitRangeGroup.classList.add('hidden');

                if (fileListUl) {
                    fileListContainer.classList.add('hidden');
                } else if (fileNameDisplay) {
                    fileNameDisplay.textContent = '';
                }
            }
        }

        // Stepper Control
        function updateStepper(step) {
            document.querySelectorAll('.pdf-step').forEach(function(el) {
                el.classList.remove('active');
            });
            var stepEl = document.getElementById('step-' + step);
            if(stepEl) stepEl.classList.add('active');
        }

        function showStepContent(step) {
            document.getElementById('step-content-1').classList.add('hidden');
            document.getElementById('step-content-2').classList.add('hidden');
            document.getElementById('result-area').classList.add('hidden');

            if(step === 1) document.getElementById('step-content-1').classList.remove('hidden');
            if(step === 2) document.getElementById('step-content-2').classList.remove('hidden');
            if(step === 3) document.getElementById('result-area').classList.remove('hidden');
        }

        // Form Submit
        form.onsubmit = function(e) {
            e.preventDefault();

            var formData = new FormData(form);
            formData.append('ajax', 1);

            // Move to Step 2
            updateStepper(2);
            showStepContent(2);

            var progressBar = document.querySelector('#upload-progress .progress-bar');

            if (progressBar) {
                progressBar.style.width = '0%';
                progressBar.setAttribute('aria-valuenow', 0);
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
                // Move to Step 3
                updateStepper(3);
                showStepContent(3);

                var resultArea = document.getElementById('result-area');

                if (xhr.status == 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.status == 'ok') {
                             var html = '<div class="alert alert-success">';
                             html += '<h4><i class="fa fa-check-circle"></i> ' + response.mess + '</h4>';
                             // Add File Info if available (backend needs to send file_info object)
                             if(response.file_info) {
                                 html += '<div class="file-result-info mt-2 mb-3">';
                                 html += '<i class="fa fa-file-o fa-3x"></i><br>';
                                 html += '<strong>' + response.file_info.name + '</strong><br>';
                                 html += '<span class="text-muted">' + response.file_info.size + '</span>';
                                 html += '</div>';
                             }

                             html += '<a href="' + response.link + '" class="btn btn-primary btn-lg-custom mt-2"><i class="fa fa-download"></i> ' + (window.lang_download || 'Download') + '</a>';
                             html += '<br><br><a href="" class="btn btn-default btn-xs" onclick="location.reload(); return false;">Start Over</a>';
                             html += '</div>';

                             resultArea.innerHTML = html;
                        } else {
                            resultArea.innerHTML = '<div class="alert alert-danger">' + response.mess + '<br><br><a href="" class="btn btn-default" onclick="location.reload(); return false;">Try Again</a></div>';
                        }
                    } catch (e) {
                        resultArea.innerHTML = '<div class="alert alert-danger">Error parsing response<br><br><a href="" class="btn btn-default" onclick="location.reload(); return false;">Try Again</a></div>';
                        console.error(xhr.responseText);
                    }
                } else {
                    resultArea.innerHTML = '<div class="alert alert-danger">Upload failed. Status: ' + xhr.status + '<br><br><a href="" class="btn btn-default" onclick="location.reload(); return false;">Try Again</a></div>';
                }
            };

            xhr.onerror = function() {
                updateStepper(3);
                showStepContent(3);
                document.getElementById('result-area').innerHTML = '<div class="alert alert-danger">Network error.<br><br><a href="" class="btn btn-default" onclick="location.reload(); return false;">Try Again</a></div>';
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
