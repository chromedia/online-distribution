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
    public function preparePackages($products)
    {
        $packages = array();

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

    public function emailCustomerForConfirmation($emailData, $mailUtil, $shippoService)
    {
        $products = $emailData['products'];
        $itemsBody = '';

        foreach ($products as $product) {
            $itemsBody .= $this->getCartItemEmailTemplate(array(
                'imageSrc'         => $product['thumb'],
                'productLink'      => $product['href'],
                'productName'      => $product['name'],
                'productPrice'     => $product['price'],
                'productQuantity'  => $product['quantity']
            ));
        }

        $message = $this->getOrderConfirmationEmailTemplate(array(
            'items'        => $itemsBody,
            'shippingCost' => $emailData['shippingCost'],
            'subTotal'     => $emailData['subTotal'],
            'total'        => $emailData['total']
        ));

        $mailUtil->sendEmail(array(
            'message' => $message,
            'email'   => $emailData['recipient'],
            'subject' => 'Opentech Collaborative Order'
        ));  
    }


    /**
     * Do email customer for order confirmation
     *
     * This is temporarily not used
     */
    public function emailCustomerForConfirmation2($emailData, $mailUtil, $shippoService)
    {
        $orderInfo = '';

        if (isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            
            foreach ($packages as $package) {
                $transaction = json_decode($package['shipping_transaction'], true);

                if (isset($transaction['tracking_number']) && !empty($transaction['tracking_number'])) {
                    $trackingNumber = $transaction['tracking_number'];
                } else {
                    // $transactionResponse = $shippoService->requestShippingInfoOfObject($transaction['object_id']);

                    // if (isset($transactionResponse['tracking_number']) && !empty($transaction['tracking_number'])) {
                    //     $trackingNumber = $transactionResponse['tracking_number'];
                    // } else {
                    //     $trackingNumber = $transactionResponse['tracking_status']['status'].' <em> Please inquire this from our admin.</em';
                    // }

                    $trackingNumber = $transaction['tracking_status']['status'].' <em> Please inquire status from our admin.</em';
                }
                // $orderInfo .= '<p>'.$package['content']['product_name'].' - '. $trackingNumber.'</p>';
            }

            $body = $this->getOrderConfirmationEmailTemplate(array(
                'items' => $items,
                'shippingCost' => $shippingCost,
                'subTotal' => $subTotal,
                'total' => $total
            ));

            $mailUtil->sendEmail(array(
                'message' => $orderInfo,
                'email'   => $recipientEmail,
                'subject' => 'Opentech Order History'
            ));            
        }
    }


    /**
     * Create order confirmation email template
     */ 
    public function getOrderConfirmationEmailTemplate($data)
    {
        $contents = file_get_contents(DIR_SYSTEM . 'email_templates/checkout_confirmation.php');
        $contents = sprintf($contents, $data['items'], $data['shippingCost'], $data['subTotal'], $data['total']);

        return $contents;
    }

    /**
     * Create order cart item template
     */
    public function getCartItemEmailTemplate($data)
    {
        $contents = file_get_contents(DIR_SYSTEM . 'email_templates/_order_information.php');
        $contents = sprintf($contents, $data['imageSrc'], $data['productLink'], $data['productName'], $data['productQuantity'], $data['productPrice']);

        return $contents;
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
