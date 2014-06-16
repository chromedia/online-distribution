<?php echo $header; ?>

<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

    <div class="content-right">
      <h1><?php echo $heading_title; ?>
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="cart-info">
          <table>
            <thead>
              <tr>
                <td class="image"><?php echo $column_image; ?></td>
                <td class="name"><?php echo $column_name; ?></td>
                <td class="model"><?php echo $column_model; ?></td>
                <td class="quantity"><?php echo $column_quantity; ?></td>
                <td class="price"><?php echo $column_price; ?></td>
                <td class="total"><?php echo $column_total; ?></td>
                <td class="action">Action</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product): ?>
              <tr>
                <td class="image"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                  <?php } ?></td>

                <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></td>
                <td class="model"><?php echo $product['model']; ?></td>
                <td class="quantity"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" /></td>
                <td class="price"><?php echo $product['price']; ?></td>
                <td class="total"><?php echo $product['total']; ?></td>
                <td class="action">  <input type="image" src="catalog/view/theme/default/image/update.png" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />
                  &nbsp;<a href="<?php echo $product['remove']; ?>"><img src="catalog/view/theme/default/image/remove.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" /></a></td>
              </tr>
             <?php endforeach;?>

            </tbody>
          </table>
        </div>
      </form>

      <div class="cart-total">
        <table id="total">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="right"><b><?php echo $total['title']; ?>:</b></td>
            <td class="right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>

      <!-- temporary -->
      <div class="content-left">
          <div id="shipping-new">
            <h3>Shipping Info</h3>

            <!-- existing address -->
            <?php if($addresses) { ?>

                <!-- Existing address selector -->
                <input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
                <label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>

                <!-- Existing Addresses -->
                <form action="" method="POST" id="shipping-form-existing">
                <input type="hidden" name="shipping_address" value="1"/>
                <div id="shipping-existing" style="overflow:auto;width:100%;height:auto;margin-bottom:15px;" align="left">
                  <select name="address_id" size="5">
                  <?php foreach ($addresses as $address) { ?>
                  <?php if ($address['address_id'] == $address_id) { ?>


                  <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>

                  <?php } ?>
                  <?php } ?>
                  </select>
                </div>
                </form>

                <!-- New address selector -->
                <p>
                  <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
                  <label for="shipping-address-new"><?php echo $text_address_new; ?></label>
                </p>

              <?php } ?>

        <!-- Shipping -->
            <form action="" method="POST" id="shipping-form-new">
              <input type="hidden" name="shipping_address" value="1"/>

              <div class="field-shipping">

                <!-- Name Column -->
                <div id="column-shipping-name">
                  <div><?php echo "Name"; ?></div>
                  <div><input id="field-shipping-name" type="text" name="name" size="25" /></div>
                </div>  

                <!-- Street Address Column -->
                <div id="column-shipping-street-address">
                  <?php echo "Street Address"; ?>
                  <input id="field-shipping-street-address" type="text" name="address" size="25" />
                </div>  

                <!-- City -->
                <div id="column-shipping-city">
                  <div><?php echo 'City'; ?></div>
                  <div><input id="field-shipping-city" type="text" name="city" size="25" /></div>
                </div>

                <!-- State/Province -->
                <div id="column-shipping-region">
                  <div><?php echo "State/Province"; ?></div>
                  <input id="field-shipping-region" type="text" name="region" maxlength="2"/>
                </div>    

                <!-- Postal Code -->
                <div id="column-shipping-postcode">
                  <div><?php echo "Postal Code"; ?></div>
                  <div><input id="field-shipping-postcode" type="text" name="postcode" size="10" /></div>
                </div>
                

                <!-- Country and Postal Code -->
                <div id="column-shipping-country">
                  <div><?php echo "Country"; ?></div>
                  <div><input id="field-shipping-country" type="text" name="country" size="25" /></div>
                </div>

                <!-- Email Column -->
                <div id="column-shipping-email">
                  <div><?php echo "Email"; ?></div>
                  <div><input id="field-shipping-email" type="text" name="email" size="25" /></div>
                </div>        

              </div>  

            </form>

            <!-- Shipping Speeds Block -->
            <div id="block-shipping-speeds">

              <div id="title-shipping-speeds" class="title-cart" >Shipping Speeds</div>
              <div id="status-shipping-speeds" class="title-cart"></div>
              <br/>
              <br/>

              <form id="form-shipping-speeds">
                <div>Please enter your shipping information in order to retrieve speeds and rates</div>
              </form>

            </div>
            
          </div>

          <!-- Payment -->
          <div id="block-payment-info">
            <h3>Payment Info</h3>
            <div id="status-payment-info" class="title-cart"></div>
            <br/>
            <br/>
             
             <!-- Payment Form -->
            <form action="" method="POST" id="payment-form">
            <span class="payment-errors" id="payment-errors"></span>

              <!-- Card Number -->
              <div id="column-card-number">
                <div>Credit Card Number</div>
                <div><input type="text" id="field-card-number" maxlength="16" data-stripe="number" autocomplete="off" class="card-number input-medium field-payment"></div>
              </div>
              <div id="error-card-number" class="error-payment"></div>

              <!-- Expiry Date -->
              <div id="column-card-expiration">
                <div>Expiration Date</div>
              <select id="field-card-month" data-stripe="exp-month" class="card-expiry-month input-mini field-payment">
                        <?php 
                        for ($i=1; $i<13; $i++){
                            // Add 0 to single digit numbers
                            if (strlen((string)$i) < 2) { 
                                $show = '0' . (string)$i;
                            }
                            else {
                                $show = $i;
                            }
                            ?><option value="<?php echo $i; ?>"><?php echo $show; ?></option><?php
                        }
                        ?>  
                     </select>
                     <span> / </span>
                     <select id="field-card-year" data-stripe="exp-year" class="card-expiry-year input-mini field-payment">
                        <?php 
                        $year = date("Y");
                        $yearlimit = $year + 8;
                        for ($i=$year; $i<$yearlimit; $i++){
                          ?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
                          }
                        ?>  
                  </select>
              </div>
              <div id="error-expiry-month" class="error-payment"></div>
              <div id="error-expiry-year" class="error-payment"></div>

              <!-- Security Code -->
            <div id="column-card-security">
              <div>Security Code</div>
              <div><input type="text" id="field-card-code" maxlength="4" data-stripe="cvc" autocomplete="off" class="card-cvc input-mini field-payment" value="424"></div>
            </div>
            <div id="error-security-code" class="error-payment"></div>

            </form>
        </div>
      </div>
    </div>


      <div class="buttons">
        <div class="right"><a href="<?php echo $checkout; ?>" class="button"><?php echo $button_checkout; ?></a></div>
        <div class="center"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_shopping; ?></a></div>
      </div>
      
  </div>

