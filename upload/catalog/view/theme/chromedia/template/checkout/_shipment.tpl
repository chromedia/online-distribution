<form class="form" id="shipment-form">
  <div class="row">
    <div class="large-6 columns">
      <label for="">
        First Name
        <input type="text">
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
        Email
        <input type="text">
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-12 columns">
      <label for="">
        Street address
        <input type="text">
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-6 columns">
      <label for="">
        City
        <input type="text">
      </label>
    </div>
  </div>
  <div class="row">
    <div class="large-9 columns">
      <label for="">
        State / Province
        <input type="text">
      </label>
    </div>
    <div class="large-3 columns">
      <label for="">
        Postal Code
        <input type="text">
      </label>
    </div>
  </div>

  <input type="submit" value="Check Rates" id="shipment-check-rates"/>
</form>

<div style="display:none;">
    <div class="row">
        <div class="large-12 columns">
          <div class="shipping-selection">
            <h3>Choose a shipping speed</h3>
             <!--  <label for="ship-regular">
                <input type="radio" id="ship-regular" name="shipping-speed" checked> Regular  <em>(average of 5 to 10 days)</em>
              </label>  
              <label for="ship-express">
                <input type="radio" id="ship-express" name="shipping-speed"> Express <em>(average of 2 to 4 days)</em>
              </label>     -->
          </div>
        </div>
    </div>

    <div class="row">
        <div class="mt20">
          <div class="large-12 columns">
            <label for="cc-info" class="use-cc-info">
              <input type="checkbox" id="cc-info" name="cc-info"> Use this information for my credit card details
            </label>  
          </div>  
        </div>
    </div>

    <div class="row">
        <div class="mt20">
          <div class="large-12 columns">
            <a href="javascript:void(0);" class="btn">Next: Enter your credit card details</a>
          </div>  
        </div>
    </div>
</div>


<?php if(false): ?>
  <div style="padding:30px;">

      <!-- Shipping -->
      <form action="" method="POST" id="shipping-form-new">
        <input type="hidden" name="shipping_address" value="1"/>
          <!-- Name Column -->
          <div id="column-shipping-name">
            <div><?php echo "Name"; ?></div>
            <div><input id="field-shipping-name" type="text" value="Laura Behrens Wu" name="name" size="25" /></div>
          </div>  

          <!-- Street Address Column -->
          <div id="column-shipping-street-address">
            <?php echo "Street Address"; ?>
            <input id="field-shipping-street-address" type="text" value="3002 New Yort St" name="address" size="25" />
          </div>  

          <!-- City -->
          <div id="column-shipping-city">
            <div><?php echo 'City'; ?></div>
            <div><input id="field-shipping-city" type="text" value="Denver" name="city" size="25" /></div>
          </div>

          <!-- State/Province -->
          <div id="column-shipping-region">
            <div><?php echo "State/Province"; ?></div>
            <input id="field-shipping-region" type="text" value="CO" name="state" maxlength="2"/>
          </div>    

          <!-- Postal Code -->
          <div id="column-shipping-postcode">
            <div><?php echo "Postal Code"; ?></div>
            <div><input id="field-shipping-postcode" type="text" value="80205" name="postcode" size="10" /></div>
          </div>
          

          <!-- Country and Postal Code -->
          <div id="column-shipping-country">
            <div><?php echo "Country"; ?></div>
            <div><input id="field-shipping-country" type="text" value="US"  name="country" size="25" /></div>
          </div>

          <!-- Email Column -->
          <div id="column-shipping-email">
            <div><?php echo "Email"; ?></div>
            <div><input id="field-shipping-email" type="text" value="floricel.colibao@chromedia.com" name="email" size="25" /></div>
          </div>        
        
          <button class="shipping-packages">Check Available Shipments</button>
      </form>

        <!-- Shipping Speeds Block -->
        <div class="block-shipping-speeds" style="display:none;">
          <h2>Shipping Speeds</h2>
        </div>
      </div>
    </div>
<?php endif;?>