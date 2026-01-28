/**
 * Lo Ban Ruler Interactive JS
 * Renders 3 stacked rulers (52.2, 42.9, 38.8) and handles dragging/scrolling.
 */

var LoBanRuler = (function() {
    var canvas, ctx;
    var currentCm = 0; // Current position in cm
    var isDragging = false;
    var startX = 0;
    var startCm = 0;

    // Ruler definitions
    // 52.2cm (Thong Thuy - Cua, Cong): 8 Cung (6.525cm each)
    // Quy Nhan, Hiem Hoa, Thien Tai, Thien Tai, Nhan Loc, Co Doc, Thien Tac, Te Tuong.
    var ruler522 = {
        len: 52.2,
        segments: [
            {n: 'Quý Nhân', g: true}, {n: 'Hiểm Họa', g: false}, {n: 'Thiên Tai', g: false}, {n: 'Thiên Tài', g: true},
            {n: 'Nhân Lộc', g: true}, {n: 'Cô Độc', g: false}, {n: 'Thiên Tặc', g: false}, {n: 'Tể Tướng', g: true}
        ]
    };

    // 42.9cm (Duong Trach - Khoi xay): 8 Cung (5.3625cm each)
    // Tai, Benh, Ly, Nghia, Quan, Kiep, Hai, Ban.
    var ruler429 = {
        len: 42.9,
        segments: [
            {n: 'Tài', g: true}, {n: 'Bệnh', g: false}, {n: 'Ly', g: false}, {n: 'Nghĩa', g: true},
            {n: 'Quan', g: true}, {n: 'Kiếp', g: false}, {n: 'Hại', g: false}, {n: 'Bản', g: true}
        ]
    };

    // 38.8cm (Am Phan - Mo ma, Do tho): 10 Cung (3.88cm each)
    // Dinh, Hai, Vuong, Kho, Nghia, Quan, Tu, Hung, That, Tai.
    var ruler388 = {
        len: 38.8,
        segments: [
            {n: 'Đinh', g: true}, {n: 'Hại', g: false}, {n: 'Vượng', g: true}, {n: 'Khổ', g: false}, {n: 'Nghĩa', g: true},
            {n: 'Quan', g: true}, {n: 'Tử', g: false}, {n: 'Hưng', g: true}, {n: 'Thất', g: false}, {n: 'Tài', g: true}
        ]
    };

    var pxPerCm = 40; // 1cm = 40px scale
    var rulerHeight = 60; // Height of each ruler bar
    var rulerGap = 20;

    function init(canvasId, inputId) {
        canvas = document.getElementById(canvasId);
        if (!canvas) return;
        ctx = canvas.getContext('2d');

        var input = document.getElementById(inputId);

        // Resize canvas to fill container
        function resize() {
            canvas.width = canvas.parentElement.offsetWidth;
            canvas.height = (rulerHeight * 3) + (rulerGap * 2) + 50; // Extra space
            draw();
        }
        window.addEventListener('resize', resize);
        resize();

        // Initial value
        if(input.value) currentCm = parseFloat(input.value);
        draw();

        // Input Listener
        input.addEventListener('input', function() {
            var val = parseFloat(this.value);
            if (!isNaN(val) && val >= 0) {
                currentCm = val;
                draw();
            }
        });

        // Drag Events
        canvas.addEventListener('mousedown', startDrag);
        canvas.addEventListener('touchstart', startDrag);

        window.addEventListener('mousemove', moveDrag);
        window.addEventListener('touchmove', moveDrag);

        window.addEventListener('mouseup', endDrag);
        window.addEventListener('touchend', endDrag);

        function startDrag(e) {
            isDragging = true;
            startX = getX(e);
            startCm = currentCm;
            canvas.style.cursor = 'grabbing';
        }

        function moveDrag(e) {
            if (!isDragging) return;
            var dx = getX(e) - startX;
            // dx pixels -> cm?
            // Moving mouse Left (negative dx) means ruler moves Left, so Value increases.
            // Wait. If I drag Tape Left, I see higher numbers.
            // So cm = startCm - (dx / pxPerCm).

            var newCm = startCm - (dx / pxPerCm);
            if (newCm < 0) newCm = 0;
            currentCm = newCm;

            input.value = currentCm.toFixed(1);
            draw();
        }

        function endDrag() {
            isDragging = false;
            canvas.style.cursor = 'grab';
        }

        function getX(e) {
            if (e.touches && e.touches.length > 0) return e.touches[0].clientX;
            return e.clientX;
        }
    }

    function draw() {
        if (!ctx) return;
        var w = canvas.width;
        var h = canvas.height;
        var cx = w / 2; // Center line position

        ctx.clearRect(0, 0, w, h);

        // Draw Center Indicator
        ctx.beginPath();
        ctx.moveTo(cx, 0);
        ctx.lineTo(cx, h);
        ctx.strokeStyle = 'red';
        ctx.lineWidth = 2;
        ctx.stroke();

        // Draw 3 Rulers
        drawRulerBar(0, ruler522, "52.2cm (Thông Thủy)");
        drawRulerBar(rulerHeight + rulerGap, ruler429, "42.9cm (Dương Trạch)");
        drawRulerBar((rulerHeight + rulerGap) * 2, ruler388, "38.8cm (Âm Phần)");
    }

    function drawRulerBar(y, rulerDef, title) {
        var w = canvas.width;
        var cx = w / 2;

        // Background
        ctx.fillStyle = '#f0f0f0';
        ctx.fillRect(0, y, w, rulerHeight);

        // Draw Title
        ctx.fillStyle = '#333';
        ctx.font = 'bold 12px Arial';
        ctx.fillText(title, 5, y - 4);

        // Calculate offset based on currentCm
        // At cx, we want value currentCm.
        // So 0cm is at cx - (currentCm * pxPerCm)
        var zeroX = cx - (currentCm * pxPerCm);

        // Optimization: render only visible range
        // Visible X from 0 to w.
        // cm = (cx - x) / pxPerCm + currentCm ? No.
        // x = zeroX + cm * pxPerCm.
        // minCm corresponding to x=0: 0 = zeroX + minCm*scale -> minCm = -zeroX/scale
        var minCm = -zeroX / pxPerCm;
        var maxCm = (w - zeroX) / pxPerCm;

        if (minCm < 0) minCm = 0;

        // Draw Ticks and Segments
        // 1. Ticks every 1cm and 0.5cm?
        // Let's draw cm lines
        ctx.strokeStyle = '#999';
        ctx.lineWidth = 1;
        ctx.fillStyle = '#000';
        ctx.font = '10px Arial';

        for (var c = Math.floor(minCm); c <= Math.ceil(maxCm); c++) {
            var x = zeroX + (c * pxPerCm);

            // 1cm tick
            ctx.beginPath();
            ctx.moveTo(x, y + rulerHeight);
            ctx.lineTo(x, y + rulerHeight - 15); // Bottom ticks
            ctx.stroke();

            // Number
            if (c % 5 === 0) {
               ctx.fillText(c, x + 2, y + rulerHeight - 18);
            }
        }

        // 2. Draw Segments
        // Repeating cycle: rulerDef.len
        // Segment size: rulerDef.len / rulerDef.segments.length
        var segSize = rulerDef.len / rulerDef.segments.length;

        var startCycle = Math.floor(minCm / rulerDef.len);
        var endCycle = Math.ceil(maxCm / rulerDef.len);

        for (var cy = startCycle; cy <= endCycle; cy++) {
            var cycleStartCm = cy * rulerDef.len;

            for (var i = 0; i < rulerDef.segments.length; i++) {
                var segStartCm = cycleStartCm + (i * segSize);
                var segEndCm = segStartCm + segSize;

                // Visible?
                if (segEndCm < minCm || segStartCm > maxCm) continue;

                var x1 = zeroX + (segStartCm * pxPerCm);
                var x2 = zeroX + (segEndCm * pxPerCm);
                var wSeg = x2 - x1;

                // Color/Text
                var seg = rulerDef.segments[i];
                ctx.fillStyle = seg.g ? 'rgba(200, 50, 50, 0.2)' : 'rgba(0, 0, 0, 0.1)'; // Reddish for good, Dark for bad
                ctx.fillRect(x1, y, wSeg, rulerHeight - 20); // Top part filled

                ctx.fillStyle = seg.g ? '#c00' : '#000';
                ctx.font = 'bold 12px Arial';
                // Center text
                var txtW = ctx.measureText(seg.n).width;
                if (wSeg > txtW) {
                    ctx.fillText(seg.n, x1 + (wSeg - txtW)/2, y + 25);
                }
            }
        }
    }

    return {
        init: init
    };

})();
