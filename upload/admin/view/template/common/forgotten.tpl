<?php echo $header; ?>

<div id="content" align="center">

  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>

<br />
<br />
<br />
<br />
<br />
<br />

      <h1>Administration Password Reset</h1>

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="forgotten">

<br />
<br />
        <input class="portal_input" type="text" name="email" placeholder="Email Address" value="<?php echo $email; ?>" />

<br />
<br />

      </form>

        <div class="buttons">
        <button class="portal_button" onclick="$('#forgotten').submit();" class="button"><?php echo $button_reset; ?></button>
        </div>
</div>

<?php echo $footer; ?>