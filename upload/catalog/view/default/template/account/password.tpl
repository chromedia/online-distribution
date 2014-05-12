<!-- Change Password -->
<h1><?php echo "Change Password"; ?></h1>

<span id="change_password_content"></span><br/><br />

    <form action="" method="post" enctype="multipart/form-data" id="change_password">

        <!-- New Password -->
        <?php echo "New Password"; ?><br /><br />
        <input type="password" name="password" value="" /><br /><br />

        <!-- Confirm New Password -->
        <?php echo "Confirm New Password"; ?><br /><br />
        <input type="password" name="confirm" value="" /><br /><br />

        <!-- Submit Button -->        
        <button type="button" id="button_password">Update Password</button>
        <input type="hidden" name="change_password" value="1" />

    </form>

    <!-- Change Password Call -->
<script type="text/javascript"><!--
jQuery(function($) {
  $('#button_password').on('click', function(event) {
  $('#button_password').prop('disabled', true);

  var sendform = $('#change_password');
  var data = sendform.serialize();    

        // Send POST data to server
        $(function() {
            $.ajax({
                type: "POST",
                url: "<?php echo HTTPS_SERVER; ?>/catalog/controller/account/account_support.php",
                data: data,
                dataType: 'json',
                success: function(jsondata) {   

                    var valid = jsondata.valid;
                    var message = jsondata.message;

                    $('#button_password').prop('disabled', false); 
                    $('#button_password').text(valid); 
                    $('#change_password_content').text(message);
                                 
                }

            }); 
        });     
    });
});
//--></script> 