<?php
class Space extends Database{

	public $id;
	public $title;
	public $description;
	public $line_token;
	public $create_time;
	public $total_device;
	public $invite_code;

	public function setSpace($space_id){
		setcookie('space_id',$space_id,time() + 3600 * 24 * 12);
		$_SESSION['space_id'] = $space_id;
	}

	public function hasPermission($user_id,$space_id){
		parent::query('SELECT id FROM space_permission WHERE space_id = :space_id AND user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::single();

		if(!empty($dataset['id'])){
			return true;
		}else{
			return false;
		}
	}

	public function defaultSpace($user_id){
		parent::query('SELECT space_id FROM space_permission WHERE user_id = :user_id ORDER BY create_time DESC LIMIT 1');
		parent::bind(':user_id' ,$user_id);
		parent::execute();
		$dataset = parent::single();
		return $dataset['space_id'];
	}

	public function randInviteCode(){
		$code = substr(md5(time()),0,8);
		return $code;
	}

	public function get($space_id){
		parent::query('SELECT id,title,description,line_token,create_time,invite_code,(SELECT COUNT(id) FROM devices AS devices WHERE space_id = space.id) total_device FROM space WHERE id = :space_id');
		parent::bind(':space_id' ,$space_id);
		parent::execute();
		$dataset = parent::single();

		$this->id 			= $dataset['id'];
		$this->title 		= $dataset['title'];
		$this->description 	= $dataset['description'];
		$this->line_token 	= $dataset['line_token'];
		$this->create_time 	= $dataset['create_time'];
		$this->total_device = $dataset['total_device'];
		$this->invite_code 	= $dataset['invite_code'];
	}

	public function listAll($user_id){
		parent::query('SELECT permission.id permission_id,permission.user_id permission_user_id,permission.permission permission_type,space.id space_id,space.title space_title,space.description space_description,(SELECT COUNT(id) FROM devices AS devices WHERE space_id = space.id) total_device FROM space_permission AS permission LEFT JOIN space AS space ON permission.space_id = space.id WHERE permission.user_id = :user_id');
		parent::bind(':user_id' ,$user_id);
		parent::execute();
		return $dataset = parent::resultset();
	}

	public function create($title,$description,$line_token,$owner_id){
		parent::query('INSERT INTO space(owner_id,title,description,line_token,create_time,update_time,invite_code) VALUE(:owner_id,:title,:description,:line_token,:create_time,:update_time,:invite_code)');
		parent::bind(':owner_id' 	,$owner_id);
		parent::bind(':title' 		,$title);
		parent::bind(':description' ,$description);
		parent::bind(':line_token' 	,$line_token);
		parent::bind(':create_time' , date('Y-m-d H:i:s'));
		parent::bind(':update_time' , date('Y-m-d H:i:s'));
		parent::bind(':invite_code' ,$this->randInviteCode());
		parent::execute();
		return parent::lastInsertId();
	}

	public function addPermission($space_id,$user_id,$permission){
		parent::query('INSERT INTO space_permission(space_id,user_id,permission,create_time) VALUE(:space_id,:user_id,:permission,:create_time)');
		parent::bind(':space_id' 	,$space_id);
		parent::bind(':user_id' 	,$user_id);
		parent::bind(':permission' 	,$permission);
		parent::bind(':create_time' , date('Y-m-d H:i:s'));
		parent::execute();
		return parent::lastInsertId();
	}

	public function edit($space_id,$title,$description,$line_token){
		parent::query('UPDATE space SET title = :title,description = :description,line_token = :line_token,update_time = :update_time WHERE id = :space_id');
		parent::bind(':space_id' 	,$space_id);
		parent::bind(':title' 		,$title);
		parent::bind(':description' ,$description);
		parent::bind(':line_token' 	,$line_token);
		parent::bind(':update_time' ,date('Y-m-d H:i:s'));
		parent::execute();
	}

	public function createZone($title,$description,$space_id){
		parent::query('INSERT INTO zone(title,description,space_id) VALUE(:title,:description,:space_id)');
		parent::bind(':title' 		,$title);
		parent::bind(':description' ,$description);
		parent::bind(':space_id' 	,$space_id);
		parent::execute();
		return parent::lastInsertId();
	}

	public function listZone($space_id){
		parent::query('SELECT zone.id,zone.title,zone.description,(SELECT COUNT(devices.id) FROM devices AS devices WHERE devices.zone_id = zone.id) counter FROM zone AS zone WHERE zone.space_id = :space_id');
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::resultset();
		return $dataset;
	}

	public function deleteZone($zone_id,$space_id){
		parent::query('UPDATE devices SET zone_id = NULL WHERE zone_id = :zone_id AND space_id = :space_id');
		parent::bind(':zone_id' 	,$zone_id);
		parent::bind(':space_id' 	,$space_id);
		parent::execute();

		parent::query('DELETE FROM zone WHERE id = :zone_id AND space_id = :space_id');
		parent::bind(':zone_id' 	,$zone_id);
		parent::bind(':space_id' 	,$space_id);
		parent::execute();
	}


	public function listAdmin($space_id){
		parent::query('SELECT sp.id,sp.user_id,sp.permission,user.email,user.fname,user.lname FROM space_permission AS sp LEFT JOIN user AS user ON sp.user_id = user.id WHERE sp.space_id = :space_id');
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::resultset();
		return $dataset;
	}

	public function alreadyAdmin($user_id,$space_id){
		parent::query('SELECT id FROM space_permission WHERE user_id = :user_id AND space_id = :space_id');
		parent::bind(':user_id',$user_id);
		parent::bind(':space_id',$space_id);
		parent::execute();
		$dataset = parent::single();
		
		if(empty($dataset['id'])) return true;
		else return false;
	}

	public function removeAdmin($user_id,$space_id){
		parent::query('DELETE FROM space_permission WHERE user_id = :user_id AND space_id = :space_id AND permission != 1');
		parent::bind(':user_id',$user_id);
		parent::bind(':space_id',$space_id);
		parent::execute();
	}


	// DAILY REPORT
	public function listAllSpace(){
		parent::query('SELECT id space_id,title space_name,line_token space_line_token FROM space');
		parent::execute();
		$dataset = parent::resultset();

		foreach ($dataset as $k => $var) {
			$dataset[$k]['space_id'] = floatval($var['space_id']);
		}

		return $dataset;
	}

	public function hasSpace($user_id){
		parent::query('SELECT COUNT(id) total FROM space_permission WHERE user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$dataset = parent::single();

		if($dataset['total'] > 0)
			return true;
		else
			return false;
	}

	public function getSpaceWithInviteCode($invite_code){
		parent::query('SELECT id FROM space WHERE invite_code = :invite_code');
		parent::bind(':invite_code',$invite_code);
		parent::execute();
		$dataset = parent::single();

		$space_id = $dataset['id'];

		$this->get($space_id);
	}
}
?>
