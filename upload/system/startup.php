<?php
<<<<<<< HEAD
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == true) {
	exit('PHP5.1+ Required');
}

// Register Globals
if (ini_get('register_globals')) {
	ini_set('session.use_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');

	session_set_cookie_params(0, '/');
	session_start();

	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset(${$key}); 
		}
	}
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
		if (is_array($data)) {
			foreach ($data as $key => $value) {
				$data[clean($key)] = clean($value);
			}
		} else {
			$data = stripslashes($data);
		}

		return $data;
	}			

	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_REQUEST = clean($_REQUEST);
	$_COOKIE = clean($_COOKIE);
}

if (!ini_get('date.timezone')) {
	date_default_timezone_set('UTC');
}

// Windows IIS Compatibility  
if (!isset($_SERVER['DOCUMENT_ROOT'])) { 
	if (isset($_SERVER['SCRIPT_FILENAME'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr($_SERVER['SCRIPT_FILENAME'], 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['DOCUMENT_ROOT'])) {
	if (isset($_SERVER['PATH_TRANSLATED'])) {
		$_SERVER['DOCUMENT_ROOT'] = str_replace('\\', '/', substr(str_replace('\\\\', '\\', $_SERVER['PATH_TRANSLATED']), 0, 0 - strlen($_SERVER['PHP_SELF'])));
	}
}

if (!isset($_SERVER['REQUEST_URI'])) { 
	$_SERVER['REQUEST_URI'] = substr($_SERVER['PHP_SELF'], 1); 

	if (isset($_SERVER['QUERY_STRING'])) { 
		$_SERVER['REQUEST_URI'] .= '?' . $_SERVER['QUERY_STRING']; 
	} 
}

if (!isset($_SERVER['HTTP_HOST'])) {
	$_SERVER['HTTP_HOST'] = getenv('HTTP_HOST');
}
=======

// Start-up Procedure
require_once(DIR_SYSTEM . 'procedure/settings.php');
require_once(DIR_SYSTEM . 'procedure/error.php'); 
require_once(DIR_SYSTEM . 'procedure/session.php');

// Application Classes
require_once(DIR_SYSTEM . 'library/customer.php');
require_once(DIR_SYSTEM . 'library/currency.php');
require_once(DIR_SYSTEM . 'library/user.php');
require_once(DIR_SYSTEM . 'library/tax.php');
require_once(DIR_SYSTEM . 'library/weight.php');
require_once(DIR_SYSTEM . 'library/length.php');
require_once(DIR_SYSTEM . 'library/cart.php');
// require_once(DIR_SYSTEM . 'library/account.php');
>>>>>>> 2cb8057bb2071b3c899fc2a459154aa01515936b

// Helper
require_once(DIR_SYSTEM . 'helper/json.php'); 
require_once(DIR_SYSTEM . 'helper/utf8.php'); 

// Engine
require_once(DIR_SYSTEM . 'engine/action.php'); 
require_once(DIR_SYSTEM . 'engine/controller.php');
require_once(DIR_SYSTEM . 'engine/front.php');
require_once(DIR_SYSTEM . 'engine/loader.php'); 
require_once(DIR_SYSTEM . 'engine/model.php');
require_once(DIR_SYSTEM . 'engine/registry.php');

// Common
require_once(DIR_SYSTEM . 'library/cache.php');
require_once(DIR_SYSTEM . 'library/url.php');
require_once(DIR_SYSTEM . 'library/config.php');
require_once(DIR_SYSTEM . 'library/db.php');
require_once(DIR_SYSTEM . 'library/document.php');
require_once(DIR_SYSTEM . 'library/encryption.php');
require_once(DIR_SYSTEM . 'library/image.php');
require_once(DIR_SYSTEM . 'library/language.php');
require_once(DIR_SYSTEM . 'library/log.php');
<<<<<<< HEAD
require_once(DIR_SYSTEM . 'library/mail.php');
require_once(DIR_SYSTEM . 'library/pagination.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/response.php');
require_once(DIR_SYSTEM . 'library/session.php');
require_once(DIR_SYSTEM . 'library/template.php');
require_once(DIR_SYSTEM . 'library/openbay.php');
require_once(DIR_SYSTEM . 'library/ebay.php');
require_once(DIR_SYSTEM . 'library/amazon.php');
require_once(DIR_SYSTEM . 'library/amazonus.php');
=======
// require_once(DIR_SYSTEM . 'library/mail.php');
require_once(DIR_SYSTEM . 'library/swiftmailer/swift_required.php');

require_once(DIR_SYSTEM . 'library/pagination.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/response.php');
require_once(DIR_SYSTEM . 'library/restcall.php');
require_once(DIR_SYSTEM . 'library/session.php');
require_once(DIR_SYSTEM . 'library/template.php');

// Integrations
// require_once(DIR_SYSTEM . 'library/stripe/Stripe.php');

// Load objects if config data is ready
if(defined('DIR_HOME')){

	// Core Objects
	$registry = new Registry();
	$loader = new Loader($registry);
	$config = new Config();
	$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
	$request = new Request();
	$response = new Response();
	$cache = new Cache();
	$session = new Session();

	// Post Core Procedure
	require_once(DIR_SYSTEM . 'procedure/settings2.php');

	// Support Objects
	$url = new Url($config->get('config_url'), $config->get('config_secure') ? $config->get('config_ssl') : $config->get('config_url'));	
	$log = new Log();
	$language = new Language($languages[$code]['directory']);

	// Load Language
	$language->load($languages[$code]['filename']);	

	// Store objects in registry's data array
	$registry->set('load', $loader);
	$registry->set('config', $config);
	$registry->set('db', $db);
	$registry->set('url', $url);
	$registry->set('log', $log);
	$registry->set('request', $request);
	$registry->set('response', $response); 
	$registry->set('cache', $cache); 
	$registry->set('session', $session);
	$registry->set('language', $language);

	// Create objects in registry's data array
	$registry->set('document', new Document()); 		
	$registry->set('customer', new Customer($registry));
	// $registry->set('account', new Account($registry));
	// $registry->set('restcall', new Restcall());
	$registry->set('currency', new Currency($registry));
	$registry->set('user', new User($registry));	
	$registry->set('tax', new Tax($registry));
	$registry->set('weight', new Weight($registry));
	$registry->set('length', new Length($registry));
	$registry->set('encryption', new Encryption($config->get('config_encryption')));
	$registry->set('cart', new Cart($registry));

	// Create Sector Objects with Registry Data
	// $cart = new Cart($registry);
	// $account = new Account($registry); 
	$front = new Front($registry);

	// Run end procedure
	//require_once(DIR_SYSTEM . 'procedure/end.php');

	// Routers
	//require_once(DIR_SYSTEM . 'router/cart.php');	

}

>>>>>>> 2cb8057bb2071b3c899fc2a459154aa01515936b
?>