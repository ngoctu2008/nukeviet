// Gieo Que JS

var shakeThreshold = 15;
var lastX, lastY, lastZ;
var lastUpdate = 0;
var shakeDuration = 0;
var shakeStartTime = 0;
var isShaking = false;
var holdTimer = null;
var holdStartTime = 0;

function performDivination(duration) {
    if (duration < 100) duration = 100; // Min duration

    // UI Transition
    $('#step-2').hide();
    $('#step-3').fadeIn();

    // API Call
    $.ajax({
        type: 'POST',
        url: nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=' + nv_module_name + '&' + nv_fc_variable + '=gieo-que',
        data: {
            api_get_result: 1,
            duration: duration
        },
        success: function(response) {
            // Delay slightly for effect
            setTimeout(function() {
                var data = response;
                if (typeof response === 'string') {
                    try {
                        data = JSON.parse(response);
                    } catch (e) {
                        console.error('JSON Parse Error', e);
                    }
                }
                renderResult(data);
            }, 1000);
        },
        error: function() {
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
            location.reload();
        }
    });
}

function renderResult(data) {
    $('#step-3').hide();
    $('#step-4').fadeIn().addClass('fade-in');

    if (data && data.id) {
        $('#res-name').text('Quẻ số ' + data.id + ': ' + data.name_han);
        $('#res-poem-han').text(data.poem_han);
        $('#res-poem-viet').text(data.poem_viet);
        $('#res-meaning').text(data.meaning);
        if (data.note) {
             $('#res-meaning').append('<br><small class="text-muted">(' + data.note + ')</small>');
        }
    } else {
        $('#res-name').text('Vô Vi Chi Quẻ');
        $('#res-meaning').text('Tâm chưa tịnh, ý chưa thông. Xin hãy thử lại sau.');
    }
}

$(document).ready(function() {

    // Step 1 -> Step 2
    $('#btn-start').click(function() {
        $('#step-1').hide();
        $('#step-2').fadeIn();

        // Init Sensors
        if (window.DeviceMotionEvent) {
            window.addEventListener('devicemotion', handleMotion, false);
        }
    });

    // Desktop Hold
    var ongXam = $('#ong-xam');

    ongXam.on('mousedown touchstart', function(e) {
        e.preventDefault();
        isShaking = true;
        holdStartTime = new Date().getTime();
        ongXam.addClass('shaking');
    });

    ongXam.on('mouseup touchend', function(e) {
        e.preventDefault();
        if (isShaking) {
            isShaking = false;
            ongXam.removeClass('shaking');
            var duration = new Date().getTime() - holdStartTime;
            if (duration > 1000) { // Must hold for 1s
                performDivination(duration);
            } else {
                alert('Hãy thành tâm lắc ống xăm lâu hơn (giữ chuột trên 1 giây).');
            }
        }
    });

    // Mobile Shake Handler
    function handleMotion(event) {
        var current = event.accelerationIncludingGravity;
        if (!current) return;

        var time = new Date().getTime();

        if ((time - lastUpdate) > 100) {
            var diffTime = time - lastUpdate;
            lastUpdate = time;

            var speed = Math.abs(current.x + current.y + current.z - lastX - lastY - lastZ) / diffTime * 10000;

            if (speed > shakeThreshold) {
                if (!shakeStartTime) shakeStartTime = time;
                shakeDuration += diffTime;

                // Visual Feedback
                ongXam.addClass('shaking');
                setTimeout(function(){ ongXam.removeClass('shaking'); }, 500);

                if (shakeDuration > 2000) { // Cumulative shake > 2s
                    window.removeEventListener('devicemotion', handleMotion);
                    performDivination(shakeDuration);
                }
            }

            lastX = current.x;
            lastY = current.y;
            lastZ = current.z;
        }
    }

    // Reload
    $('#btn-retry').click(function(){
        location.reload();
    });
});
