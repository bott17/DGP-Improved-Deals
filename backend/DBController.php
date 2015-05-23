<?php

error_reporting(E_WARNING | E_ERROR | E_PARSE);
error_reporting(E_ALL);
include ('utils/utils.php');
include ('DB/DBAccess.php');

$allowedActions = array(
	'helloworld',
	'registeruser'
);

$response = array('status' => 0, 'error_code' => 0, 'description' => 'Success', 'data' => array());
$continue = True;
$REQUEST = $_GET;
$ACTION = '';
//Si el parametro action está o no
if(!isset($REQUEST['action'])){
	$continue = False;
	$response['status'] = 1;
	$response['error_code'] = 1;
	$response['description'] = 'Invalid parameter(1): action is not set';		
}

if($continue){
	
	$ACTION = $REQUEST['action'];
}

//Si la acción es permitida o no
if(!in_array($ACTION, $allowedActions)){
		$continue = False;
		$response['status'] = 1;
		$response['error_code'] = 2;
		$response['description'] = 'Invalid action';
}

$db = DBAccess::getInstance();

if($continue){
	
	switch(strtolower($ACTION)){

		case 'helloworld':
			
			$params = array(
				'hello'
			);
			
			if(!in_array("hello", $REQUEST)){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 3;
				$response['description'] = 'Invalid parameters';
			}

			if($continue){
				//TODO A EXAMPLE FUNCTION HELLO WORLD IN DBACCESS 

			}

		break;
		
		case 'registeruser':
			
			$params = array(
				'user',
				'pass'
			);
			
			if(!isset($REQUEST['user']) || !isset($REQUEST['pass'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 3;
				$response['description'] = 'Invalid params(2): Invalid User or password';
			}

			if($continue){
				
				$filters['user'] = $REQUEST['user'];
				$filters['pass'] = $REQUEST['pass'];
			
				$response = $db->registerUser($filters);
			}

		break;
	}
}

print json_encode($response);

?>