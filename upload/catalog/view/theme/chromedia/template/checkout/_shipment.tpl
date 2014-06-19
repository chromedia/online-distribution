<form class="form" id="shipment-form" method="POST">
  <div class="row">

    <div class="large-6 columns">
      <label for="shipping-name">
        Name
        <input type="text" id="shipping-name" name="name" size="25" required="required" />
      </label>
    </div>

    <div class="large-6 columns">
      <label for="shipping-email">
        Email
        <input type="text" id="shipping-email" name="email" size="25" required="required" data-type="email" />
      </label>
    </div>
  </div>
 
  <div class="row">
    <div class="large-12 columns">
      <label for="shipping-street-address">
        Street address
        <input id="shipping-street-address" type="text" name="address" size="25" required="required"  />
      </label>
    </div>
  </div>

  <div class="row">
    <div class="large-6 columns">
      <label for="shipping-city">
        City
        <input id="shipping-city" type="text" name="city" size="25" required="required" />
      </label>
    </div>

    <div class="large-6 columns">
      <label for="shipping-country">
        Country

        <select name="country" id="shipping-country">
          <?php foreach($countries as $country): ?>
            <option value="<?php echo $country['iso_code_2']; ?>" <?php if('US' == $country['iso_code_2']): ?>selected="selected"<?php endif?>><?php echo $country['name']; ?></option>
          <?php endforeach; ?>
        </select>

      </label>
    </div>
  </div>

  <div class="row">
    <div class="large-9 columns">
      <label for="state">
        State / Province

        <select name="state" id="shipping-us-states" required="required">
          <?php foreach($us_states as $state): ?>
            <option value="<?php echo $state['iso_code_2'];?>"><?php echo $state['name'];?></option>
          <?php endforeach;?>
        </select>

        <select name="state" id="shipping-canada-regions" style="display:none;" disabled>
          <?php foreach($canada_regions as $region): ?>
            <option value="<?php echo $region['iso_code_2'];?>"><?php echo $region['name'];?></option>
          <?php endforeach;?>
        </select>

        <input type="text" id="shipping-province" name="state" style="display:none;" disabled/>
       <!--  <input type="text" name="state" maxlength="2" required="required"/>
        <input type="text" name="state" maxlength="2" required="required" style=""/> -->
      </label>
    </div>

    <div class="large-3 columns">
      <label for="field-shipping-postcode">
        Postal Code
        <input id="field-shipping-postcode" type="text" name="postcode" size="10" required="required" />
      </label>
    </div>
    
  </div>

  <input type="submit" value="Check Rates" id="shipment-check-rates"/>
</form>

<div id="display-on-rates-checked" style="display:none;">
  <!-- <div id="display-on-rates-checked"> -->
    <div class="row">
        <div class="large-12 columns">
          <div class="shipping-selection">
            <h3>Choose a shipping speed</h3>
          </div>
        </div>
    </div>

    <div class="row">
        <div class="mt20">
          <div class="large-12 columns">
            <label for="cc-info" class="use-cc-info">
              <input type="checkbox" id="use-cc-info-checkbox" name="use-cc-info-checkbox"> Use this information for my credit card details
            </label>  
          </div>  
        </div>
    </div>

    <div class="row">
        <div class="mt20">
          <div class="large-12 columns">
            <a href="javascript:void(0);" id="step2-trigger-btn" class="btn">Next: Enter your credit card details</a>
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