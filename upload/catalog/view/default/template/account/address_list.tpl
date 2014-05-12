<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

  <h1><?php echo $heading_title; ?></h1>

<span id="response"><br/></span><br/><br/><br/>      

  <?php foreach ($addresses as $result) { ?>
  
    <table style="width: 30%;">
      <tr id="<?php echo 'row' . $result['address_id']; ?>">
        <td><?php echo $result['address']; ?></td>
        <td style="text-align: right;"> &nbsp; <button id="<?php echo $result['address_id']; ?>" class="address_delete_button"><?php echo $button_delete; ?></a></td>
      </tr>
    </table>

  <?php } ?>

<!-- Form HTML -->
<form id="sendform"></form>


<!-- Delete Address -->
<script type="text/javascript"><!--
jQuery(function($) {
    $('.address_delete_button').on('click', function(event) {
        $(event.target).prop('disabled', true);

        // Set form
        var sendform = $('#sendform');
        var address_id = event.target.id;

        // Append address ID
        sendform.append($('<input type="hidden" name="address_id" />').val(address_id));
        sendform.append($('<input type="hidden" name="delete_address" />').val(1));

        // Serialize form data
        var data = sendform.serialize();    

            // Send POST data to server
            $(function() {
                $.ajax({
                    type: "POST",
                    url: "<?php echo HTTPS_SERVER; ?>/catalog/controller/account/account_support.php",
                    data: data,
                    dataType: 'json',
                    success: function(jsondata) {   

                        var message = jsondata.message;

                        $('#row' + event.target.id).remove();
                        $('#response').text(message);
                                     
                    }

                }); 
            });     

        // Clear form
        $("#sendform").children().remove();    
    });
});
//--></script> 