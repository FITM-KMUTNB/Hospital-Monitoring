<?php
require_once 'autoload.php';
header("Content-type: text/json");

// API Request $_POST
if($_POST['calling'] != '' && $signature->verifySign($_POST['sign'])){
	switch ($_POST['calling']) {
		case 'device':
			switch ($_POST['action']) {
				case 'submit':
					if(!empty($_POST['device_id'])){
						$devices->edit($_POST['device_id'],$_POST['name'],$_POST['description'],$_POST['space_id'],$_POST['zone_id'],$_POST['max'],$_POST['min'],0);
						$api->successMessage('Device Updated.',0,'');
					}else{
						$device_id = $devices->create($_POST['name'],$_POST['description'],$_POST['space_id'],$_POST['zone_id'],$_POST['max'],$_POST['min'],0);
						$api->successMessage('New Device Created.',$device_id,'');
					}
					break;
				case 'token_reset':
					$devices->tokenReset($_POST['device_id']);
					$api->successMessage('Reset token key success.','','');
					break;
				case 'status_toggle':
					$devices->toggleStatus($_POST['device_id']);
					$api->successMessage('Device status changed.','','');
					break;
				case 'notify_toggle':
					$devices->toggleNotify($_POST['device_id']);
					$api->successMessage('Device notify changed.','','');
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
