<?php

require_once(DIR_SYSTEM . 'services/CartService.php');

class ControllerCheckoutCart extends Controller {
	private $error = array();

	public function index() {
		$this->language->load('checkout/cart');
		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('common/home'),
			'text'      => $this->language->get('text_home'),
			'separator' => false
		);

		$this->data['breadcrumbs'][] = array(
			'href'      => $this->url->link('checkout/cart'),
			'text'      => $this->language->get('heading_title'),
			'separator' => $this->language->get('text_separator')
		);

		if ($this->cart->hasProducts() || !empty($this->session->data['vouchers'])) {	
			$this->data['heading_title'] = $this->language->get('heading_title');
			$this->data['action'] = $this->url->link('checkout/cart');   

			if ($this->config->get('config_cart_weight')) {
				$this->data['weight'] = $this->weight->format($this->cart->getWeight(), $this->config->get('config_weight_class_id'), $this->language->get('decimal_point'), $this->language->get('thousand_point'));
			} else {
				$this->data['weight'] = '';
			}

			$this->data['action'] = $this->url->link('checkout/cart');

			$cartTotalPrice = 0;
			
			$this->load->model('tool/image');
			$this->data['products'] = array();

			$products = $this->cart->getProducts();

			foreach ($products as $product) {
				$product_total = 0;

				// Loop to get quantities or products
				foreach ($products as $product_2) {
					if ($product_2['product_id'] == $product['product_id']) {
						$product_total += $product_2['quantity'];
					}
				}

				if ($product['minimum'] > $product_total) {
					$this->data['error_warning'] = sprintf($this->language->get('error_minimum'), $product['name'], $product['minimum']);
				}

				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], 55, 55/*$this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height')*/);
				}

				if (!isset($image) || empty($image) || is_null($image)) {
					$image = $this->model_tool_image->resize('no_image.jpg', 55, 55);
				}

				$option_data = array();

				$price = $this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax'));
				$total = $price * $product['quantity'];//$product['price'] * $product['quantity'];
				$cartTotalPrice += $total;

				//$total = $this->currency->format($total);
				//$price = $this->currency->format();

				$this->data['products'][] = array(
					'id'                  => $product['product_id'],
					'key'                 => $product['key'],
					'thumb'               => $image,
					'name'                => $product['name'],
					'quantity'            => $product['quantity'],
					'stock'               => $product['stock'] ? true : !(!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning')),
					'price'               => $this->currency->format($price),
					'total'               => $this->currency->format($total),
					'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					'remove'              => $this->url->link('checkout/cart', 'remove=' . $product['key']),
				);
			}

			$this->data['products_in_cart_count'] = $this->cart->countProducts();
			$this->data['subTotalWithCurrency'] = $this->currency->format($cartTotalPrice);
			$this->data['subTotal'] = $cartTotalPrice;

			$this->data['continue'] = $this->url->link('common/home');

			$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

			$this->load->model('setting/extension');

			$this->data['checkout_buttons'] = array();

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}

			$this->children = array(
				// 'common/column_left',
				// 'common/column_right',
				// 'common/content_bottom',
				// 'common/content_top',
				'paymentForm'  => 'checkout/checkout/paymentForm',
				'shippingForm' => 'checkout/checkout/shippingForm',
				'common/footer',
				'common/header'	
			);

			$this->response->setOutput($this->render());					
		} else {
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['text_error'] = $this->language->get('text_empty');

			$this->data['button_continue'] = $this->language->get('button_continue');

			$this->data['continue'] = $this->url->link('common/home');

			unset($this->session->data['success']);

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}

			$this->children = array(
				// 'common/column_left',
				// 'common/column_right',
				// 'common/content_top',
				// 'common/content_bottom',
				'common/footer',
				'common/header'	
			);

			$this->response->setOutput($this->render());			
		}
	}


	public function add() {
		$this->language->load('checkout/cart');

		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {			
			if (isset($this->request->post['quantity'])) {
				$quantity = $this->request->post['quantity'];
			} else {
				$quantity = 1;
			}

			if (!$json) {
				$this->cart->add($this->request->post['product_id'], $quantity, ''/*, $option, $profile_id*/);

				$json['success'] = sprintf($this->language->get('text_success'), $product_info['name'], $this->url->link('checkout/cart'));

				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
				unset($this->session->data['payment_method']);
				unset($this->session->data['payment_methods']);

				// Totals
				$this->load->model('setting/extension');

				$total_data = array();					
				$total = 0;
				

				$json['total'] = $this->cart->countProducts();// + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0); //sprintf($this->language->get('text_items'), $this->cart->countProducts() + (isset($this->session->data['vouchers']) ? count($this->session->data['vouchers']) : 0), $this->currency->format($total));

			} else {
				$json['redirect'] = str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $this->request->post['product_id']));
			}
		}

		$this->response->setOutput(json_encode($json));		
	}

	/**
	 * Updates cart product quantity
	 */
	public function updateCartProductQuantity()
	{
		$data = array();
		$key = $this->request->post['key'];
		$quantity = $this->request->post['quantity'];

		// Update
		if (!empty($key) && !empty($quantity)) {
			$this->cart->update($key, $quantity);

			$products = $this->cart->getProducts();
			$productNewPrice = $this->tax->calculate($products[$key]['price'], $products[$key]['tax_class_id'], $this->config->get('config_tax'));

			$data = array(
				'total' => number_format($this->cart->getTotal(), 2, '.', ','),
				'productsCount' => $this->cart->countProducts(),
				'productNewPrice' => number_format($productNewPrice * $quantity,  2, '.', ',')
			);
		}

		// Check rates if exist and update rates about the shipment
		$this->response->setOutput(json_encode($data));
	}

	/**
	 * Removes product in the cart
	 */
	public function removeProductInCart()
	{
		$key = $this->request->post['key'];

		if (!empty($key)) {
			$this->cart->remove($key);

			return $this->response->setOutput(json_encode(array(
				'success' => true,
				'total'   => number_format($this->cart->getTotal(), 2, '.', ','),
				'productsCount' => $this->cart->countProducts()
			)));

			// TODO: Check shipments rate
		}

		return $this->response->setOutput(json_encode(array('success' => false, 'msg' => 'Invalid product key.')));		
	}

	public function country() {
		$json = array();

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);

		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}

		$this->response->setOutput(json_encode($json));
	}
}
