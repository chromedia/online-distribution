<?php

// Version 0.01 in progress

// Require Configuration Data
if (file_exists('config.php')) {
	require_once('config.php');
}  

// Redirect to Install Page if filepaths not defined
if (!defined('DIR_APPLICATION')) {
	header('Location: install/index.php');
	exit;
}

// Define and Sessionize Home Directory
define('DIR_HOME', 'http://' . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/');
$_SESSION['HOME'] = DIR_HOME;

// Startup
require_once(DIR_SYSTEM . 'startup.php');
	
// Get Link Route
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Prepare alternative files
$front->addPreAction(new Action('common/maintenance'));
$front->addPreAction(new Action('common/seo_url'));	

// Dispatch
$front->dispatch($action, new Action('error/not_found'));

// Output
$response->output();


/*****/
// include(dirname( __FILE__ ) .'/news/index.php');
// WORDPRESS END //