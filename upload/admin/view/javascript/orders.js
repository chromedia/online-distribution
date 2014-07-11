


// Toggle Display of Order Contents
$(".contents1").on("click", function(event){
	var target = $( event.target );
	target.parent().children(".contents2").toggle();
});
$(".contents2").hide();

// Change Order Status
$(".new_status").on("change", function(event){
	var target = $( event.target );
	var new_status = target.attr('value');
	var order_id = target.siblings(".order_id").attr('value');
	var data = { new_status : new_status, order_id : order_id };
	var token = $('#token').attr('value');

	if(new_status != "null"){
		// Send POST data to server
		$(function() {
			$.ajax({
				url: 'index.php?route=sale/order/changeOrderStatus&token='+token,
				type: 'post',
				data: data,
				dataType: 'json',
				success: function(json) {
					//console.log(json);
					target.siblings(".current_status").text("");
				},
				error: function(error) {
					//console.log("error");
				}
			});
		});
	}
});
