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

 var removeProduct = function(element) {
    var closestContainer = element.closest('.group');

    $.ajax({
        type: "POST",
        url: "index.php?route=checkout/cart/removeProductInCart",
        data: {key : element.attr('key')},
        dataType: 'json',
        beforeSend: function() {
            closestContainer.css({'opacity' : 0.5});
            closestContainer.find('input').attr('readonly', true);
        },
        success: function(jsondata) {
            if (jsondata.success) {
                closestContainer.remove();

                if (jsondata.total == 0) {
                    window.location = 'index.php?route=checkout/cart';
                }

                updateSubTotal(jsondata.total);
                updateProductsCount(jsondata.productsCount);

                refreshShipmentData();
            } else {
                closestContainer.css({'opacity' : 1});
                closestContainer.find('input').attr('readonly', false);

                alert(jsondata.msg);
            }
        },
        error: function(error) {
            closestContainer.css({'opacity' : 1});
            closestContainer.find('input').attr('readonly', false);

            alert(error);
        }
    });
}

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
    $(this).showConfirmationModal({
        'contentMessage' : 'Are you sure you want to remove this product?',
        'onConfirm'      : removeProduct
    });
}); 