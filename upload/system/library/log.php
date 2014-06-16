<?php
class Log {
	private $filename;
	
<<<<<<< HEAD
	public function __construct($filename) {
		$this->filename = $filename;
	}
	
	public function write($message) {
		$file = DIR_LOGS . $this->filename;
		
		$handle = fopen($file, 'a+'); 
		
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true)  . "\n");
			
=======
	public function __construct() {
		$this->filename = 'error.txt';
	}
	
	public function write($message) {

		// Set Path to Error Log File
		$file = DIR_LOGS . $this->filename;
		
		// Set File Access to Write
		$handle = fopen($file, 'a+'); 

		// Add Error Message
		fwrite($handle, date('Y-m-d G:i:s') . ' - ' . $message . "\n");
			
		// Close File	
>>>>>>> 2cb8057bb2071b3c899fc2a459154aa01515936b
		fclose($handle); 
	}
}
?>