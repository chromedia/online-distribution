<?php

/**
 * Provides helper methods for url manipulation
 */
class UrlUtil
{
    private static $instance;

    private $curlUtil;

    const GOOGLE_ENDPOINT = 'https://www.googleapis.com/urlshortener/v1/url';
    const API_KEY = 'AIzaSyCHZN0KrjzT-6I7vM39OdK8JNXKkkoslCc';
    
    /**
     * Returns instance
     */
    public static function getInstance($curlUtil)
    {
        if (is_null(self::$instance)) {
            self::$instance = new UrlUtil($curlUtil);
        }

        return self::$instance;
    }

    /**
     * Instantiates this service class 
     */
    public function __construct($curlUtil)
    {
        $this->curlUtil = $curlUtil;
    }

    /**
     * shortened url
     */
    public function shortenUrl($longUrl)
    {
        try {
            if (!empty($longUrl)) {
                $url = self::GOOGLE_ENDPOINT;

                $response = $this->curlUtil->call($url, 'POST', '', array('longUrl' => $longUrl, 'key' => self::API_KEY), 'application/json');
                $response = json_decode($response, true);
               
                if (isset($response['id'])) {
                    return $response['id'];
                }
            }
            
            return $longUrl;
        } catch(Exception $e) {
            return $longUrl;
        }
        
    }
}