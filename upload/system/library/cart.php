<?php
class Cart {
	private $config;
	private $db;
	private $data = array();

	/* Function List:

		*General*
		__construct
		getProduct

		*Product Nav*
		add_item

		*Cart*
		change_quantity
		delete_item
		subtotal
		payment_info
		confirm_address
		package_logic
		ship_speeds
		select_ship_speed

		*Checkout*
		checkout
		process_payment
		request_shipping
		create_order
		contact_user

	*/

	public function __construct($registry) {

		// Load objects from registry
		$this->config = $registry->get('config');
		$this->request = $registry->get('request');
		$this->db = $registry->get('db');
		$this->session = $registry->get('session');		
		$this->restcall = $registry->get('restcall');

		$this->customer = $registry->get('customer');
		$this->tax = $registry->get('tax');
		$this->weight = $registry->get('weight');

		// Set cart array if unset
		if (!isset($this->session->data['cart']) || !is_array($this->session->data['cart'])) {
			$this->session->data['cart'] = array();
		}	

	} 

	// Return Product Data Array
	public function getProduct($product_id) {	

		// Connect to database
		$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

		$query = $db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id) AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id) AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id) AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id) AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.model = '" . (int)$product_id . "' AND p.status = '1' AND p.date_available <= NOW()");

		//$query = $db->query($sql);
		//$result = $db->query("SELECT * FROM " . DB_PREFIX . "WHERE product_id = '" . (int)$product_id . "'");

		//echo $result;
		//var_dump($query);

			if ($query->num_rows) {
				$array = array(
					'product_id'       => $query->row['model'],
					'name'             => $query->row['name'],
					'description'      => $query->row['description'],
					'meta_description' => $query->row['meta_description'],
					'meta_keyword'     => $query->row['meta_keyword'],
					'tag'              => $query->row['tag'],
					'model'            => $query->row['model'],
					'sku'              => $query->row['sku'],
					'upc'              => $query->row['upc'],
					'ean'              => $query->row['ean'],
					'jan'              => $query->row['jan'],
					'isbn'             => $query->row['isbn'],
					'mpn'              => $query->row['mpn'],
					'location'         => $query->row['location'],
					'quantity'         => $query->row['quantity'],
					'stock_status'     => $query->row['stock_status'],
					'image'            => $query->row['image'],
					'manufacturer_id'  => $query->row['manufacturer_id'],
					'manufacturer'     => $query->row['manufacturer'],
					'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
					'special'          => $query->row['special'],
					'reward'           => $query->row['reward'],
					'points'           => $query->row['points'],
					'tax_class_id'     => $query->row['tax_class_id'],
					'date_available'   => $query->row['date_available'],
					'weight'           => $query->row['weight'],
					'weight_class_id'  => $query->row['weight_class_id'],
					'length'           => $query->row['length'],
					'width'            => $query->row['width'],
					'height'           => $query->row['height'],
					'length_class_id'  => $query->row['length_class_id'],
					'subtract'         => $query->row['subtract'],
					'rating'           => round($query->row['rating']),
					'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
					'minimum'          => $query->row['minimum'],
					'sort_order'       => $query->row['sort_order'],
					'status'           => $query->row['status'],
					'date_added'       => $query->row['date_added'],
					'date_modified'    => $query->row['date_modified'],
					'viewed'           => $query->row['viewed']
				);
				
				return $array;
			} 

			//var_dump($array);
		//$query->close();

		//$db->close();

	}	

	// Receive product id and quantity + Return new product quantity
	public function add_item() {

		// Get product id and quantity to add
		$product_id = (int)$_POST['product_id'];
		$quantity = (int)$_POST['quantity'];

		// Set minimum quantity to 1
		if($quantity<1) { 
			$quantity = 1; 
		}

		// Set cart if needed
		if(!isset($_SESSION['xcart'])){
			$_SESSION['xcart'] = array();
		}

		// Set quantity key if needed
		if(!isset($_SESSION['xcart'][$product_id]['quantity'])){
			$_SESSION['xcart'][$product_id]['quantity'] = 0;
		}		

		// Add product quantity and key to cart
		$_SESSION['xcart'][$product_id]['quantity'] += $quantity;
		$_SESSION['xcart'][$product_id]['key'] = $product_id;

		// Send data back to browser
		$update = array(

			"quantity" => $quantity,
			"product_id" => $product_id

		);

		echo json_encode($update);	
	}

	// Receive product id
	public function delete_item() {

		// Get product id
		$product_id = $_POST['product_id'];

		// Get cart array
		$cart = $_SESSION['xcart'];

		// Delete item from cart
		unset($cart[$product_id]);

		// Update cart array
		$_SESSION['xcart'] = $cart;

		// Send data back to browser
		$update = array(

			"product_id" => $product_id

		);

		echo json_encode($update);

	}

	public function change_quantity() {

		// Get new quantity and product id
		$new_quantity = $this->request->post['quantity_value'];
		$product_id = $this->request->post['product_id'];

		// Get cart array, order array
		$cart = $_SESSION['xcart'];
		$order = $_SESSION['order'];

		// Calculate and format new item total
		$item_total = $new_quantity * $cart[$product_id]['price'];
		$item_total = number_format($item_total, 2, '.', '');

		// Store new quantity and item total
		$cart[$product_id]['quantity'] = $new_quantity;
		$cart[$product_id]['total'] = $item_total;

		// Calculate new subtotal
		$subtotal = $this->subtotal($cart);		

		// Store new subtotal
		$order['subtotal'] = $subtotal;

		// Update cart array, order array
		$_SESSION['xcart'] = $cart;
		$_SESSION['order'] = $order;

		// Set response array
		$response = array(
			"product_id" => $product_id,
			"quantity_value"=> $new_quantity,
			"item_total"=> $item_total,
			"subtotal" => $subtotal
		);

		// Send data back to page
		echo json_encode($response);
	}

	// Return Subtotal
	public static function subtotal($array) {

		// Calculate subtotal
		$subtotal = 0;
		foreach($array as $item) {

			$subtotal += $item['total'];

		}

		// Return subtotal with 2 decimals
		return number_format($subtotal, 2, '.', '');

	}	

	// Store Stripe token
	public function payment_info() {

		// Store the received token string in a session variable
		$_SESSION['token'] = $this->request->post['stripeToken'];

		// Set token in php array
		$token = array(
			"token" => $_SESSION['token'],
			"token2"=> 'hello'
		);

		// Encode array into JSON
		echo json_encode($token);		

	}

	// Receive address + Return shipping speeds
	public function confirm_address() {

		try {

			// Get POST data
			$name = $this->request->post['name'];
			$company = "";
			$address = $this->request->post['address'];
			$city = $this->request->post['city'];
			$postcode = $this->request->post['postcode'];
			$region = $this->request->post['region'];
			$country = $this->request->post['country'];	
			$email = $this->request->post['email'];	

			// Sessionize shipping address
			$_SESSION['shipping_address'] = array(

				'name' => $name,
				'company' => $company,
				'address' => $address,
				'city' => $city,
				'postcode' => $postcode,
				'region' => $region,
				'country' => $country,
				'email' => $email

			);

			// To Address Data
			$data = array(
			    "object_purpose" => "PURCHASE",
			    "name" => $name,
			    "company" => $company,
			    "street1" => $address,
			    "street_no" => "",
			    "street2" => "",
			    "city" => $city,
			    "state" => $region,
			    "zip" => $postcode,
			    "country" => $country,
			    "phone" => "",
			    "email" => $email,
			    "ip" => "",
			    "metadata" => ""
			);

			// Call Data
			$url = 'https://api.goshippo.com/v1/addresses/';
			$method = 'POST';
			$credentials = SHIPPO_AUTHORIZATION;

			// Run call
			$output = $this->restcall->call($url, $method, $credentials, $data);
			$output = json_decode($output);

			// Throw Error If Invalid Address
			if(!is_object($output)){
				$error = 1;
			    throw new Exception($error);					
			}				

			// Set To Address
			$to_address = $output->object_id;

			// Set JSON request for FROM ADDRESS
			$senddata = '{ 
			    "object_purpose": "PURCHASE",
			    "name": "Laura Behrens Wu",
			    "company": "Shippo",
			    "street1": "Clayton St.",
			    "street_no": "220",
			    "street2": "",
			    "city": "San Francisco",
			    "state": "CA",
			    "zip": "94117",
			    "country": "US",
			    "phone": "+1 555 341 9393",
			    "email": "laura@goshippo.com",
			    "ip": "",
			    "metadata": "Customer ID 123456"
			 	}';
			$senddata = json_decode($senddata, true);

			// Set variables
			$url = 'https://api.goshippo.com/v1/addresses/';
			$method = 'POST';
			$credentials = SHIPPO_AUTHORIZATION;
			$data = $senddata;

			// Run call
			$output = $this->restcall->call($url, $method, $credentials, $data);
			$output = json_decode($output);

			// Set From Address
			$from_address = $output->object_id;	

			// Sessionize addresses
			$_SESSION['to_address'] = $to_address;
			$_SESSION['from_address'] = $from_address;

			$success = 1;			

		} catch (Exception $error){

			$success = 0;

		}

		$bbb = 2;

		// Send data back to browser
		$update = array(

			"success" => $success, //$_SESSION['shipments'][1]['length'],
			"yes" => $bbb,

		);

		echo json_encode($update);		

	}	

	// Receive cart array + Return shipments array (transforms cart items into packages with length-width-height-weight values)
	public function package_logic() {

		// Get cart array
		$cart_array = $_SESSION['xcart'];

		// Set shipments array
		$packages = array();

		// For each unique product 
		foreach($cart_array as $product_array) {

			// Get product id
			$product_id = $product_array['key'];

			// Reset product data
			unset($product);

			// Retrieve product data from database
			$product = $this->getProduct($product_id);

			// Get product data
			$product_id = intval($product['product_id']);
			$product_name = intval($product['name']);

			$length = intval($product['length']);
			$width = intval($product['width']);
			$height = intval($product['height']);
			$weight = intval($product['weight']);

			// Get product quantity
			$quantity = $product_array['quantity'];

			// For each unit of quantity
			for($i=0; $i<$quantity; $i++) {
				
				// Count number of shipments set
				$count = count($packages);

				// Create contents array
				$contents = array();
				$contents[$product_id] = array(

					'product_id' => $product_id,
					'product_name' => $product_name,
					'quantity' => 1

				);

				// Create new shipment and add dimensions + weight + contents array
				$packages[$count] = array(

					'contents' 	=> $contents,
					'length'	=> $length,
					'width'		=> $width,
					'height'	=> $height,
					"distance_unit" => "mm",
					'weight'	=> $weight,
					"mass_unit" => "kg",
				    "template" => "",
				    "metadata" => ""					

				);				
			}
		}	

		// Update session packages array
		$_SESSION['packages'] = $packages;

		$success = 1;

		// Send data back to browser
		$update = array(

			"success" => $success

		);

		echo json_encode($update);	

	}

	// Make Parcel Call and Shipment Call to Shippo, then Get Shipping Rates
	public function ship_speeds () {

		// Get order info
		$order = $_SESSION['order'];
		$packages = $_SESSION['packages'];
		$to_address = $_SESSION['to_address'];
		$from_address = $_SESSION['from_address'];

		// Set rate array
		if(!isset($rate_array)){
			$rate_array = array();
		}			

		// Define rates_html variable
		if(!isset($rates_html)) {
			$rates_html = '';
		}		

		// Count number of packages
		$count = count($packages);

		// For each package, make parcel call and shipment call
		for($i=0; $i<$count; $i++) {

			// Get current package data
			$package = $packages[$i];
			
			// Parcel Data
			$data = array(

				//'contents' 	=> array(),
				'length'	=> $package['length'],
				'width'		=> $package['width'],
				'height'	=> $package['height'],
				"distance_unit" => "mm",
				'weight'	=> $package['weight'],
				"mass_unit" => "kg",
			    "template" => "",
			    "metadata" => ""					

			);			

			// Call Data
			$url = 'https://api.goshippo.com/v1/parcels/';
			$method = 'POST';
			$credentials = SHIPPO_AUTHORIZATION;

			// Run call
			$response = $this->restcall->call($url, $method, $credentials, $data);

			// Decode json response into php object
			$object = json_decode($response);

			// Store parcel id
			$parcel_id = $object->object_id;	

			// Shipment Data
			$data = array(
			    "object_purpose" => "PURCHASE",
			    "address_from" => $from_address,
			    "address_to" => $to_address,
			    "parcel" => $parcel_id,
			    "submission_type" => "PICKUP",
			    "submission_date" => "",
			    "insurance_value" => "",
			    "insurance_currency" => "USD",
			    "extra" => json_encode(array(
			        "signature_confirmation" => true
			    )),
			    "customs_declaration" => "",
			    "reference_1" => "",
			    "reference_2" => "",
			    "metadata" => "Customer ID 123456"
			);		

			// Call Data
			$url = 'https://api.goshippo.com/v1/shipments/';
			$method = 'POST';
			$credentials = SHIPPO_AUTHORIZATION;

			// Run call
			$response = $this->restcall->call($url, $method, $credentials, $data);

			// Decode json response into php object
			$object = json_decode($response);

			// Get rates url for this shipment
			$rates_url = $object->rates_url;

			// Debug echo shipment id
			//echo $object->object_id;

			// Store rates url in package array
			$packages[$i]['rates_url'] = $rates_url; 

		}

		// Give time for rates to be generated at Shippo for each shipment
		sleep(2);

		// Get rates for each package
		for($i=0; $i<$count; $i++) {

			// Get current package data
			$package = $packages[$i];

			// Get rates_url for this package
			$rates_url = $package['rates_url'];

			// Rates Data
			$data = false;

			// Call Data
			$url = $rates_url;	
			$method = 'GET';
			$credentials = SHIPPO_AUTHORIZATION;		

			// Run call
			$response = $this->restcall->call($url, $method, $credentials, $data);

			// Decode json response into php object
			$object = json_decode($response);

			// Set rates object
			$rates = $object->results;			

			// Go through rates of current package and add rates to rates array by service level
			foreach($rates as $rate) {

				// Get rate provider
				$provider = $rate->provider;

				// Filter by rate provider
				if($provider == "UPS"){

					// Get rate data
					$rate_id = (string)$rate->object_id;
					$shipment_id = (string)$rate->shipment;					
					$service = (string)$rate->servicelevel_name;
					$amount = (float)$rate->amount;	

					// Store rate data into package array on next available index
					$package['rates'][] = array(
						"rate_id" => $rate_id,
						"service" => $service
					);
					
					// Store rate data into rate array
					$rate_array[$service]['provider'] = $provider;
					$rate_array[$service]['service'] = $service;
					$rate_array[$service]['amount'][] = $amount;
					$rate_array[$service]['rate_id'][] = $rate_id;
					$rate_array[$service]['shipment_id'][] = $shipment_id;

					// Update total cost for this rate service from all packages
					if(!isset($rate_array[$service]['total'])) {
						// Set new total
						$rate_array[$service]['total'] = (float)$amount;
					}
					else {
						// Add to total
						$rate_array[$service]['total'] += (float)$amount;
					}
				}
			}

			// Update packages array
			$packages[$i] = $package;
		}

		// Find cheapest total cost for rate services from all packages
		foreach($rate_array as $service){

			// Get current service cost and service level
			$new_amount = $service['total'];
			$new_service = $service['service'];

			// Set low amount
			if(!isset($low_service)){
				$low_service = $new_service;
				$low_amount = $new_amount;
			}

			// Compare cost and save cheaper service
			if($new_amount < $low_amount){
				$low_service = $new_service;
				$low_amount = $new_amount;
			}

		}
		// Record cheapest to rate array
		$rate_array[$low_service]['checked'] = true;

		// Generate Service HTML for each service level
		foreach($rate_array as $service){

			// Service Data
			$provider_name = $service['provider'];
			$service_name = $service['service'];
			$total_value = $service['total'];

			// Determined if selected			
			if(isset($service['checked'])){
				$selected = 'checked';
			}
			else {
				$selected = "";
			}

			// Format Total
			$total = number_format($total_value, 2, '.', '');
			$total = (string)$total;

			// Service HTML
			$rates_html .= '<input type="radio" name="service" value="' . $service_name . '"' . $selected . '>' . $provider_name . ' ' . $service_name . ' ' . $total . '</input><br/>';
	
		}

		// Sessionize updated arrays
		$_SESSION['packages'] = $packages;
		$_SESSION['rate_array'] = $rate_array;
		$_SESSION['order'] = $order;

		// Send data back to browser
		$update = array(

			"success" => 1,
			"rates" => $rates_html

		);

		echo json_encode($update);		

	}

	public function select_ship_speed () {	

		// Get selected rate
		$selected_service = $this->request->post['rate'];

		// Get order array
		$order = $_SESSION['order'];
		$rate_array = $_SESSION['rate_array'];

		// Store selected rate
		$order['rate'] = $selected_service;

		// Get rate total cost
		$cost = $rate_array[$selected_service]['total'];

		// Calculate new total
		$order['total'] = $order['subtotal'] + $cost;

		// Update order array
		$_SESSION['order'] = $order;

		// Set response array
		$order = array(

			"success" => '1',
			"total" => $order['total'],
			"cost" => $cost

		);

		// Encode into JSON
		echo json_encode($order);	

	}		

	// Total rates for each service level
	// error on no common service level
	// don't show uncommon service levels
	// design so rate selection into mass label buying is easy-done

		// Calculate shipments array
		//$shipments = $this->shiplogic($cart_array);

		// Sessionize shipments array
		//$_SESSION['shipments'] = $shipments;

		// Calculate shipping speeds for shipments array
		//$shipspeeds = $this->shipspeeds($shipments);

		// Export shipspeeds array to readable string
		//$shipspeeds = var_export($shipspeeds, true);

	// Checkout Function
	public function checkout() {

		$this->process_payment();
		$this->request_shipping();
		$this->create_order();
		$this->contact_user();

		// Set response array
		$response = array(

			"state" => 'Success',
			"state2"=> 'hello'

		);

		// Encode into JSON
		echo json_encode($response);		


	}

	public function process_payment() {

		// Process Payment
		try {
		        // set your secret key: remember to change this to your live secret key in production
		        // see your keys here https://manage.stripe.com/account
		        Stripe::setApiKey(STRIPE_PRIVATE_KEY);

		        // Charge the order:
		        $charge = Stripe_Charge::create(array(
		                "amount" => 10*100, // amount in cents, again
		                "currency" => "cad",
		                "card" => $_SESSION['token'],
		                "description" => "payinguser@example.com"
		                )
		        );

		        // Check that it was paid:
		        if ($charge->paid == true) {

		                

		        } else { // Charge was not paid!	
		                echo '<div class="alert alert-error"><h4>Payment System Error!</h4>Your payment could NOT be processed (i.e., you have not been charged) because the payment system rejected the transaction. You can try again or use another card.</div>';
		        }

		} catch (Stripe_CardError $e) {
		        // Card was declined.
		        $e_json = $e->getJsonBody();
		        $err = $e_json['error'];
		        $errors['stripe'] = $err['message'];
		} catch (Stripe_ApiConnectionError $e) {
		        // Network problem, perhaps try again.
		} catch (Stripe_InvalidRequestError $e) {
		        // You screwed up in your programming. Shouldn't happen!
		} catch (Stripe_ApiError $e) {
		        // Stripe's servers are down!
		} catch (Stripe_CardError $e) {
		        // Something else that's not the customer's fault.
		}		


	}

	public function request_shipping(){

		// Get order and package arrays
		$order = $_SESSION['order'];
		$packages = $_SESSION['packages'];

		// Get selected shipping service
		$selected_service = $order['rate'];

		// For each package, purchase shipping label for selected service
		$count = count($packages);
		for($i=0; $i<$count; $i++){

			// Set current package
			$package = $packages[$i];

			// In the package rate array, find rate id of selected service
			foreach($package['rates'] as $rate){
				if($selected_service == $rate['service']){
					$rate_id = $rate['rate_id'];
					break;
				}
			}

			// Shipping Label Data
			$data = array(
				"rate" => $rate_id,
			    "notification_email_from" => false,
			    "notification_email_to" => false,
			    "notification_email_other" => "",
			    "metadata" => ""
			);		

			// Call Data
			$url = 'https://api.goshippo.com/v1/transactions/';
			$method = 'POST';
			$credentials = SHIPPO_AUTHORIZATION;

			// Run call (label purchase request)
			$response = $this->restcall->call($url, $method, $credentials, $data);
			$object = json_decode($response);

			// Get transaction id
			$transaction_id = $object->object_id;

			// Store transaction id in package array
			$package['transaction_id'] = $transaction_id;

			// Updates packages array
			$packages[$i] = $package;

		}

		// Wait 2 second for label purchases to process
		sleep(3);

		// For each package, get tracking number and label url
		for($i=0; $i<$count; $i++){

			// Set current package
			$package = $packages[$i];

			// Get package transaction id
			$transaction_id = $package['transaction_id'];		

			// If purchase not complete, wait and try data request again
			$wait = true;
			while($wait == true) {

				// No Data
				$data = false;

				// Call Data
				$url = 'https://api.goshippo.com/v1/transactions/' . $transaction_id;
				$method = 'GET';
				$credentials = SHIPPO_AUTHORIZATION;

				// Run call and decode json response into php object
				$response = $this->restcall->call($url, $method, $credentials, $data);
				$object = json_decode($response);

				// Get status
				$status = $object->object_status;

				// If purchase not complete yet, wait and try again
				if ($status == "WAITING" || $status == "QUEUED") {
					sleep(1);
					$wait = true;
				}
				// If error, stop process
				else if ($status == "ERROR") {
					$wait = false;
				}
				// If success, continue
				else if ($status == "SUCCESS") {
					break;
					$wait = false;
				}			

			}

			// Get label data and tracking number
			$label_url = $object->label_url;
			$tracking_number = $object->tracking_number;

			// Store label url and tracking number
			$package['label_url'] = $label_url;
			$package['tracking_number'] = $tracking_number;

			// Update local packages array
			$packages[$i] = $package;

		}	

		// Update session packages array
		$_SESSION['packages'] = $packages;
		
	}	

	public function create_order(){
		
		// Get order info
		$order = $_SESSION['order'];
		$shipping_address = $_SESSION['shipping_address'];
		$packages = $_SESSION['packages'];

		// Get data from each package
		$storage_array = array();
		$count = count($packages);
		for($i=0; $i<$count; $i++){

			// Set current package
			$package = $packages[$i];

			// Get package data
			$label_url = $package['label_url'];
			$tracking_number =$package['tracking_number'];
			$contents = $package['contents'];

			// Set package array to store in database
			$package_array = array(

				'label_url' => $label_url,
				'tracking_number' => $tracking_number,
				'contents' => $contents

			);

			// Add to package strings array
			$storage_array[] = $package_array;

		}

		// Serialize array of packages into single string for database
		$shipping_code = serialize($storage_array);

		// ----

		// Get Last Order ID Number and Set Order Status ID
		$order_id = $this->db->getLastId();
		$order_status_id = 1;

		// ----

		// Store Order in Database
		$sql = "INSERT INTO `" . DB_PREFIX . "order` SET order_id = '" . (int)$order_id; 
		$sql .= "', order_status_id = '" . (int)$order_status_id;
		$sql .= "', email = '" . $shipping_address['email'];
		$sql .= "', shipping_code = '" . $shipping_code;
		$sql .= "' ";

		$this->db->query($sql);

	}	

	public function contact_user(){
		
		// Set Email Address
		$email = $_SESSION['shipping_address']['email'];

		// Email Subject
		$subject = sprintf('Your Order Confirmation at', $this->config->get('config_name'));

		// Email Message
		$message = sprintf('Welcome', $this->config->get('config_name')) . "\n\n";		

		// Send Email to Account User
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter'); //'-f' . $this->config->get('config_email');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($email);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();

	}			

}	

?>