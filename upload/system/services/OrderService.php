<?php

/**
 * Provides services for order
 */
class OrderService
{
    private static $instance;
    
    /**
     * Returns instance
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new OrderService();
        }

        return self::$instance;
    }

    /**
     * Saves order
     */
    public function saveOrder($data, $model)
    {
        // $data['invoice_prefix'] = $this->config->get('config_invoice_prefix');
        // $data['store_id'] = $this->config->get('config_store_id');
        // $data['store_name'] = $this->config->get('config_name');
        // $data['button_confirm'] = $this->config->get('button_confirm');

        // if ($data['store_id']) {
        //     $data['store_url'] = $this->config->get('config_url');      
        // } else {
        //     $data['store_url'] = HTTP_SERVER;   
        // }
        
        // $data['customer_id'] = 0;
        // $data['customer_group_id'] = $this->session->data['guest']['customer_group_id'];
        // $data['firstname'] = $this->session->data['guest']['firstname'];
        // $data['lastname'] = $this->session->data['guest']['lastname'];
        // $data['email'] = $this->session->data['guest']['email'];
        // $data['telephone'] = '';
        // $data['fax'] = '';

        // $data['payment_firstname'] = $this->session->data['guest']['payment']['firstname'];
        // $data['payment_lastname'] = ''; 
        // $data['payment_company'] = '';  
        // $data['payment_company_id'] = '';   
        // $data['payment_tax_id'] = '';   
        // $data['payment_address_1'] = '';
        // $data['payment_address_2'] = '';
        // $data['payment_city'] = '';
        // $data['payment_postcode'] = '';
        // $data['payment_zone'] = '';
        // $data['payment_zone_id'] = '';
        // $data['payment_country'] = '';
        // $data['payment_country_id'] = '';
        // $data['payment_address_format'] = '';

        // $data['payment_method'] = '';
        // $data['payment_code'] = $this->session->data['guest']['payment']['code'];

        // $data['shipping_firstname'] = '';
        // $data['shipping_lastname'] = '';    
        // $data['shipping_company'] = ''; 
        // $data['shipping_address_1'] = '';
        // $data['shipping_address_2'] = '';
        // $data['shipping_city'] = '';
        // $data['shipping_postcode'] = '';
        // $data['shipping_zone'] = '';
        // $data['shipping_zone_id'] = '';
        // $data['shipping_country'] = '';
        // $data['shipping_country_id'] = '';
        // $data['shipping_address_format'] = '';
        // $data['shipping_method'] = '';
        // $data['shipping_code'] = '';

        // $product_data = array();
        // $products = $this->cart->getProducts();

        // foreach ($products as $product) {
        //     $option_data = array();

        //     $product_data[] = array(
        //         'product_id' => $product['product_id'],
        //         'name'       => $product['name'],
        //         'model'      => '',//$product['model'],
        //         'option'     => array(),
        //         'download'   => array(),//$product['download'],
        //         'quantity'   => $product['quantity'],
        //         'subtract'   => '',//$product['subtract'],
        //         'price'      => $product['price'],
        //         'total'      => $product['total'],
        //         'tax'        => $this->tax->getTax($product['price'], $product['tax_class_id']),
        //         'reward'     => ''
        //     ); 
        // }

        // $data['products'] = $product_data;
        // $data['vouchers'] = array();
        // $data['totals'] = array();
        // $data['comment'] = '';
        // $data['totals'] = array();
        // $data['total'] = 0;
       
        // $data['affiliate_id'] = 0;
        // $data['commission'] = 0;
        
        // $data['language_id'] = $this->config->get('config_language_id');
        // $data['currency_id'] = $this->currency->getId();
        // $data['currency_code'] = $this->currency->getCode();
        // $data['currency_value'] = $this->currency->getValue($this->currency->getCode());
        // $data['ip'] = $this->request->server['REMOTE_ADDR'];

        // if (!empty($this->request->server['HTTP_X_FORWARDED_FOR'])) {
        //     $data['forwarded_ip'] = $this->request->server['HTTP_X_FORWARDED_FOR']; 
        // } elseif(!empty($this->request->server['HTTP_CLIENT_IP'])) {
        //     $data['forwarded_ip'] = $this->request->server['HTTP_CLIENT_IP'];   
        // } else {
        //     $data['forwarded_ip'] = '';
        // }

        // if (isset($this->request->server['HTTP_USER_AGENT'])) {
        //     $data['user_agent'] = $this->request->server['HTTP_USER_AGENT'];    
        // } else {
        //     $data['user_agent'] = '';
        // }

        // if (isset($this->request->server['HTTP_ACCEPT_LANGUAGE'])) {
        //     $data['accept_language'] = $this->request->server['HTTP_ACCEPT_LANGUAGE'];  
        // } else {
        //     $data['accept_language'] = '';
        // }

        // $this->load->model('checkout/order');
        
        // return $this->model_checkout_order->addOrder($data);
    }
}
