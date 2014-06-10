<?php

require_once(DIR_SYSTEM . 'utilities/MailUtil.php');

/**
 * Provides services for cart
 */
class CartService
{
    private static $instance;

    private $mailUtil;
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new CartService();
        }

        return self::$instance;
    }


    /**
     * Instantiates shippo service class
     */
    public function __construct()
    {
        $this->maillUtil = new MailUtil();
    }

    /**
     * Prepares packages
     */
    public function preparePackages($cart)
    {
        $packages = array();
        $products = $cart->getProducts();

        foreach ($products as $product) {
            $packages[] = array(
                'content' => array(
                    'product_id'   => $product['product_id'],
                    'product_name' => $product['name'],
                    'quantity'     => 1,
                ),
                'length'          => $product['length'],
                'width'           => $product['width'],
                'height'          => $product['height'],
                'weight'          => $product['weight'],
            );
        }
        
        return $packages;
    }

    /**
     * Get amount of shipping service rate
     */
    public function getAmountOfShippingServiceRate($serviceName)
    {
        $amount = 0;

        if (isset($_SESSION['rates'])) {
            $rates = $_SESSION['rates'];

            if (!isset($rates[$serviceName]['total'])) {
                throw new Exception('Please select valid carrier.');
            }

            $amount = $rates[$serviceName]['total'];
        } else {
            throw new Exception('Session alredy expired due to inactivity. Please restart process.');
        }

        return $amount;
    }

    /**
     * Do email customer for order confirmation
     */
    public function emailCustomerForConfirmation($recipientEmail = '')
    {
        $orderInfo = '';

        if (isset($_SESSION['packages'])) {
            foreach ($packages as $package) {
                $transaction = json_decode($package['transaction'], true);


                if (isset($transaction['tracking_number'])) {
                    $trackingNumber = $transaction['tracking_number'];
                } else {
                    $trackingNumber = 'Please verify this.';
                }

                $orderInfo .= '<p>'.$package['content']['product_name'].' - '.$trackingNumber.'</p>';
            }

            $this->maillUtil->sendEmail(array(
                'message' => $orderInfo,
                'mail'    => $recipientEmail
            ));            
        }
    }

    /***** JUST A TODO ****/
    /***** For the meantime, returned values are sorted *****/
    
    /**
     * Sorts rates
     */
    // public function sortRates($rates)
    // {
    //     foreach ($rates) {

    //     }
    // }
}
