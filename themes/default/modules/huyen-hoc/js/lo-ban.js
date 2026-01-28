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
    var ruler522 = {
        len: 52.2,
        title: "Thước Lỗ Ban 52.2cm: Khoảng thông thủy (cửa, cửa sổ...)",
        segments: [
            {n: 'Quý Nhân', g: true, subs: ['Quyền lộc', 'Trung tín', 'Tác quan', 'Phát đạt', 'Thông minh']},
            {n: 'Hiểm Họa', g: false, subs: ['Tán thành', 'Thời nhơn', 'Tự ải', 'Quan tư', 'Cô quả']},
            {n: 'Thiên Tai', g: false, subs: ['Hoàn trường', 'Thiếu an', 'Bệnh tật', 'Thân tàn', 'Hệ suất']},
            {n: 'Thiên Tài', g: true, subs: ['Thi thơ', 'Văn học', 'Thanh quý', 'Tác lộc', 'Thiên lộc']},
            {n: 'Nhân Lộc', g: true, subs: ['Tử tôn', 'Phú quý', 'Tấn bửu', 'Thập thiện', 'Văn chương']},
            {n: 'Cô Độc', g: false, subs: ['Bạc nghịch', 'Vô vọng', 'Ly tán', 'Tửu thực', 'Dâm dục']},
            {n: 'Thiên Tặc', g: false, subs: ['Phòng bệnh', 'Chiêu ôn', 'Ôn tai', 'Ngục tù', 'Quan tài']},
            {n: 'Tể Tướng', g: true, subs: ['Đại tài', 'Thi thơ', 'Hoạch tài', 'Hiếu tử', 'Quý nhân']}
        ]
    };

    var ruler429 = {
        len: 42.9,
        title: "Thước Lỗ Ban 42.9cm (dương trạch): Khối xây dựng (bếp, bệ, bậc...)",
        segments: [
            {n: 'Tài', g: true, subs: ['Tài đức', 'Bảo khố', 'Lục hợp', 'Nghênh phúc']},
            {n: 'Bệnh', g: false, subs: ['Thoái tài', 'Công sự', 'Lao chấp', 'Cô quả']},
            {n: 'Ly', g: false, subs: ['Trường khố', 'Kiếp tài', 'Quan quỷ', 'Thất thoát']},
            {n: 'Nghĩa', g: true, subs: ['Thiêm đinh', 'Ích lợi', 'Quý tử', 'Đại cát']},
            {n: 'Quan', g: true, subs: ['Thuận khoa', 'Hoành tài', 'Tiến ích', 'Phú quý']},
            {n: 'Kiếp', g: false, subs: ['Tử biệt', 'Thoái khẩu', 'Ly hương', 'Tài thất']},
            {n: 'Hại', g: false, subs: ['Tai chi', 'Tử tuyệt', 'Bệnh lâm', 'Khẩu thiệt']},
            {n: 'Bản', g: true, subs: ['Tài chí', 'Đăng khoa', 'Tiến bảo', 'Hưng vượng']}
        ]
    };

    var ruler388 = {
        len: 38.8,
        title: "Thước Lỗ Ban 38.8cm (âm phần): Đồ nội thất (bàn thờ, tủ...)",
        segments: [
            {n: 'Đinh', g: true, subs: ['Phúc tinh', 'Cấp đệ', 'Tài vượng', 'Đăng khoa']},
            {n: 'Hại', g: false, subs: ['Khẩu thiệt', 'Bệnh lâm', 'Tử tuyệt', 'Tai chi']},
            {n: 'Vượng', g: true, subs: ['Thiên đức', 'Hỷ sự', 'Tiến bảo', 'Nạp phúc']},
            {n: 'Khổ', g: false, subs: ['Thất thoát', 'Quan quỷ', 'Kiếp tài', 'Vô tự']},
            {n: 'Nghĩa', g: true, subs: ['Đại cát', 'Tài vượng', 'Ích lợi', 'Thiên khố']},
            {n: 'Quan', g: true, subs: ['Phú quý', 'Tiến bảo', 'Hoành tài', 'Thuận khoa']},
            {n: 'Tử', g: false, subs: ['Ly hương', 'Tử biệt', 'Thoái đinh', 'Thất tài']},
            {n: 'Hưng', g: true, subs: ['Đăng khoa', 'Quý tử', 'Thêm đinh', 'Hưng vượng']},
            {n: 'Thất', g: false, subs: ['Cô quả', 'Lao chấp', 'Công sự', 'Thoái tài']},
            {n: 'Tài', g: true, subs: ['Nghênh phúc', 'Lục hợp', 'Tiến bảo', 'Tài đức']}
        ]
    };

    var pxPerCm = 40; // 1cm = 40px scale
    var rulerHeight = 110; // Height of each ruler block
    var rulerGap = 20;
    var headerHeight = 30; // Height for the ticks area

    function init(canvasId, inputId) {
        canvas = document.getElementById(canvasId);
        if (!canvas) {
            console.error("LoBanRuler: Canvas not found", canvasId);
            return;
        }
        ctx = canvas.getContext('2d');

        var input = document.getElementById(inputId);

        function resize() {
            var parentW = canvas.parentElement.offsetWidth;
            if (parentW === 0) parentW = window.innerWidth - 40;

            canvas.width = parentW;
            canvas.height = (rulerHeight * 3) + (rulerGap * 2) + 20;
            draw();
        }
        window.addEventListener('resize', resize);
        setTimeout(resize, 100);
        resize();

        // Initial value
        if(input.value) currentCm = parseFloat(input.value);
        draw();

        input.addEventListener('input', function() {
            var val = parseFloat(this.value);
            if (!isNaN(val) && val >= 0) {
                currentCm = val;
                draw();
            }
        });

        // Drag Events
        canvas.addEventListener('mousedown', startDrag);
        canvas.addEventListener('touchstart', startDrag, {passive: false});

        window.addEventListener('mousemove', moveDrag);
        window.addEventListener('touchmove', moveDrag, {passive: false});

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
            e.preventDefault();
            var dx = getX(e) - startX;
            var newCm = startCm - (dx / pxPerCm);
            if (newCm < 0) newCm = 0;
            currentCm = newCm;

            // Update input (less frequent could be better but this is fine)
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
        var cx = w / 2;

        ctx.clearRect(0, 0, w, h);

        // Background
        ctx.fillStyle = '#fff';
        ctx.fillRect(0,0,w,h);

        // Draw 3 Rulers
        var y1 = 0;
        var y2 = rulerHeight + rulerGap;
        var y3 = (rulerHeight + rulerGap) * 2;

        drawRulerBar(y1, ruler522);
        drawRulerBar(y2, ruler429);
        drawRulerBar(y3, ruler388);

        // Draw Center Indicator (Overlay)
        ctx.beginPath();
        ctx.moveTo(cx, 0);
        ctx.lineTo(cx, h);
        ctx.strokeStyle = '#ff9800'; // Orange line like image
        ctx.lineWidth = 2;
        ctx.stroke();

        // Floating Value Box
        drawFloatingValue(cx, 0);
    }

    function drawFloatingValue(cx, y) {
        var valMM = Math.round(currentCm * 10);
        var text = valMM + " mm";

        ctx.font = 'bold 20px Arial';
        var textMetrics = ctx.measureText(text);
        var boxW = textMetrics.width + 20;
        var boxH = 34;
        var boxX = cx - boxW/2;
        var boxY = y + 10; // Top padding

        // Box Shadow
        ctx.shadowColor = "rgba(0,0,0,0.2)";
        ctx.shadowBlur = 5;
        ctx.shadowOffsetX = 2;
        ctx.shadowOffsetY = 2;

        // Box BG
        ctx.fillStyle = '#f0f0f0'; // Light gray
        ctx.fillRect(boxX, boxY, boxW, boxH);
        ctx.strokeStyle = '#ff9800';
        ctx.lineWidth = 1;
        ctx.strokeRect(boxX, boxY, boxW, boxH);

        // Reset Shadow
        ctx.shadowColor = "transparent";

        // Text
        ctx.fillStyle = '#d9534f'; // Red text
        ctx.textAlign = 'center';
        ctx.textBaseline = 'middle';
        ctx.fillText(text, cx, boxY + boxH/2);
        ctx.textBaseline = 'alphabetic'; // Reset
    }

    function drawRulerBar(y, rulerDef) {
        var w = canvas.width;
        var cx = w / 2;

        // Title
        ctx.fillStyle = '#000';
        ctx.font = 'bold 14px Arial';
        ctx.textAlign = 'left';
        ctx.fillText(rulerDef.title, 5, y + 15);

        // Ruler baseline (start of ticks)
        var topY = y + 25;
        var bottomY = y + rulerHeight;

        // Background for ruler strip
        // ctx.fillStyle = '#f9f9f9';
        // ctx.fillRect(0, topY, w, rulerHeight - 25);

        // Border
        ctx.strokeStyle = '#ccc';
        ctx.lineWidth = 1;
        ctx.strokeRect(0, topY, w, rulerHeight - 25);

        // Ticks Baseline
        var ticksBaseY = topY + 25;

        var zeroX = cx - (currentCm * pxPerCm);
        var minCm = -zeroX / pxPerCm;
        var maxCm = (w - zeroX) / pxPerCm;

        if (minCm < 0) minCm = 0;

        // 1. Draw Segments (Backgrounds & Text)
        var segSize = rulerDef.len / rulerDef.segments.length;
        var startCycle = Math.floor(minCm / rulerDef.len);
        var endCycle = Math.ceil(maxCm / rulerDef.len);

        ctx.textAlign = 'center';

        for (var cy = startCycle; cy <= endCycle; cy++) {
            var cycleStartCm = cy * rulerDef.len;

            for (var i = 0; i < rulerDef.segments.length; i++) {
                var segStartCm = cycleStartCm + (i * segSize);
                var segEndCm = segStartCm + segSize;

                if (segEndCm < minCm || segStartCm > maxCm) continue;

                var x1 = zeroX + (segStartCm * pxPerCm);
                var x2 = zeroX + (segEndCm * pxPerCm);
                var wSeg = x2 - x1;

                var seg = rulerDef.segments[i];
                var color = seg.g ? '#d9534f' : '#000'; // Red / Black

                // Draw Separator Line
                ctx.beginPath();
                ctx.moveTo(x1, ticksBaseY);
                ctx.lineTo(x1, bottomY);
                ctx.strokeStyle = '#ccc';
                ctx.stroke();

                // Major Name
                ctx.fillStyle = color;
                ctx.font = 'bold 16px Arial';
                // Center of Major Segment (Top half)
                var midX = x1 + wSeg/2;
                var majorY = ticksBaseY + 25;
                if (wSeg > 30) {
                     ctx.fillText(seg.n, midX, majorY);
                }

                // --- Sub Segments ---
                var subs = seg.subs || [];
                var numSubs = subs.length;
                var subW = wSeg / numSubs;
                var subY = ticksBaseY + 40; // Start of sub row
                var subH = bottomY - subY;

                ctx.beginPath();
                ctx.moveTo(x1, subY);
                ctx.lineTo(x2, subY);
                ctx.strokeStyle = '#ccc';
                ctx.stroke();

                for (var j = 0; j < numSubs; j++) {
                    var subX1 = x1 + (j * subW);
                    var subX2 = subX1 + subW;
                    var subMidX = subX1 + subW/2;

                    // Sub Separator
                    if (j > 0) {
                        ctx.beginPath();
                        ctx.moveTo(subX1, subY);
                        ctx.lineTo(subX1, bottomY);
                        ctx.stroke();
                    }

                    // Sub Text
                    ctx.font = '11px Arial';
                    ctx.fillStyle = color; // Inherit color

                    if (subW > 15) {
                        var textWidth = ctx.measureText(subs[j]).width;
                        var words = subs[j].split(' ');

                        // If text is too wide and has spaces, split into 2 lines
                        if (textWidth > subW - 2 && words.length > 1) {
                             ctx.font = '10px Arial';
                             // First word
                             ctx.fillText(words[0], subMidX, subY + 14);
                             // Remaining words
                             ctx.fillText(words.slice(1).join(' '), subMidX, subY + 26);
                        } else {
                             // Single line
                             // Adjust font if still too wide?
                             if (textWidth > subW) ctx.font = '10px Arial';
                             ctx.fillText(subs[j], subMidX, subY + 20);
                        }
                    }
                }
            }
        }

        // 2. Draw Ticks (Overlays)
        ctx.beginPath();
        ctx.strokeStyle = '#333';
        ctx.fillStyle = '#000';
        ctx.textAlign = 'center';

        // Optimization: only loop visible range in mm
        // Visible MM range
        var startMM = Math.floor(minCm * 10);
        var endMM = Math.ceil(maxCm * 10);

        for (var mm = startMM; mm <= endMM; mm++) {
            var x = zeroX + (mm / 10 * pxPerCm);

            var tickH = 5; // 1mm
            if (mm % 10 === 0) tickH = 15; // 1cm
            else if (mm % 5 === 0) tickH = 10; // 5mm

            ctx.moveTo(x, ticksBaseY);
            ctx.lineTo(x, ticksBaseY - tickH);

            // Draw Number for CM
            if (mm % 10 === 0) {
                var cmVal = mm / 10;
                if (cmVal % 1 === 0) { // integer cm
                    ctx.font = '10px Arial';
                    // ctx.fillText(cmVal, x, ticksBaseY - 18); // Optional: Draw all CM? Too crowded.
                    // Only draw every 5cm?
                     if (cmVal % 5 === 0) {
                         ctx.fillText(cmVal + "cm", x, ticksBaseY - 18);
                     }
                }
            }
        }
        ctx.stroke();

        // Draw bottom border line for ticks
        ctx.beginPath();
        ctx.moveTo(0, ticksBaseY);
        ctx.lineTo(w, ticksBaseY);
        ctx.strokeStyle = '#333';
        ctx.stroke();
    }

    return {
        init: init
    };

})();
