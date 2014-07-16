<form class="form" id="shipment-form" method="POST">
  <div class="row">

    <div class="large-6 columns">
      <label for="shipping-name">
        Name
        <input type="text" id="shipping-name" name="name" size="25" value="<?php echo isset($shipping['name']) ? $shipping['name'] : ''; ?>" required="required" />
      </label>
    </div>

    <div class="large-6 columns">
      <label for="shipping-email">
        Email
        <input type="text" id="shipping-email" name="email" size="25" required="required" value="<?php echo isset($shipping['email']) ? $shipping['email'] : ''; ?>" data-type="email" />
      </label>
    </div>
  </div>
 
  <div class="row">
    <div class="large-12 columns">
      <label for="shipping-street-address">
        Street address
        <input id="shipping-street-address" type="text" name="address" size="25" required="required" value="<?php echo isset($shipping['address']) ? $shipping['address'] : ''; ?>" />
      </label>
    </div>
  </div>

  <div class="row">
    <div class="large-8 columns">
      <label for="shipping-city">
        City
        <input id="shipping-city" type="text" value="<?php echo isset($shipping['city']) ? $shipping['city'] : ''; ?>" name="city" size="25" required="required" />
      </label>
    </div>

    <div class="large-4 columns">
      <label for="field-shipping-postcode">
        Postal Code
        <input id="field-shipping-postcode" type="text" name="postcode" size="10" required="required" value="<?php echo isset($shipping['postcode']) ? $shipping['postcode'] : ''; ?>" />
      </label>
    </div>
  </div>

  <div class="row">
    <div class="large-6 columns">
      <label for="shipping-country">
        Country
      </label>
      <select name="country" id="shipping-country">
          <?php foreach($countries as $country): ?>
            <?php if(isset($shipping['country'])): ?>
              <option value="<?php echo $country['iso_code_2']; ?>" <?php if($shipping['country'] == $country['iso_code_2']):?>selected="selected"<?php endif;?>>
                <?php echo $country['name']; ?>
              </option>
            <?php else: ?>
              <option value="<?php echo $country['iso_code_2']; ?>" <?php if('US' == $country['iso_code_2']): ?>selected="selected"<?php endif?>><?php echo $country['name']; ?></option>
            <?php endif;?>
          <?php endforeach; ?>
        </select>
    </div>

    <?php $has_country = isset($shipping['country']);?>
    <div class="large-6 columns state-container" <?php if($has_country && ($shipping['country'] != 'CA' && $shipping['country'] != 'US')): ?> style="display:none;" <?php endif;?> />
      
      <label for="state">
        State / Region
      </label>
      
        <select name="state" id="shipping-us-states" required="required" <?php if($has_country && $shipping['country'] != 'US'): ?>style="display:none;" disabled<?php endif;?>>

          <?php foreach($us_states as $state): ?>
            <?php if(isset($shipping['state'])): ?>
              <option value="<?php echo $state['iso_code_2'];?>" <?php if($shipping['state'] == $state['iso_code_2']): ?>selected="selected"<?php endif;?>>
                <?php echo $state['name'];?>
              </option>
            <?php else: ?>
              <option value="<?php echo $state['iso_code_2'];?>"><?php echo $state['name'];?></option>
            <?php endif;?>
          <?php endforeach;?>
        </select>

        <select name="state" id="shipping-canada-regions" <?php if(!$has_country || ($has_country && $shipping['country'] != 'CA')): ?>style="display:none;"  disabled<?php endif;?>>

          <?php foreach($canada_regions as $region): ?>
            <?php if(isset($shipping['state'])): ?>
              <option value="<?php echo $region['iso_code_2'];?>" 
                <?php if($shipping['state'] == $region['iso_code_2']): ?>selected="selected"<?php endif;?>
              >
                <?php echo $region['name'];?>
              </option>
            <?php else: ?>
              <option value="<?php echo $region['iso_code_2'];?>"><?php echo $region['name'];?></option>
            <?php endif;?>
          <?php endforeach;?>
        </select>
    </div>
    
  </div>

    <div class="row">
        <div class="large-12 columns">
            <input type="checkbox" name="enable-signature-confirmation" value="1" checked/> Use signature confirmation?
        </div>
    </div>
    <input type="submit" value="Check Shipping Rates" class="btn btn-small" />
</form>


  <div class="row display-on-rates-checked" style="display:none;">
      <div class="large-12 columns">
        <div class="shipping-selection">
          <h3 class="items-header">Choose a shipping speed</h3>

        </div>
      </div>
  </div>

  <div class="row display-on-rates-checked" style="display:none;">
      <div class="mt20">
        <div class="large-12 columns">
          <a href="javascript:void(0);" id="step2-trigger-btn" class="btn">Next: Enter your credit card details</a>
        </div>  
      </div>
  </div>

  <input type="hidden" id="shipment-cost" value="<?php echo isset($shipping['cost']) ? $shipping['cost'] : 0; ?>"/>

