<?php
class Account {
	private $config;
	private $db;

	public function __construct($registry) {

		// Load objects from registry
		$this->config = $registry->get('config');
		$this->customer = $registry->get('customer');
		$this->session = $registry->get('session');
		$this->db = $registry->get('db');
		$this->request = $registry->get('request');
		$this->load = $registry->get('load');
		$this->url = $registry->get('url');

	} 

	// Change Account Info
	public function change_account_info() {

		// Get account info
		$first_name = $this->request->post['firstname'];
		$last_name = $this->request->post['lastname'];
		$phone = $this->request->post['phone'];
		$email = $this->customer->getEmail();		

		// Make array
		$data = array(
			'firstname' => $first_name,
			'lastname' => $last_name,
			'phone' => $phone,
			'email' => $email
		);

		// Update database
		$this->customer->editCustomer($data);

		// Success message
		$valid = true;
		$message = 'Account info has been updated!';			

		// Send data back to browser
		$update = array(

			"valid" => $valid,
			"message" => $message

		);

		echo json_encode($update);		

	}

	// Change Password
	public function change_password() {

		// Get password, confirm, email
		$password = $this->request->post['password'];
		$confirm = $this->request->post['confirm'];
		$email = $this->customer->getEmail();

		// Default to valid password
		$valid = true;		

		// Check password length
		if (utf8_strlen($password) < 4 || utf8_strlen($password) > 20) {
			$valid = false;
			$message = 'Password must be between 4 and 20 characters!';
		}

		// Check passwords match
		if ($password != $confirm) {
			$valid = false;
			$message = 'Passwords must match!';
		}

		// Update password if no errors
		if ($valid == true) {	

			$this->customer->editPassword($email, $password);

			// Success message
			$message = 'Password has been successfully updated!';			

		}

		// Send data back to browser
		$update = array(

			"valid" => $valid,
			"message" => $message

		);

		echo json_encode($update);		

	}

	// Add Address
	public function add_address() {

	}

	// Delete Address
	public function delete_address() {

		// Get address id
		$address_id = $this->request->post['address_id'];

		// Delete address
		$this->db->query("DELETE FROM " . DB_PREFIX . "address WHERE address_id = '" . (int)$address_id . "' AND customer_id = '" . (int)$this->customer->getId() . "'");

		// Set messages
		$valid = true;
		$message = 'Address removed!';

		// Send data back to browser
		$update = array(

			"valid" => $valid,
			"message" => $message

		);

		echo json_encode($update);			

	}

	// Order History
	public function order_history() {

	}

	// Address Book
	public function address_book() {
		
	}	

	public function add_user($data) {

		//$this->db->query("DELETE FROM " . DB_PREFIX . "customer WHERE email = '" . $this->db->escape($data['email']) . "'");

		//return;

		// Set email verification state 
		$verification = 0;

		// Set verifier code
		$bytes = openssl_random_pseudo_bytes(20);
		$verifier = bin2hex($bytes); 

		// Get next user id
		$customer_id = $this->db->getLastId();

		// Add email and password into database
		$this->db->query("INSERT INTO " . DB_PREFIX . "customer SET customer_id = '" . (int)$customer_id . "', store_id = '" . (int)$this->config->get('config_store_id') . "', email = '" . $this->db->escape($data['email']) . "', salt = '" . $this->db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $this->db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', customer_group_id = '" . (int)1 . "', ip = '" . $this->db->escape($this->request->server['REMOTE_ADDR']) . "', status = '1', approved = '" . (int)0 . "', token = '" . $verifier . "', date_added = NOW()");


		//$this->db->query("INSERT INTO " . DB_PREFIX . "address SET customer_id = '" . (int)$customer_id . "', firstname = '" . $this->db->escape($data['firstname']) . "', lastname = '" . $this->db->escape($data['lastname']) . "', company = '" . $this->db->escape($data['company']) . "', company_id = '" . $this->db->escape($data['company_id']) . "', tax_id = '" . $this->db->escape($data['tax_id']) . "', address_1 = '" . $this->db->escape($data['address_1']) . "', address_2 = '" . $this->db->escape($data['address_2']) . "', city = '" . $this->db->escape($data['city']) . "', postcode = '" . $this->db->escape($data['postcode']) . "', country_id = '" . (int)$data['country_id'] . "', zone_id = '" . (int)$data['zone_id'] . "'");
		
		// Get next user id and address id
		//
		//$address_id = $this->db->getLastId();
		//$this->db->query("UPDATE " . DB_PREFIX . "customer SET address_id = '" . (int)$address_id . "' WHERE customer_id = '" . (int)$customer_id . "'");



		// Email Subject
		$subject = sprintf('Your Account Confirmation at', $this->config->get('config_name'));

		// Email Message
		$message = sprintf('Welcome', $this->config->get('config_name')) . "\n\n";		
		$message .= $this->url->link('account/login' . '&token=' . $verifier, '', 'SSL') . "\n\n";
		$message .= 'asd' . "\n\n";		
		$message .= 'asd' . "\n";
		$message .= $this->config->get('config_name');

		// Send Email to Account User
		$mail = new Mail();
		$mail->protocol = $this->config->get('config_mail_protocol');
		$mail->parameter = $this->config->get('config_mail_parameter');
		$mail->hostname = $this->config->get('config_smtp_host');
		$mail->username = $this->config->get('config_smtp_username');
		$mail->password = $this->config->get('config_smtp_password');
		$mail->port = $this->config->get('config_smtp_port');
		$mail->timeout = $this->config->get('config_smtp_timeout');				
		$mail->setTo($data['email']);
		$mail->setFrom($this->config->get('config_email'));
		$mail->setSender($this->config->get('config_name'));
		$mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
		$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
		$mail->send();
	}

	public function verify_email($token) {

		// Find account with matching token
		$query_call = "SELECT email, approved FROM " . DB_PREFIX . "customer WHERE token = '" . $token . "'";
		$query = $this->db->query($query_call);

		// If account with token exists, approve account
		if ($query->num_rows) {

			// Set approved to TRUE where token is matched.
			$this->db->query("UPDATE " . DB_PREFIX . "customer SET approved = '" . 1 . "' WHERE token = '" . $token . "'");

			return true;

		}
		else {
			return false;
		}
	}
}	

?>