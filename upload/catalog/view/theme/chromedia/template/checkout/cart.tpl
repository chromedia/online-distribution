<?php echo $header; ?>

<!-- CONTENT STARTS HERE -->
<div class="bar">
  <div class="row">
    <ul class="steps-bar">
      <li class="active step1">Shipping information</li>
      <li><i class="icon-arrow-right"></i></li>
      <li class="step2">Credit Card Information</li>  
    </ul>  
  </div>
</div>

<div class="notif-msg" style="display:none;">
    <div class="notif error"></div>  
</div>

<div class="mtb40"> 
    <div class="row">
        <div class="large-6 columns step-content">
            <span id="step-shipping">
                <?php echo $shippingForm; ?>
            </span>
            <span id="step-payment" style="display:none;">
                <?php echo $paymentForm; //include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_shipment.tpl'); ?>
            </span>
        </div>

        <?php include_once(DIR_APPLICATION . 'view/theme/chromedia/template/checkout/_items.tpl'); ?>
    </div>
</div>

<input type="hidden" class="subtotal-value" value="<?php echo $subTotal; ?>"/>

<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/form.js"></script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/cart.js"></script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/checkout.js"></script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/shipment.js"></script>


<!-- Stripe JS Library -->
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    var publishableKey = "<?php echo STRIPE_PUBLIC_KEY; ?>";
    // Stripe.setPublishableKey(publishableKey);
</script>
<script type="text/javascript" src="catalog/view/theme/chromedia/javascripts/payment.js"></script>

<?php echo $footer;?>