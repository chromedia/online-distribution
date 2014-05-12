<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>

<!-- Stylesheet CSS -->
<link rel="stylesheet" type="text/css" href="catalog/view/default/stylesheet/stylesheet.css" />
<link rel="stylesheet" type="text/css" href="catalog/view/default/stylesheet/header.css" />
<!-- JQuery Custom UI CSS -->
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<!-- Other CSS -->
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>

<!-- Modernizr FastClick Jquery -->
<script type="text/javascript" src="catalog/view/javascript/vendor/modernizr.js"></script>
<script type="text/javascript" src="catalog/view/javascript/vendor/fastclick.js"></script>

<!-- Foundation JS -->
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery.js"></script>
<script type="text/javascript" src="catalog/view/javascript/foundation.min.js"></script>

<!-- JQuery Custom UI JS -->
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>

<!-- Common JS -->
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>



<!-- Other JS -->
<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<!-- Google Analytics -->
<?php echo $google_analytics; ?>

</head>
<body>

<div id="header">

    <div id="logo"><a href="<?php echo $home; ?>"><img id="logo-image" src="<?php echo $logo; ?>" title="<?php echo "Logo"; ?>" alt="<?php echo "logo"; ?>" /></a></div>

    <!-- Show User Email -->
    <!--
    <div id="header-email">
        <?php if ($logged) { ?>
            <?php echo $text_email; ?>
        <?php } ?>
    </div>
    -->    

    <!--
      <div id="search">
        <input type="text" name="search" value="<?php echo $search; ?>" />
        <div class="button-search"></div>
      </div>  
    -->  

      <?php echo $language; ?>
      
      <!-- text variables declared in catalog/language/english/common/header.php -->
      <!-- variables defined in catalog/controller/common/header.php -->

    <!-- Show Links -->
    <div class="links">
      <a href="<?php echo $shopping_cart; ?>"><?php echo 'Learn More'; ?></a>    
      <a href="<?php echo $home; ?>"><?php echo 'Store'; ?></a>
      <a href="<?php echo $shopping_cart; ?>"><?php echo 'Cart'; ?></a>
      <a href="<?php echo $shopping_cart; ?>"><?php echo 'Contact Us'; ?></a>

<!--
      <?php if ($logged) { ?>
    <a href="<?php echo $login; ?>"><?php echo 'Login'; ?></a>    
    	<a id="header-register" href="<?php echo $signup; ?>"><?php echo 'Register'; ?></a>  
      <?php } else { ?>  
      <a href="<?php echo $account; ?>"><?php echo 'My Account'; ?></a>
      <a href="<?php echo $logout; ?>"><?php echo 'Logout'; ?></a>
      <?php } ?>
-->

    </div>

</div>

<?php if ($error) { ?>
    
    <div class="warning"><?php echo $error ?><img src="catalog/view/default/image/close.png" alt="" class="close" /></div>
    
<?php } ?>
<div id="notification"></div>
