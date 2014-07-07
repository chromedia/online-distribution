<?php

require_once(DIR_SYSTEM . 'services/CheckoutService.php');
require_once(DIR_SYSTEM . 'services/ProductService.php');
require_once(DIR_SYSTEM . 'utilities/MailUtil.php');
require_once(DIR_SYSTEM . 'services/ShippoService.php');


/**
 * Handles checkout success
 */
class ControllerCheckoutSuccess extends Controller { 
	public function index() { 	
		if (isset($this->session->data['order_id'])) {
            $this->data['breadcrumbs'][] = array(
                'href'      => $this->url->link('common/home'),
                'text'      => $this->language->get('text_home'),
                'separator' => false
            );

            $this->data['breadcrumbs'][] = array(
                'href'      => '',
                'text'      => 'Checkout Success',
                'separator' => $this->language->get('text_separator')
            );

            $products = $this->cart->getProducts();

            if ($products) {   
                $this->data['heading_title'] = $this->language->get('heading_title');                
                $this->load->model('tool/image');

                $productService = ProductService::getInstance($this->config, $this->currency, $this->model_tool_image, $this->tax, $this->url);
                $this->data['products'] = $productService->getProductCheckoutInfo($products);
            }

            $cartTotalPrice = $this->cart->getTotal();
            $this->data['products_in_cart_count'] = $this->cart->countProducts();
            $this->data['subTotal'] = $this->currency->format($cartTotalPrice);
            $this->data['shippingCost'] = $this->currency->format($this->session->data['shipping']['cost']);
            $this->data['total'] = $this->currency->format($cartTotalPrice + $this->session->data['shipping']['cost']);

            $emailData = array(
                'recipient' => $this->session->data['shipping']['email'],//$this->session->data['guest']['email'],
                'total'     => $this->data['total'],
                'subTotal'  => $this->data['subTotal'],
                'shippingCost' => $this->data['shippingCost'],
                'products'  => $this->data['products']
            );

            $checkoutService = CheckoutService::getInstance();

            try {
                $checkoutService->emailCustomerForConfirmation($emailData, MailUtil::getInstance($this->config), ShippoService::getInstance());
            } catch(\Exception $e) {
                $this->log->write($e->getMessage());
            }
            

            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/checkout/success.tpl';
            } else {
                $this->template = '';
            }

            $this->cart->clear();
            $this->__unsetSessionVariablesInCheckout();

            $this->children = array(
                'common/footer',
                'common/header' 
            );

            $this->response->setOutput($this->render());
        } else {
            $this->redirect($this->url->link('checkout/cart', '', 'SSL'));
        }
	}

	/**
     * Unsets all session involves in checkout
     */
    private function __unsetSessionVariablesInCheckout()
    {
        // unset($this->session->data['shipping_cost']);
        unset($this->session->data['order_id']);
        unset($this->session->data['packages']);
        unset($this->session->data['shipping']);
        unset($this->session->data['payment']);
        unset($this->session->data['guest']);
        unset($this->session->data['rates']);
    }
}