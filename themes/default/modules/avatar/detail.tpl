<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/avatar.css">

<div class="avatar-app" id="avatar-app">
    <!-- Steps Navigation -->
    <div class="step-nav-container">
        <ul class="step-nav">
            <!-- Step 1 (Choose) is skipped visually or marked done -->
            <li class="completed" id="step-nav-2" onclick="setStep(2)">
                <span class="step-icon"><i class="fa fa-upload"></i></span>
                <span class="step-text">{LANG.step_upload_photo}</span>
            </li>
            <li id="step-nav-3" onclick="setStep(3)">
                <span class="step-icon"><i class="fa fa-paint-brush"></i></span>
                <span class="step-text">{LANG.step_edit}</span>
            </li>
            <li id="step-nav-4" onclick="setStep(4)">
                <span class="step-icon"><i class="fa fa-eye"></i></span>
                <span class="step-text">{LANG.step_preview}</span>
            </li>
            <li id="step-nav-5" onclick="setStep(5)">
                <span class="step-icon"><i class="fa fa-download"></i></span>
                <span class="step-text">{LANG.step_download}</span>
            </li>
        </ul>
    </div>

    <!-- Step 2: Upload Photo -->
    <div class="step-content" id="step-2">
        <div class="upload-area" id="upload-area">
            <div class="upload-placeholder">
                <i class="fa fa-cloud-upload fa-4x"></i>
                <h3>{LANG.upload_your_photo}</h3>
                <p>{LANG.drag_drop_support}</p>
                <button class="btn btn-primary btn-lg" onclick="document.getElementById('file-upload').click()">{LANG.click_to_upload}</button>
                <input type="file" id="file-upload" accept="image/*" style="display:none" onchange="handleFileUpload(this)">
            </div>
        </div>
        <div class="row" style="margin-top:20px;">
             <div class="col-xs-12 text-center">
                 <button class="btn btn-default" onclick="history.back()"><i class="fa fa-arrow-left"></i> {LANG.back}</button>
             </div>
        </div>
    </div>

    <!-- Step 3: Editor -->
    <div class="step-content" id="step-3" style="display:none;">
        <div class="editor-container">
            <!-- Sidebar Tools -->
            <div class="editor-sidebar">
                <ul class="sidebar-tabs">
                    <li class="main-tab active" onclick="switchMainTab('image')"><i class="fa fa-picture-o"></i> {LANG.tab_image}</li>
                    <li class="main-tab" onclick="switchMainTab('text')"><i class="fa fa-font"></i> {LANG.tab_text}</li>
                </ul>

                <!-- Image Panel -->
                <div class="editor-panel active" id="panel-image">
                    <div class="control-group">
                        <button class="btn btn-warning btn-block btn-sm" onclick="document.getElementById('file-upload').click()">{LANG.change_photo}</button>
                        <hr />
                        <label>{LANG.zoom}</label>
                        <input type="range" id="ctrl-zoom" min="0.1" max="3" step="0.1" value="1" oninput="updateUserImage('scale', parseFloat(this.value))">

                        <label style="margin-top:10px">{LANG.rotate}</label>
                        <input type="range" id="ctrl-rotate" min="-180" max="180" step="1" value="0" oninput="updateUserImage('angle', parseInt(this.value))">

                        <button class="btn btn-default btn-sm btn-block" style="margin-top:10px" onclick="resetImage()"><i class="fa fa-refresh"></i> Reset</button>
                    </div>
                </div>

                <!-- Text Panel -->
                <div class="editor-panel" id="panel-text">
                    <button class="btn btn-success btn-block" onclick="addText()"><i class="fa fa-plus"></i> {LANG.add_text_btn}</button>

                    <div id="no-text-msg" style="margin-top:20px; text-align:center; color:#777;">
                        {LANG.select_text_to_edit}
                    </div>

                    <div id="text-edit-area" style="display:none; margin-top:15px;">
                        <textarea id="ctrl-text-content" class="form-control" rows="2" oninput="updateActiveText('text', this.value)"></textarea>

                        <div class="sub-tabs-nav">
                            <span class="sub-tab active" onclick="switchSubTab('pos')" title="{LANG.tab_position}"><i class="fa fa-arrows"></i></span>
                            <span class="sub-tab" onclick="switchSubTab('format')" title="{LANG.tab_format}"><i class="fa fa-bold"></i></span>
                            <span class="sub-tab" onclick="switchSubTab('font')" title="{LANG.tab_font}"><i class="fa fa-font"></i></span>
                            <span class="sub-tab" onclick="switchSubTab('color')" title="{LANG.tab_color}"><i class="fa fa-tint"></i></span>
                            <span class="sub-tab" onclick="switchSubTab('outline')" title="{LANG.tab_outline}"><i class="fa fa-square-o"></i></span>
                            <span class="sub-tab" onclick="switchSubTab('shadow')" title="{LANG.tab_shadow}"><i class="fa fa-clone"></i></span>
                        </div>

                        <!-- Sub Panels -->
                        <div class="sub-panel active" id="sub-pos">
                            <div class="btn-group btn-group-justified" style="margin-bottom:5px">
                                <div class="btn-group"><button class="btn btn-default btn-sm" onclick="alignText('left')"><i class="fa fa-align-left"></i></button></div>
                                <div class="btn-group"><button class="btn btn-default btn-sm" onclick="alignText('center')"><i class="fa fa-align-center"></i></button></div>
                                <div class="btn-group"><button class="btn btn-default btn-sm" onclick="alignText('right')"><i class="fa fa-align-right"></i></button></div>
                            </div>
                             <div class="control-pad">
                                <button onclick="moveText('up')"><i class="fa fa-arrow-up"></i></button>
                                <button onclick="moveText('left')"><i class="fa fa-arrow-left"></i></button>
                                <button onclick="moveText('down')"><i class="fa fa-arrow-down"></i></button>
                                <button onclick="moveText('right')"><i class="fa fa-arrow-right"></i></button>
                            </div>
                            <button class="btn btn-danger btn-sm btn-block" style="margin-top:10px" onclick="deleteActiveObj()"><i class="fa fa-trash"></i> {LANG.delete}</button>
                        </div>

                        <div class="sub-panel" id="sub-format">
                             <div class="btn-group btn-group-justified">
                                <div class="btn-group"><button class="btn btn-default btn-sm" id="btn-bold" onclick="toggleStyle('fontWeight', 'bold')"><b>B</b></button></div>
                                <div class="btn-group"><button class="btn btn-default btn-sm" id="btn-italic" onclick="toggleStyle('fontStyle', 'italic')"><i>I</i></button></div>
                                <div class="btn-group"><button class="btn btn-default btn-sm" id="btn-underline" onclick="toggleStyle('underline', true)"><u>U</u></button></div>
                            </div>
                            <label style="margin-top:5px">Line Height</label>
                            <input type="range" id="ctrl-lineHeight" min="0.5" max="3" step="0.1" oninput="updateActiveText('lineHeight', parseFloat(this.value))">
                             <label>Char Spacing</label>
                            <input type="range" id="ctrl-charSpacing" min="-100" max="500" step="10" oninput="updateActiveText('charSpacing', parseInt(this.value))">
                        </div>

                        <div class="sub-panel" id="sub-font">
                            <select id="ctrl-fontFamily" class="form-control" onchange="updateActiveText('fontFamily', this.value)">
                                <option value="Arial">Arial</option>
                                <option value="Times New Roman">Times New Roman</option>
                                <option value="Verdana">Verdana</option>
                                <option value="Courier New">Courier New</option>
                                <option value="Tahoma">Tahoma</option>
                            </select>
                            <label style="margin-top:5px">{LANG.size}</label>
                            <input type="number" id="ctrl-fontSize" class="form-control" value="30" oninput="updateActiveText('fontSize', parseInt(this.value))">
                        </div>

                        <div class="sub-panel" id="sub-color">
                             <label>{LANG.text_color}</label>
                             <input type="color" id="ctrl-fill" class="form-control" onchange="updateActiveText('fill', this.value)">
                        </div>

                        <div class="sub-panel" id="sub-outline">
                            <label>{LANG.text_color}</label>
                            <input type="color" id="ctrl-stroke" class="form-control" onchange="updateActiveText('stroke', this.value)">
                            <label>{LANG.width}</label>
                            <input type="number" id="ctrl-strokeWidth" class="form-control" min="0" max="10" oninput="updateActiveText('strokeWidth', parseInt(this.value))">
                        </div>

                        <div class="sub-panel" id="sub-shadow">
                            <label>Color</label>
                            <input type="color" id="ctrl-shadowColor" class="form-control" onchange="updateShadow()">
                            <label>Blur</label>
                            <input type="range" id="ctrl-shadowBlur" min="0" max="50" oninput="updateShadow()">
                            <label>X Offset</label>
                            <input type="range" id="ctrl-shadowX" min="-50" max="50" oninput="updateShadow()">
                            <label>Y Offset</label>
                            <input type="range" id="ctrl-shadowY" min="-50" max="50" oninput="updateShadow()">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Canvas Area -->
            <div class="editor-workspace">
                 <div class="canvas-wrapper" id="canvas-wrapper">
                     <canvas id="c"></canvas>
                 </div>
                 <div class="editor-actions text-center" style="margin-top:15px">
                     <button class="btn btn-default" onclick="setStep(2)"><i class="fa fa-arrow-left"></i> {LANG.back}</button>
                     <button class="btn btn-primary" onclick="generatePreview()">{LANG.finish_preview} <i class="fa fa-arrow-right"></i></button>
                 </div>
            </div>
        </div>
    </div>

    <!-- Step 4: Preview -->
    <div class="step-content text-center" id="step-4" style="display:none;">
        <h3 id="preview-title">{ROW.title}</h3>
        <div class="preview-box">
            <img id="final-result-img" class="img-thumbnail" style="max-height:500px">
        </div>
        <div style="margin-top:20px">
            <button class="btn btn-default" onclick="setStep(3)"><i class="fa fa-pencil"></i> {LANG.step_edit}</button>
            <!-- BEGIN: allow_use -->
            <button class="btn btn-success btn-lg" onclick="downloadResult()"><i class="fa fa-download"></i> {LANG.download}</button>
            <!-- END: allow_use -->
        </div>
        <div class="stats-info" style="margin-top:15px; color:#888;">
             <i class="fa fa-eye"></i> <span id="preview-views">{ROW.views}</span> &nbsp;
             <i class="fa fa-download"></i> <span id="preview-downloads">{ROW.downloads}</span>
        </div>
    </div>
