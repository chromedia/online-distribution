<?php
final class Registry {

	// Array to store data
	private $data = array();

	// Retrieve data from array
	public function get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : null);
	}

	// Store data in array
	public function set($key, $value) {
		$this->data[$key] = $value;
	}

	// Does it exist in array
	public function has($key) {
		return isset($this->data[$key]);
	}
}
?>