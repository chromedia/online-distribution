<?php

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
require_once(DIR_SYSTEM . 'library/account.php');

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
require_once(DIR_SYSTEM . 'library/mail.php');
require_once(DIR_SYSTEM . 'library/pagination.php');
require_once(DIR_SYSTEM . 'library/request.php');
require_once(DIR_SYSTEM . 'library/response.php');
require_once(DIR_SYSTEM . 'library/restcall.php');
require_once(DIR_SYSTEM . 'library/session.php');
require_once(DIR_SYSTEM . 'library/template.php');

// Integrations
require_once(DIR_SYSTEM . 'library/stripe/Stripe.php');

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
	$registry->set('account', new Account($registry));
	$registry->set('restcall', new Restcall());
	$registry->set('currency', new Currency($registry));
	$registry->set('user', new User($registry));	
	$registry->set('tax', new Tax($registry));
	$registry->set('weight', new Weight($registry));
	$registry->set('length', new Length($registry));
	$registry->set('encryption', new Encryption($config->get('config_encryption')));

	// Create Sector Objects with Registry Data
	$cart = new Cart($registry);
	$account = new Account($registry); 
	$front = new Front($registry);

	// Run end procedure
	//require_once(DIR_SYSTEM . 'procedure/end.php');

	// Routers
	require_once(DIR_SYSTEM . 'router/cart.php');	

}

?>