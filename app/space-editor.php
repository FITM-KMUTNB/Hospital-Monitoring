<?php
include_once 'autoload.php';
if(!$user_online){
	header("Location: ".DOMAIN."/login.php");
	die();
}

$space->get($_GET['id']);
$hasPermission = $space->hasPermission($user->id,$space->id);

if(!$hasPermission && !empty($_GET['id'])){
	header("Location: ".DOMAIN."/permission-error.php");
	die();
}

if(!empty($space->id) && isset($space->id)){
	$allzone = $space->listZone($space->id);
	$allAdmin = $space->listAdmin($space->id);
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
<title><?php echo $space->title;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<header class="header">
	<div class="btn-icon"></div>
	<div class="title">ตั้งค่าโปรเจ็ค</div>
	<a class="btn-icon" href="/" target="_parent"><i class="fas fa-times"></i></a>
</header>
<div class="form vertical-center">
	<div class="form-items">
		<label for="name">ชื่อโปรเจ็ค</label>
		<div class="input"><input class="input-text" type="text" placeholder="ไม่เกิน 20 ตัวอักษร" autocomplete="off" id="name" value="<?php echo $space->title;?>"></div>
	</div>
	<div class="form-items <?php echo (empty($space->id) ? 'hidden' : '');?>">
		<label for="line_token">
			<div>LINE Access Token</div>
			<a href="https://notify-bot.line.me/th/" target="_blank" class="label-button">สร้างคีย์ใหม่</a>
		</label>
		<div class="input">
			<input class="input-text" type="text" autocomplete="off" id="line_token" value="<?php echo $space->line_token;?>">
		</div>
	</div>
	<div class="form-items hidden">
		<label for="description">รายละเอียด</label>
		<div class="input"><textarea class="input-textarea" id="description"><?php echo $space->description;?></textarea></div>
	</div>

	<input type="hidden" id="return_device" value="<?php echo $_GET['return_device'];?>">
	<input type="hidden" id="space_id" value="<?php echo $space->id?>">
	<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('space_editor',SECRET_KEY);?>">

	<div class="form-items">
		<button class="btn-submit" id="btn-save"><?php echo (!empty($space->id) ? 'บันทึก' : 'สร้างกลุ่มใหม่');?></button>
	</div>
</div>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/space.min.js"></script>
</body>
</html>
