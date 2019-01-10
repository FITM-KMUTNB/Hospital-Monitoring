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
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>

</head>
<body>
<header class="header">
	<?php if(empty($space->id)){?>
	<a href="space/<?php echo $_GET['back'];?>" class="btn-icon" target="_parent"><i class="far fa-arrow-left"></i></a>
	<?php }else{?>
	<a href="space/<?php echo $space->id;?>" class="btn-icon" target="_parent"><i class="far fa-arrow-left"></i></a>
	<?php }?>
</header>
<div class="form">
	<h2>จัดการกลุ่ม</h2>
	<div class="form-items">
		<div class="label">ชื่อกลุ่ม</div>
		<div class="input"><input class="input-text" type="text" id="name" value="<?php echo $space->title;?>"></div>
	</div>
	<div class="form-items <?php echo (empty($space->id)?'-hidden':'');?>">
		<div class="label">รายละเอียด</div>
		<div class="input"><textarea class="input-textarea" id="description"><?php echo $space->description;?></textarea></div>
	</div>

	<?php if(!empty($space->id) && isset($space->id)){?>
	<div class="form-items">
		<div class="label">สถานที่</div>
		<div class="input">
			<div class="list">
				<?php foreach ($allzone as $var) {?>
				<div class="list-items" id="zone-<?php echo $var['id'];?>" data-id="<?php echo $var['id'];?>"><span class="c"><i class="fal fa-map-pin"></i><?php echo $var['title'];?></span><span class="btn btn-zone-delete"><i class="fal fa-trash-alt"></i></span></div>
				<?php }?>
			</div>
			<div class="list-input">
				<input type="text" id="zone_title" class="input-list-text" placeholder="เพิ่มสถานที่...">
				<div class="btn" id="btn-zone-save"><i class="far fa-plus"></i></div>
			</div>
		</div>
	</div>
	<?php }?>

	<div class="form-items <?php echo (empty($space->id)?'-hidden':'');?>">
		<div class="label">LINE Access Token</div>
		<div class="input">
			<input class="input-text" type="text" id="line_token" value="<?php echo $space->line_token;?>">
			<p>คุณจำเป็นต้องมี Access Token จาก LINE Notify เพื่อใช้ในการแจ้งเตือน <a href="https://notify-bot.line.me/th/">ขอคีย์ได้ที่นี่<i class="far fa-external-link-square"></i></a></p>
		</div>
	</div>

	<input type="hidden" id="return_device" value="<?php echo $_GET['return_device'];?>">
	<input type="hidden" id="space_id" value="<?php echo $space->id?>">
	<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('space_editor',SECRET_KEY);?>">

	<div class="form-items">
		<div class="input">
			<button class="btn" id="btn-save"><?php echo (!empty($space->id)?'บันทึก':'สร้างกลุ่มใหม่');?></button>

			<?php if(!empty($space->id)){?>
			<a class="btn-nav" id="btn-nav" href="space/<?php echo $space->id;?>">ไปหน้ากลุ่ม<i class="fa fa-arrow-right" aria-hidden="true"></i></a>
			<?php }?>
		</div>
	</div>
</div>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/space.min.js"></script>
</body>
</html>
