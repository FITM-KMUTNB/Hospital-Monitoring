<?php
require_once 'autoload.php';
header('Access-Control-Allow-Origin: *');
header('Accept: application/json');
$datajson = json_decode(file_get_contents('php://input'), true);

$alert_delay = 480; // ระยะห่างการแจ้งเตือนแต่ละครั้ง (กรณีที่สูงหรือต่ำเกินไป)
$protect_time = 30; // ระยะเวลาป้องกันส่งซ้ำ

$token = $datajson['token'];
$temp = $datajson['temp'];

// Temp value validation
if (!is_numeric($temp) || $temp < -50 || $temp > 150 || $temp == 85) {
	$message = 'temperature is not numeric!';
} else if ($devices->tokenValid($token)) {
	
	// Find Device id with Token.
	$device_id = $devices->deviceAuthentication($token);

	$temp = (float)$temp;
	$temp = round($temp,1);

	// Get Device data.
	$devices->getdevice($device_id);
	$lastUpdateTime = $log->lastUpdate($device_id);

	// Frequency checking and Protect.
	if ((time() - $lastUpdateTime) < $protect_time) {
		$data = array(
			"apiVersion" => "1.0",
			"data" => array(
				"waiting" => floatval(($protect_time-(time()-$lastUpdateTime)))
			)
		);
		echo json_encode($data);
		exit();
	}

	$message = array(
		'alert' 	=> "🚨 ".$devices->name." ผิดปกติ [".$temp."°C]",
		'standard' 	=> "👍 ".$devices->name." สู่สภาวะปกติแล้ว [".$temp."°C]",
	);

	$lastNotify = $notify->lastUpdate($device_id); // GET LAST UPDATE!
	// $firstNotify = $notify->firstNotify($device_id);

	$lasttime = $lastNotify['update_time'];
	$lasttype = $lastNotify['type'];
	$lastcount = $lastNotify['count'];

	if (empty($lastcount)) $lastcount = 0;

	if (!empty($device_id) && $devices->status == 'active'){

		$log_id = $log->save($device_id,$temp); // บันทึกค่าล่าสุด!

		if ($devices->notify == 'active') { // เปิดการแจ้งเตือน

			if ($temp >= $devices->max || $temp <= $devices->min) {
				if ($lasttype == 'standard' || empty($lasttype)) {
					// First Alert่
					$msg = $message['alert'];
					$msg .= " · ".today();
					$msg .= "\n\nตรวจสอบ [".DOMAIN."/device.php?id=".$devices->id.']';

					$noti_id = $notify->save($device_id,$msg,'alert',1,1);
					$res = $notify->lineNotify($msg, 2, 153, $devices->line_token);
				} else if ($lasttype == 'alert' && (time() - $lasttime) >= $alert_delay) {
					// สูงหรือต่ำเกินไป
					$msg = $message['alert'];
					
					if ($lastcount > 0)
						$msg .= " · ".today();
					else
						$msg .= "\n\nตรวจสอบ ".DOMAIN."/device.php?id=".$devices->id;

					$noti_id = $notify->save($device_id,$msg,'alert',++$lastcount,1);
					$res = $notify->lineNotify($msg,NULL,NULL,$devices->line_token);
				}
			} else {
				// สภาวะปกติ
				if ($lasttype == 'alert') {
					$lastcount = 1;
					$msg = $message['standard'];
					$noti_id = $notify->save($device_id,$msg,'standard',$lastcount,1);
					$res = $notify->lineNotify($msg, 2, 516, $devices->line_token);
				}
			}
		}
		
		$data = array(
			'timediff' 	=> floatval(time()-$lasttime),
			'notify' 	=> $msg,
			'notify_type' => $lasttype,
			'temp' 		=> floatval($temp),
			'alert_count' => floatval($lastcount),
			'noti_id' 	=> floatval($noti_id),
			'first' 	=> $diffFirstNotify,
			'res' 		=> $res,
		);

		$message = 'Push Success';
		$state = true;
	}else{
		if($devices->status == 'disable'){
			$message = 'Device id disable!';
		}else{
			$message = 'Device id empty!';
		}
		$log_id = NULL;
		$state = false;
	}
}else{
	$message = 'Token invalid!';
	$log_id = NULL;
	$state = false;
}

// Export data to json format
$data = array(
	"apiVersion" => "1.0",
	"data" => array(
		"message" 	=> $message,
		"device" => array(
			"id" => floatval($device_id),
			"status" => $devices->status,
			"notify" => $devices->notify,
			"logid" 	=> floatval($log_id),
		),
		"notify" 	=> $data,
		"state" 	=> $state,
		"update" 	=> time(),
		"execute" => floatval(round(microtime(true)-StTime,4)),
	),
);

echo json_encode($data);

function today(){
	$datetime = date('Y-m-d H:i:s');
	$monthText = array('ม.ค.','ก.พ.','มี.ค.','เม.ย.','พ.ค.','มิ.ย.','ก.ค.','ส.ค.','ก.ย.','ต.ค.','พ.ย.','ธ.ค.');
	
	$hour   = date('H',strtotime($datetime));
  $minute = date("i",strtotime($datetime));
	$year   = date('Y',strtotime($datetime))+543;
	$month  = date('n',strtotime($datetime));
	$date   = date('j',strtotime($datetime));

	$month  = $monthText[$month-1];
	// return $hour.':'.$minute.' น.';
	return '';
}
?>