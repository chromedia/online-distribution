<?php echo $header; ?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div id="content">

    <div align="center">

    <br />
    <br />
    <br />
    <br />  
    <br />
    <br />
    <br />
    <br />  

      <h1><?php echo "Create a New Account"; ?></h1>

    <br />
    <br />  

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

      <!-- Email Address -->
            <input class="input_account" type="text" name="email" placeholder="Email Address" value="<?php echo $email; ?>" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>

    <br />
    <br />

        <!-- Password -->
        <input class="input_account" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>


    <br />
    <br />

        <!-- Confirm Password -->
        <input class="input_account" type="password" name="confirm" placeholder="Confirm Password" value="<?php echo $confirm; ?>" />
            <?php if ($error_confirm) { ?>
            <span class="error"><?php echo $error_confirm; ?></span>
            <?php } ?>

    <br />
    <br />

        <!-- Sign Up -->
        <input class="account_button" type="submit" value="Sign Up" class="button" />

    <br />
    <br />

      </form>

    </div>
</div>
<?php echo $footer; ?>
