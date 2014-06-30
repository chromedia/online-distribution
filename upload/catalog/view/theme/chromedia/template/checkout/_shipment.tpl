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
      </label>
      <select name="country" id="shipping-country">
          <?php foreach($countries as $country): ?>
            <option value="<?php echo $country['iso_code_2']; ?>" <?php if('US' == $country['iso_code_2']): ?>selected="selected"<?php endif?>><?php echo $country['name']; ?></option>
          <?php endforeach; ?>
        </select>
    </div>
  </div>

  <div class="row">
    <div class="large-9 columns state-container">
      <label for="state">
        State / Province
        <input type="text" id="shipping-province" name="state" style="display:none;" disabled/>
       <!--  <input type="text" name="state" maxlength="2" required="required"/>
        <input type="text" name="state" maxlength="2" required="required" style=""/> -->
      </label>

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
    </div>

    <div class="large-3 columns">
      <label for="field-shipping-postcode">
        Postal Code
        <input id="field-shipping-postcode" type="text" name="postcode" size="10" required="required" />
      </label>
    </div>
    
  </div>

  <!-- <a href="javascript:void(0);" class="btn btn-small">Check shipping rates</a> -->
  <input type="submit" value="Check Shipping Rates" class="btn btn-small" />
</form>

  <div class="row display-on-rates-checked" style="display:none;">
      <div class="large-12 columns">
        <div class="shipping-selection">
          <h3 class="items-header">Choose a shipping speed</h3>
        </div>
      </div>
  </div>

<!--   <div class="row display-on-rates-checked" style="display:none;">
      <div class="mt20">
        <div class="large-12 columns">
          <label for="cc-info" class="use-cc-info">
            <input type="checkbox" id="use-cc-info-checkbox" name="use-cc-info-checkbox"> Use this information for my credit card details
          </label>  
        </div>  
      </div>
  </div> -->

  <div class="row display-on-rates-checked" style="display:none;">
      <div class="mt20">
        <div class="large-12 columns">
          <a href="javascript:void(0);" id="step2-trigger-btn" class="btn">Next: Enter your credit card details</a>
        </div>  
      </div>
  </div>