<?php
class Utils {

	
    public static function makeRequest($url) {


	    //  Initiate curl
		$ch = curl_init();
		// Disable SSL verification
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		// Will return the response, if false it print the response
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		// Set the url
		curl_setopt($ch, CURLOPT_URL,$url);
		// Execute
		$response=curl_exec($ch);
		// Closing
		curl_close($ch);
		// Will dump a beauty json :

		return $response;

    }
}