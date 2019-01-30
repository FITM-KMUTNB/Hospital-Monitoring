<?php
class Devices extends Database{

	public $id;
	public $name;
	public $min;
	public $max;
	public $token;
	public $url_short;
	public $line_token;
	public $space_id;
	public $space_name;
	public $space_description;
	public $status;
	public $notify;
	public $devices_set;

	public function toggleStatus($device_id) {
		$this->getdevice($device_id);

		if ($this->status == 'active')
			$status = 'disable';
		else
			$status = 'active';

		parent::query('UPDATE devices SET status = :status, edit_time = :edit_time, ip = :ip WHERE id = :device_id');
		parent::bind(':status', $status);
		parent::bind(':ip', parent::GetIpAddress());
		parent::bind(':edit_time', date('Y-m-d H:i:s'));
		parent::bind(':device_id', $device_id);
		parent::execute();
	}

	public function toggleNotify($device_id) {
		$this->getdevice($device_id);

		if ($this->notify == 'active')
			$notify = 'disable';
		else
			$notify = 'active';

		parent::query('UPDATE devices SET notify = :notify,edit_time = :edit_time,ip = :ip WHERE id = :device_id');
		parent::bind(':notify', $notify);
		parent::bind(':ip', parent::GetIpAddress());
		parent::bind(':edit_time', date('Y-m-d H:i:s'));
		parent::bind(':device_id', $device_id);
		parent::execute();
	}

	public function tokenReset($device_id) {
		parent::query('UPDATE devices SET token = :token, edit_time = :edit_time, ip = :ip WHERE id = :device_id');
		parent::bind(':token', $this->tokenGenerate());
		parent::bind(':ip', parent::GetIpAddress());
		parent::bind(':edit_time', date('Y-m-d H:i:s'));
		parent::bind(':device_id', $device_id);
		parent::execute();
	}

	public function create($name,$description,$space_id,$max,$min,$warning) {
		$sort = $this->getLastSort($space_id);
		parent::query('INSERT INTO devices(name,description,space_id,token,max,min,warning,ip,create_time,update_time,edit_time,sort,status) VALUE(:name,:description,:space_id,:token,:max,:min,:warning,:ip,:create_time,:update_time,:edit_time,:sort,:status)');
		parent::bind(':name', $name);
		parent::bind(':description', $description);
		parent::bind(':space_id', $space_id);
		parent::bind(':token', $this->tokenGenerate());
		parent::bind(':max', $max);
		parent::bind(':min', $min);
		parent::bind(':warning', $warning);
		parent::bind(':ip', parent::GetIpAddress());
		parent::bind(':create_time', date('Y-m-d H:i:s'));
		parent::bind(':update_time', date('Y-m-d H:i:s'));
		parent::bind(':edit_time', date('Y-m-d H:i:s'));
		parent::bind(':sort', ($sort + 1));
		parent::bind(':status', 'active');
		parent::execute();
		return parent::lastInsertId();
	}

	public function edit($device_id,$name,$description,$space_id,$max,$min,$warning) {
		parent::query('UPDATE devices SET name = :name, description = :description, space_id = :space_id, min = :min, max = :max, warning = :warning, ip = :ip, edit_time = :edit_time WHERE id = :device_id');
		parent::bind(':device_id', $device_id);
		parent::bind(':name', $name);
		parent::bind(':description', $description);
		parent::bind(':space_id', $space_id);
		parent::bind(':max', $max);
		parent::bind(':min', $min);
		parent::bind(':warning', $warning);
		parent::bind(':ip', parent::GetIpAddress());
		parent::bind(':edit_time', date('Y-m-d H:i:s'));
		parent::execute();
	}

	public function tokenGenerate(){
		$token = md5(bin2hex(mt_rand()));
		$token = substr_replace($token,'d',11,0); //eggxs
		return $token;
	}

	public function tokenValid($token){
		if(substr($token,11,1) == 'd')
			return true;
		else
			return false;
	}

	public function deviceAuthentication($token){
		parent::query('SELECT id FROM devices WHERE token = :token');
		parent::bind(':token',$token);
		parent::execute();
		$dataset = parent::single();
		return $dataset['id'];
	}

	public function getdevice($device_id) {
		parent::query('SELECT devices.id,devices.name,devices.description,devices.url_short,devices.token,devices.max,devices.min,devices.warning,devices.ip,devices.create_time,devices.edit_time,devices.type,devices.notify,devices.status,space.title space_name,space.description space_description,space.id space_id,space.line_token line_token FROM devices AS devices LEFT JOIN space AS space ON devices.space_id = space.id WHERE devices.id = :device_id');
		parent::bind(':device_id',$device_id);
		parent::execute();
		$dataset = parent::single();

		$this->id 			= $dataset['id'];
		$this->name 		= $dataset['name'];
		$this->description 	= $dataset['description'];
		$this->url_short 	= $dataset['url_short'];
		$this->token 		= $dataset['token'];
		$this->min 			= $dataset['min'];
		$this->max 			= $dataset['max'];
		$this->status 		= $dataset['status'];
		$this->notify 		= $dataset['notify'];
		$this->space_id 	= $dataset['space_id'];
		$this->space_name 	= $dataset['space_name'];
		$this->space_description = $dataset['space_description'];
		$this->line_token 	= $dataset['line_token'];
	}

	private function getLastSort($space_id){
		parent::query('SELECT sort FROM devices WHERE space_id = :space_id ORDER BY sort DESC LIMIT 1');
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::single();

		return $dataset['sort'];
	}

	public function listDevices($user_id){
		parent::query('SELECT space.id,space.title,space.description,space.line_token,permission.permission,space.create_time,space.update_time,space.invite_code FROM space_permission AS permission LEFT JOIN space AS space ON permission.space_id = space.id WHERE permission.user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$space_lists = parent::resultset();

		foreach ($space_lists as $k => $var) {
			parent::query('SELECT devices.id,devices.name,devices.description,devices.token,devices.max,devices.min,devices.warning,devices.ip,devices.create_time,devices.edit_time,devices.type,devices.status,devices.notify FROM devices AS devices WHERE devices.space_id = :space_id ORDER BY devices.sort ASC');
			parent::bind(':space_id',$var['id']);
			parent::execute();
			$dataset = parent::resultset();

			$space_lists[$k]['devices'] = $dataset;
		}
		return $space_lists;
	}
}
?>
