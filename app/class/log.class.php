<?php
class Log extends Database{

	public function save($device_id,$temp){
		parent::query('INSERT INTO log(device_id,temp,update_time,time_stamp,ip) VALUE(:device_id,:temp,:update_time,:time_stamp,:ip)');
		parent::bind(':device_id' 	,$device_id);
		parent::bind(':temp' 		,$temp);
		parent::bind(':ip' 			,parent::GetIpAddress());
		parent::bind(':update_time' , date('Y-m-d H:i:s'));
		parent::bind(':time_stamp' 	, time());
		parent::execute();
		$log_id = parent::lastInsertId();

		$this->updateDevice($device_id,$temp);

		return $log_id;
	}

	private function updateDevice($device_id,$temp){
		parent::query('UPDATE devices SET temp = :temp,update_time = :update_time WHERE id = :device_id');
		parent::bind(':device_id' 	,$device_id);
		parent::bind(':temp' 		,$temp);
		parent::bind(':update_time' , date('Y-m-d H:i:s'));
		parent::execute();
	}

	public function lastlog($user_id){
		parent::query('SELECT devices.id device_id,devices.temp device_temp,devices.update_time,devices.name,devices.description,devices.min device_min,devices.max device_max,permission.user_id permission_user_id FROM devices AS devices LEFT JOIN space_permission AS permission ON devices.space_id = permission.space_id WHERE permission.user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$dataset = parent::resultset();

		foreach ($dataset as $k => $var) {
			$dataset[$k]['device_id'] = floatval($var['device_id']);
			$dataset[$k]['device_temp'] = floatval(round($var['device_temp'], 1));
			$dataset[$k]['device_min'] = floatval($var['device_min']);
			$dataset[$k]['device_max'] = floatval($var['device_max']);
			$dataset[$k]['update_timestemp'] = time() - strtotime($var['update_time']);
			$dataset[$k]['update_datetime'] = parent::time_thaiformat($var['update_time']);
			$dataset[$k]['update_time'] = parent::date_facebookformat($var['update_time']);
		}

		return $dataset;
	}

	public function findMin($device_id){
		parent::query('SELECT temp,update_time time FROM log WHERE device_id = :device_id AND DATE(update_time) = :nowdate ORDER BY temp ASC LIMIT 1');
		parent::bind(':device_id',$device_id);
		parent::bind(':nowdate',date('Y-m-d'));
		parent::execute();
		$dataset = parent::single();
		$dataset['temp'] = floatval(round($dataset['temp'],1));
		$dataset['time'] = parent::datetimeformat($dataset['time']);
		return $dataset;
	}

	public function findMax($device_id){
		parent::query('SELECT temp,update_time time FROM log WHERE device_id = :device_id AND DATE(update_time) = :nowdate ORDER BY temp DESC LIMIT 1');
		parent::bind(':device_id',$device_id);
		parent::bind(':nowdate',date('Y-m-d'));
		parent::execute();
		$dataset = parent::single();
		$dataset['temp'] = floatval(round($dataset['temp'],1));
		$dataset['time'] = parent::datetimeformat($dataset['time']);
		return $dataset;
	}

	public function lastUpdate($device_id){
		parent::query('SELECT time_stamp FROM log WHERE device_id = :device_id ORDER BY time_stamp DESC');
		parent::bind(':device_id',$device_id);
		parent::execute();
		$dataset = parent::single();
		return $dataset['time_stamp'];
	}

	public function historylog($device_id,$start,$limit,$time_stamp){

		if(empty($limit)) $limit = 20;

		if($time_stamp == 0){
			parent::query('SELECT log.id log_id,log.temp log_temp,log.update_time log_time,log.time_stamp log_timestamp FROM log AS log WHERE (log.device_id = :device_id) ORDER BY log.update_time DESC LIMIT '.$start.','.$limit);
		}else{
			parent::query('SELECT log.id log_id,log.temp log_temp,log.update_time log_time,log.time_stamp log_timestamp FROM log AS log WHERE (log.device_id = :device_id AND log.time_stamp > :time_stamp) ORDER BY log.update_time DESC LIMIT '.$start.','.$limit);
			parent::bind(':time_stamp',$time_stamp);
		}
		parent::bind(':device_id',$device_id);
		parent::execute();
		$dataset = parent::resultset();
		$temp = null;

		foreach ($dataset as $k => $var) {
			$dataset[$k]['log_id'] 			= floatval($var['log_id']);
			$dataset[$k]['log_temp'] 		= floatval(round($var['log_temp'],1));
			$dataset[$k]['log_timestamp'] 	= floatval($var['log_timestamp']);
			$dataset[$k]['log_time_fb'] 	= parent::date_facebookformat($var['log_time']);
			$dataset[$k]['log_time'] 		= parent::datetimeformat($var['log_time']);

			if($dataset[$k]['log_temp'] > $temp && $temp != null) $dataset[$k-1]['log_state'] = 'down';
			else if($dataset[$k]['log_temp'] < $temp && $temp != null) $dataset[$k-1]['log_state'] = 'up';

			// $dataset[$k]['log_temp_prev'] = $temp;
			$temp = $dataset[$k]['log_temp'];
		}

		return $dataset;
	}

	public function countHistory($device_id){
		parent::query('SELECT COUNT(id) total FROM log WHERE device_id = :device_id');
		parent::bind(':device_id',$device_id);
		parent::execute();
		$dataset = parent::single();

		return $dataset['total'];
	}

	public function avgTemp($space_id){
		parent::query('SELECT id device_id,name device_name,temp device_temp FROM devices WHERE space_id = :space_id ORDER BY sort ASC');
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::resultset();

		foreach ($dataset as $k => $var) {
			$dataset[$k]['device_id'] 	= floatval($var['device_id']);
			$dataset[$k]['device_temp'] = floatval(round($var['device_temp'],1));
		}

		return $dataset;
	}
}
?>
