<?php
class Log {
	private $filename;
	
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
		fclose($handle); 
	}
}
?>