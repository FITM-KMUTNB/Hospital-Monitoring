<?php
require_once 'autoload.php';
header("Content-type: text/json");

// API Request $_POST
if($_POST['calling'] != '' && $signature->verifySign($_POST['sign'])){
	switch ($_POST['calling']) {
		case 'space':
			switch ($_POST['action']) {
				case 'submit':
					if(!empty($_POST['space_id'])){
						$space->edit($_POST['space_id'],$_POST['name'],$_POST['description'],$_POST['line_token']);
						$api->successMessage('Space Updated.',0,'');
					}else{
						$space_id = $space->create($_POST['name'],$_POST['description'],$_POST['line_token'],$user->id);
						$space->addPermission($space_id,$user->id,1);
						$api->successMessage('New Space Created.',$space_id,'');
					}
					break;
				case 'create_zone':
					$zone_id = $space->createZone($_POST['title'],'',$_POST['space_id']);
					$api->successMessage('New Zone Created.',$zone_id,'');
					break;
				case 'delete_zone':
					$space->deleteZone($_POST['zone_id'],$_POST['space_id']);
					$api->successMessage('ZONE'.$_POST['zone_id'].' Deleted',true,'');
					break;
				case 'add_admin':
					$user_id = $user->findUserWithEmail($_POST['email']);

					if($space->alreadyAdmin($user_id,$_POST['space_id'])){
						$space->addPermission($_POST['space_id'],$user_id,3);
						$api->successMessage('Add Admin success',true,'');
					}else{
						$api->successMessage('User is already in space!',false,'');
					}
					break;
				case 'remove_admin':
					$space->removeAdmin($_POST['user_id'],$_POST['space_id']);
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