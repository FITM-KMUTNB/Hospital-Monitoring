<?php
include_once 'autoload.php';
if(!$user_online){
	header('Location: '.DOMAIN.'/signin');
	die();
}

$space_id = $_GET['space'];

if(!empty($space_id) && isset($space_id)){
	$space->get($space_id);

	if(empty($space->id)){
		header('Location: error-400.php');
		die();
	}
}else{
	$space_id = $space->defaultSpace($user->id);
	$space->get($space_id);

	if(!empty($space->id) && isset($space->id)){
		header('Location: '.DOMAIN.'/space/'.$space->id);
		die();
	}
}

$hasSpace 	= $space->hasSpace($user->id);
$allzone 	= $space->listZone($space->id);
$spacelist 	= $space->listAll($user->id);
$devices->listDevices($space->id,$_GET['zone']);
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
<meta name="description" content="<?php echo DESCRIPTION;?>"/>
<meta property="og:title" content="<?php echo TITLE;?>"/>
<meta property="og:description" content="<?php echo DESCRIPTION;?>"/>
<meta property="og:url" content="<?php echo DOMAIN;?>"/>
<meta property="og:image" content="<?php echo DOMAIN;?>/image/ogimage.jpg"/>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="<?php echo SITENAME;?>"/>

<meta itemprop="name" content="<?php echo TITLE;?>">
<meta itemprop="description" content="<?php echo DESCRIPTION;?>">
<meta itemprop="image" content="<?php echo DOMAIN;?>/image/ogimage.jpg">

<title><?php echo $space->title;?> | <?php echo TITLE.' '.VERSION;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>
</head>
<body>

<div id="loading-bar"></div>
<div id="filter"></div>

<header class="header">
	<a class="logo-icon" href="index.php"><img src="image/logo.png" alt=""></a>
	<a class="logo-title" href="index.php"><?php echo TITLE;?></a>
	<a class="btn-icon right" href="logout" target="_parent"><i class="fal fa-power-off"></i></a>
</header>

<div class="container">
	<div class="space-list">
		<?php foreach ($spacelist as $var){ ?>
		<a class="link <?php echo ($var['space_id'] == $space->id?'active':'');?>" href="space/<?php echo $var['space_id'];?>">
			<i class="fa fa-folder-o" aria-hidden="true"></i><?php echo $var['space_title'];?><?php echo ($var['total_device'] > 0 ? ' ('.$var['total_device'].')':'');?></a>
		<?php }?>
		<a class="link button" href="newspace?back=<?php echo $space->id;?>">สร้างกลุ่ม</a>
	</div>

	<?php if($hasSpace){?>
	<div class="option-list">
		<?php if(count($allzone) > 0){?>
		<a href="space/<?php echo $space->id;?>" class="button <?php echo (empty($_GET['zone'])?'active':'');?>" target="_parent">ดูทั้งหมด</a>
		<?php }?>
		<?php foreach ($allzone as $var){ ?>
		<a href="space/<?php echo $space->id;?>/<?php echo $var['id'];?>" class="button <?php echo ($_GET['zone'] == $var['id']?'active':'');?>" target="_parent"><?php echo $var['title'];?></a>
		<?php }?>

		<a class="button right" href="editspace/<?php echo $space->id;?>"><i class="fal fa-cogs"></i>แก้ไขกลุ่ม</a>
		<a class="button right" href="space-user.php?id=<?php echo $space->id;?>"><i class="fal fa-user-plus"></i>เพิ่มผู้ดูแล</a>
	</div>
	<?php }?>

	<?php if(!$hasSpace){?>
	<div class="message-box">
		<div class="icon"><i class="fa fa-cube" aria-hidden="true"></i></div>
		<div class="msg">คุณจำเป็นต้องมีกลุ่มสำหรับเพิ่มอุปกรณ์</div>
		<div class="control"><a href="newspace" class="btn">สร้างกลุ่มใหม่</a></div>
	</div>
	<?php }else{?>
		<?php if($space->total_device > 0){?>
			<?php if(empty($space->line_token)){?>
				<div class="message-box -minibox">
					<div class="icon"><i class="fa fa-chain-broken" aria-hidden="true"></i></div>
					<div class="msg">คุณไม่มี LINE Access Token สำหรับการแจ้งเตือน</div>
					<div class="control">
						<a class="btn" href="editspace/<?php echo $space->id;?>#line_token" class="btn">แก้ไข Access Token</a>
					</div>
				</div>
			<?php }?>
			<div class="templist">
				<a class="temp-card" href="newdevice/space/<?php echo $space->id;?>">เพิ่มอุปกรณ์</a>
			<?php
			foreach ($devices->devices_set as $var) {
				$status = ($var['status'] == 'active'?true:false);
				$notify = ($var['notify'] == 'active'?true:false);
			?>
				<a class="temp-card" id="device-<?php echo $var[id];?>" a href="device/<?php echo $var['id'];?>">
					<div class="icon"><i class="fa fa-thermometer-full" aria-hidden="true"></i></div>
					<div class="temp">n/a</div>
					<div class="name">
						<?php echo $var['name'];?>
						<?php echo ($var['status'] != 'active'?'<i class="fa fa-lock"></i>':'');?>
						<?php echo ($var['notify'] != 'active'?'<i class="fa fa-bell-slash"></i>':'');?>
					</div>
					<div class="desc">
						<?php echo (!empty($var['zone_title'])?'<span>'.$var['zone_title'].'</span> · ':'');?>
						<span class="time"><?php echo (status?'กำลังโหลด..':'ปิดรับข้อมูล')?></span>
					</div>
				</a>
			<?php }?>
			</div>
		<?php }else{?>
			<div class="message-box">
				<div class="icon"><i class="fa fa-file-o" aria-hidden="true"></i></div>
				<div class="msg">ในกลุ่ม "<?php echo $space->title;?>" ยังไม่มีอุปกรณ์</div>
				<div class="control">
					<a class="btn" href="newdevice/space/<?php echo $space->id;?>" class="btn">เพิ่มอุปกรณ์ใหม่</a>
				</div>
			</div>
		<?php }?>

	<?php }?>
</div>

<input type="hidden" value="<?php echo $space->id;?>" id="space_id">

<?php include'footer.php';?>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/timeline.min.js"></script>
<script type="text/javascript" src="js/min/layout.min.js"></script>
</body>
</html>