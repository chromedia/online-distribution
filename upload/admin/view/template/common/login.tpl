<?php echo $header; ?>

<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="login">

    <div class="login-heading">Administration Login</div>

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">

        <!-- Username -->
        <div class="login-element"><input class="portal_input" type="text" name="username" placeholder="Username" value="<?php echo $username; ?>" style="margin-top: 4px;" /></div>
        <!-- Password -->
        <div class="login-element"><input class="portal_input" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" style="margin-top: 4px;" /></div>
        <!-- Login Button -->
        <div class="login-element"><button class="portal_button" onclick="$('#form').submit();" ><?php echo $button_login; ?></button></div>
        <!-- Forgot Password -->
        <div class="login-element"><a id="forgot_password" href="<?php echo $forgotten; ?>"><?php echo 'Forgot Password?'; ?></a></div>

        <?php if ($redirect) { ?>
        <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
        <?php } ?>

      </form>
      
</div>

<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#form').submit();
	}
});
//--></script> 
<?php echo $footer; ?>