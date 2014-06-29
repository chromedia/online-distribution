<?php 

require_once(DIR_SYSTEM . 'services/StripeService.php');
require_once(DIR_SYSTEM . 'services/ShippoService.php');
require_once(DIR_SYSTEM . 'services/CheckoutService.php');
require_once(DIR_SYSTEM . 'services/ProductService.php');
require_once(DIR_SYSTEM . 'services/OrderService.php');


/**
 * Checkout controller class
 */
class ControllerCheckoutCheckout extends Controller { 

    /**
     * Handles final order
     */
    public function processOrder()
    {
        try {
            $this->__prepareSelectedShipping($this->request->post['service_name']);

            // $cartService = CartService::getInstance();
            //$shippingAmount = $cartService->getAmountOfShippingServiceRate($this->request->post['service_name']);
            $cartTotal = $this->cart->getTotal();

            $amount = $cartTotal + $this->session->data['shipping']['cost'];//$shippingAmount;
            $email = $this->request->post['customer_email'];
            $response = array();

            $stripeService = StripeService::getInstance();
            $charge = $stripeService->processPayment(array(
                'amount'   => (int)($amount * 100),
                'currency' => 'usd',
                'card'     => $this->request->post['token'],
                'description' => $email
            ));

            if ($charge['paid'] === true) {
                // $this->session->data['guest']['payment']['firstname'] = $this->request->post['customer_name'];
                // $this->session->data['guest']['payment']['code'] = $charge['id'];

                $shippoService = ShippoService::getInstance();
                $shippoService->requestShipping($this->session->data['shipping']['method']);
                // $orderId = $this->__addOrder();

                $paymentInfo = array(
                    'firstname' => $this->request->post['customer_name'],
                    'email'     => $email,
                    'method'    => 'Stripe'
                );

                $this->__saveOrder($paymentInfo/*, $shippingInfo*/);
                $response = array('success' => true);
            } else {
                $response = array('success' => false, 'errorMsg' => 'Payment System Error!');
            }
        } catch (Exception $e) {
            $response = array('success' => false, 'errorMsg' => $e->getMessage());
        }

        echo json_encode($response);
        exit;
    }



    /**
     * Pay via paypal
     */
    public function payViaPaypal()
    {
        if ((!$this->cart->hasProducts())) {
            $this->redirect($this->url->link('checkout/cart'));
        }

        $this->__prepareSelectedShipping($this->request->post['service_name']);

        $this->load->model('payment/pp_express');
        $this->load->model('tool/image');

        $max_amount = $this->currency->convert($this->cart->getTotal(), $this->config->get('config_currency'), 'USD');
        $max_amount = min($max_amount * 1.5, 10000);
        $max_amount = $this->currency->format($max_amount, $this->currency->getCode(), '', false);

        $data = array(
            'METHOD' => 'SetExpressCheckout',
            'MAXAMT' => $max_amount,
            'RETURNURL' => $this->url->link('checkout/checkout/paypalPaymentDone', '', 'SSL'),
            'CANCELURL' => $this->url->link('checkout/cart'),
            'REQCONFIRMSHIPPING' => 0,
            'NOSHIPPING' => 1,//$shipping,
            'ALLOWNOTE' => $this->config->get('pp_express_allow_note'),
            'LOCALECODE' => 'EN',
            'LANDINGPAGE' => 'Login',
            'HDRIMG' => $this->model_tool_image->resize('image/'.$this->config->get('config_logo'), 790, 90),
            'HDRBORDERCOLOR' => $this->config->get('pp_express_border_colour'),
            'HDRBACKCOLOR' => $this->config->get('pp_express_header_colour'),
            'PAYFLOWCOLOR' => $this->config->get('pp_express_page_colour'),
            'CHANNELTYPE' => 'Merchant',

        );

        $data = array_merge($data, $this->model_payment_pp_express->paymentRequestInfo());
        $result = $this->model_payment_pp_express->call($data);

        /**
         * If a failed PayPal setup happens, handle it.
         */
        if(!isset($result['TOKEN'])) {
            $this->session->data['error'] = $result['L_LONGMESSAGE0'];
        
            if($this->config->get('pp_express_debug')) {
                $this->log->write(serialize($result));
            }

            echo json_encode(array('success' => false, 'error' => serialize($result)));
            // $this->redirect($this->url->link('checkout/checkout', '', 'SSL'));
        } else {
            $this->session->data['paypal']['token'] = $result['TOKEN'];

            if (PAYPAL_ENVIRONMENT == 'sandbox') {
                echo json_encode(array('success' => true, 'url' => 'https://www.sandbox.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=' . $result['TOKEN']));
            } else {
                echo json_encode(array('success' => true, 'url' => 'https://www.paypal.com/cgi-bin/webscr?cmd=_express-checkout&useraction=commit&token=' . $result['TOKEN']));
            }
        }

        exit;
    }

