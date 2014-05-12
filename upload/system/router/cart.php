<?php
// Payment Processing Tokenize
if (isset($_POST['stripeToken'])) {

	$cart->payment_info();
	exit;

}

// Quantity Update
if (isset($_POST['update'])) {

	$cart->change_quantity();
	exit;

}	

// Delete Item
if (isset($_POST['delete'])) {

	$cart->delete_item();
	exit;

}

// Add to Cart
if (isset($_POST['addtocart'])) {

	$cart->add_item();	
	exit;

}

// Set Shipping Address
if (isset($_POST['shipping_address'])) {

	$cart->confirm_address();
	exit;

}

// Calculate Packages
if (isset($_POST['package_logic'])) {

	$cart->package_logic();
	exit;

}

// Calculate Packages
if (isset($_POST['shipping_speeds'])) {

	$cart->ship_speeds();
	exit;

}

// Checkout
if (isset($_POST['checkout'])) {

	$cart->checkout();
	exit;

}
?>