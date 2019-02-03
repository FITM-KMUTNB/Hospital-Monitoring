<?php
include_once 'autoload.php';
if ($user_online) {
	header("Location: index.php");
	die();
}

$invite_code = $_GET['invite'];

if (strlen($invite_code) == 8 && !empty($invite_code) && isset($invite_code)) {
	$project->getProjectWithInviteCode($invite_code);
}

$title 	= TITLE;
$desc 	= DESCRIPTION;
$link 	= DOMAIN.'/register.php';
$image 	= DOMAIN.'/image/ogimage.png';

if(!empty($project->id)){
	$title 	= 'คำเชิญเข้ากลุ่ม '.$project->title.' - '.TITLE;
	$link 	= DOMAIN.'/register.php?invite='.$invite_code;
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
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<div id="progress-bar"></div>
<form class="form login" action="javascript:register();">
	<div class="logo"><img src="image/logo.png" alt=""></div>
	<div class="form-items">
		<label for="name">ชื่อ-นามสกุล</label>
		<input class="input-text" type="text" id="name" autofocus>
	</div>
	<div class="form-items">
		<label for="email">อีเมล</label>
		<input class="input-text" type="email" id="email">
	</div>
	<div class="form-items">
		<label for="password">รหัสผ่าน</label>
		<input class="input-text" type="password" id="password">
	</div>
	<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('register',SECRET_KEY);?>">
	<input type="hidden" id="invite_code" value="<?php echo $_GET['invite'];?>">
	<div class="form-items">
		<button class="btn-submit" id="btn-register">ลงทะเบียน<?php echo (!empty($project->id)?'และร่วมกลุ่ม':'');?></button>
	</div>
	<p>ถ้าคุณมีบัญชีอยู่แล้ว <a href="login.php<?php echo (!empty($_GET['invite']) ? '?invite='.$_GET['invite'] : '');?>">เข้าระบบ</a></p>
</form>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/user.min.js"></script>
</body>
</html>