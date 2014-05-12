<?php echo $header; ?>

<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div id="content">

<br />
<br />
<br />
<br />  
<br />
<br />
<br />
<br />  

  <div align="center">

    <h1>Reset Password</h1>
    <br />    

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

            <!-- Email Address -->
            <input class="input_account" type="text" name="email" placeholder="Email Address" value="" /><br /><br />

            <!-- Send Email -->
            <input class="account_button" type="submit" value="<?php echo 'Email New Password'; ?>" class="button" />

        </form>

  </div>

</div>

<?php echo $footer; ?>