<form class="form" id="payment-form" method="POST">
    <div class="secure-notice">
      <i class="icon-lock-sm"></i>
      Your information is safe and it will not be stored in our system.  
    </div>
    
    <div class="credit-cards"></div>
    
    <!-- <div class="row">
      <div class="large-6 columns">

        <label for="payment-name">
          Name
          <input type="text" id="payment-name" name="payment-name" required="required">
        </label>

      </div>
      <div class="large-6 columns">
        <label for="payment-email">
          Email
          <input type="text" id="payment-email" name="payment-email" data-type="email" required="required">
        </label>
      </div>
    </div> -->

    <div class="row">
      <div class="large-6 columns">
        <label for="cc-number">
          Credit card number
          <input type="text" id="cc-number" name="cc-number" maxlength="16" data-stripe="number" autocomplete="off" required="required">
        </label>
      </div>

      <div class="large-3 columns">
        <label for="cc-securityCode">
          Security Code
          <input type="text" id="cc-securityCode" name="cc-securityCode" maxlength="4" data-stripe="cvc" autocomplete="off" class="qty-input-3" required="required">
        </label>
      </div>

      <div class="large-3 columns"></div>
    </div>

    <div class="row">
      <label for="cc-expirationYear" style="margin-left:16px;">
        Expiration date
      </label>
      <div class="large-6 columns">  
        <select name="cc-expirationYear" id="cc-expirationYear" data-stripe="exp-year" class="qty-input inline" required="required">
            <?php foreach($years as $year): ?>
                <option value="<?php echo $year;?>" <?php if($current_year == $year):?>selected<?php endif;?>><?php echo $year;?></option>
            <?php endforeach;?>
        </select>      
      </div>

      <div class="large-6 columns">
        <select name="cc-expirationMonth" id="cc-expirationMonth" data-stripe="exp-month" class="qty-input inline" required="required">
              <?php foreach($months as $value => $month): ?>
                  <option value="<?php echo $value;?>" <?php if($current_month == $month):?>selected<?php endif;?>><?php echo $month;?></option>
              <?php endforeach;?>
          </select>
      </div>
    </div>

    <span class="loader-container"></span>
    <div class="row">
        <div class="mt20">
            <div class="large-6 columns">
                <p>
                    <a href="javascript:void(0);" class="btn btn-checkout">Checkout</a>
                </p>
            </div>  

            <div class="paypal large-6 columns" style="margin-top: 14px;">
                <a href="javascript:void(0);" class="pay-via-paypal"> or Pay with PayPal <i class="icon-paypal"></i> </a> 
            </div>
        </div>
    </div>
</form>

<div class="row">
    <div class="mt20">
        <div class="shipping-summary">
            <h3>Shipping Information</h3>
            <div class="row">
                <div class="shipping-info-row group">
                    <div class="large-4 columns">Name</div>  
                    <div class="large-8 columns shipping-info-name"></div>
                </div>
              
            </div>
            <div class="row">
              <div class="shipping-info-row group">
                <div class="large-4 columns">Email</div>  
                <div class="large-8 columns shipping-info-email"></div>  
              </div>
              
            </div>
            <div class="row">
              <div class="shipping-info-row group">
                <div class="large-4 columns">Address</div>  
                <div class="large-8 columns shipping-info-address"></div>  
              </div>
              
            </div>
            <div class="row">
              <div class="shipping-info-row group">
                <div class="large-4 columns">Shipping Speed</div>  
                <div class="large-8 columns shipping-info-speed"></div>  
              </div>
            </div>
            <div class="shipping-info-row">
              <a href="javascript:void(0);" class="edit-shipping">Edit shippping information</a>
            </div>
        </div>
    </div>
</div>