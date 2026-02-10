// Gieo Que JS - Redesigned Interface

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
    var currentUrl = window.location.href;
    var ajaxUrl = currentUrl + (currentUrl.indexOf('?') !== -1 ? '&' : '?') + 'nv_ajax=1';

    $.ajax({
        type: 'POST',
        url: ajaxUrl,
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
            }, 1500);
        },
        error: function() {
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
            location.reload();
        }
    });
}

function renderHexagram(containerId, lines) {
    var container = $(containerId);
    container.empty();
    if (!lines || !Array.isArray(lines)) return;

    // Lines come Top -> Bottom (index 0 is Top line 6)
    // We render them simply as divs
    lines.forEach(function(val) {
        var type = (val == 1) ? 'yang' : 'yin';
        var lineDiv = $('<div class="line ' + type + '"></div>');
        container.append(lineDiv);
    });
}

function renderResult(data) {
    $('#step-3').hide();
    $('#step-4').fadeIn().addClass('fade-in');

    if (data && data.chu) {
        // 1. Render Hexagrams
        renderHexagram('#hex-vis-chu', data.chu.lines);
        renderHexagram('#hex-vis-ho', data.ho.lines);
        renderHexagram('#hex-vis-bien', data.bien.lines);

        // 2. Render Basic Info
        $('#res-chu-name').text(data.chu.name);
        $('#res-chu-nghia').text(data.chu.nghia);
        $('#res-chu-dong').text(data.chu.dong);

        $('#res-ho-name').text(data.ho.name);
        $('#res-ho-nghia').text(data.ho.nghia);

        $('#res-bien-name').text(data.bien.name);
        $('#res-bien-nghia').text(data.bien.nghia);

        // 3. Render Detailed Interpretation
        if (data.tong_luan) {
            $('#interp-context').html(data.tong_luan.context);
            $('#interp-process').html(data.tong_luan.process);
            $('#interp-outcome').html(data.tong_luan.outcome);
            $('#interp-advice').html(data.tong_luan.advice);
        } else {
             // Fallback
             $('#interp-context').text('Đang cập nhật...');
        }

    } else {
        var msg = (data && data.error) ? data.error : 'Tâm chưa tịnh, ý chưa thông. Xin hãy thử lại sau.';
        $('#res-chu-name').text('Vô Vi Chi Quẻ');
        $('#res-chu-nghia').text(msg);
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
            // Lower threshold for quick clicks (e.g. 500ms)
            if (duration > 500) {
                performDivination(duration);
            } else {
                alert('Hãy thành tâm giữ và lắc lâu hơn một chút (giữ chuột/tay trên 0.5 giây).');
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

                if (shakeDuration > 1500) { // Cumulative shake > 1.5s
                    window.removeEventListener('devicemotion', handleMotion);
                    performDivination(shakeDuration);
                }
            }

            lastX = current.x;
            lastY = current.y;
            lastZ = current.z;
        }
    }
});
