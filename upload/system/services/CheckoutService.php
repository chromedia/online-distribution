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
     * Prepares packages
     */
    public function preparePackages($products, $tax, $config)
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
                        'price'        => $tax->calculate($product['price'], $product['tax_class_id'], $config->get('config_tax'))//$product['price']
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


    /**
     * Sends order email cofirmation
     */
    public function emailCustomerForConfirmation($emailData, $mailUtil, $shippoService)
    {
        if (isset($_SESSION['packages'])) {
            $packages = $_SESSION['packages'];
            $products = $emailData['products'];
            $itemsBody = '';
            $trackingNumber = 'Unknown - Please verify from admin for more information.';
            $labelUrl = '';
            $ctr = 0;

            foreach ($packages as $key => $package) {
                $ctr++;
                $transaction = json_decode($package['shipping_transaction'], true);

                if (isset($transaction['tracking_number']) && !empty($transaction['tracking_number'])) {
                    $trackingNumber = $transaction['tracking_number'];
                    $trackingUrl = $transaction['tracking_url_provider'];
                }

                $product = $package['content'];
                $itemsBody .= $this->getCartItemEmailTemplate(array(
                    'productName'      => $product['product_name'],
                    'productPrice'     => $product['price'],
                    'productQuantity'  => $product['quantity'],
                    'trackingNumber'   => $trackingNumber,
                    'trackingUrl'      => $trackingUrl,
                    'package'          => 'Package'.$ctr
                ));
            }

            $message = $this->getOrderConfirmationEmailTemplate(array(
                'packageCount' => count($packages),
                'items'        => $itemsBody,
                'shippingCost' => $emailData['shippingCost'],
                'subTotal'     => $emailData['subTotal'],
                'total'        => $emailData['total'],
                'footer'       => $this->getEmailFooter()
            ));

            $mailUtil->sendEmail(array(
                'message' => $message,
                'email'   => $emailData['recipient'],
                'subject' => 'Opentech Collaborative Order'
            ));
        }
    }


    /**
     * Create order confirmation email template
     */
    public function getOrderConfirmationEmailTemplate($data)
    {
        $logo = DIR_HOME.'catalog/view/theme/chromedia/image/logo.png';

        $contents = file_get_contents(DIR_SYSTEM . 'email_templates/checkout_confirmation.php');
        $contents = sprintf($contents, $logo, $data['packageCount'], $data['items'], $data['shippingCost'], $data['subTotal'], $data['total'], $data['footer']);

        return $contents;
    }

    /**
     * Create order cart item template
     */
    public function getCartItemEmailTemplate($data)
    {
        $contents = file_get_contents(DIR_SYSTEM . 'email_templates/_order_information.php');
        $contents = sprintf($contents, $data['trackingUrl'], $data['package'], $data['productName'], $data['productQuantity'], $data['productPrice'], $data['trackingNumber']);

        return $contents;
    }

    /**
     * Returns email footer
     */
    public function getEmailFooter()
    {
        $homepage = DIR_HOME;
        $aboutUs = DIR_HOME.'index.php?route=information/learnmore'; 
        $terms = DIR_HOME.'index.php?route=information/terms_of_service';

        $contents = file_get_contents(DIR_SYSTEM . 'email_templates/_email_footer.php');
        $contents = sprintf($contents, $homepage, $aboutUs, $terms);

        return $contents;
    }
}
