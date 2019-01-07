<?php
require_once 'autoload.php';
header("Content-type: text/json");

// API Request $_POST
if($_POST['calling'] != '' && $signature->verifySign($_POST['sign'])){
	switch ($_POST['calling']){
		case 'user':
			switch ($_POST['action']) {
				case 'register':
					$state = $user->register($_POST['email'],$_POST['name'],$_POST['password']);

					if($state != 0){
						$user->login($_POST['email'],$_POST['password']);
						$message = 'Register success';
					}else $message = 'n/a';

					$api->successMessage($message,$state,'');
					break;
				case 'login':
					
					$state = $user->login($_POST['email'],$_POST['password']);

					if($state == 1) $message = 'Login success';
					else if($state == 1) $message = 'Login fail';
					else if($state == -1) $message = 'Account Locked';
					else $message = 'n/a';

					$api->successMessage($message,$state,'');
					break;
				default:
					break;
			}
			break;
		default:
			$api->errorMessage('COMMENT POST API ERROR!');
			break;
	}
}

// API Request $_GET
else if($_GET['calling'] != ''){
	switch ($_GET['calling']) {
		case 'device':
			switch ($_GET['action']) {
				case 'list_patient':
					// $dataset = $patient->listPatient($_SESSION['station_id'],$_GET['status']);

					// Export data to json format
					$data = array(
						"apiVersion" => "1.0",
						"data" => array(
							"update" => time(),
							"execute" => round(microtime(true)-StTime,4)."s",
							"totalFeeds" => floatval($total),
							"items" => $dataset
						),
					);
					
					echo json_encode($data);
					break;
				default:
					break;
			}
			break;
		default:
			$api->errorMessage('COMMENT GET API ERROR!');
			break;
	}
}

// API Request is Fail or Null calling
else{
	$api->errorMessage('Invalid Signature or API not found!');
}

exit();
?>