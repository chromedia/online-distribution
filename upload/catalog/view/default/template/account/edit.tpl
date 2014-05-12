
<h1><?php echo $heading_title; ?></h1>

<span id="response"></span><br/><br/>

    <form id="account_info_form" action="" method="post" enctype="multipart/form-data">

            <!-- Account Email -->
            <?php echo $email; ?><br/><br/>

            <!-- Name -->    
            <?php echo "Name"; ?><br/>
            <input type="text" maxlength="30" name="firstname" value="<?php echo $firstname; ?>" />
            <input type="text" maxlength="30" name="lastname" value="<?php echo $lastname; ?>" /><br/><br/>

            <?php if ($error_firstname) { ?>
            <span class="error"><?php echo $error_firstname; ?></span>
            <?php } ?>

            <?php if ($error_lastname) { ?>
            <span class="error"><?php echo $error_lastname; ?></span>
            <?php } ?>

            <!-- Phone Number -->
            <?php echo $entry_telephone; ?><br/>
            <input type="text" maxlength="30" name="phone" value="<?php echo $telephone; ?>" /><br/><br/>

            <?php if ($error_telephone) { ?>
            <span class="error"><?php echo $error_telephone; ?></span>
            <?php } ?>
            
     
            <button id="button_account_info" type="button" class="button">Update</button>
            <input type="hidden" name="account_info" value="1" />

    </form>
</div>

<!-- Change Password Call -->
<script type="text/javascript"><!--
jQuery(function($) {
    $('#button_account_info').on('click', function(event) {
        $('#button_account_info').prop('disabled', true);

        var sendform = $('#account_info_form');
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

                        $('#button_account_info').prop('disabled', false); 
                        $('#button_account_info').text(valid); 
                        $('#response').text(message);
                                     
                    }

                }); 
            });     
    });
});
//--></script> 