    /**
     * Paypal payment done
     */
    public function paypalPaymentDone()
    {
        $this->load->model('payment/pp_express');
        $data = array(
            'METHOD' => 'GetExpressCheckoutDetails',
            'TOKEN' => $this->session->data['paypal']['token'],
        );

        // TODO: Check name, etc of paypal owner

        $result = $this->model_payment_pp_express->call($data);
        $this->session->data['paypal']['payerid']   = $result['PAYERID'];
        $this->session->data['paypal']['result']    = $result;

        $this->session->data['comment'] = '';

        if(isset($result['PAYMENTREQUEST_0_NOTETEXT'])) {
            $this->session->data['comment'] = $result['PAYMENTREQUEST_0_NOTETEXT'];
        }

        $shippoService = ShippoService::getInstance();
        $shippoService->requestShipping($this->session->data['shipping']['method']);

        $paymentInfo = array(
            'method'    => 'Paypal ('.$result['PAYERID'].')',
            'firstname' => isset($result['FIRSTNAME']) ? $result['FIRSTNAME'] : '',
            'lastname'  => isset($result['LASTNAME']) ? $result['LASTNAME'] : '',
            'email'     => isset($result['EMAIL'])  ? $result['EMAIL'] : ''
        );
        $this->__saveOrder($paymentInfo);

        // $orderId = $this->__addOrder();

        // $this->session->data['order_id'] = $orderId;
        //$this->session->data['shipping_cost'] = $this->session->data['shipping']['amount'] ;

        $this->redirect($this->url->link('checkout/success', '', 'SSL'));
    }   

    /**
     * Checks shipping info/rates
     */
    public function checkShippingInfo()
    {
        try {
            $checkoutService = CheckoutService::getInstance();
            $packages = $checkoutService->preparePackages($this->cart->getProducts());

            $toAddressData = array(
                'name' => $this->request->post['name'],
                'street1' => $this->request->post['address'],
                'city'    => $this->request->post['city'],
                'state'   => $this->request->post['state'],
                'zip'     => $this->request->post['postcode'],
                'country' => $this->request->post['country'],
                'email'   => $this->request->post['email']
            );

            // TODO: Please make it dynamic
            $fromAddressData = array(
                'name'      => 'Laura Behrens Wu',
                'street1'   => 'Clayton St.',
                'street_no' => '220',
                'city'      => 'San Francisco',
                'state'     => 'CA',
                'zip'       => '94117',
                'country'   => 'US',
                'phone'     => '+1 555 341 9393',
                'email'     => 'floricel.colibao@gmail.com'
            );

            $shippoService = ShippoService::getInstance();

            $toAddress = $shippoService->confirmAddress($toAddressData);
            $fromAddress = $shippoService->confirmAddress($fromAddressData);

            $info = $shippoService->getShipmentInfo($packages, $fromAddress, $toAddress);
            $rates = array('success' => true, 'rates' => $info, 'rates_count' => count($info));

            $this->__addShippingInformation($toAddressData, $info);

            echo json_encode($rates);
        } catch(Exception $e) {
            echo json_encode(array('success' => false, 'errorMsg' => $e->getMessage()));
        }

        exit;
    }

