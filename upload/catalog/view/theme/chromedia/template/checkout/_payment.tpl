
<form class="form" action="">
    <div class="secure-notice">
      <i class="icon-lock-sm"></i>
      Your information is safe and it will not be stored in our system.  
    </div>
    
    <div class="credit-cards"></div>
    <div class="row">
      <div class="large-6 columns">

        <label for="" class="has-error">
          First Name
          <input type="text" class="has-error">
        </label>
      </div>
      <div class="large-6 columns">
        <label for="">
          Last Name
          <input type="text">
        </label>
      </div>
    </div>
    <div class="row">
      <div class="large-6 columns">
        <label for="">
          Credit card number
          <input type="text">
        </label>
      </div>
      <div class="large-3 columns">
        <label for="">
          Security Code
          <input type="text" class="qty-input-3">
        </label>
      </div>
      <div class="large-3 columns"></div>
    </div>
    <div class="row">
      <div class="large-6 columns">
        <label for="">
          Expiration date
          <div>
            <input type="text" class="qty-input inline" placeholder="mm">
            <input type="text" class="qty-input inline" placeholder="yy">  
          </div>
          
        </label>
      </div>
    </div>

    <div class="row">
      <div class="mt20">
        <div class="large-12 columns">
          <a href="" class="btn btn-checkout">Checkout</a>
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