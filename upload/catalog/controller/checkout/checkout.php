<?php 

require_once(DIR_SYSTEM . 'services/ShippoService.php');
require_once(DIR_SYSTEM . 'services/CartService.php');

class ControllerCheckoutCheckout extends Controller { 
	// public function index() {
	// 	// Validate cart has products and has stock.
	// 	if ((!$this->cart->hasProducts() && empty($this->session->data['vouchers'])) || (!$this->cart->hasStock() && !$this->config->get('config_stock_checkout'))) {
	// 		$this->redirect($this->url->link('checkout/cart'));
	// 	}

	// 	// Validate minimum quantity requirments.			
	// 	$products = $this->cart->getProducts();

	// 	foreach ($products as $product) {
	// 		$product_total = 0;

	// 		foreach ($products as $product_2) {
	// 			if ($product_2['product_id'] == $product['product_id']) {
	// 				$product_total += $product_2['quantity'];
	// 			}
	// 		}		

	// 		if ($product['minimum'] > $product_total) {
	// 			$this->redirect($this->url->link('checkout/cart'));
	// 		}				
	// 	}

	// 	$this->language->load('checkout/checkout');

	// 	$this->document->setTitle($this->language->get('heading_title')); 
	// 	$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
	// 	$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

	// 	$this->data['breadcrumbs'] = array();

	// 	$this->data['breadcrumbs'][] = array(
	// 		'text'      => $this->language->get('text_home'),
	// 		'href'      => $this->url->link('common/home'),
	// 		'separator' => false
	// 	);

	// 	$this->data['breadcrumbs'][] = array(
	// 		'text'      => $this->language->get('text_cart'),
	// 		'href'      => $this->url->link('checkout/cart'),
	// 		'separator' => $this->language->get('text_separator')
	// 	);

	// 	$this->data['breadcrumbs'][] = array(
	// 		'text'      => $this->language->get('heading_title'),
	// 		'href'      => $this->url->link('checkout/checkout', '', 'SSL'),
	// 		'separator' => $this->language->get('text_separator')
	// 	);

	// 	$this->data['heading_title'] = $this->language->get('heading_title');

	// 	$this->data['text_checkout_option'] = $this->language->get('text_checkout_option');
	// 	$this->data['text_checkout_account'] = $this->language->get('text_checkout_account');
	// 	$this->data['text_checkout_payment_address'] = $this->language->get('text_checkout_payment_address');
	// 	$this->data['text_checkout_shipping_address'] = $this->language->get('text_checkout_shipping_address');
	// 	$this->data['text_checkout_shipping_method'] = $this->language->get('text_checkout_shipping_method');
	// 	$this->data['text_checkout_payment_method'] = $this->language->get('text_checkout_payment_method');		
	// 	$this->data['text_checkout_confirm'] = $this->language->get('text_checkout_confirm');
	// 	$this->data['text_modify'] = $this->language->get('text_modify');

	// 	$this->data['logged'] = $this->customer->isLogged();
	// 	$this->data['shipping_required'] = $this->cart->hasShipping();	

	// 	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/checkout.tpl')) {
	// 		$this->template = $this->config->get('config_template') . '/template/checkout/checkout.tpl';
	// 	} else {
	// 		$this->template = 'default/template/checkout/checkout.tpl';
	// 	}

	// 	$this->children = array(
	// 		'common/column_left',
	// 		'common/column_right',
	// 		'common/content_top',
	// 		'common/content_bottom',
	// 		'common/footer',
	// 		'common/header'	
	// 	);

	// 	if (isset($this->request->get['quickconfirm'])) {
	// 		$this->data['quickconfirm'] = $this->request->get['quickconfirm'];
	// 	}

	// 	$this->response->setOutput($this->render());
	// }

	// TODO: Transfer to order controller
    // 1. Pay
    // 2. Ship
    // 3. Send email with transaction #, etc.

    /**
     * Handles final order
     */
    public function processOrder()
    {
        // Process Order
        try {
            $cartService = CartService::getInstance();
            $shippingAmount = $cartService->getAmountOfShippingServiceRate($this->request->post['service_name']);

            $amount = $this->cart->getTotal() + $shippingAmount;
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

                $shippoService = ShippoService::getInstance();
                $shippoService->requestShipping($this->request->post['service_name']);

            	$this->load->model('checkout/order');
            	// $this->model_checkout_order->addOrder(array(
            	// 	''
            	// ));
            	// Store Order in Database
				// $sql = "INSERT INTO `" . DB_PREFIX . "order` SET order_id = '" . (int)1; 
				// $sql .= "', order_status_id = '" . (int)true;
				// $sql .= "', email = '" . $email;
				// $sql .= "', shipping_code = '" . 'test';
				// $sql .= "' ";

				// $this->db->query($sql);

				

                //$cartService->emailCustomerForConfirmation(MailUtil::getInstance($this->config), $email);
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
     * Successful checkout
     */
    public function onSuccess()
    {
    	if (isset($this->session->data['order_id'])) {
			$this->cart->clear();

			$cartService = CartService::getInstance();
			$cartService->emailCustomerForConfirmation(MailUtil::getInstance($this->config), $email);
			// On retrieve order for thank you message

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/success.tpl')) {
	            $this->template = $this->config->get('config_template') . '/template/checkout/success.tpl';
	        } else {
	            $this->template = '';
	        }
		}

		$this->response->setOutput($this->render());
    }

    /**
     * Checks shipping info/rates
     */
    public function checkShippingInfo()
    {
        /*** Confirming address **/
        try {
            $toAddressData = array(
                'name' => $this->request->post['name'],
                'street1' => $this->request->post['address'],
                'city'    => $this->request->post['city'],
                'state'   => $this->request->post['state'],
                'zip'     => $this->request->post['postcode'],
                'country' => $this->request->post['country'],
                'email'   => $this->request->post['email']
            );

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

            $_SESSION['toAddress'] = $toAddress;
            $_SESSION['fromAddress'] = $fromAddress;
            
            $cartService = CartService::getInstance();
            $packages = $cartService->preparePackages($this->cart->getProducts());

            $info = $shippoService->getShipmentInfo($packages, $fromAddress, $toAddress);

            $rates = array('rates' => $info);
            $_SESSION['rates'] = $info;

            echo json_encode($rates);
            exit;

        } catch(Exception $e) {
            throw $e;
        }
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
            $months[$ctr] = date("m", strtotime(date('Y').'-'.$ctr.'-'.date('d')));
        }

        $this->data['years'] = $years;
        $this->data['months'] = $months;
        $this->data['current_month'] = date("m");
        $this->data['current_year'] = date("y");


    	if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/_payment.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/checkout/_payment.tpl';
        } else {
            $this->template = '';
        }

        $this->render();
    }
}