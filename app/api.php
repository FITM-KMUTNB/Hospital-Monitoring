<?php
require_once 'autoload.php';
header("Content-type: text/json");

// API Request $_POST
if($_POST['calling'] != ''){
	switch ($_POST['calling']) {
		case 'patient':
			switch ($_POST['action']) {
				case 'visited':
					$patient->updateVisied($_POST['cid']);
					$api->successMessage($return_message,$state,'');
					break;
				default:
					break;
			}
			break;
		default:
			$api->errorMessage('COMMENT POST API ERROR!');
			break;
	}
} else if ($_GET['calling'] != '') {
	switch ($_GET['calling']) {
		case 'log':
			switch ($_GET['action']) {
				case 'getupdated':
					$project_id = $_GET['project_id'];
					$dataset = $log->lastlog($user->id);
					foreach ($dataset as $k => $var) {
						if ($var['device_temp'] > $var['device_max'] || $var['device_temp'] < $var['device_min'])
							$dataset[$k]['device_alert'] = true;
						else
							$dataset[$k]['device_alert'] = false;
					}
					$data = array(
						"apiVersion" => "1.0",
						"data" => array(
							"update" 		=> time(),
							"execute" 		=> round(microtime(true)-StTime, 4),
							"totalFeeds" 	=> floatval($total),
							"items" 		=> $dataset,
						)
					);
					echo json_encode($data);
					break;
				case 'history_log':
					$dataset = $log->historylog($_GET['device_id'],0,$_GET['limit'],$_GET['time_stamp']);
					$devices->getdevice($_GET['device_id']);
					$min = $log->findMin($_GET['device_id']);
					$max = $log->findMax($_GET['device_id']);

					$current_data = $dataset[0];

					foreach ($dataset as $k => $var) {
						if($var['log_temp'] > $devices->max || $var['log_temp'] < $devices->min)
							$dataset[$k]['alert'] = true;
						else
							$dataset[$k]['alert'] = false;
					}
					$data = array(
						"apiVersion" => "1.1",
						"data" => array(
							"message" 		=> 'History Logs',
							"update" 		=> time(),
							"execute" 		=> floatval(round(microtime(true)-StTime,4)),
							"totalFeeds" 	=> floatval(count($dataset)),
							"items" 		=> $dataset,
							"device_log" => array(
								'current' => array(
									'temp' => floatval($current_data['log_temp']),
									'time' => $current_data['log_time'],
								),
								'min' => array(
									'temp' => floatval($min['temp']),
									'time' => $min['time'],
								),
								'max' => array(
									'temp' => floatval($max['temp']),
									'time' => $max['time'],
								)
							)
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
} else {
	$api->errorMessage('API NOT FOUND!');
}
exit();
?>