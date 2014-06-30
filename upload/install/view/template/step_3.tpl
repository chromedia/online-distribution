<?php echo $header; ?>
<h1>Step 3 - Configuration</h1>
<div id="column-right">
  <ul>
    <li>License</li>
    <li>Pre-Installation</li>
    <li><b>Configuration</b></li>
    <li>Finished</li>
  </ul>
</div>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <p>1. Please enter your database connection details.</p>
    <fieldset>
      <table class="form">
        <tr>
          <td>Database Driver:</td>
          <td><select name="db_driver">
              <option value="mysqli">MySQLi</option>
              <option value="mysql">MySQL</option>
            </select>
            <br />
            <?php if ($error_db_driver) { ?>
            <span class="required"><?php echo $error_db_driver; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Database Host:</td>
          <td><input type="text" name="db_host" value="<?php echo $db_host; ?>" />
            <br />
            <?php if ($error_db_host) { ?>
            <span class="required"><?php echo $error_db_host; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> User:</td>
          <td><input type="text" name="db_user" value="<?php echo $db_user; ?>" />
            <br />
            <?php if ($error_db_user) { ?>
            <span class="required"><?php echo $error_db_user; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td>Password:</td>
          <td><input type="text" name="db_password" value="<?php echo $db_password; ?>" /></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Database Name:</td>
          <td><input type="text" name="db_name" value="<?php echo $db_name; ?>" />
            <br />
            <?php if ($error_db_name) { ?>
            <span class="required"><?php echo $error_db_name; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td>Database Prefix:</td>
          <td><input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" />
            <br />
            <?php if ($error_db_prefix) { ?>
            <span class="required"><?php echo $error_db_prefix; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </fieldset>
    <p>2. Please enter a username and password for the administration.</p>
    <fieldset>
      <table class="form">
        <tr>
          <td><span class="required">*</span> Username:</td>
          <td><input type="text" name="username" value="<?php echo $username; ?>" />
            <br />
            <?php if ($error_username) { ?>
            <span class="required"><?php echo $error_username; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> Password:</td>
          <td><input type="text" name="password" value="<?php echo $password; ?>" />
            <br />
            <?php if ($error_password) { ?>
            <span class="required"><?php echo $error_password; ?></span>
            <?php } ?></td>
        </tr>
        <tr>
          <td><span class="required">*</span> E-Mail:</td>
          <td><input type="text" name="email" value="<?php echo $email; ?>" />
            <br />
            <?php if ($error_email) { ?>
            <span class="required"><?php echo $error_email; ?></span>
            <?php } ?></td>
        </tr>
      </table>
    </fieldset>
    <p>3. Please enter Stripe and Shippo information credentials.</p>
    <fieldset>
      <table class="form">
        <tr>
          <td>Stripe Private Key:</td>
          <td><input type="text" name="stripe_private_key" value="<?php echo $stripe_private_key; ?>" /></td>
        </tr>
        <tr>
          <td>Stripe Public Key:</td>
          <td><input type="text" name="stripe_public_key" value="<?php echo $stripe_public_key; ?>" /></td>
        </tr>
        <tr>
          <td>Shippo Authorization:</td>
          <td><input type="text" name="shippo_authorization" value="<?php echo $shippo_authorization; ?>" /></td>
        </tr>
      </table>
    </fieldset>

    <!-- <p>4. Paypal credentials for alternative payment method.</p>
    <fieldset>
      <table class="form">
        <tr>
          <td>Paypal Environment:</td>
          <td>
            <input type="radio" name="paypal_environment" value="sandbox" checked /> Sandbox
            <input type="radio" name="paypal_environment" value="production" /> Production
          </td>
        </tr>
        <tr>
          <td>Paypal Client Id:</td>
          <td><input type="text" name="paypal_client_id" value="<?php //echo $paypal_client_id; ?>" /></td>
        </tr>
        <tr>
          <td>Paypal Client Secret:</td>
          <td><input type="text" name="paypal_client_secret" value="<?php //echo $paypal_client_secret; ?>" /></td>
        </tr>
      </table>
    </fieldset> -->
    <div class="buttons">
      <div class="left"><a href="<?php echo $back; ?>" class="button">Back</a></div>
      <div class="right">
        <input type="submit" value="Continue" class="button" />
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?>