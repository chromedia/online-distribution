(function($){
    var sizes = new Array();
    sizes['small'] = '20px';
    sizes['medium'] = '50px';
    sizes['large'] = '70px';
    
    $.fn.showLoader = function(options){
        var settings = $.extend({
            'size' : sizes['medium'],
            'center' : true
        }, options);
        
        var size = settings.size in sizes ? sizes[settings.size] : settings.size;
        var centerStyle = settings.center ? "style='display:block; margin-left:auto; margin-right:auto;'" : ''
        
        // "<img "+centerStyle+
        //          "src='../image/loader_" + size + ".gif' "+ 
        //          "width='"+size+"' "+
        //          "height='"+size+"' "+
        //      "/>"+

        return this.append(
            "<div class='loader' style='height:"+size+"'><em>Processing...</em></div>"
        );  
    };
    
    $.fn.removeLoader = function(){
        $('div.loader', this).remove();
        
        return this;
    };
    
})(jQuery);