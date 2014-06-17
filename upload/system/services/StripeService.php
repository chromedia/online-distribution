<?php

require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');

class StripeService
{
    const END_POINT = 'https://api.stripe.com/v1/';

    private static $instance;

    private $curlUtil;

    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new StripeService();
        }

        return self::$instance;
    }


    /**
     * Instantiates shippo service class
     */
    public function __construct()
    {
        $this->curlUtil = new CurlUtil();
    }

    /**
     * Process payment
     */
    public function processPayment($data)
    {
        // var_dump($data);exit;
        $url = self::END_POINT.'charges';

        $response = $this->curlUtil->call($url, 'POST', STRIPE_PRIVATE_KEY, $data);

        return $this->__processResponse($response);
    }


    /**
     * Process response
     */
    private function __processResponse($response)
    {
        // TODO: Check errors
        $jsonDecoded = json_decode($response, true);

        if (isset($jsonDecoded['paid'])) {
            return $jsonDecoded;
        } else {
            return false;
            // TODO: check error type
            var_dump($jsonEncoded);exit;
        }
    }
}
