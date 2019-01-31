<?php
class Project extends Database{

	public $id;
	public $title;
	public $description;
	public $line_token;
	public $create_time;
	public $total_device;
	public $invite_code;

	public function setProject($project_id){
		setcookie('project_id',$project_id,time() + 3600 * 24 * 12);
		$_SESSION['project_id'] = $project_id;
	}

	public function hasPermission($user_id,$project_id){
		parent::query('SELECT id FROM project_permission WHERE project_id = :project_id AND user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::bind(':project_id',$project_id);
		parent::execute();
		$dataset = parent::single();

		if(!empty($dataset['id'])){
			return true;
		}else{
			return false;
		}
	}

	public function defaultProject($user_id){
		parent::query('SELECT project_id FROM project_permission WHERE user_id = :user_id ORDER BY create_time DESC LIMIT 1');
		parent::bind(':user_id' ,$user_id);
		parent::execute();
		$dataset = parent::single();
		return $dataset['project_id'];
	}

	public function randInviteCode(){
		$code = substr(md5(time()),0,8);
		return $code;
	}

	public function get($project_id){
		parent::query('SELECT id,title,description,line_token,create_time,invite_code,(SELECT COUNT(id) FROM devices AS devices WHERE project_id = project.id) total_device FROM project WHERE id = :project_id');
		parent::bind(':project_id' ,$project_id);
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
		parent::query('SELECT permission.id permission_id,permission.user_id permission_user_id,permission.permission permission_type,project.id project_id,project.title project_title,project.description project_description,(SELECT COUNT(id) FROM devices AS devices WHERE project_id = project.id) total_device FROM project_permission AS permission LEFT JOIN project AS project ON permission.project_id = project.id WHERE permission.user_id = :user_id');
		parent::bind(':user_id' ,$user_id);
		parent::execute();
		return $dataset = parent::resultset();
	}

	public function create($title,$description,$line_token,$owner_id){
		parent::query('INSERT INTO project(owner_id,title,description,line_token,create_time,update_time,invite_code) VALUE(:owner_id,:title,:description,:line_token,:create_time,:update_time,:invite_code)');
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

	public function addPermission($project_id,$user_id,$permission){
		parent::query('INSERT INTO project_permission(project_id,user_id,permission,create_time) VALUE(:project_id,:user_id,:permission,:create_time)');
		parent::bind(':project_id' 	,$project_id);
		parent::bind(':user_id' 	,$user_id);
		parent::bind(':permission' 	,$permission);
		parent::bind(':create_time' , date('Y-m-d H:i:s'));
		parent::execute();
		return parent::lastInsertId();
	}

	public function edit($project_id,$title,$description,$line_token){
		parent::query('UPDATE project SET title = :title,description = :description,line_token = :line_token,update_time = :update_time WHERE id = :project_id');
		parent::bind(':project_id' 	,$project_id);
		parent::bind(':title' 		,$title);
		parent::bind(':description' ,$description);
		parent::bind(':line_token' 	,$line_token);
		parent::bind(':update_time' ,date('Y-m-d H:i:s'));
		parent::execute();
	}
	
	public function listAdmin($project_id){
		parent::query('SELECT sp.id,sp.user_id,sp.permission,user.email,user.fname,user.lname FROM project_permission AS sp LEFT JOIN user AS user ON sp.user_id = user.id WHERE sp.project_id = :project_id');
		parent::bind(':project_id',$project_id);
		parent::execute();
		$dataset = parent::resultset();
		return $dataset;
	}

	public function alreadyAdmin($user_id,$project_id){
		parent::query('SELECT id FROM project_permission WHERE user_id = :user_id AND project_id = :project_id');
		parent::bind(':user_id',$user_id);
		parent::bind(':project_id',$project_id);
		parent::execute();
		$dataset = parent::single();
		
		if(empty($dataset['id'])) return true;
		else return false;
	}

	public function removeAdmin($user_id,$project_id){
		parent::query('DELETE FROM project_permission WHERE user_id = :user_id AND project_id = :project_id AND permission != 1');
		parent::bind(':user_id',$user_id);
		parent::bind(':project_id',$project_id);
		parent::execute();
	}


	// DAILY REPORT
	public function listAllProject(){
		parent::query('SELECT id project_id,title project_name,line_token project_line_token FROM project');
		parent::execute();
		$dataset = parent::resultset();

		foreach ($dataset as $k => $var) {
			$dataset[$k]['project_id'] = floatval($var['project_id']);
		}

		return $dataset;
	}

	public function hasProject($user_id){
		parent::query('SELECT COUNT(id) total FROM project_permission WHERE user_id = :user_id');
		parent::bind(':user_id',$user_id);
		parent::execute();
		$dataset = parent::single();

		if($dataset['total'] > 0)
			return true;
		else
			return false;
	}

	public function getProjectWithInviteCode($invite_code){
		parent::query('SELECT id FROM project WHERE invite_code = :invite_code');
		parent::bind(':invite_code',$invite_code);
		parent::execute();
		$dataset = parent::single();

		$project_id = $dataset['id'];

		$this->get($project_id);
	}
}
?>