</div>

<script src="{NV_BASE_SITEURL}themes/default/js/avatar.js"></script>
<script>
    // Initialize App State
    var appState = {
        step: 1,
        canvas: null,
        frameImg: '{ROW.image}',
        userImg: null,
        canvasWidth: 800,
        canvasHeight: 800,
        tplId: {ROW.id},
        tplTitle: '{ROW.title}',
        tplViews: {ROW.views},
        tplDownloads: {ROW.downloads}
    };

    // Initialize Fabric Canvas
    function initCanvas() {
        if(appState.canvas) return;

        var wrapper = document.getElementById('canvas-wrapper');
        var w = wrapper.clientWidth;
        // Make it square or fit aspect ratio of frame (assuming square for now or load frame to check)
        // Let's default to 800x800 internal, scaled via CSS

        appState.canvas = new fabric.Canvas('c', {
            width: appState.canvasWidth,
            height: appState.canvasHeight,
            preserveObjectStacking: true
        });

        // Event Listeners
        appState.canvas.on('selection:created', onObjSelect);
        appState.canvas.on('selection:updated', onObjSelect);
        appState.canvas.on('selection:cleared', onObjClear);

        // Load Frame Overlay
        if(appState.frameImg) {
            fabric.Image.fromURL(appState.frameImg, function(img) {
                // Scale frame to fit canvas
                img.scaleToWidth(appState.canvasWidth);
                img.scaleToHeight(appState.canvasHeight);
                img.selectable = false;
                img.evented = false;

                appState.frameObj = img;
                appState.canvas.add(img);
                img.bringToFront();
            }, { crossOrigin: 'anonymous' });
        }
    }

    // Skip directly to upload step
    window.addEventListener('load', function() {
        setStep(2);
    });

    // --- Application Logic Functions ---

    function setStep(step) {
        // Validation for step 3 (must have image)
        if(step === 3 && !appState.userImg) {
            alert('{LANG.please_upload_photo}');
            return;
        }

        appState.step = step;

        // Update nav UI
        document.querySelectorAll('.step-nav li').forEach(li => {
            li.classList.remove('active');
        });
        var currentNav = document.getElementById('step-nav-' + step);
        if(currentNav) currentNav.classList.add('active');

        // Mark previous steps completed
        for(var i=1; i<step; i++) {
             var prev = document.getElementById('step-nav-' + i);
             if(prev) prev.classList.add('completed');
        }

        // Show Content
        document.querySelectorAll('.step-content').forEach(d => d.style.display = 'none');
        document.getElementById('step-' + step).style.display = 'block';

        if(step === 3) {
            initCanvas();
        }
    }

    function handleFileUpload(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var imgObj = new Image();
                imgObj.src = e.target.result;
                imgObj.onload = function() {
                    loadImageToCanvas(imgObj);
                    setStep(3);
                };
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function loadImageToCanvas(imgElem) {
        if(!appState.canvas) initCanvas();
        if(appState.userImg) appState.canvas.remove(appState.userImg);

        var imgInstance = new fabric.Image(imgElem);
        appState.userImg = imgInstance;

        var scale = Math.max(appState.canvasWidth / imgInstance.width, appState.canvasHeight / imgInstance.height);

        imgInstance.set({
            left: appState.canvasWidth/2, top: appState.canvasHeight/2,
            originX: 'center', originY: 'center',
            scaleX: scale, scaleY: scale
        });

        appState.canvas.add(imgInstance);
        imgInstance.sendToBack();
        appState.canvas.setActiveObject(imgInstance);

        // Reset controls
        document.getElementById('ctrl-zoom').value = scale;
        document.getElementById('ctrl-rotate').value = 0;

        switchMainTab('image');
    }

    function switchMainTab(tab) {
        document.querySelectorAll('.main-tab').forEach(e => e.classList.remove('active'));
        document.querySelectorAll('.editor-panel').forEach(e => e.classList.remove('active'));

        if(tab === 'image') {
            document.querySelectorAll('.main-tab')[0].classList.add('active');
            document.getElementById('panel-image').classList.add('active');
            if(appState.userImg) {
                appState.canvas.setActiveObject(appState.userImg);
                appState.canvas.requestRenderAll();
            }
        } else {
            document.querySelectorAll('.main-tab')[1].classList.add('active');
            document.getElementById('panel-text').classList.add('active');
            var texts = appState.canvas.getObjects('i-text');
            if(texts.length > 0) {
                appState.canvas.setActiveObject(texts[0]);
                appState.canvas.requestRenderAll();
            }
        }
    }

    function switchSubTab(tab) {
        document.querySelectorAll('.sub-tab').forEach(e => e.classList.remove('active'));
        document.querySelectorAll('.sub-panel').forEach(e => e.classList.remove('active'));

        // Activate tab
        var tabIndex = ['pos', 'format', 'font', 'color', 'outline', 'shadow'].indexOf(tab);
        if(tabIndex >= 0) {
            document.querySelectorAll('.sub-tab')[tabIndex].classList.add('active');
            document.getElementById('sub-'+tab).classList.add('active');
        }
    }

    // Image Ops
    function updateUserImage(prop, val) {
        if(appState.userImg) {
            if (prop === 'scale') {
                appState.userImg.scale(val);
            } else {
                appState.userImg.set(prop, val);
            }
            appState.canvas.requestRenderAll();
        }
    }
    function resetImage() {
         if(appState.userImg) {
             appState.userImg.set({ angle: 0 });
             document.getElementById('ctrl-rotate').value = 0;
             appState.canvas.requestRenderAll();
         }
    }

    // Text Ops
    function addText() {
        var text = new fabric.IText('New Text', {
            left: appState.canvasWidth/2, top: appState.canvasHeight/2,
            fontFamily: 'Arial', fontSize: 30, fill: '#333333',
            originX: 'center', originY: 'center'
        });
        appState.canvas.add(text);
        text.bringToFront();
        appState.canvas.setActiveObject(text);
    }

    function onObjSelect(e) {
        var obj = e.selected ? e.selected[0] : appState.canvas.getActiveObject();
        if(obj && (obj.type === 'i-text' || obj.type === 'text')) {
            document.getElementById('no-text-msg').style.display = 'none';
            document.getElementById('text-edit-area').style.display = 'block';

            // Sync
            document.getElementById('ctrl-text-content').value = obj.text;
            document.getElementById('ctrl-fontSize').value = obj.fontSize;
            document.getElementById('ctrl-fill').value = obj.fill;
            document.getElementById('ctrl-charSpacing').value = obj.charSpacing;
            document.getElementById('ctrl-lineHeight').value = obj.lineHeight;
            document.getElementById('ctrl-stroke').value = obj.stroke || '#000000';
            document.getElementById('ctrl-strokeWidth').value = obj.strokeWidth || 0;

            if(obj.shadow) {
                document.getElementById('ctrl-shadowColor').value = obj.shadow.color;
                document.getElementById('ctrl-shadowBlur').value = obj.shadow.blur;
                document.getElementById('ctrl-shadowX').value = obj.shadow.offsetX;
                document.getElementById('ctrl-shadowY').value = obj.shadow.offsetY;
            }

            // Sync Styles
            document.getElementById('btn-bold').classList.toggle('active', obj.fontWeight === 'bold');
            document.getElementById('btn-italic').classList.toggle('active', obj.fontStyle === 'italic');
            document.getElementById('btn-underline').classList.toggle('active', !!obj.underline);

            // Switch to Text tab if not there
            if(!document.getElementById('panel-text').classList.contains('active')) {
                switchMainTab('text');
            }
        } else if (obj === appState.userImg) {
             document.getElementById('ctrl-zoom').value = obj.scaleX;
             document.getElementById('ctrl-rotate').value = obj.angle;
        }
    }

    function onObjClear() {
         document.getElementById('no-text-msg').style.display = 'block';
         document.getElementById('text-edit-area').style.display = 'none';
    }

    function updateActiveText(prop, val) {
        var obj = appState.canvas.getActiveObject();
        if(obj && (obj.type === 'i-text' || obj.type === 'text')) {
            if(prop === 'text') obj.set('text', val);
            else obj.set(prop, val);
            appState.canvas.requestRenderAll();
        }
    }

    function toggleStyle(prop, val) {
         var obj = appState.canvas.getActiveObject();
         if(obj) {
             if(prop === 'underline') {
                 obj.set('underline', !obj.underline);
                 document.getElementById('btn-underline').classList.toggle('active');
             } else {
                 var current = obj.get(prop);
                 var newVal = current === val ? 'normal' : val;
                 obj.set(prop, newVal);
                 // Update btn state
                 if(prop === 'fontWeight') document.getElementById('btn-bold').classList.toggle('active', newVal === val);
                 if(prop === 'fontStyle') document.getElementById('btn-italic').classList.toggle('active', newVal === val);
             }
             appState.canvas.requestRenderAll();
         }
    }

    function alignText(align) {
         // This aligns the text within its bounding box, or moves object if we want.
         // Usually for single line text, this doesn't do much visually unless multiline.
         // Or we can move the object to canvas center/left/right
         updateActiveText('textAlign', align);
    }

    function moveText(dir) {
        var obj = appState.canvas.getActiveObject();
        if(obj) {
            var step = 5;
            if(dir === 'up') obj.top -= step;
            if(dir === 'down') obj.top += step;
            if(dir === 'left') obj.left -= step;
            if(dir === 'right') obj.left += step;
            obj.setCoords();
            appState.canvas.requestRenderAll();
        }
    }

    function updateShadow() {
        var obj = appState.canvas.getActiveObject();
        if(obj) {
            var color = document.getElementById('ctrl-shadowColor').value;
            var blur = parseInt(document.getElementById('ctrl-shadowBlur').value);
            var x = parseInt(document.getElementById('ctrl-shadowX').value);
            var y = parseInt(document.getElementById('ctrl-shadowY').value);

            obj.setShadow({ color: color, blur: blur, offsetX: x, offsetY: y });
            appState.canvas.requestRenderAll();
        }
    }

    function deleteActiveObj() {
        var obj = appState.canvas.getActiveObject();
        if(obj) {
            appState.canvas.remove(obj);
            appState.canvas.discardActiveObject();
            appState.canvas.requestRenderAll();
        }
    }

    // --- Finalize ---
    function generatePreview() {
        appState.canvas.discardActiveObject();
        appState.canvas.requestRenderAll();

        var dataURL = appState.canvas.toDataURL({ format: 'png', multiplier: 2 }); // High res

        document.getElementById('final-result-img').src = dataURL;
        document.getElementById('preview-title').innerText = appState.tplTitle;
        document.getElementById('preview-views').innerText = appState.tplViews;
        document.getElementById('preview-downloads').innerText = appState.tplDownloads;

        setStep(4);
    }

    function downloadResult() {
        var link = document.createElement('a');
        link.download = 'avatar_' + Date.now() + '.png';
        link.href = document.getElementById('final-result-img').src;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);

        document.getElementById('step-nav-5').classList.add('completed');
        trackAction('download', appState.tplId);
    }

    function trackAction(action, id) {
        if(typeof $ !== 'undefined' && id > 0) {
            $.post('{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=ajax', {
                action: action,
                id: id
            });
        }
    }

</script>
<!-- END: main -->
