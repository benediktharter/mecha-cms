/**
 * Modal
 * -----
 *
 *    <div class="modal" data-trigger="#my-button">
 *      <h3 class="modal-header">Modal Title</h3>
 *      <a class="modal-close-x" href="#">&times;</a>
 *      <div class="modal-content">
 *        <div class="modal-content-inner">
 *          <p>Test content.</p>
 *        </div>
 *      </div>
 *      <div class="modal-footer">
 *        <button class="btn btn-default modal-close">Close Modal</button>
 *      </div>
 *    </div>
 *
 */

(function($, base) {

    var $body = $(document.body),
        $modal = $('.modal'),
        $close = $('.modal-close, .modal-close-x');

    if (!$modal.length) return;

    $close.on("click", function(e) {
        $(this).closest('.modal').hide().next().hide();
        $body.css({
            position: "",
            overflow: ""
        }).parent().css({
            position: "",
            overflow: ""
        });
        base.fire('on_modal_hide', {
            'event': e,
            'target': this
        });
        return false;
    });

    $modal.each(function() {
        var $this = $(this),
            $trigger = $this.data('trigger') || false,
            $overlay = $('<div class="modal-overlay"></div>'),
            stack = parseInt($this.css('z-index'), 10) - 1;
        $overlay.css('z-index', stack).on("click", function(e) {
            $(this).hide().prev().hide();
            $body.css({
                position: "",
                overflow: ""
            }).parent().css({
                position: "",
                overflow: ""
            });
            base.fire('on_modal_hide', {
                'event': e,
                'target': this
            });
        }).insertAfter($this);
        if (!$(this).find('.modal-close-x').length) {
            $('<a class="modal-close-x" href="#modal:close"><i class="fa fa-times"></i></a>').on("click", function(e) {
                $overlay.trigger("click");
                base.fire('on_modal_hide', {
                    'event': e,
                    'target': this
                });
                return false;
            }).insertAfter($('.modal-header', this));
        }
        if ($trigger) {
            $body.on("click", $trigger, function(e) {
                $this.show().next().show();
                if ($this.hasClass('modal-full-screen')) {
                    $body.css({
                        position: 'static',
                        overflow: 'hidden'
                    }).parent().css({
                        position: 'static',
                        overflow: 'hidden'
                    });
                }
                base.fire('on_modal_show', {
                    'event': e,
                    'target': this
                });
                return false;
            });
        }
    });

})(window.Zepto || window.jQuery, DASHBOARD);