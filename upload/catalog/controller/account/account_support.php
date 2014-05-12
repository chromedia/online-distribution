<?php

// Start the session
if (!isset($_SESSION)) {
	session_start();
}

// Config File
require_once($_SESSION['HOME'] . 'config.php');

// Startup File
require_once(DIR_SYSTEM . 'startup.php');

// Change Password
if (isset($_POST['change_password'])) {

	$account->change_password();

}

// Account Info
if (isset($_POST['account_info'])) {

	$account->change_account_info();

}

// Store New Address
if (isset($_POST['new_address'])) {

	$account->add_address();

}

// Delete Address
if (isset($_POST['delete_address'])) {

	$account->delete_address();

}

// Order History
if (isset($_POST['order_history'])) {



}

?>