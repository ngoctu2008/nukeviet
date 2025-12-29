<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/css/avatar.css">
<script src="{NV_BASE_SITEURL}themes/default/js/avatar.js"></script>

<div class="avatar-app" id="avatar-app">

    <!-- Steps Nav -->
    <div class="steps-nav">
        <div class="step-item completed" id="step-nav-1">1. {LANG.step_choose_frame}</div>
        <div class="step-item active" id="step-nav-2">2. {LANG.step_upload_photo}</div>
        <div class="step-item" id="step-nav-3">3. {LANG.step_edit}</div>
        <div class="step-item" id="step-nav-4">4. {LANG.step_preview}</div>
    </div>

    <!-- Step 2: Upload -->
    <div id="step-2" class="step-panel active">
        <div class="text-center">
            <h3>{LANG.upload_your_photo}</h3>
            <div class="upload-area" id="drop-zone">
                <p>{LANG.drag_drop_support}</p>
                <input type="file" id="upload-input" accept="image/*" style="display:none">
                <button class="btn btn-primary btn-lg" onclick="document.getElementById('upload-input').click()">{LANG.click_to_upload}</button>
            </div>
        </div>
    </div>

    <!-- Step 3: Editor -->
    <div id="step-3" class="step-panel">
        <div class="editor-container">
            <div class="canvas-wrap" id="canvas-wrap">
                <canvas id="c"></canvas>
            </div>

            <div class="controls-panel">
                <!-- Tabs -->
                <div class="controls-tabs">
                    <div class="tab-item active" onclick="switchMainTab('pos')"><i class="fa fa-arrows"></i> {LANG.tab_position}</div>
                    <div class="tab-item" onclick="switchMainTab('text')"><i class="fa fa-font"></i> {LANG.tab_text}</div>
                </div>

                <!-- Pos Tab -->
                <div id="panel-pos" class="sub-panel active">
                    <div class="form-group">
                        <label>{LANG.zoom}</label>
                        <input type="range" id="ctrl-zoom" min="0.1" max="3" step="0.1" value="1" oninput="updateUserImage('scale', parseFloat(this.value))">
                    </div>
                    <div class="form-group">
                        <label>{LANG.rotate}</label>
                        <input type="range" id="ctrl-rotate" min="-180" max="180" step="1" value="0" oninput="updateUserImage('angle', parseInt(this.value))">
                    </div>
                    <div class="text-center">
                        <button class="btn btn-warning btn-sm" onclick="resetImage()">{LANG.reset}</button>
                    </div>
                </div>

                <!-- Text Tab -->
                <div id="panel-text" class="sub-panel">
                    <div class="text-center" style="margin-bottom: 10px;">
                        <button class="btn btn-success btn-sm" onclick="addText()"><i class="fa fa-plus"></i> {LANG.add_text_btn}</button>
                    </div>

                    <div id="no-text-msg" class="text-muted text-center" style="font-size: 0.9em;">
                        {LANG.select_text_to_edit}
                    </div>

                    <div id="text-edit-area" style="display:none">
                        <div class="form-group">
                            <input type="text" id="ctrl-text-content" class="form-control" oninput="updateActiveText('text', this.value)">
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <label>{LANG.size}</label>
                                <input type="number" id="ctrl-fontSize" class="form-control" oninput="updateActiveText('fontSize', parseInt(this.value))">
                            </div>
                            <div class="col-xs-6">
                                <label>{LANG.tab_color}</label>
                                <input type="color" id="ctrl-fill" class="form-control" oninput="updateActiveText('fill', this.value)">
                            </div>
                        </div>

                        <div class="btn-group btn-group-justified" style="margin-top: 10px;">
                            <div class="btn-group">
                                <button class="btn btn-default" id="btn-bold" onclick="toggleStyle('fontWeight', 'bold')"><i class="fa fa-bold"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" id="btn-italic" onclick="toggleStyle('fontStyle', 'italic')"><i class="fa fa-italic"></i></button>
                            </div>
                            <div class="btn-group">
                                <button class="btn btn-default" id="btn-underline" onclick="toggleStyle('underline', true)"><i class="fa fa-underline"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="margin-top: 20px; text-center;">
                    <button class="btn btn-primary btn-block" onclick="generatePreview()">{LANG.finish_preview}</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Step 4: Result -->
    <div id="step-4" class="step-panel">
        <div class="text-center">
            <h3>{LANG.success_title}</h3>
            <img id="final-result-img" src="" class="img-responsive img-thumbnail" style="max-width: 400px; margin: 0 auto;">

            <div style="margin-top: 20px;">
                <h4 id="preview-title"></h4>
                <p>
                    <i class="fa fa-eye"></i> <span id="preview-views"></span>
                    <i class="fa fa-download"></i> <span id="preview-downloads"></span>
                </p>
            </div>

            <div style="margin-top: 20px;">
                <!-- BEGIN: allow_use -->
                <button class="btn btn-success btn-lg" onclick="downloadResult()"><i class="fa fa-download"></i> {LANG.download}</button>
                <!-- END: allow_use -->
                <a href="{CAT_INFO.link}" class="btn btn-default btn-lg">{LANG.back}</a>
            </div>
        </div>
    </div>

</div>

