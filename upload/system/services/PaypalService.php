<?php

require_once(DIR_SYSTEM . 'utilities/CurlUtil.php');

class PaypalService
{
    private static $instance;

    private $curlUtil;

    private $endpoint;

    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new PaypalService();
        }

        return self::$instance;
    }

    /**
     * Sets paypal checkout
     */
    public function setPaypalCheckout()
    {
        
    }


    // /**
    //  * Instantiates paypal service class
    //  */
    // public function __construct()
    // {
    //     $this->curlUtil = new CurlUtil();
    //     $this->endpoint = 'SANBOX' == strtoupper(PAYPAL_ENVIRONMENT) ? 'https://api.sandbox.paypal.com/v1/' : 'https://api.paypal.com/v1/';
    // }

    // /**
    //  * Init checkout
    //  *
    //  * @param array $paymentData Must contain amount : array('total' => $x, 'currency' => $x), description => $x
    //  */
    // public function initCheckout($paymentData)
    // {
    //     $knownData = array(
    //         'intent' => 'sale',
    //         'redirect_urls' => array(
    //             'return_url' =>   
    //             'cancel_url' => 
    //         ),
    //         'payer' => array(
    //             'payment_method' => 'paypal'
    //         ),
    //         'transactions' => array(
    //             $paymentData
    //         )
    //     ); 

    //     $url = $this->endpoint.'/payments/payment';

    //     $response = $this->curlUtil->call($url, 'POST', STRIPE_PRIVATE_KEY, $data, 'application/json');

    //     return $this->__processResponse($response);
    // }


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
