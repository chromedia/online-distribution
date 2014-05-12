<?php echo $header; ?>

<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>

    <br />
    <br />
    <br />

<!-- Left Column -->
<div id="content-left">

    <h1><?php echo $heading_title; ?></h1>

    <h2><?php echo $text_my_account; ?></h2>
    <div class="content">
          <a id="account_info_click"><?php echo "Account Information"; ?></a><br/><br/>
          <a id="change_password_click"><?php echo "Change Password"; ?></a><br/><br/>
          <a id="address_book_click"><?php echo "Address Book"; ?></a><br/><br/>
    </div>


  <h2><?php echo $text_my_orders; ?></h2>
  <div class="content">

      <a id="order_history_click"><?php echo "Order History"; ?></a><br/><br/>
      <!-- <li><a href="<?php echo $return; ?>"><?php echo "Returns"; ?></a></li> -->
      <!-- <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li> -->
 
  </div>

</div>

<!-- Right Column -->
<div id="content-right">

</div>

<?php echo $footer; ?> 

<!-- Load Account Info HTML -->
<script type="text/javascript">
jQuery(function($) {
    $('#account_info_click').on('click', function(event) {

        $(function() {    
            $.ajax({
                url: 'index.php?route=account/edit',
                dataType: 'html',
                success: function(html) {   
                    $('#content-right').html(html);     
                }
            }); 
        });
    });    
});       
</script> 

<!-- Load Password Change HTML -->
<script type="text/javascript">
jQuery(function($) {
    $('#change_password_click').on('click', function(event) {

        $(function() {    
            $.ajax({
                url: 'index.php?route=account/password',
                dataType: 'html',
                success: function(html) {   
                    $('#content-right').html(html);     
                }
            }); 
        });
    });    
});       
</script> 

<!-- Load Address Book HTML -->
<script type="text/javascript">
jQuery(function($) {
    //$('#address_book_click').on('click', function(event) {

        $(function() {    
            $.ajax({
                url: 'index.php?route=account/address',
                dataType: 'html',
                success: function(html) {   
                    $('#content-right').html(html);     
                }
            }); 
        });
    //});    
});       
</script> 

<!-- Load Order History HTML -->
<script type="text/javascript">
jQuery(function($) {
    $('#order_history_click').on('click', function(event) {

        $(function() {    
            $.ajax({
                url: 'index.php?route=account/order',
                dataType: 'html',
                success: function(html) {   
                    $('#content-right').html(html);     
                }
            }); 
        });
    });    
});       
</script> 



