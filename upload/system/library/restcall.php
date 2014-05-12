<?php
class Restcall {

	public function call($url, $method, $credentials, $data) {

	    // Clean url
		$url = (string) trim(strip_tags($url));
		$url = str_replace('&amp;', '&', $url);	   		 

		// Initialize curl
	    $curl = curl_init($url);

	    // Prepare call based on method
	    switch ($method)
	    {
	    	case "GET":
	    		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'GET');
	    		break;
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            if ($data)
	                $url = sprintf("%s?%s", $url, http_build_query($data));
	    }

	    // Optional Authentication:
	    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	    curl_setopt($curl, CURLOPT_USERPWD, $credentials);

	    // Set Options
	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);	    

	    // Run curl session
	    $output = curl_exec($curl);

	    // Close curl session
	    @curl_close($curl);

	    // Return response as JSON string
	    return $output;
	}
}
?>