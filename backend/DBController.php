<?php

error_reporting(E_WARNING | E_ERROR | E_PARSE);
error_reporting(E_ALL);
include ('utils/utils.php');
include ('DB/DBAccess.php');

$allowedActions = array(
	'helloworld',
	'registeruser',
	'search',
	'rentroom'
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
				if($response['data']!=1){
					$response['status'] = 1;
					$response['error_code'] = 4;
					$response['description'] = 'Fail:Could not insert given user possibly because email already exists in database';	
				}
			}

		break;		
		case 'search':
					
			
			if(!isset($REQUEST['dateini']) || !isset($REQUEST['dateend'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 5;
				$response['description'] = 'Invalid params(4): Missing dateini or dateend';
			}



			if($continue){
				
				$filters['dateini'] = $REQUEST['dateini'];
				$filters['dateend'] = $REQUEST['dateend'];
				if(isset($REQUEST['zone']))$filters['zone'] = $REQUEST['zone'];
				if(isset($REQUEST['rooms']))$filters['rooms'] = $REQUEST['rooms'];
				if(isset($REQUEST['type']))$filters['type'] = $REQUEST['type'];
				if(isset($REQUEST['pension']))$filters['pension'] = $REQUEST['pension'];
				if(isset($REQUEST['garage']))$filters['garage'] = $REQUEST['garage'];
				if(isset($REQUEST['security']))$filters['security'] = $REQUEST['security'];
				if(isset($REQUEST['airconditioner']))$filters['airconditioner'] = $REQUEST['airconditioner'];
				if(isset($REQUEST['balcony']))$filters['balcony'] = $REQUEST['balcony'];
				if(isset($REQUEST['swimmingpool']))$filters['swimmingpool'] = $REQUEST['swimmingpool'];
				if(isset($REQUEST['internet']))$filters['internet'] = $REQUEST['internet'];
				if(isset($REQUEST['heating']))$filters['heating'] = $REQUEST['heating'];
				if(isset($REQUEST['tv']))$filters['tv'] = $REQUEST['tv'];
				if(isset($REQUEST['garden']))$filters['garden'] = $REQUEST['garden'];
				if(isset($REQUEST['phone']))$filters['phone'] = $REQUEST['phone'];

				$response['data']= $db->search($filters);

				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 6;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
			}

		break;		
		case 'rentroom':
					
			
			if(!isset($REQUEST['dateini']) || !isset($REQUEST['dateend'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 5;
				$response['description'] = 'Invalid params(4): Missing dateini or dateend';
			}



			if($continue){
				
				$filters['dateini'] = $REQUEST['dateini'];
				$filters['dateend'] = $REQUEST['dateend'];
				$filters['idproperty'] = $REQUEST['idproperty'];
				$filters['email'] = $REQUEST['email'];


				$response['data']= $db->rentRoom($filters);
				print_r($response['data']);
				exit;
				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 6;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
			}

		break;
	}
}

print json_encode($response);

?>