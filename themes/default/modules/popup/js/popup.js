var NV_POPUP = {
    shown: [],

    setCookie: function(name, value, minutes) {
        var expires = "";
        if (minutes) {
            var date = new Date();
            date.setTime(date.getTime() + (minutes * 60 * 1000));
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "")  + expires + "; path=/";
    },

    getCookie: function(name) {
        var nameEQ = name + "=";
        var ca = document.cookie.split(';');
        for(var i=0;i < ca.length;i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1,c.length);
            if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
        }
        return null;
    },

    log: function(id, action) {
        $.post(nv_base_siteurl + 'index.php?' + nv_lang_variable + '=' + nv_lang_data + '&' + nv_name_variable + '=popup&' + nv_fc_variable + '=ajax', {
            action: 'log',
            id: id,
            type: action,
            nv_ajax: 1
        });
    },

    show: function(id) {
        if (this.shown.indexOf(id) !== -1) return; // Already shown in this page load
        this.shown.push(id);

        var $el = $('#nv-popup-' + id);
        var isModal = $el.hasClass('nv-popup-modal');

        if (isModal) {
            $el.modal('show');
            // Bootstrap modal close event
            $el.on('hidden.bs.modal', function () {
                NV_POPUP.close(id);
            });
        } else {
            // Check position for slide effect
            if ($el.hasClass('popup-bar_top') || $el.hasClass('popup-bar_bottom')) {
                $el.slideDown(500); // 500ms slide effect
            } else {
                $el.fadeIn();
            }
        }

        // Log View
        this.log(id, 'view');

        // Set Cookie (Frequency)
        // If frequency > 0, set expiration. If 0, session cookie (default behavior of setCookie without minutes is session? No, need to verify).
        // My setCookie impl: if minutes is falsy/0/undefined, expires is empty string -> Session cookie. Correct.
        var freq = parseInt($el.data('frequency'));
        this.setCookie('nv_popup_' + id, '1', freq > 0 ? freq : 0);

        // Track Clicks
        $el.find('.nv-popup-content a').on('click', function() {
            NV_POPUP.log(id, 'click');
        });
    },

    close: function(id) {
        var $el = $('#nv-popup-' + id);
        var isModal = $el.hasClass('nv-popup-modal');

        if (!isModal) {
            if ($el.hasClass('popup-bar_top') || $el.hasClass('popup-bar_bottom')) {
                $el.slideUp(500);
            } else {
                $el.fadeOut();
            }
        }
        this.log(id, 'close');
    }
};

function nv_popup_close(id) {
    NV_POPUP.close(id);
}

function nv_popup_init(id, type, value) {
    // Check client-side cookie to prevent F5 re-show if PHP layer didn't catch it (e.g. block caching)
    if (NV_POPUP.getCookie('nv_popup_' + id)) {
        return;
    }

    if (type === 'immediate') {
        NV_POPUP.show(id);
    } else if (type === 'delay') {
        setTimeout(function() {
            NV_POPUP.show(id);
        }, value * 1000);
    } else if (type === 'scroll') {
        var scrollHandler = function() {
            if ($(window).scrollTop() > value) {
                NV_POPUP.show(id);
                $(window).off('scroll', scrollHandler);
            }
        };
        $(window).on('scroll', scrollHandler);
    }
}
