<?php

/**
 * Provides helper methods for curl
 */
class CurlUtil
{
    /**
     * Curl call to third party
     */
    public function call($url, $method, $credentials, $data = array())
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
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_USERPWD, $credentials);

            switch ($method)
            {
                case "GET":
                    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
                    break;
                case "POST":
                    curl_setopt($ch, CURLOPT_POST, 1);

                    if (!empty($data)) {
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
                        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
                    }

                    break;
            }
            
            // getting response from server 
            $httpResponse = curl_exec($ch);
            // @curl_close($ch);
            
            if (!$httpResponse) {
                throw new Exception('API call failed: '.curl_error($ch));
            } 

            return $httpResponse;
        } catch(Exception $e) {
            throw $e;
        }
    }

}