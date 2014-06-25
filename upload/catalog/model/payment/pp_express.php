<?php
class ModelPaymentPPExpress extends Model {
	public function cleanReturn($data) {
		$data = explode('&', $data);

		$arr = array();

		foreach($data as $k=>$v) {
			$tmp = explode('=', $v);
			$arr[$tmp[0]] = urldecode($tmp[1]);
		}

		return $arr;
	}

	public function call($data) {
		if (PAYPAL_ENVIRONMENT == 'sandbox') {
			$api_endpoint = 'https://api-3t.sandbox.paypal.com/nvp';
		} else {
			$api_endpoint = 'https://api-3t.paypal.com/nvp';
		}

		/*$settings = array(
			'USER' => $this->config->get('pp_express_username'),
			'PWD' => $this->config->get('pp_express_password'),
			'SIGNATURE' => $this->config->get('pp_express_signature'),
			'VERSION' => '65.2',
			'BUTTONSOURCE' => 'OpenCart_Cart_EC',
		);*/

		$settings = array(
			'USER' => 'opentech_api1.gmail.com',
			'PWD' => '1403599206',
			'SIGNATURE' => 'AErfVUrwyqdtjXcFeRKYILFoEEUIAHWESlLn6n0UdZ0l2AVUO9jAVt-z',
			'VERSION' => '65.2',
			//'BUTTONSOURCE' => 'OpenCart_Cart_EC',
		);

		//$settings = array();

		$this->log($data, 'Call data');

		$defaults = array(
			CURLOPT_POST => 1,
			CURLOPT_HEADER => 0,
			CURLOPT_URL => $api_endpoint,
			CURLOPT_USERAGENT => "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.1) Gecko/20061204 Firefox/2.0.0.1",
			CURLOPT_FRESH_CONNECT => 1,
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_FORBID_REUSE => 1,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_POSTFIELDS => http_build_query(array_merge($data, $settings), '', "&")
		);

		$ch = curl_init();

		curl_setopt_array($ch, $defaults);
		$result = curl_exec($ch);


		if( !$result ) {
			$this->log(array('error' => curl_error($ch), 'errno' => curl_errno($ch)), 'cURL failed');
		}

		$this->log($result, 'Result');

		curl_close($ch);

		return $this->cleanReturn($result);
	}

	public function createToken($len = 32) {
		$base='ABCDEFGHKLMNOPQRSTWXYZabcdefghjkmnpqrstwxyz123456789';
		$max=strlen($base)-1;
		$activatecode='';
		mt_srand((double)microtime()*1000000);
		while (strlen($activatecode)<$len+1)
			$activatecode.=$base{mt_rand(0,$max)};

		return $activatecode;
	}

	public function log($data, $title = null) {
		if($this->config->get('pp_express_debug')) {
			$this->log->write('PayPal Express debug ('.$title.'): '.json_encode($data));
		}
	}

	public function getMethod($address, $total) {

		$this->load->language('payment/pp_express');

		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "zone_to_geo_zone` WHERE `geo_zone_id` = '" . (int)$this->config->get('pp_express_geo_zone_id') . "' AND `country_id` = '" . (int)$address['country_id'] . "' AND (`zone_id` = '" . (int)$address['zone_id'] . "' OR `zone_id` = '0')");

		if ($this->config->get('pp_express_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('pp_express_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code'       => 'pp_express',
				'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('pp_express_sort_order')
			);
		}

