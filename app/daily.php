<?php
require_once 'autoload.php';
header('Access-Control-Allow-Origin: *');
header("Content-type: text/json");

$spaces = $space->listAllSpace();

foreach ($spaces as $k => $var) {

	if(!empty($var['space_line_token'])){
		
		$dataset = $log->avgTemp($var['space_id']);
		$spaces[$k]['dataset'] 	= $dataset;
		$spaces[$k]['items'] 	= floatval(COUNT($dataset));

		/**
		 * Emoji Unicode Tables
		 * https://apps.timwhitlock.info/emoji/tables/unicode
		**/

		if(COUNT($dataset) >= 1){
			$msg = "\n⏰ ".today()."\n\n";
			foreach ($dataset as $key) {
				$msg .= "❄ ".$key['device_name']."\t[".$key['device_temp']."°]\n";
			}
			// echo $msg;

			$res = $notify->lineNotify($msg,2,159,$var['space_line_token']);
		}

		$spaces[$k]['send'] = $res;
	}
}

$message = 'Daily reports';

// Export data to json format
$data = array(
	"apiVersion" => "1.0",
	"data" => array(
		"message" 	=> $message,
		"execute" 	=> floatval(round(microtime(true)-StTime,4)),
		"update" 	=> time(),
		"data" 		=> $spaces,
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
	return $date.' '.$month.' ··· '.$hour.':'.$minute.' น.';
}
?>