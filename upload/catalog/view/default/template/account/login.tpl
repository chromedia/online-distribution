<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  
<br />
<br />
<br />
<br />	
<br />
<br />
<br />
<br />		

	<!-- Center -->
	<div align="center" id='login'>
	  <h1><?php echo 'Welcome User!'; ?></h1>
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		<br />

			<!-- Email ID -->
			<input id="user_email" class="input_account" type="text" name="email" placeholder="Email Address" value="<?php echo $email; ?>" />
			<br />
			<br />

			<!-- Password -->
			<input id="user_password" class="input_account" type="password" name="password" placeholder="Password" value="<?php echo $password; ?>" />
			<br />
			<br />

			<!-- Login -->
			<input class="account_button" type="submit" value="Log In"/>
			<br />
			<br />

			<!-- Forgot Password -->
			<a id="forgot_password" href="<?php echo $forgotten; ?>"><?php echo 'Forgot Password?'; ?></a><br />
			<br />

	  </form>
	</div>
		   


<!-- Hide Sign Up Form -->
<script type="text/javascript"><!--

//--></script> 




<?php echo $footer; ?>