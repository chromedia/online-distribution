<?php

/**
 * Provides services for order
 */
class OrderService
{
    private static $instance;
    
    private $orderModel;

    /**
     * Returns instance
     */
    public static function getInstance($orderModel)
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderService($orderModel);
        }

        return self::$instance;
    }

    /**
     * Instantiates this class
     */
    public function __construct($model)
    {
        $this->orderModel = $model;
    }

    /**
     * Save order
     */
    public function saveOrder($config, $request, $orderInfo)
    {
        $data['invoice_prefix'] = $config->get('config_invoice_prefix');
        $data['store_id'] = $config->get('config_store_id');
        $data['store_name'] = $config->get('config_name');
        $data['language_id'] = $config->get('config_language_id');
        $data['customer_group_id'] = $config->get('config_customer_group_id');

        if ($data['store_id']) {
            $data['store_url'] = $this->config->get('config_url');      
        } else {
            $data['store_url'] = HTTP_SERVER;   
        }
        
        $data = $this->__setRequestInformation($data, $request);
        
        $data = $this->__setCustomerInfo($data, $orderInfo['guest']);
        $data = $this->__setPaymentInfo($data, $orderInfo['payment']);
        $data = $this->__setProductsInfo($data, $orderInfo['products']);
        $data = $this->__setCurencyInformation($data, $orderInfo['currency']);

        $data = $this->__grabShippingInfo($data);

        $data['vouchers'] = array();
        $data['totals'] = array();
        $data['comment'] = '';
        $data['totals'] = array();
        $data['total'] = $orderInfo['total'];
       
        $data['affiliate_id'] = 0;
        $data['commission'] = 0;

        return $this->orderModel->saveOrder($data);
    }

    /**
     * Set customer data
     */
    private function __setCustomerInfo($data, $customer)
    {
        $data['customer_id'] = 0;
        $data['firstname'] = isset($customer['firstname']) ? $customer['firstname'] : '';
        $data['lastname'] = isset($customer['lastname']) ? $customer['lastname'] : '';
        $data['email'] = isset($customer['email']) ? $customer['email'] : '';
        $data['telephone'] = '';
        $data['fax'] = '';

        return $data;
    }

    /**
     * Set payment information
     */
    private function __setPaymentInfo($data, $payment)
    {   
        $data['payment_method'] = isset($payment['method']) ? $payment['method'] : '';
        $data['payment_code'] = isset($payment['code']) ? $payment['code'] : '';

        $data['payment_firstname'] = isset($payment['firstname']) ? $payment['firstname'] : '';
        $data['payment_lastname'] = isset($payment['lastname']) ? $payment['lastname'] : '';
        $data['payment_company'] = '';  
        $data['payment_company_id'] = '';   
        $data['payment_tax_id'] = '';   
        $data['payment_address_1'] = '';
        $data['payment_address_2'] = '';
        $data['payment_city'] = '';
        $data['payment_postcode'] = '';
        $data['payment_zone'] = '';
        $data['payment_zone_id'] = '';
        $data['payment_country'] = '';
        $data['payment_country_id'] = '';
        $data['payment_address_format'] = '';

        return $data;
    }

    /**
     * Grabs shipping info
     */
    private function __grabShippingInfo($data)
    {

        $data['shipping_firstname'] = isset($_SESSION['shipping']['firstname']) ? $_SESSION['shipping']['firstname'] : '';
        $data['shipping_lastname'] = isset($_SESSION['shipping']['lastname']) ? $_SESSION['shipping']['lastname'] : '';    
        $data['shipping_company'] = ''; 
        $data['shipping_address_1'] = isset($_SESSION['shipping']['address1']) ? $_SESSION['shipping']['address1'] : '';
        $data['shipping_address_2'] = '';
        $data['shipping_city'] = isset($_SESSION['shipping']['city']) ? $_SESSION['shipping']['city'] : '';
        $data['shipping_postcode'] = isset($_SESSION['shipping']['postcode']) ? $_SESSION['shipping']['postcode'] : '';
        $data['shipping_zone'] = '';
        $data['shipping_zone_id'] = '';
        $data['shipping_country'] = isset($_SESSION['shipping']['country']) ? $_SESSION['shipping']['country'] : '';
        $data['shipping_country_id'] = '';
        $data['shipping_address_format'] = '';
        $data['shipping_method'] = isset($_SESSION['shipping']['method']) ? $_SESSION['shipping']['method'] : '';
        $data['shipping_code'] = $this->__getShippingCode();

        return $data;
    }

    /**
     * Returns shipping code
     */
    private function __getShippingCode()
    {
        $packages = $_SESSION['packages'];
        $shipping = array();

        foreach($packages as $package) {
            $labelUrl = '';
            $trackingNumber = '';
            $trackingUrlProvider = '';
            $messages = array();

            if (isset($package['shipping_transaction'])) {
                $transaction = json_decode($package['shipping_transaction'], true);
                $labelUrl = isset($transaction['label_url']) ? $transaction['label_url'] : '';
                $trackingNumber = isset($transaction['tracking_number']) ? $transaction['tracking_number'] : '';
                $trackingUrlProvider = isset($transaction['tracking_url_provider']) ? $transaction['tracking_url_provider'] : '';
                $messages = isset($transaction['messages']) ? $transaction['messages'] : array();
            }

            $shipping[] = array(
                'content'               => $package['content'],
                'messages'              => $messages,
                'label_url'             => $labelUrl,
                'tracking_number'       => $trackingNumber,
                'tracking_url_provider' => $trackingUrlProvider 
            );
        }

        $shippingCode = base64_encode(serialize($shipping));

        return $shippingCode;
    }

    /**
     * Set products data
     */
    private function __setProductsInfo($data, $products)
    {
        $product_data = array();

        foreach ($products as $product) {
            $option_data = array();

            $product_data[] = array(
                'product_id' => $product['product_id'],
                'name'       => $product['name'],
                'model'      => '',
                'option'     => array(),
                'download'   => array(),
                'quantity'   => $product['quantity'],
                'subtract'   => '',
                'price'      => $product['price'],
                'total'      => $product['total'],
                'tax'        => 0,//$this->tax->getTax($product['price'], $product['tax_class_id']),
                'reward'     => ''
            ); 
        }

        $data['products'] = $product_data;

        return $data;
    }

    /**
     * Set currency info
     */
    private function __setCurencyInformation($data, $currency)
    {
        
        $data['currency_id'] = $currency->getId();
        $data['currency_code'] = $currency->getCode();
        $data['currency_value'] = $currency->getValue($currency->getCode());

        return $data;
    }

    /**
     * Set request information
     */
    private function __setRequestInformation($data, $request)
    {
        $data['ip'] = $request->server['REMOTE_ADDR'];

        if (!empty($request->server['HTTP_X_FORWARDED_FOR'])) {
            $data['forwarded_ip'] = $request->server['HTTP_X_FORWARDED_FOR']; 
        } elseif(!empty($request->server['HTTP_CLIENT_IP'])) {
            $data['forwarded_ip'] = $request->server['HTTP_CLIENT_IP'];   
        } else {
            $data['forwarded_ip'] = '';
        }

        if (isset($request->server['HTTP_USER_AGENT'])) {
            $data['user_agent'] = $request->server['HTTP_USER_AGENT'];    
        } else {
            $data['user_agent'] = '';
        }

        if (isset($request->server['HTTP_ACCEPT_LANGUAGE'])) {
            $data['accept_language'] = $request->server['HTTP_ACCEPT_LANGUAGE'];  
        } else {
            $data['accept_language'] = '';
        }

        return $data;
    }
}
