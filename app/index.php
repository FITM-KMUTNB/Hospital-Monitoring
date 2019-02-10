<?php
include_once 'autoload.php';
if (!$user_online) {
	header('Location: login.php');
	die();
}
$projectlist = $devices->listDevices($user->id);
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
<title><?php echo TITLE.' '.VERSION;?></title>
<link rel="stylesheet" type="text/css" href="css/style.css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>
</head>
<body>
<div id="loading-bar"></div>
<div id="filter"></div>
<header class="header">
	<a class="logo-icon" href="index.php" target="_parent"><img src="image/logo.png" alt="logo"></a>
	<?php if (count($projectlist) > 0) {?>
	<a class="btn-text create" href="project-editor.php" target="_parent">สร้างโปรเจค</a>
	<?php }?>
</header>
<div class="container">
	<?php if (count($projectlist) > 0) {?>
	<?php foreach ($projectlist as $project) { ?>
	<div class="box">
		<div class="head">
			<div class="title"><?php echo $project['title'];?></div>
			<a href="project-editor.php?id=<?php echo $project['id'];?>">ตั้งค่า</a>
		</div>
		<div class="device-list">
			<?php
			foreach ($project['devices'] as $device) {
				$status = ($device['status'] == 'active' ? true : false);
				$notify = ($device['notify'] == 'active' ? true : false);
			?>
			<a class="device-card" id="device-<?php echo $device['id'];?>" a href="device.php?id=<?php echo $device['id'];?>">
				<div class="name"><?php echo $device['name'];?></div>
				<div class="temp">n/a</div>
				<div class="info">
					<div class="updated"><?php echo ($status ? 'กำลังโหลด' : 'ปิดรับข้อมูล');?></div>
					<?php if ($device['notify'] != 'active') {?>
					<div class="icon"><i class="fas fa-bell-slash"></i></div>
					<?php }?>
					<div class="icon status-icon"><i class="fas fa-thermometer-full"></i></div>
				</div>
			</a>
			<?php }?>

			<a class="device-card create-button" href="device-editor.php?project=<?php echo $project['id'];?>">
				<p>เพิ่มอุปกรณ์</p>
			</a>
		</div>
	</div>
	<?php }?>
	<?php } else {?>
	<div class="box-empty">
		<h1>FITM Monitoring</h1>
		<p>ซอฟต์แวร์ที่ช่วยให้อุปกรณ์ที่เชื่อมต่อสามารถทำงานร่วมกับแอปพลิเคชัน จัดระเบียบ ตรวจสอบจากระยะไกล ได้อย่างปลอดภัยและง่ายดาย</p>
		<a href="project-editor.php" target="_parent">สร้างโปรเจคใหม่</a>
	</div>
	<?php }?>
</div>
<input type="hidden" value="<?php echo $user->id;?>" id="project_id">
<?php
if (count($projectlist) > 0) {
	include'footer.php';
}
?>
<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/device.timeline.min.js"></script>
<script type="text/javascript" src="js/lib/tippy.all.min.js"></script>
</body>
</html>