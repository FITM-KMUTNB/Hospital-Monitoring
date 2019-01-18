<?php
include_once 'autoload.php';
if (!$user_online) {
	header('Location: '.DOMAIN.'/signin');
	die();
}
$spacelist = $devices->listDevices($user->id);
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
	<a class="logo-icon" href="index.php"><img src="image/logo.png" alt="logo"></a>
	<a class="btn-icon" href="newspace" target="_parent" title="สร้างโปรเจ็คใหม่"><i class="fal fa-plus-circle"></i></a>
	<a class="btn-icon" href="logout" target="_parent" title="ออกจากระบบ"><i class="fal fa-power-off"></i></a>
</header>
<div class="container">
	<?php foreach ($spacelist as $project) { ?>
	<div class="box">
		<div class="head">
			<h2><?php echo $project['title'];?></h2>
			<a class="button-option first-right" href="space-user.php?id=<?php echo $project['id'];?>"><i class="fal fa-user-plus"></i>เพิ่มผู้ดูแล</a>
			<a class="button-option" href="editspace/<?php echo $project['id'];?>"><i class="fal fa-cog"></i>แก้ไข</a>
			<?php if(empty($project['line_token'])){?>
			<a class="button-option" href="editspace/<?php echo $project['id'];?>#line_token"><i class="fal fa-exclamation-circle"></i>ใส่ LINE Token</a>
			<?php }?>
		</div>
		<div class="device-list">
			<?php
			foreach ($project['devices'] as $device) {
				$status = ($device['status'] == 'active' ? true : false);
				$notify = ($device['notify'] == 'active' ? true : false);
			?>
			<a class="device-card" id="device-<?php echo $device['id'];?>" a href="device/<?php echo $device['id'];?>">
				<div class="icon"><i class="far fa-thermometer-full"></i></div>
				<div class="temp">n/a</div>
				<div class="name">
					<?php echo $device['name'];?>
					<?php echo ($device['status'] != 'active' ? '<i class="fa fa-lock"></i>' : '');?>
					<?php echo ($device['notify'] != 'active' ? '<i class="fa fa-bell-slash"></i>' : '');?>
					<?php echo (!empty($device['zone_title']) ? ' '.$device['zone_title'] : '');?>
				</div>
				<div class="desc"><?php echo (status ? 'กำลังโหลด' : 'ปิดรับข้อมูล');?></div>
			</a>
			<?php }?>

			<a class="device-card create-button" href="newdevice/space/<?php echo $project['id'];?>">
				<i class="fal fa-plus-circle"></i>
				<p>เพิ่มอุปกรณ์</p>
			</a>
		</div>
	</div>
	<?php }?>
</div>
<input type="hidden" value="<?php echo $space->id;?>" id="space_id">
<?php include'footer.php';?>
<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/timeline.min.js"></script>
<script type="text/javascript" src="js/min/layout.min.js"></script>
<script type="text/javascript" src="js/lib/tippy.all.min.js"></script>
</body>
</html>