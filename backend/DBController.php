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


//Si la acción es permitida o no
	if(!in_array($ACTION, $allowedActions)){
		$continue = False;
		$response['status'] = 1;
		$response['error_code'] = 2;
		$response['description'] = 'Invalid action';
	}

}

if($continue){
	$db = DBAccess::getInstance();

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
				'email',
				'name',
				'surname',
				'password',
				'phone',
				'hostelero',
				'company-name',
				'nif'
			);
			
			
			if(!isset($REQUEST['email']) || !isset($REQUEST['name']) || !isset($REQUEST['surname']) || !isset($REQUEST['password']) || 
				!isset($REQUEST['phone']) || !isset($REQUEST['hostelero'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 3;
				$response['description'] = 'Invalid params(2): Missing email, name, surname, password, phone, or hostelero';
			}

			if($continue) if((($REQUEST['hostelero']==1)&&( !isset($REQUEST['company-name']) || !isset($REQUEST['nif'])))){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 3;
				$response['description'] = 'Invalid params(3): Missing company-name or nif';
			}

			if($continue){
				
				$filters['email'] = $REQUEST['email'];
				$filters['name'] = $REQUEST['name'];
				$filters['surname'] = $REQUEST['surname'];
				$filters['password'] = $REQUEST['password'];
				$filters['phone'] = $REQUEST['phone'];
				$filters['hostelero'] = $REQUEST['hostelero'];
				if(isset($REQUEST['company-name']))
				$filters['company-name'] = $REQUEST['company-name'];
				if(isset($REQUEST['nif']))
				$filters['nif'] = $REQUEST['nif'];
			
				$response['data']= $db->registerUser($filters);
				if($response!=1){
					$response['status'] = 1;
					$response['error_code'] = 4;
					$response['description'] = 'Fail:Could not insert given user possibly because email already exists in database';	
				}
			}

		break;
	}
}

print json_encode($response);

?>