<?php

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

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);
$registry->set('url', $url);

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`");

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);
$registry->set('language', $language);

// Login
$front->addPreAction(new Action('common/home/login'));
$front->addPreAction(new Action('common/home/permission'));

// Router
if (isset($request->get['route'])) {
	$action = new Action($request->get['route']);
} else {
	$action = new Action('common/home');
}

// Dispatch
$front->dispatch($action, new Action('error/not_found'));

// Output
$response->output();
?>