    /**
     * Displays shipping form
     */
    public function shippingForm()
    {
        $this->load->model('localisation/country');
        $this->load->model('localisation/us_states');
        $this->load->model('localisation/canada_regions');

        $this->data['countries'] = $this->model_localisation_country->getCountries();
        $this->data['us_states'] = $this->model_localisation_us_states->getUsStates();
        $this->data['canada_regions'] = $this->model_localisation_canada_regions->getCanadaRegions();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/_shipment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/_shipment.tpl';
        } else {
            $this->template = '';
        }

        $this->render();
    }

    /**
     * Displays payment form
     */
    public function paymentForm()
    {
    	$currentYear = date("Y");
        $limitYear = $currentYear + 30;

        for ($ctr = $currentYear; $ctr < $limitYear; $ctr++) {
            $years[$ctr] = $ctr;
        }

        for ($ctr = 1; $ctr <= 12; $ctr++) {
            $months[$ctr] = date("F", strtotime(date('Y').'-'.$ctr.'-'.date('d')));
        }

        $this->data['years'] = $years;
        $this->data['months'] = $months;
        $this->data['current_month'] = date("F");
        $this->data['current_year'] = date("y");


    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/_payment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/_payment.tpl';
        } else {
            $this->template = '';
        }

        $this->render();
    }

    /**
     * Prepares selected shipping and cost for successful payment
     */
    private function __prepareSelectedShipping($serviceName)
    {
        $this->session->data['shipping']['method'] = $serviceName;

        $checkoutService = CheckoutService::getInstance();
        $shippingAmount = $checkoutService->getAmountOfShippingServiceRate($this->session->data['shipping']['method']);

        $this->session->data['shipping']['cost'] = $shippingAmount;
    }

    /**
     * Saves order
     */
    private function __saveOrder($paymentInfo/*, $shippingInfo*/)
    {
        // $this->session->data['shipping']['cost'] = $shippingInfo['cost'];
        // $this->session->data['shipping']['method'] = $shippingInfo['method'];

        $this->load->model('checkout/order');
        $orderService = OrderService::getInstance($this->model_checkout_order);


        // since we don't have saving of visitor or customer, payment info will be the customer info
        $orderId = $orderService->saveOrder($this->config, $this->request, array(
            'products'    => $this->cart->getProducts(),
            'payment'     => $paymentInfo,
            'guest'       => $paymentInfo,
            'currency'    => $this->currency,
            'total'       => $this->cart->getTotal() + $this->session->data['shipping']['cost']
        ));

        $this->session->data['order_id'] = $orderId;
        $this->session->data['guest']['email'] = $paymentInfo['email'];
    }

    /**
     * Add shipping information
     */
    private function __addShippingInformation($data, $rates = array())
    {
        $this->session->data['shipping'] = array(
            'firstname' => $data['name'],
            'lastname'  => '',
            'address1'  => $data['street1'],
            'city'      => $data['city'],
            'country'   => $data['country'],
            'state'     => $data['state'],
            'postcode'  => $data['zip']
        );

        $this->session->data['rates'] = $rates;
    }

    // /**
    //  * Add as guest user
    //  */
    // private function __addAsGuestUser($data)
    // {
    //     $this->session->data['guest']['firstname'] = $data['name'];
    //     $this->session->data['guest']['lastname'] = '';
    //     $this->session->data['guest']['email'] = $data['email'];
    //     $this->session->data['guest']['customer_group_id'] = $this->config->get('config_customer_group_id');
    // }

    // /**
    //  * Add payment information
    //  */
    // private function __addPaymentInformation($data)
    // {
    //     $this->session->data['payment'] = array(
    //         'firstname' => $data['name'],
    //         'email'     => $data['email'],
    //         'payment_method' => $data['method']
    //     );
    // }
}