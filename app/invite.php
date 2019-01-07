<?php
require_once 'autoload.php';
$invite_code = $_GET['c'];

if(!$user_online){
	header('Location: '.DOMAIN.'/signup?invite='.$invite_code);
	die();
}

if(strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code)){
	$space->getSpaceWithInviteCode($invite_code);

	if($_GET['action'] == 'accept'){
		if(!empty($space->id)){
			if($space->alreadyAdmin($user->id,$space->id)){
				$space->addPermission($space->id,$user->id,3);
			}

			$redirect = DOMAIN.'/space/'.$space->id;
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
<title>กำลังเข้ากลุ่ม...</title>
<base href="<?php echo DOMAIN;?>">

<!-- CSS -->
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/font-awesome/css/font-awesome.min.css"/>

</head>
<body>
<div class="message-box">
	<div class="icon"><i class="fa fa-sign-in" aria-hidden="true"></i></div>
	<div class="msg">คุณต้องการเข้าร่วมกลุ่ม "<?php echo $space->title;?>" ใช่หรือไม่ ?</div>
	<div class="control">
		<?php if($_GET['action'] == 'accept'){?>
			<p>กรุณารอซักครู่...</p>
		<?php }else{?>
			<a href="index.php" class="btn btn-not-Accept">ยกเลิก</a>
			<a href="invite?c=<?php echo $invite_code;?>&action=accept" class="btn btn-accept">เข้ากลุ่ม<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
		<?php }?>
	</div>
</div>
</body>

<?php if(strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code) && $_GET['action'] == 'accept'){?>
<script type="text/javascript">
	setTimeout(function(){window.location = '<?php echo $redirect;?>';},2000);
</script>
<?php }?>
</html>