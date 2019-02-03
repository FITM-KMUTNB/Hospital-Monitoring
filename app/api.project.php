<?php
require_once 'autoload.php';
header("Content-type: text/json");

// API Request $_POST
if($_POST['calling'] != '' && $signature->verifySign($_POST['sign'])){
	switch ($_POST['calling']) {
		case 'project':
			switch ($_POST['action']) {
				case 'submit':
					if (!empty($_POST['project_id'])) {
						$project->edit($_POST['project_id'],$_POST['name'],$_POST['description'],$_POST['line_token']);
						$api->successMessage('Space Updated.',0,'');
					} else {
						$project_id = $project->create($_POST['name'], $_POST['description'], $_POST['line_token'], $user->id);
						$project->addPermission($project_id, $user->id, 1);
						$api->successMessage('New Space Created.', $project_id, '');
					}
					break;
				case 'add_admin':
					$user_id = $user->findUserWithEmail($_POST['email']);

					if($project->alreadyAdmin($user_id,$_POST['project_id'])){
						$project->addPermission($_POST['project_id'],$user_id,3);
						$api->successMessage('Add Admin success',true,'');
					}else{
						$api->successMessage('User is already in project!',false,'');
					}
					break;
				case 'remove_admin':
					$project->removeAdmin($_POST['user_id'],$_POST['project_id']);
					$api->successMessage('Admin Removed!',true,'');
					break;
				default:
					break;
			}
			break;
		default:
			$api->errorMessage('COMMENT POST API ERROR!');
			break;
	}
}else{
	$api->errorMessage('Invalid Signature or API not found!');
}

exit();
?>