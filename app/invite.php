<?php
require_once 'autoload.php';
$invite_code = $_GET['c'];

if(!$user_online){
	header('Location: '.DOMAIN.'/register.php?invite='.$invite_code);
	die();
}

if(strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code)){
	$project->getProjectWithInviteCode($invite_code);

	if($_GET['action'] == 'accept'){
		if(!empty($project->id)){
			if($project->alreadyAdmin($user->id,$project->id)){
				$project->addPermission($project->id,$user->id,3);
			}

			$redirect = 'index.php';
		}else{
			$redirect = 'index.php';
		}
	}
}
?>
<!doctype html>
<html lang="en-US" itemscope itemtype="http://schema.org/Blog" prefix="og: http://ogp.me/ns#">
<head>

<!--[if lt IE 9]>
<script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE9.js"></script>
<![endif]-->

<!-- Meta Tag -->
<meta charset="utf-8">

<!-- Viewport (Responsive) -->
<meta name="viewport" content="width=device-width">
<meta name="viewport" content="user-scalable=no">
<meta name="viewport" content="initial-scale=1,maximum-scale=1">

<?php include'favicon.php';?>
<title>คำเชิญเข้าร่วมโปรเจค</title>

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<div class="message-box">
	<div class="msg">คุณได้รับคำเชิญเข้าร่วมโปรเจค <strong><?php echo $project->title;?></strong></div>
	<div class="control">
		<?php if ($_GET['action'] == 'accept') {?>
			<p>กำลังเข้าร่วมโปรเจค...</p>
		<?php } else {?>
			<a href="index.php" class="btn btn-not-Accept">ยกเลิก</a>
			<a href="invite.php?c=<?php echo $invite_code;?>&action=accept" class="btn btn-accept">เข้าร่วม<i class="fa fa-arrow-right"></i></a>
		<?php }?>
	</div>
</div>
</body>

<?php if (strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code) && $_GET['action'] == 'accept') {?>
<script type="text/javascript">
	setTimeout(function(){ window.location = '<?php echo $redirect;?>'; },2000);
</script>
<?php }?>
</html>