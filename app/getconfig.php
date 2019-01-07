<?php
require_once 'autoload.php';
header('Access-Control-Allow-Origin: *');
header("Content-type: text/json");

$token 	= $_POST['token'];

if($devices->tokenValid($token)){
	$device_id = $devices->deviceAuthentication($token);
	
	if(!empty($device_id)){
		$devices->getdevice($device_id);
		$message = 'Get Config Success';
		$state = true;
	}else{
		$message = 'Token invalid!';
		$log_id = NULL;
		$state = false;
	}
} else {
	$message = 'Token invalid!';
}

// Export data to json format
$data = array(
	"apiVersion" => "1.0",
	"data" => array(
		"message" 		=> $message,
		"execute" 		=> floatval(round(microtime(true)-StTime,4)),
		"update" 		=> time(),
		"data" => array(
			'min' => floatval($devices->min),
			'max' => floatval($devices->max),
		),
	),
);
					
echo json_encode($data);
exit();
?>