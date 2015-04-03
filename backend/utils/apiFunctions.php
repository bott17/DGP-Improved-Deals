<?php

include ('DB/DBAccess.php');

$responseTemplate = array(
	'status' => 0,
	'error_code' => 0,
	'description' => 'Success',
	'data' => array()
);

function hello(){
	global $responseTemplate; 
	$db = DBAccess::getInstance();


	$response = $responseTemplate;
	$response['data'] = $db->test();
	print json_encode($response);
}


?>
