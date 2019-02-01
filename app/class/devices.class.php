<?php
class Devices extends Database{

	public $id;
	public $name;
	public $min;
	public $max;
	public $token;
	public $url_short;
	public $line_token;
	public $project_id;
	public $project_name;
	public $project_description;
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

	public function create($name,$description,$project_id,$max,$min,$warning) {
		$sort = $this->getLastSort($project_id);
		parent::query('INSERT INTO devices(name,description,project_id,token,max,min,warning,ip,create_time,update_time,edit_time,sort,status) VALUE(:name,:description,:project_id,:token,:max,:min,:warning,:ip,:create_time,:update_time,:edit_time,:sort,:status)');
		parent::bind(':name', $name);
		parent::bind(':description', $description);
		parent::bind(':project_id', $project_id);
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

	public function edit($device_id,$name,$description,$project_id,$max,$min,$warning) {
		parent::query('UPDATE devices SET name = :name, description = :description, project_id = :project_id, min = :min, max = :max, warning = :warning, ip = :ip, edit_time = :edit_time WHERE id = :device_id');
		parent::bind(':device_id', $device_id);
		parent::bind(':name', $name);
		parent::bind(':description', $description);
		parent::bind(':project_id', $project_id);
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
		parent::query('SELECT devices.id,devices.name,devices.description,devices.url_short,devices.token,devices.max,devices.min,devices.warning,devices.ip,devices.create_time,devices.edit_time,devices.type,devices.notify,devices.status,project.title project_name,project.description project_description,project.id project_id,project.line_token line_token FROM devices AS devices LEFT JOIN project AS project ON devices.project_id = project.id WHERE devices.id = :device_id');
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
		$this->project_id 	= $dataset['project_id'];
		$this->project_name 	= $dataset['project_name'];
		$this->project_description = $dataset['project_description'];
		$this->line_token 	= $dataset['line_token'];
	}

	private function getLastSort($project_id){
		parent::query('SELECT sort FROM devices WHERE project_id = :project_id ORDER BY sort DESC LIMIT 1');
		parent::bind(':project_id',$project_id);
		parent::execute();
		$dataset = parent::single();

		return $dataset['sort'];
	}

	public function listDevices($user_id){
		parent::query('SELECT project.id,project.title,project.description,project.line_token,permission.permission,project.create_time,project.update_time,project.invite_code FROM project_permission AS permission LEFT JOIN project AS project ON permission.project_id = project.id WHERE permission.user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$project_lists = parent::resultset();

		foreach ($project_lists as $k => $var) {
			parent::query('SELECT devices.id,devices.name,devices.description,devices.token,devices.max,devices.min,devices.warning,devices.ip,devices.create_time,devices.edit_time,devices.type,devices.status,devices.notify FROM devices AS devices WHERE devices.project_id = :project_id ORDER BY devices.sort ASC');
			parent::bind(':project_id',$var['id']);
			parent::execute();
			$dataset = parent::resultset();

			$project_lists[$k]['devices'] = $dataset;
		}
		return $project_lists;
	}
}
?>
