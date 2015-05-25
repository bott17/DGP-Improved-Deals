<?php

error_reporting(E_WARNING | E_ERROR | E_PARSE);
//error_reporting(E_ALL);
include ('utils/utils.php');
include ('DB/DBAccess.php');

$allowedActions = array(
	'helloworld',
	'registeruser',
	'search',
	'rentroom',
	'login',
	'listsimilar',
	'listpropuser',
	'lastproperties',
	'lastcomments'
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
//print json_encode($response);exit;
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
				
				$filters['dateini'] = DateTime::createFromFormat('D M d Y H:i:s eO', base64_decode($REQUEST['dateini']))->format('Y-m-d');
				$filters['dateend'] = DateTime::createFromFormat('D M d Y H:i:s eO', base64_decode($REQUEST['dateend']))->format('Y-m-d');

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
					
			
			if(!isset($REQUEST['dateini']) || !isset($REQUEST['dateend']) || !isset($REQUEST['idproperty']) || !isset($REQUEST['email'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 7;
				$response['description'] = 'Invalid params(5): Missing dateini, dateend, idproperty or email';
			}



			if($continue){
				
				$filters['dateini'] = DateTime::createFromFormat('D M d Y H:i:s eO', base64_decode($REQUEST['dateini']))->format('Y-m-d');
				$filters['dateend'] = DateTime::createFromFormat('D M d Y H:i:s eO', base64_decode($REQUEST['dateend']))->format('Y-m-d');
				$filters['idproperty'] = $REQUEST['idproperty'];
				$filters['email'] = $REQUEST['email'];


				$response['data']= $db->rentRoom($filters);

				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 8;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 9;
					$response['description'] = 'Fail:Could not insert given rent possibly because it already exists in database';	
				}
			}

		break;
		case 'login':		
			
			if(!isset($REQUEST['password']) || !isset($REQUEST['email'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 10;
				$response['description'] = 'Invalid params(6): Missing email or password';
			}



			if($continue){
				
				$filters['password'] = $REQUEST['password'];
				$filters['email'] = $REQUEST['email'];


				$response['data']= $db->logIn($filters);
				//print_r($response['data']);
				//exit;
				//print_r($response);exit;
				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 11;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 12;
					$response['description'] = 'Fail:Could not retrieve user possibly due to a connection problem with DataBase';	
				}
				if($response['data']==-2){
					$response['status'] = 1;
					$response['error_code'] = 13;
					$response['description'] = 'Fail:Wrong user email or password';	
				}
			}

		break;
		case 'listsimilar':		
			
			if(!isset($REQUEST['zone']) || !isset($REQUEST['idproperty'])){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 14;
				$response['description'] = 'Invalid params(6): Missing zone or idproperty';
			}



			if($continue){
				
				$filters['zone'] = $REQUEST['zone'];
				$filters['idproperty'] = $REQUEST['idproperty'];


				$response['data']= $db->listSimilar($filters);
				//print_r($response['data']);
				//exit;
				//print_r($response);exit;
				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 15;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 16;
					$response['description'] = 'Fail:Could not retrieve user possibly due to a connection problem with DataBase';	
				}
				if($response['data']==-2){
					$response['status'] = 1;
					$response['error_code'] = 17;
					$response['description'] = 'Warning: no properties found';	
				}
			}

		break;
		case 'listpropuser':		
			
			if(!isset($REQUEST['email']) ){
				$continue = False;
				$response['status'] = 1;
				$response['error_code'] = 18;
				$response['description'] = 'Invalid params(6): Missing user email';
			}



			if($continue){
				
				$filters['email'] = $REQUEST['email'];

				$response['data']= $db->listPropUser($filters);
				//print_r($response['data']);
				//exit;
				//print_r($response);exit;
				if($response['data']==-1){
					$response['status'] = 1;
					$response['error_code'] = 19;
					$response['description'] = 'Fail: Could not retrieve information possibly due to an undetected error in parameters';	
				}
				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 20;
					$response['description'] = 'Fail:Could not retrieve user possibly due to a connection problem with DataBase';	
				}
				if($response['data']==-2){
					$response['status'] = 1;
					$response['error_code'] = 21;
					$response['description'] = 'Warning: no properties found';	
				}
			}

		break;
		case 'lastproperties':		
			
			if($continue){
				$response['data']= $db->lastProperties();
				//print_r($response['data']);
				//exit;
				//print_r($response);exit;

				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 22;
					$response['description'] = 'Fail:Could not retrieve user possibly due to a connection problem with DataBase';	
				}
				if($response['data']==-2){
					$response['status'] = 1;
					$response['error_code'] = 23;
					$response['description'] = 'Warning: no properties found';	
				}
			}

		break;
		case 'lastcomments':		
			
			if($continue){
				$response['data']= $db->lastComments();
				//print_r($response['data']);
				//exit;
				//print_r($response);exit;

				if($response['data']==0){
					$response['status'] = 1;
					$response['error_code'] = 24;
					$response['description'] = 'Fail:Could not retrieve user possibly due to a connection problem with DataBase';	
				}
				if($response['data']==-2){
					$response['status'] = 1;
					$response['error_code'] = 25;
					$response['description'] = 'Warning: no comments found';	
				}
			}

		break;
		
	}
}

print json_encode($response);
//print_r($response);


?>