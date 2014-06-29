(function($){
    var sizes = new Array();
    sizes['small'] = '30px';
    sizes['medium'] = '50px';
    sizes['large'] = '70px';
    
    $.fn.showLoader = function(options){
        var settings = $.extend({
            'size' : sizes['medium'],
            'center' : true
        }, options);
        
        var size = settings.size in sizes ? sizes[settings.size] : settings.size;
        var centerStyle = settings.center ? "style='display:block; margin-left:auto; margin-right:auto;'" : ''
   

        return this.append(
            "<div class='loader' style='height:"+size+"'><img "+centerStyle+
                 "src='catalog/view/theme/chromedia/image/loader_" + size + ".GIF' "+ 
                 "width='"+size+"' "+
                 "height='"+size+"' "+
             "/></div>"
        );  
    };
    
    $.fn.removeLoader = function(){
        $('div.loader', this).remove();
        
        return this;
    };
    
})(jQuery);