		return $method_data;
	}

	public function addOrder($order_data) {
		/**
		 * 1 to 1 relationship with order table (extends order info)
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order` SET
			`order_id` = '".(int)$order_data['order_id']."',
			`created` = NOW(),
			`modified` = NOW(),
			`capture_status` = '".$this->db->escape($order_data['capture_status'])."',
			`currency_code` = '".$this->db->escape($order_data['currency_code'])."',
			`total` = '".(double)$order_data['total']."',
			`authorization_id` = '".$this->db->escape($order_data['authorization_id'])."'");

		return $this->db->getLastId();
	}

	public function addTransaction($transaction_data) {
		/**
		 * 1 to many relationship with paypal order table, many transactions per 1 order
		 */

		$this->db->query("INSERT INTO `" . DB_PREFIX . "paypal_order_transaction` SET
			`paypal_order_id` = '".(int)$transaction_data['paypal_order_id']."',
			`transaction_id` = '".$this->db->escape($transaction_data['transaction_id'])."',
			`parent_transaction_id` = '".$this->db->escape($transaction_data['parent_transaction_id'])."',
			`created` = NOW(),
			`note` = '".$this->db->escape($transaction_data['note'])."',
			`msgsubid` = '".$this->db->escape($transaction_data['msgsubid'])."',
			`receipt_id` = '".$this->db->escape($transaction_data['receipt_id'])."',
			`payment_type` = '".$this->db->escape($transaction_data['payment_type'])."',
			`payment_status` = '".$this->db->escape($transaction_data['payment_status'])."',
			`pending_reason` = '".$this->db->escape($transaction_data['pending_reason'])."',
			`transaction_entity` = '".$this->db->escape($transaction_data['transaction_entity'])."',
			`amount` = '".(double)$transaction_data['amount']."',
			`debug_data` = '".$this->db->escape($transaction_data['debug_data'])."'");
	}

	public function paymentRequestInfo() {

		$data['PAYMENTREQUEST_0_SHIPPINGAMT'] = '';//$_SESSION['shipping']['amount'];//'';
		$data['PAYMENTREQUEST_0_CURRENCYCODE'] = $this->currency->getCode();
		$data['PAYMENTREQUEST_0_PAYMENTACTION'] = $this->config->get('pp_express_method');

		$i = 0;
		$item_total = 0;
		$products = $this->cart->getProducts();

		foreach ($products as $item) {
			$data['L_PAYMENTREQUEST_0_DESC' . $i] = '';
			// $data['L_PAYMENTREQUEST_0_DESC' . $i] = substr($data['L_PAYMENTREQUEST_0_DESC' . $i], 0, 126);

			$item_price = $this->tax->calculate($item['price'], $item['tax_class_id'], $this->config->get('config_tax'));

			$data['L_PAYMENTREQUEST_0_NAME' . $i] = $item['name'];
			$data['L_PAYMENTREQUEST_0_AMT' . $i] = $item_price;
			$item_total += number_format($item_price * $item['quantity'], 2);
			$data['L_PAYMENTREQUEST_0_QTY' . $i] = $item['quantity'];

			$data['L_PAYMENTREQUEST_0_ITEMURL' . $i] = $this->url->link('product/product', 'product_id=' . $item['product_id']);

			if ($this->config->get('config_cart_weight')) {
				$weight = $this->weight->convert($item['weight'], $item['weight_class_id'], $this->config->get('config_weight_class_id'));
				$data['L_PAYMENTREQUEST_0_ITEMWEIGHTVALUE' . $i] = number_format($weight / $item['quantity'], 2);
				$data['L_PAYMENTREQUEST_0_ITEMWEIGHTUNIT' . $i] = $this->weight->getUnit($this->config->get('config_weight_class_id'));
			}

			if ($item['length'] > 0 || $item['width'] > 0 || $item['height'] > 0) {
				$unit = $this->length->getUnit($item['length_class_id']);
				$data['L_PAYMENTREQUEST_0_ITEMLENGTHVALUE' . $i] = $item['length'];
				$data['L_PAYMENTREQUEST_0_ITEMLENGTHUNIT' . $i] = $unit;
				$data['L_PAYMENTREQUEST_0_ITEMWIDTHVALUE' . $i] = $item['width'];
				$data['L_PAYMENTREQUEST_0_ITEMWIDTHUNIT' . $i] = $unit;
				$data['L_PAYMENTREQUEST_0_ITEMHEIGHTVALUE' . $i] = $item['height'];
				$data['L_PAYMENTREQUEST_0_ITEMHEIGHTUNIT' . $i] = $unit;
			}

			$i++;
		}

		$data['L_PAYMENTREQUEST_0_DESC' . $i] = 'Shipping Cost';
        $data['L_PAYMENTREQUEST_0_NAME' . $i] = $_SESSION['shipping']['service_name'];
        $data['L_PAYMENTREQUEST_0_AMT' . $i]  = number_format($_SESSION['shipping']['amount'], 2, '.', '');
        $data['L_PAYMENTREQUEST_0_QTY' . $i]  = 1;
        
        $item_total += $_SESSION['shipping']['amount'];

		$data['PAYMENTREQUEST_0_ITEMAMT'] = number_format($item_total, 2, '.', '');
		$data['PAYMENTREQUEST_0_AMT'] = number_format($item_total, 2, '.', '');

		// var_dump($data);exit;

		return $data;
	}

	public function isMobile() {
		/*
		 * This will check the user agent and "try" to match if it is a mobile device
		 */
		if (preg_match("/Mobile|Android|BlackBerry|iPhone|Windows Phone/", $this->request->server['HTTP_USER_AGENT'])) {
			return true;
		} else {
			return false;
		}
	}

	public function getTransactionRow($transaction_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "paypal_order_transaction` `pt` LEFT JOIN `" . DB_PREFIX . "paypal_order` `po` ON `pt`.`paypal_order_id` = `po`.`paypal_order_id`  WHERE `pt`.`transaction_id` = '" . $this->db->escape($transaction_id) . "' LIMIT 1");

		if($qry->num_rows > 0) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function totalCaptured($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `pending_reason` != 'authorization' AND `pending_reason` != 'paymentreview' AND (`payment_status` = 'Partially-Refunded' OR `payment_status` = 'Completed' OR `payment_status` = 'Pending') AND `transaction_entity` = 'payment'");

		return $qry->row['amount'];
	}

	public function totalRefundedOrder($paypal_order_id) {
		$qry = $this->db->query("SELECT SUM(`amount`) AS `amount` FROM `" . DB_PREFIX . "paypal_order_transaction` WHERE `paypal_order_id` = '" . (int)$paypal_order_id . "' AND `payment_status` = 'Refunded'");

		return $qry->row['amount'];
	}

	public function updateOrder($capture_status, $order_id) {
		$this->db->query("UPDATE `" . DB_PREFIX . "paypal_order` SET `modified` = now(), `capture_status` = '".$this->db->escape($capture_status)."' WHERE `order_id` = '".(int)$order_id."'");
	}

	public function recurringCancel($ref) {

		$data = array(
			'METHOD' => 'ManageRecurringPaymentsProfileStatus',
			'PROFILEID' => $ref,
			'ACTION' => 'Cancel'
		);

		return $this->call($data);
	}

	public function recurringPayments() {
		/*
		 * Used by the checkout to state the module
		 * supports recurring profiles.
		 */
		return true;
	}
}
?>
