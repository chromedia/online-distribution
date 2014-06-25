(function($){
    $.fn.addToCart = function(options){
        var settings = $.extend({
            'product_id'     : 0,
            'quantity_input' : $('input[name="quantity"]'),
            'on_success'     : function() {},
            'on_error'       : null
        }, options);

        this.off('click').on('click', function() {
            var data = { product_id : settings.product_id, quantity : settings.quantity_input.val() };
        
            // Send POST data to server
            $(function() {
                $.ajax({
                    url: 'index.php?route=checkout/cart/add',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    success: function(json) {
                        $('.items-in-cart').show();
                        $('.items-in-cart').html(json.total);
                        settings.on_success(json);
                    },
                    error: function(error) {
                        if (settings.on_error) {
                            settings.on_error(error);
                        } else {
                            alert('There is an error while adding product to cart.');
                        }  
                    }
                });
            });
        });
    };
})(jQuery);


$('.qty-in-cart').on('input', function() {
    var qtyInput = $(this);
    var quantity = qtyInput.val();

    quantity = quantity.length > 0 ? quantity : 1;

    if (quantity) {
        var data = {
            quantity : quantity,
            key : $(this).attr('key')
        };

        $.ajax({
            type: "POST",
            url: 'index.php?route=checkout/cart/updateCartProductQuantity',
            data: data,
            dataType: 'json',
            beforeSend: function() {
                qtyInput.css({'opacity' : 0.5});
                qtyInput.attr('readonly', true);
            },
            success: function(jsondata) {
                qtyInput.css({'opacity' : 1});
                qtyInput.prop('readonly', false);

                updateSubTotal(jsondata.total);
                updateProductsCount(jsondata.productsCount);

                refreshShipmentData();
            },
            error: function(error) {
                qtyInput.css({'opacity' : 1});
                qtyInput.prop('readonly', false);

                alert(error);
            }
        });
    }
});

$('.remove').off('click').on('click', function() {
    removeProduct($(this));
}); 