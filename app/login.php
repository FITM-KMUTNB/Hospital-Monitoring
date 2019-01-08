<?php
include_once 'autoload.php';
if($user_online){
	header("Location: index.php");
	die();
}

$invite_code = $_GET['invite'];

if(strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code)){
	$space->getSpaceWithInviteCode($invite_code);
}

$title 	= TITLE;
$desc 	= DESCRIPTION;
$link 	= DOMAIN.'/signin';
$image 	= DOMAIN.'/image/ogimage.png';

if(!empty($space->id)){
	$title 	= 'คำเชิญเข้ากลุ่ม '.$space->title.' - '.TITLE;
	$link 	= DOMAIN.'/signin?invite='.$invite_code;
	$image 	= DOMAIN.'/image/ogimage_invite.png';
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

<!-- Meta Tag Main -->
<meta name="description" content="<?php echo $desc;?>"/>
<meta property="og:title" content="<?php echo $title;?>"/>
<meta property="og:description" content="<?php echo $desc;?>"/>
<meta property="og:url" content="<?php echo $link;?>"/>
<meta property="og:image" content="<?php echo $image;?>"/>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="<?php echo SITENAME;?>"/>
<meta property="fb:app_id" content="<?php echo FACEBOOK_APPID;?>"/>

<meta itemprop="name" content="<?php echo $title;?>">
<meta itemprop="description" content="<?php echo $desc;?>">
<meta itemprop="image" content="<?php echo $image;?>">

<title><?php echo $title;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>
</head>
<body>
<div id="progress-bar"></div>
<form class="login" action="javascript:login();">
	<div>
		<div class="logo"><img src="image/logo.png" alt=""></div>
		<h1><a href="index.php"><?php echo TITLE;?></a></h1>
		<p><?php echo DESCRIPTION;?></p>
		
		<input class="input-text" type="email" id="email" placeholder="อีเมล" autofocus>
		<input class="input-text" type="password" id="password" placeholder="รหัสผ่าน">
		<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('login',SECRET_KEY);?>">
		<button id="btn-login" class="btn">เข้าสู่ระบบ<?php echo (!empty($space->id)?'และร่วมกลุ่ม':'');?></button>
		<p>ยังไม่มีบัญชี <a href="signup<?php echo (!empty($_GET['invite'])?'?invite='.$_GET['invite']:'');?>">ลงทะเบียน<i class="fal fa-link"></i></a></p>

		<input type="hidden" id="redirect_page" value="<?php echo $_GET['redirect'];?>">
		<input type="hidden" id="redirect_id" value="<?php echo $_GET['id'];?>">
		<input type="hidden" id="invite_code" value="<?php echo $_GET['invite'];?>">
	</div>
</form>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/user.min.js"></script>
</body>
</html>
