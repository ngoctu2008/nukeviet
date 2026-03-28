<!-- BEGIN: main -->
<link rel="stylesheet" href="{NV_BASE_SITEURL}themes/default/modules/popup/css/popup.css">

<!-- BEGIN: modal -->
<div class="modal fade nv-popup-modal" id="nv-popup-{ROW.id}" tabindex="-1" role="dialog"
     data-id="{ROW.id}"
     data-frequency="{ROW.frequency}"
     data-trigger-type="{ROW.trigger_config.type}"
     data-trigger-value="{ROW.trigger_config.value}">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-body position-relative p-0">
                <button type="button" class="close position-absolute" style="top: 10px; right: 10px; z-index: 1050;" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <div class="nv-popup-content">
                    {ROW.content}
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END: modal -->

<!-- BEGIN: banner -->
<div class="nv-popup-banner {POSITION_CLASS}" id="nv-popup-{ROW.id}" style="display:none;"
     data-id="{ROW.id}"
     data-frequency="{ROW.frequency}"
     data-trigger-type="{ROW.trigger_config.type}"
     data-trigger-value="{ROW.trigger_config.value}">
    <div class="nv-banner-wrapper">
        <button type="button" class="nv-banner-close" onclick="nv_popup_close({ROW.id})">&times;</button>
        <div class="nv-popup-content">
            {ROW.content}
        </div>
    </div>
</div>
<!-- END: banner -->

<script src="{NV_BASE_SITEURL}themes/default/modules/popup/js/popup.js"></script>
<script>
    $(document).ready(function() {
        // Initialize popups
        $('.nv-popup-modal, .nv-popup-banner').each(function() {
            var $popup = $(this);
            var id = $popup.data('id');
            var triggerType = $popup.data('trigger-type');
            var triggerValue = parseInt($popup.data('trigger-value'));

            nv_popup_init(id, triggerType, triggerValue);
        });
    });
</script>
<!-- END: main -->
