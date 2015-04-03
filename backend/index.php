<?php
require('Slim/Slim.php');
\Slim\Slim::registerAutoloader();
// require('autoload.php');
include ('utils/apiFunctions.php');
error_reporting(E_ERROR | E_WARNING);
error_reporting(E_ALL);
session_write_close();

// global $responseTemplate; 
$responseTemplate = array(
	'status' => 0,
	'error_code' => 0,
	'description' => 'Success',
	'data' => array()
);

$appOptions = array();

$app = new \Slim\Slim($appOptions);


\Slim\Route::setDefaultConditions(array(
    'email' => '[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,4})',
	'service' => '\w\w+',
	'ip' => '\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}(:\d{1,5})?'
));


$app->get('/hello','hello'); 


$app->run();
?>
