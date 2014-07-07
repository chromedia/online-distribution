<?php

/**
 * Provides helper methods for curl
 */
class CurlUtil
{
    // TODO: Refactor this class to be more flexible
    // flexible headers
    // flexible setting of tokens

    private static $instance;

    const URL_ENCODED_DATA = 'application/x-www-form-urlencoded';

    const JSON_ENCODED_DATA = 'application/json';
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new CurlUtil();
        }

        return self::$instance;
    }

    /**
     * Curl call to third party
     */
    public function call($url, $method, $credentials = '', $data = array(), $contentType = self::URL_ENCODED_DATA)
    {
        try {
            $url = (string) trim(strip_tags($url));
            $ch = curl_init($url);

            // setting the curl parameters.
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_VERBOSE, 1);

            // turning off the server and peer verification(TrustManager Concept).
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            if (!empty($credentials)) {
                curl_setopt($ch, CURLOPT_USERPWD, $credentials);
            }

            switch ($method)
            {
                case "GET":
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POST, 1);

                    if (!empty($data)) {
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: '.$contentType));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->formatData($data, $contentType));
                    }

                    break;
            }
            
            // getting response from server 
            $httpResponse = curl_exec($ch);
            curl_close($ch);
            
            if (!$httpResponse) {
                var_dump(curl_error($ch));
                throw new Exception('API call failed: '.curl_error($ch));
            } 

            return $httpResponse;
        } catch(Exception $e) {
            throw $e;
        }
    }

    /**
     * Formats data
     */ 
    private function formatData($data, $contentType)
    {
        switch($contentType) {
            case self::URL_ENCODED_DATA:
                return http_build_query($data);
            case self::JSON_ENCODED_DATA:
                return json_encode($data);
        }
    }

}