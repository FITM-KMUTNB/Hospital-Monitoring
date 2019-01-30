<?php
class Notify extends Database{

	private $line_api = 'https://notify-api.line.me/api/notify';
	// define('LINE_TOKEN','q7xLAxYSD0Yy0Lv1yppRCBMBckyYfAAJjY5RBQWdXEh');

	// sticker_list
	// https://devdocs.line.me/files/sticker_list.pdf
	// $res = notify_message('จริงจังแค่ไหน แค่ไหนเรียกจริงจัง ผิดเพียงแปดครั้ง ถึงเก้าซะที่ไหน',1,103);
	// var_dump($res);

	public function lineNotify($message, $stickerPkg, $stickerId, $token){

		if (empty($token) || strlen($token) < 35) return false;

		$queryData = array(
			'message' => $message,
			'stickerPackageId' => $stickerPkg,
			'stickerId' => $stickerId,
		);

		$queryData = http_build_query($queryData,'','&');
		$headerOptions = array(
			'http'=>array(
				'method'=>'POST',
				'header'=> "Content-Type: application/x-www-form-urlencoded\r\n"
				."Authorization: Bearer ".$token."\r\n"
				."Content-Length: ".strlen($queryData)."\r\n",
				'content' => $queryData
			)
		);

		$context = stream_context_create($headerOptions);
		$result = file_get_contents($this->line_api,FALSE,$context);
		$res = json_decode($result);
		return $res;
	}

	public function lastUpdate($device_id){
		parent::query('SELECT device_id,update_time,count,type,status FROM notify WHERE device_id = :device_id ORDER BY update_time DESC LIMIT 1');
		parent::bind(':device_id',$device_id);
		parent::execute();
		return $dataset = parent::single();
	}

	public function firstNotify($device_id){
		parent::query('SELECT update_time FROM notify WHERE device_id = :device_id AND type = "alert_f" ORDER BY update_time DESC LIMIT 1');
		parent::bind(':device_id',$device_id);
		parent::execute();
		$dataset = parent::single();
		return parent::timeDiff($dataset['update_time']);
	}
	
	public function save($device_id,$message,$type,$count,$status){
		parent::query('INSERT INTO notify(device_id,message,type,count,update_time,status) VALUE(:device_id,:message,:type,:count,:update_time,:status)');
		parent::bind(':device_id' 	,$device_id);
		parent::bind(':message' 	, '');
		parent::bind(':type' 		,$type);
		parent::bind(':count' 		,$count);
		parent::bind(':update_time' ,time());
		parent::bind(':status' 		,$status);
		parent::execute();
		return parent::lastInsertId();
	}
}
?>
