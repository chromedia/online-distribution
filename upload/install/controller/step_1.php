<?php
class ControllerStep1 extends Controller {
	private $error = array();

	public function index() {
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->redirect($this->url->link('step_2'));
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';	
		}

		$this->data['action'] = $this->url->link('step_1');

		$this->data['config_catalog'] = DIR_ECOM . 'config.php';
		$this->data['config_admin'] = DIR_ECOM . 'admin/config.php';

		$this->data['cache'] = DIR_SYSTEM . 'cache';
		$this->data['logs'] = DIR_SYSTEM . 'logs';
		$this->data['image'] = DIR_ECOM . 'image';
		$this->data['image_cache'] = DIR_ECOM . 'image/cache';
		$this->data['image_data'] = DIR_ECOM . 'image/data';
		$this->data['download'] = DIR_ECOM . 'download';

		$this->data['back'] = $this->url->link('step_1');

		$this->template = 'step_1.tpl';
		$this->children = array(
			'header',
			'footer'
		);		

		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (phpversion() < '5.0') {
			$this->error['warning'] = 'Warning: You need to use PHP5 or above!';
		}

		if (!ini_get('file_uploads')) {
			$this->error['warning'] = 'Warning: file_uploads needs to be enabled!';
		}

		if (ini_get('session.auto_start')) {
			$this->error['warning'] = 'Warning: session.auto_start should not be enabled!';
		}

		if (!extension_loaded('mysql')) {
			$this->error['warning'] = 'Warning: MySQL extension needs to be loaded!';
		}

		if (!extension_loaded('gd')) {
			$this->error['warning'] = 'Warning: GD extension needs to be loaded!';
		}

		if (!extension_loaded('curl')) {
			$this->error['warning'] = 'Warning: CURL extension needs to be loaded!';
		}

		if (!function_exists('mcrypt_encrypt')) {
			$this->error['warning'] = 'Warning: mCrypt extension needs to be loaded!';
		}

		if (!extension_loaded('zlib')) {
			$this->error['warning'] = 'Warning: ZLIB extension needs to be loaded!';
		}

		if (!file_exists(DIR_ECOM . 'config.php')) {
			$this->error['warning'] = 'Warning: config.php does not exist. You need to rename config-dist.php to config.php!';
		} elseif (!is_writable(DIR_ECOM . 'config.php')) {
			$this->error['warning'] = 'Warning: config.php needs to be writable!';
		}

		if (!file_exists(DIR_ECOM . 'admin/config.php')) {
			$this->error['warning'] = 'Warning: admin/config.php does not exist. You need to rename admin/config-dist.php to admin/config.php!';
		} elseif (!is_writable(DIR_ECOM . 'admin/config.php')) {
			$this->error['warning'] = 'Warning: admin/config.php!';
		}

		if (!is_writable(DIR_SYSTEM . 'cache')) {
			$this->error['warning'] = 'Warning: Cache directory needs to be writable!';
		}

		if (!is_writable(DIR_SYSTEM . 'logs')) {
			$this->error['warning'] = 'Warning: Logs directory needs to be writable!';
		}

		if (!is_writable(DIR_ECOM . 'image')) {
			$this->error['warning'] = 'Warning: Image directory needs to be writable!';
		}

		if (!is_writable(DIR_ECOM . 'image/cache')) {
			$this->error['warning'] = 'Warning: Image cache directory needs to be writable!';
		}

		if (!is_writable(DIR_ECOM . 'image/data')) {
			$this->error['warning'] = 'Warning: Image data directory needs to be writable!';
		}

		if (!is_writable(DIR_ECOM . 'download')) {
			$this->error['warning'] = 'Warning: Download directory needs to be writable!';
		}

		if (!$this->error) {
			return true;
		} else {
			return false;
		}
	}
}
?>