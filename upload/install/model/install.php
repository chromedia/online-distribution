<?php
class ModelInstall extends Model {
	public function database($data) {
		$db = new DB($data['db_driver'], $data['db_host'], $data['db_user'], $data['db_password'], $data['db_name']);
				
		$file = DIR_APPLICATION . 'opencart.sql';
		
		if (!file_exists($file)) { 
			exit('Could not load sql file: ' . $file); 
		}
		
		$lines = file($file);
		
		if ($lines) {
			$sql = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;

					if (preg_match('/;\s*$/', $line)) {
						$sql = str_replace("DROP TABLE IF EXISTS `oc_", "DROP TABLE IF EXISTS `" . $data['db_prefix'], $sql);
						$sql = str_replace("CREATE TABLE `oc_", "CREATE TABLE `" . $data['db_prefix'], $sql);
						$sql = str_replace("INSERT INTO `oc_", "INSERT INTO `" . $data['db_prefix'], $sql);
						
						$db->query($sql);
	
						$sql = '';
					}
				}
			}
			
			$db->query("SET CHARACTER SET utf8");
	
			$db->query("SET @@session.sql_mode = 'MYSQL40'");
		
			$db->query("DELETE FROM `" . $data['db_prefix'] . "user` WHERE user_id = '1'");
		
			$db->query("INSERT INTO `" . $data['db_prefix'] . "user` SET user_id = '1', user_group_id = '1', username = '" . $db->escape($data['username']) . "', salt = '" . $db->escape($salt = substr(md5(uniqid(rand(), true)), 0, 9)) . "', password = '" . $db->escape(sha1($salt . sha1($salt . sha1($data['password'])))) . "', status = '1', email = '" . $db->escape($data['email']) . "', date_added = NOW()");

			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_email'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_email', value = '" . $db->escape($data['email']) . "'");
			
			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_url'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_url', value = '" . $db->escape(HTTP_OPENCART) . "'");
			
			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_encryption'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_encryption', value = '" . $db->escape(md5(mt_rand())) . "'");
			
			$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_template'");
			$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_template', value = '" . $db->escape('chromedia') . "'");
			
			$db->query("UPDATE `" . $data['db_prefix'] . "product` SET `viewed` = '0'");

			$this->customDatabase($db, $data);
		}		
	}

	/**
	 * Added loader for custom database
	 */
	public function customDatabase($db, $data) {		
		$file = DIR_APPLICATION . 'opentech.custom.sql';
		
		if (!file_exists($file)) { 
			exit('Could not load sql file: ' . $file); 
		}
		
		$lines = file($file);
		
		if ($lines) {
			$sql = '';

			foreach($lines as $line) {
				if ($line && (substr($line, 0, 2) != '--') && (substr($line, 0, 1) != '#')) {
					$sql .= $line;

					if (preg_match('/;\s*$/', $line)) {
						$db->query($sql);

						$sql = '';
					}
				}
			}
		}

		// Customizes product table
		$db->query("ALTER TABLE `" . $data['db_prefix'] . "product` ADD `is_featured` TINYINT NOT NULL DEFAULT '1'; ");
		$db->query("ALTER TABLE `" . $data['db_prefix'] . "product_description` ADD `details` TEXT NOT NULL DEFAULT ''; ");
		$db->query("ALTER TABLE `" . $data['db_prefix'] . "product_description` ADD `documentation` TEXT NOT NULL DEFAULT ''; ");

		// Alters shipping code type
		$db->query("ALTER TABLE `" . $data['db_prefix'] . "order` MODIFY `shipping_code` TEXT NOT NULL DEFAULT ''; ");

		// Sets setting to use seo by default
		$db->query("DELETE FROM `" . $data['db_prefix'] . "setting` WHERE `key` = 'config_seo_url'");
		$db->query("INSERT INTO `" . $data['db_prefix'] . "setting` SET `group` = 'config', `key` = 'config_seo_url', value = '" . $db->escape(1) . "'");

		$this->createVideoTable($db, $data['db_prefix']);
		$this->addShippingDefaultInformation($db, $data['db_prefix']);
		$this->addDefaultUrlAlias($db, $data['db_prefix']);
	}

	/**
	 * Creates video table
	 */	
	public function createVideoTable($db, $prefix)
	{
		$sql = "
			CREATE TABLE IF NOT EXISTS `".$prefix."product_video` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `product_id` int(11) NOT NULL,
			  `video_key` varchar(30) NOT NULL DEFAULT '',
			  `thumbnail_link` varchar(500) NOT NULL DEFAULT '',
			  `url_link` varchar(500) NOT NULL DEFAULT '',
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;
		";
		$db->query($sql);
	}

	/**
	 * Add shipping default information
	 */
	public function addShippingDefaultInformation($db, $prefix)
	{
		$group = 'config';
		$data = array(
			'shipper_name'     => 'Laura',
			'shipping_zone'    => '3624',
			'shipping_country' => '223',
			'shipping_city'    => 'San Francisco',
			'shipping_street'  => 'Clayton St.',
			'shipping_zip'     => '94117'  
		);

		foreach ($data as $key => $value) {
			if (!is_array($value)) {
				$db->query("INSERT INTO " . $prefix . "setting SET  `group` = '". $db->escape($group)  ."', `key` = '" . $db->escape($key) . "', `value` = '" . $db->escape($value) . "'");
			} else {
				$db->query("INSERT INTO " . $prefix . "setting SET `group` = '" . $db->escape($group) . "', `key` = '" . $db->escape($key) . "', `value` = '" . $db->escape(serialize($value)) . "', serialized = '1'");
			}
		}
	}

	/**
	 * Add page url alias
	 */
	public function addDefaultUrlAlias($db, $prefix)
	{
		$data = array(
			array('query' => 'information/learnmore', 'keyword' => 'about-us.html'),
			array('query' => 'collaborate/products', 'keyword' => 'active-projects.html'),
			array('query' => 'checkout/cart', 'keyword' => 'cart.html'),
			array('query' => 'checkout/success', 'keyword' => 'checkout-success.html')
		);

		foreach ($data as $entry) {
			$db->query("INSERT INTO " . $prefix . "url_alias SET `query` = '" . $db->escape($entry['query']) . "', `keyword` = '" . $db->escape($entry['keyword']) ."'");
		}
	}
}