(function($){
    $.fn.tab = function(options) {
        this.off('click').on('click', function(e) {
            e.preventDefault();

            activateTab($(this), false);
        });

        activateTab = function(tab, scrollToContent) {
            $('.tabs-content').show();
            $('.tabs-content .product-description').hide();
            $('.product-tabs li a').removeClass('active');
            
            var href = tab.attr('data-content');

            window.location = href;
            $(href).show();

            if (!tab.hasClass('active')) {
                tab.addClass('active');
            }

            if (scrollToContent) {
                var offset = tab.offset();

                $('html, body').animate({
                  scrollTop: offset.top - 80
                }, 1000);   
            }
        }

        displayAnchor();
    }
    
    function displayAnchor() {
        var url = document.location.toString();
        
        setTimeout(function() {
            if (url.match('#')) {
                var anchor = '#' + url.split('#')[1];
                activateTab($('a[data-content="' + anchor + '"]'), true);
            } else {
                $('.tabs-content').show();
            }
        }, 1);
    }
})(jQuery);