<?php 
include_once 'autoload.php';
header("Content-type: text/json");

$login_state = $sector->login($_POST['password']);

$data = array(
	"apiVersion" 	=> "1.0",
	"message" 		=> 'User Login!',
	"execute" 		=> round(microtime(true)-StTime,4)."s",
	"state" 			=> $login_state,
);

// JSON Encode and Echo.
echo json_encode($data);
?>