<script>
    var appState = {
        step: 2,
        tplId: {ROW.id},
        tplTitle: '{ROW.title}',
        tplViews: {ROW.views},
        tplDownloads: {ROW.downloads},
        canvas: null,
        canvasWidth: 800,
        canvasHeight: 800, // Default, will adjust
        userImg: null
    };

    // Init Fabric
    var canvasEl = document.getElementById('c');
    appState.canvas = new fabric.Canvas('c', {
        preserveObjectStacking: true
    });

    // Resize canvas logic
    function resizeCanvas() {
        var wrap = document.getElementById('canvas-wrap');
        var w = wrap.clientWidth;
        if(w > 800) w = 800; // Max width
        var scale = w / appState.canvasWidth;

        appState.canvas.setDimensions({ width: w, height: appState.canvasHeight * scale });
        appState.canvas.setZoom(scale);
    }
    window.addEventListener('resize', resizeCanvas);

    // Initial Load of Frame
    function initFrame() {
        var imgUrl = '{ROW.image}';

        fabric.Image.fromURL(imgUrl, function(img) {
            // Set canvas size to frame size
            appState.canvasWidth = img.width;
            appState.canvasHeight = img.height;
            appState.canvas.setDimensions({ width: img.width, height: img.height });

            // Set Overlay
            appState.canvas.setOverlayImage(img, appState.canvas.renderAll.bind(appState.canvas));

            resizeCanvas();
        });
    }

    function setStep(step) {
        document.querySelectorAll('.step-panel').forEach(e => e.classList.remove('active'));
        document.getElementById('step-'+step).classList.add('active');

        document.querySelectorAll('.step-item').forEach(e => e.classList.remove('active'));
        document.getElementById('step-nav-'+step).classList.add('active');
        if(step > 1) document.getElementById('step-nav-'+(step-1)).classList.add('completed');

        appState.step = step;

        if(step === 3) {
            setTimeout(resizeCanvas, 100);
        }
    }

    // Upload Handler
    document.getElementById('upload-input').addEventListener('change', function(e) {
        var file = e.target.files[0];
        if(!file) return;

        var reader = new FileReader();
        reader.onload = function(f) {
            fabric.Image.fromURL(f.target.result, function(img) {
                // Scale image to fit roughly
                var scale = Math.min(appState.canvasWidth / img.width, appState.canvasHeight / img.height);
                img.set({
                    left: appState.canvasWidth/2,
                    top: appState.canvasHeight/2,
                    originX: 'center',
                    originY: 'center',
                    scaleX: scale,
                    scaleY: scale
                });

                // Remove old user image
                if(appState.userImg) appState.canvas.remove(appState.userImg);

                appState.userImg = img;
                appState.canvas.add(img);
                img.sendToBack(); // Behind overlay
                appState.canvas.setActiveObject(img);

                setStep(3);
            });
        };
        reader.readAsDataURL(file);
    });

    // UI Switchers
    function switchMainTab(tab) {
        document.querySelectorAll('.tab-item').forEach(e => e.classList.remove('active'));
        document.querySelectorAll('.sub-panel').forEach(e => e.classList.remove('active'));

        if(tab === 'pos') {
            document.querySelector('.tab-item:nth-child(1)').classList.add('active');
            document.getElementById('panel-pos').classList.add('active');
        } else {
            document.querySelector('.tab-item:nth-child(2)').classList.add('active');
            document.getElementById('panel-text').classList.add('active');
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
             document.getElementById('ctrl-zoom').value = 1;
             updateUserImage('scale', 1);
             appState.canvas.requestRenderAll();
         }
    }

    // Text Ops
    function addText() {
        var text = new fabric.IText('Your Text', {
            left: appState.canvasWidth/2, top: appState.canvasHeight/2,
            fontFamily: 'Arial', fontSize: 30, fill: '#333333',
            originX: 'center', originY: 'center'
        });
        appState.canvas.add(text);
        text.bringToFront();
        appState.canvas.setActiveObject(text);
        switchMainTab('text');
    }

    // Canvas Events
    appState.canvas.on('selection:created', onObjSelect);
    appState.canvas.on('selection:updated', onObjSelect);
    appState.canvas.on('selection:cleared', onObjClear);

    function onObjSelect(e) {
        var obj = e.selected ? e.selected[0] : appState.canvas.getActiveObject();
        if(obj && (obj.type === 'i-text' || obj.type === 'text')) {
            document.getElementById('no-text-msg').style.display = 'none';
            document.getElementById('text-edit-area').style.display = 'block';

            document.getElementById('ctrl-text-content').value = obj.text;
            document.getElementById('ctrl-fontSize').value = obj.fontSize;
            document.getElementById('ctrl-fill').value = obj.fill;
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
             var current = obj.get(prop);
             var newVal = current === val ? 'normal' : val;
             if(prop === 'underline') newVal = !current;
             obj.set(prop, newVal);
             appState.canvas.requestRenderAll();
         }
    }

    // Finalize
    function generatePreview() {
        appState.canvas.discardActiveObject();
        appState.canvas.requestRenderAll();

        var dataURL = appState.canvas.toDataURL({ format: 'png', multiplier: 2 });

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

        // Count download
         $.post('{NV_BASE_SITEURL}index.php?{NV_LANG_VARIABLE}={NV_LANG_DATA}&{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=ajax', {
            action: 'download',
            id: appState.tplId
        });
    }

    // Start
    initFrame();
</script>
<!-- END: main -->
