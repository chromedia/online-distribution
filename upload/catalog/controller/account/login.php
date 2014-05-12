<?php 
class ControllerAccountLogin extends Controller {
	private $error = array();

	public function index() {

		// Load data
		$this->load->model('account/customer');		
		$this->language->load('account/login');

		// Redirect if Logged In
		if ($this->customer->isLogged()) {  
			$this->redirect($this->url->link('account/account', '', 'SSL'));
		}

		// Log In Process
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
			if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
				$this->redirect(str_replace('&amp;', '&', $this->request->post['redirect']));
			} else {
				// Log in successful, redirect to account page
				$this->redirect($this->url->link('account/account', '', 'SSL')); 
			}
		}		

		// Email Verification Process
		if (isset($this->request->get['token'])) {

			// Set token
			$token = $this->request->get['token'];

			// Check and verify account
			$verify = $this->account->verify_email($token);

			// Message user
			if ($verify == TRUE) {
				$this->session->data['success'] = 'Success: Your email has been verified!';
			}
			else {
				$this->error['warning'] = 'There was an error verifying your email.';
			}

		}

		// Set Text
		$this->document->setTitle($this->language->get('heading_title'));	

		// Set warning
		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		// Set links
		$this->data['action'] = $this->url->link('account/login', '', 'SSL');
		$this->data['forgotten'] = $this->url->link('account/forgotten', '', 'SSL');

		// Added strpos check to pass McAfee PCI compliance test (http://forum.opencart.com/viewtopic.php?f=10&t=12043&p=151494#p151295)
		if (isset($this->request->post['redirect']) && (strpos($this->request->post['redirect'], $this->config->get('config_url')) !== false || strpos($this->request->post['redirect'], $this->config->get('config_ssl')) !== false)) {
			$this->data['redirect'] = $this->request->post['redirect'];
		} elseif (isset($this->session->data['redirect'])) {
			$this->data['redirect'] = $this->session->data['redirect'];

			unset($this->session->data['redirect']);		  	
		} else {
			$this->data['redirect'] = '';
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->request->post['email'])) {
			$this->data['email'] = $this->request->post['email'];
		} else {
			$this->data['email'] = '';
		}

		if (isset($this->request->post['password'])) {
			$this->data['password'] = $this->request->post['password'];
		} else {
			$this->data['password'] = '';
		}

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/account/login.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/account/login.tpl';
		} else {
			$this->template = 'default/template/account/login.tpl';
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

	protected function validate() {

		// Check login id and password
		if (!$this->customer->login($this->request->post['email'], $this->request->post['password'])) {
			$this->error['warning'] = 'No match found for email address and password.';
		}

		// Check user info and email confirmation status
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['approved']) {
			$this->error['warning'] = 'Your account must be confirmed in order to log in.';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>