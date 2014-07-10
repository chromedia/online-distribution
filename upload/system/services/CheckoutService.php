<?php



/**
 * Provides services for checkout
 */
class CheckoutService
{
    private static $instance;

    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new CheckoutService();
        }

        return self::$instance;
    }

    /**
     * Get Shippo Shipment data
     */
    public function getShippoOfStore()
    {
        // TODO: Please make it dynamic
        $fromAddressData = array(
            'name'      => 'Laura Behrens Wu',
            'street1'   => 'Clayton St.',
            'street_no' => '220',
            'city'      => 'San Francisco',
            'state'     => 'CA',
            'zip'       => '94117',
            'country'   => 'US',
            'phone'     => '15553419393',
            'email'     => 'floricel.colibao@gmail.com'
        );

        return $fromAddressData;
    }

    /**
     * Prepares packages
     */
    public function preparePackages($products)
    {
        $packages = array();

        foreach ($products as $product) {
            $quantity = $product['quantity'];

            for($ctr = 0; $ctr < $quantity; $ctr++) {
                $packages[] = array(
                    'content' => array(
                        'product_id'   => $product['product_id'],
                        'product_name' => $product['name'],
                        'quantity'     => 1,
                    ),
                    'length'          => $product['length'],
                    'width'           => $product['width'],
                    'height'          => $product['height'],
                    'length_unit'     => isset($product['length_unit']) ? $product['length_unit'] : 'mm',
                    'weight'          => $product['weight'],
                    'weight_unit'     => isset($product['weight_unit']) ? $product['weight_unit'] : 'g',
                );
            }
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
        if (isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            $products = $emailData['products'];
            $itemsBody = '';

            foreach ($products as $product) {
                $trackingNumber = 'Please Verify';

                foreach ($packages as $key => $package) {
                    if ($package['content']['product_id'] == $product['id']) {
                        $transaction = json_decode($package['shipping_transaction'], true);

                        if (isset($transaction['tracking_number']) && !empty($transaction['tracking_number'])) {
                            $trackingNumber = $transaction['tracking_number'];
                        }

                        unset($packages[$key]);

                        break;
                    }
                }

                $itemsBody .= $this->getCartItemEmailTemplate(array(
                    'imageSrc'         => DIR_HOME.$product['thumb'],
                    'productLink'      => $product['href'],
                    'productName'      => $product['name'],
                    'productPrice'     => $product['price'],
                    'productQuantity'  => $product['quantity'],
                    'trackingNumber'   => $trackingNumber
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
        $contents = sprintf($contents, $data['imageSrc'], $data['productLink'], $data['productName'], $data['productQuantity'], $data['productPrice'], $data['trackingNumber']);

        return $contents;
    }
}
