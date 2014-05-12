<?php 
class ControllerCheckoutCart extends Controller {
	private $error = array();

	public function index() {
		
		// Shipping Info PHP

		$this->data['text_address_existing'] = $this->language->get('text_address_existing');
		$this->data['text_address_new'] = $this->language->get('text_address_new');
		$this->data['text_select'] = $this->language->get('text_select');
		$this->data['text_none'] = $this->language->get('text_none');

		$this->data['entry_firstname'] = $this->language->get('entry_firstname');
		$this->data['entry_lastname'] = $this->language->get('entry_lastname');
		$this->data['entry_company'] = $this->language->get('entry_company');
		$this->data['entry_address_1'] = $this->language->get('entry_address_1');
		$this->data['entry_address_2'] = $this->language->get('entry_address_2');
		$this->data['entry_postcode'] = $this->language->get('entry_postcode');
		$this->data['entry_city'] = $this->language->get('entry_city');
		$this->data['entry_country'] = $this->language->get('entry_country');
		$this->data['entry_zone'] = $this->language->get('entry_zone');
	
		$this->data['button_continue'] = $this->language->get('button_continue');
			
		if (isset($this->session->data['shipping_address_id'])) {
			$this->data['address_id'] = $this->session->data['shipping_address_id'];
		} else {
			$this->data['address_id'] = $this->customer->getAddressId();
		}

		$this->load->model('account/address');

		$this->data['addresses'] = $this->model_account_address->getAddresses();

		if (isset($this->session->data['shipping_postcode'])) {
			$this->data['postcode'] = $this->session->data['shipping_postcode'];		
		} else {
			$this->data['postcode'] = '';
		}
				
		if (isset($this->session->data['shipping_country_id'])) {
			$this->data['country_id'] = $this->session->data['shipping_country_id'];		
		} else {
			$this->data['country_id'] = $this->config->get('config_country_id');
		}
				
		if (isset($this->session->data['shipping_zone_id'])) {
			$this->data['zone_id'] = $this->session->data['shipping_zone_id'];		
		} else {
			$this->data['zone_id'] = '';
		}
						
		$this->load->model('localisation/country');
		
		$this->data['countries'] = $this->model_localisation_country->getCountries();

		// End Shipping Info PHP





		// Load language file for cart page
		$this->language->load('checkout/cart');

		// Page Prep
		$this->document->setTitle($this->language->get('heading_title'));
		$this->document->addScript('catalog/view/javascript/jquery/colorbox/jquery.colorbox-min.js');
		$this->document->addStyle('catalog/view/javascript/jquery/colorbox/colorbox.css');

		// If cart has products
		// if ( $this->cart->hasProducts() ) { }

		// Cart Page Info
		if (1==1) {

			// Language Set
			$this->data['heading_title'] = $this->language->get('heading_title');

			$this->data['entry_coupon'] = $this->language->get('entry_coupon');
			$this->data['entry_voucher'] = $this->language->get('entry_voucher');
			$this->data['entry_country'] = $this->language->get('entry_country');
			$this->data['entry_zone'] = $this->language->get('entry_zone');
			$this->data['entry_postcode'] = $this->language->get('entry_postcode');

			// Check if Logged In
			if ($this->config->get('config_customer_price') && !$this->customer->isLogged()) {
				$this->data['attention'] = sprintf($this->language->get('text_login'), $this->url->link('account/login'), $this->url->link('account/register'));
			} else {
				$this->data['attention'] = '';
			}

			// 
			$this->load->model('tool/image');

			// Prepare products array
			$this->data['products'] = array();

			// Load products in cart
			//$products = $this->cart->getProducts();

			// Set cart array if needed
			if(!isset($_SESSION['xcart'])){
				$_SESSION['xcart'] = array();
			}

			// Set order array if needed
			if(!isset($_SESSION['order'])){
				$_SESSION['order'] = array();
			}	

			// Set shipment array if needed
			if(!isset($_SESSION['shipment'])){
				$_SESSION['shipment'] = array();
			}							

			// For each product, load its info
			foreach ($_SESSION['xcart'] as $product_array) {

				if(!isset($product_array['key'])) {
					break;
				}

				// Get product id
				$product_id = $product_array['key'];

				// Load product model code
				$this->load->model('catalog/product');

				// Retrieve product data from database
				$product = $this->model_catalog_product->getProduct($product_id);

				//var_dump($product);

				// Configure image
				if ($product['image']) {
					$image = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_cart_width'), $this->config->get('config_image_cart_height'));
				} else {
					$image = '';
				}

				$option_data = array();

				// Check product options
				/*foreach ($product['option'] as $option) {
					if ($option['type'] != 'file') {
						$value = $option['option_value'];
					} else {
						$filename = $this->encryption->decrypt($option['option_value']);

						$value = utf8_substr($filename, 0, utf8_strrpos($filename, '.'));
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}*/

				$profile_description = '';

				// Get unit price
				$price = number_format($product['price'], 2, '.', '');
				// Get quantity
				$quantity = $_SESSION['xcart'][$product_id]['quantity'];				
				// Get extended price
				$total = number_format($product['price'] * $quantity, 2, '.', '') ; 	

				// Sessionize unit price
				$_SESSION['xcart'][$product_id]['price'] = $price;

				// Sessionize extended price
				$_SESSION['xcart'][$product_id]['total'] = $total;	

				// Set product array
				$this->data['products'][] = array(
					'key'                 => $product['model'],
					'thumb'               => $image,
					'name'                => $product['name'],
					'model'               => $product['model'],
					'option'              => $option_data,
					'quantity'            => $quantity,
					'price'               => $price,
					'total'               => $total,
					'href'                => $this->url->link('product/product', 'product_id=' . $product['product_id']),
					//'recurring'           => $product['recurring'],
					//'profile_name'        => $product['profile_name'],
					'profile_description' => $profile_description,
				);
			}

			$_SESSION['subtotal'] = Cart::subtotal($_SESSION['xcart']);


			



			$this->data['checkout_buttons'] = array();

			// Load HTML files
			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/checkout/cart.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/checkout/cart.tpl';
			} else {
				$this->template = 'default/template/checkout/cart.tpl';
			}

			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_bottom',
				'common/content_top',
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
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'	
			);

			$this->response->setOutput($this->render());			
		}
	}	
}
?>
