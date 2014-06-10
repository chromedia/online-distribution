<?php

// Tracking Cookie?
if (isset($request->get['tracking'])) {
    setcookie('tracking', $request->get['tracking'], time() + 3600 * 24 * 1000, '/');
}

// Store
if (isset($_SERVER['HTTPS']) && (($_SERVER['HTTPS'] == 'on') || ($_SERVER['HTTPS'] == '1'))) {
    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`ssl`, 'www.', '') = '" . $db->escape('https://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
} else {
    $store_query = $db->query("SELECT * FROM " . DB_PREFIX . "store WHERE REPLACE(`url`, 'www.', '') = '" . $db->escape('http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']) . rtrim(dirname($_SERVER['PHP_SELF']), '/.\\') . '/') . "'");
}

if ($store_query->num_rows) {
    $config->set('config_store_id', $store_query->row['store_id']);
} else {
    $config->set('config_store_id', 0);
}
        
// Get store settings 
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0' OR store_id = '" . (int)$config->get('config_store_id') . "' ORDER BY store_id ASC");

foreach ($query->rows as $setting) {
    if (!$setting['serialized']) {
        $config->set($setting['key'], $setting['value']);
    } else {
        $config->set($setting['key'], unserialize($setting['value']));
    }
}

// Store HTTP and HTTPS urls
if (!$store_query->num_rows) {
    $config->set('config_url', HTTP_SERVER);
    $config->set('config_ssl', HTTPS_SERVER);   
}

// Language Detection
$languages = array();
$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language` WHERE status = '1'"); 

foreach ($query->rows as $result) {
    $languages[$result['code']] = $result;
}

$detect = '';

if (isset($request->server['HTTP_ACCEPT_LANGUAGE']) && $request->server['HTTP_ACCEPT_LANGUAGE']) { 
    $browser_languages = explode(',', $request->server['HTTP_ACCEPT_LANGUAGE']);
    
    foreach ($browser_languages as $browser_language) {
        foreach ($languages as $key => $value) {
            if ($value['status']) {
                $locale = explode(',', $value['locale']);

                if (in_array($browser_language, $locale)) {
                    $detect = $key;
                }
            }
        }
    }
}

if (isset($session->data['language']) && array_key_exists($session->data['language'], $languages) && $languages[$session->data['language']]['status']) {
    $code = $session->data['language'];
} elseif (isset($request->cookie['language']) && array_key_exists($request->cookie['language'], $languages) && $languages[$request->cookie['language']]['status']) {
    $code = $request->cookie['language'];
} elseif ($detect) {
    $code = $detect;
} else {
    $code = $config->get('config_language');
}

if (!isset($session->data['language']) || $session->data['language'] != $code) {
    $session->data['language'] = $code;
}

if (!isset($request->cookie['language']) || $request->cookie['language'] != $code) {      
    setcookie('language', $code, time() + 60 * 60 * 24 * 30, '/', $request->server['HTTP_HOST']);
}           

// Store language ID and Code in config object
$config->set('config_language_id', $languages[$code]['language_id']);
$config->set('config_language', $languages[$code]['code']);

// Set Header Style and Compression
$response->addHeader('Content-Type: text/html; charset=utf-8');
$response->setCompression($config->get('config_compression'));

?>