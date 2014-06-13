<?php

/**
 * Provides services for cart
 */
class CartService
{
    private static $instance;
    
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
    public function emailCustomerForConfirmation($mailUtil, $recipientEmail = '')
    {
        $orderInfo = '';

        if (isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            
            foreach ($packages as $package) {
                $transaction = json_decode($package['transaction'], true);

                if (isset($transaction['tracking_number']) && !empty($transaction['tracking_number'])) {
                    $trackingNumber = $transaction['tracking_number'];
                } else {
                    $trackingNumber = '<em>Please inquire this from our admin.</em>';
                }

                $orderInfo .= '<p>'.$package['content']['product_name'].' - '.$trackingNumber.'</p>';
            }

            $mailUtil->sendEmail(array(
                'message' => $orderInfo,
                'email'   => $recipientEmail,
                'subject' => 'Opentech Order History'
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
