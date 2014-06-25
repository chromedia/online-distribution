(function($){
    $.fn.showConfirmationModal = function(options){
        var self = this;
        var settings = $.extend({
            'contentMessage' : 'Are you sure of doing it?',
            'onConfirm'      : function(element) {},
            'onCancel'       : function(element) {}
        }, options);
        
        $('#confirmation_popup').find('.confirmationMsg').html(settings.contentMessage);
        $('#confirmation_popup').foundation('reveal', 'open');

        $('#confirmation_popup').find('#confirmation_ok').off('click').on('click', function () {
            $('.close-reveal-modal').click();
            
            if (settings.onConfirm) {
                settings.onConfirm(self);
            }

            // $('#confirmation_popup').trigger('reveal:close');;
        });

        // $('#confirmation_popup').find('#confirmation_cancel').off('click').on('click', function () {
        //     if (settings.onCancel) {
        //         settings.onCancel(self);
        //     }
            
        //     $('#confirmation_popup').trigger('reveal:close');;
        // });

        return this;
    };
})(jQuery);