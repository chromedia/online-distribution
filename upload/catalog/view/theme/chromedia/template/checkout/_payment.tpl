<form class="form" id="payment-form">
    <div class="secure-notice">
      <i class="icon-lock-sm"></i>
      Your information is safe and it will not be stored in our system.  
    </div>
    
    <div class="credit-cards"></div>
    <div class="row">
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
    </div>

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

      <div class="large-6 columns">
        <label for="cc-expirationYear">
          Expiration date
          <div>
            <select name="cc-expirationYear" id="cc-expirationYear" data-stripe="exp-year" class="qty-input inline" required="required">
                <?php foreach($years as $year): ?>
                    <option value="<?php echo $year;?>" <?php if($current_year == $year):?>selected<?php endif;?>><?php echo $year;?></option>
                <?php endforeach;?>
            </select>

            <select name="cc-expirationMonth" id="cc-expirationMonth" data-stripe="exp-month" class="qty-input inline" required="required">
                <?php foreach($months as $month): ?>
                    <option value="<?php echo $month;?>" <?php if($current_month == $month):?>selected<?php endif;?>><?php echo $month;?></option>
                <?php endforeach;?>
            </select>
            <!-- <input type="text" class="qty-input inline" placeholder="mm"> -->
            <!-- <input type="text" class="qty-input inline" placeholder="yy">   -->
          </div>
        </label>
      </div>

    </div>

    <div class="row">
      <div class="mt20">
        <div class="large-12 columns">
          <a href="javascript:void(0);" class="btn btn-checkout">Checkout</a>
        </div>  
      </div>
    </div>
  </form>

<?php if(false): ?>
  <div id="block-payment-info" style="padding:30px;">

    <h2>Payment Info</h2>
     
     <!-- Payment Form -->
    <form action="" method="POST" id="payment-form">
    <span class="payment-errors" id="payment-errors"></span>

    <!-- <input type="text" name="email" id="email" value="floricel.colibao@chromedia.com" /> -->

      <!-- Card Number -->
      <div id="column-card-number">
        <div>Credit Card Number</div>
        <div><input type="text" id="field-card-number" maxlength="16" data-stripe="number" autocomplete="off" class="card-number input-medium field-payment" value="4242424242424242"></div>
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

      <!-- Security Code -->
    <div id="column-card-security">
      <div>Security Code</div>
      <div><input type="text" id="field-card-code" maxlength="4" data-stripe="cvc" autocomplete="off" class="card-cvc input-mini field-payment" value="424"></div>
    </div>
    <div id="error-security-code" class="error-payment"></div>

      <input type="submit" class="btn" value="Process Order"/>
      <!-- <button id="pay-button">Pay</button> -->
    </form>

  </div>
<?php endif;?>