<script type="text/javascript"><!--
$('input[name=\'next\']').bind('change', function() {
    $('.cart-module > div').hide();
    
    $('#' + this.value).show();
});
//--></script>
<?php if ($shipping_status) { ?>
<script type="text/javascript"><!--
// $('#button-quote').live('click', function() {
//     $.ajax({
//         url: 'index.php?route=checkout/cart/quote',
//         type: 'post',
//         data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
//         dataType: 'json',       
//         beforeSend: function() {
//             $('#button-quote').attr('disabled', true);
//             $('#button-quote').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
//         },
//         complete: function() {
//             $('#button-quote').attr('disabled', false);
//             $('.wait').remove();
//         },      
//         success: function(json) {
//             $('.success, .warning, .attention, .error').remove();           
                        
//             if (json['error']) {
//                 if (json['error']['warning']) {
//                     $('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    
//                     $('.warning').fadeIn('slow');
                    
//                     $('html, body').animate({ scrollTop: 0 }, 'slow'); 
//                 }   
                            
//                 if (json['error']['country']) {
//                     $('select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
//                 }   
                
//                 if (json['error']['zone']) {
//                     $('select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
//                 }
                
//                 if (json['error']['postcode']) {
//                     $('input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
//                 }                   
//             }
            
//             if (json['shipping_method']) {
//                 html  = '<h2><?php echo $text_shipping_method; ?></h2>';
//                 html += '<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">';
//                 html += '  <table class="radio">';
                
//                 for (i in json['shipping_method']) {
//                     html += '<tr>';
//                     html += '  <td colspan="3"><b>' + json['shipping_method'][i]['title'] + '</b></td>';
//                     html += '</tr>';
                
//                     if (!json['shipping_method'][i]['error']) {
//                         for (j in json['shipping_method'][i]['quote']) {
//                             html += '<tr class="highlight">';
                            
//                             if (json['shipping_method'][i]['quote'][j]['code'] == '<?php echo $shipping_method; ?>') {
//                                 html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" checked="checked" /></td>';
//                             } else {
//                                 html += '<td><input type="radio" name="shipping_method" value="' + json['shipping_method'][i]['quote'][j]['code'] + '" id="' + json['shipping_method'][i]['quote'][j]['code'] + '" /></td>';
//                             }
                                
//                             html += '  <td><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['title'] + '</label></td>';
//                             html += '  <td style="text-align: right;"><label for="' + json['shipping_method'][i]['quote'][j]['code'] + '">' + json['shipping_method'][i]['quote'][j]['text'] + '</label></td>';
//                             html += '</tr>';
//                         }       
//                     } else {
//                         html += '<tr>';
//                         html += '  <td colspan="3"><div class="error">' + json['shipping_method'][i]['error'] + '</div></td>';
//                         html += '</tr>';                        
//                     }
//                 }
                
//                 html += '  </table>';
//                 html += '  <br />';
//                 html += '  <input type="hidden" name="next" value="shipping" />';
                
//                 <?php if ($shipping_method) { ?>
//                 html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" />';    
//                 <?php } else { ?>
//                 html += '  <input type="submit" value="<?php echo $button_shipping; ?>" id="button-shipping" class="button" disabled="disabled" />';    
//                 <?php } ?>
                            
//                 html += '</form>';
                
//                 $.colorbox({
//                     overlayClose: true,
//                     opacity: 0.5,
//                     width: '600px',
//                     height: '400px',
//                     href: false,
//                     html: html
//                 });
                
//                 $('input[name=\'shipping_method\']').bind('change', function() {
//                     $('#button-shipping').attr('disabled', false);
//                 });
//             }
//         }
//     });
// });
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
    $.ajax({
        url: 'index.php?route=checkout/cart/country&country_id=' + this.value,
        dataType: 'json',
        beforeSend: function() {
            $('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
        },
        complete: function() {
            $('.wait').remove();
        },          
        success: function(json) {
            if (json['postcode_required'] == '1') {
                $('#postcode-required').show();
            } else {
                $('#postcode-required').hide();
            }
            
            html = '<option value=""><?php echo $text_select; ?></option>';
            
            if (json['zone'] != '') {
                for (i = 0; i < json['zone'].length; i++) {
                    html += '<option value="' + json['zone'][i]['zone_id'] + '"';
                    
                    if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                        html += ' selected="selected"';
                    }
    
                    html += '>' + json['zone'][i]['name'] + '</option>';
                }
            } else {
                html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
            }
            
            $('select[name=\'zone_id\']').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('select[name=\'country_id\']').trigger('change');
//--></script>
<?php } ?>
<?php echo $footer; ?>
