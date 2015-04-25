<?php

error_reporting(E_WARNING | E_ERROR | E_PARSE);
error_reporting(E_ALL);
include ('utils/utils.php');

/**
 * Función que hace la request
 * @param $url: es la url a la que se hace la petición
 * @param $response: variable con la estructura de la respuesta.
 */
function Request($url, &$response){
	$remoteResponse = Utils::MakeRequest($url);
	
	$continue = True;
	//print_r($url);
	if(empty($remoteResponse)){
		$continue = False;
		$response['status'] = 1;
		$response['error_code'] = 4;
		$response['description'] = 'Remote request failed';
	}
	
	utf8_encode($remoteResponse);

	if($continue){
		$jsonResponse = json_decode($remoteResponse, True);

		if(empty($jsonResponse)){
			$continue = False;
			$response['status'] = 1;
			$response['error_code'] = 5;
			$response['description'] = 'Remote request decode failed';
		}
	}
	
	if($continue){
		$response['data'] = $jsonResponse['data'];
	}

	return $response;
}

$allowedActions = array(
	'helloworld'
);

$response = array('status' => 0, 'error_code' => 0, 'description' => 'Success', 'data' => array());
$continue = True;
$REQUEST = $_POST;
$ACTION = '';
//Si la accion está o no
if(isset($REQUEST['action']))
{
	$ACTION = $REQUEST['action'];		
}

//Si la acción es permitida o no
if(!in_array($ACTION, $allowedActions)){
		$continue = False;
		$response['status'] = 1;
		$response['error_code'] = 2;
		$response['description'] = 'Invalid action';
}

if($continue){
	
	switch(strtolower($ACTION)){

		case 'helloworld':

			if(!in_array("hello", $REQUEST)){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 3;
				$response['description'] = 'Invalid parameters';
			}

			if($continue){
				$url = sprintf('http://localhost/DGP-Improved-Deals/backend/hello'); 

				$response = Request($url, $response);
			}

		break;
	}
}

print json_encode($response);

?>