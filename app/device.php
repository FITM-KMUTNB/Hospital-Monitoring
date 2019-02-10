<?php
include_once 'autoload.php';

$device_id = $_GET['id'];

// ดึงข้อมูลอุปกรณ์
$devices->getdevice($device_id);

// เช็คสิทธิ์การเข้าถึง
$hasPermission = $project->hasPermission($user->id,$devices->project_id);

if(!$user_online){ // ไม่ออนไลน์
	// header("Location: ".DOMAIN."/login.php?redirect=device&id=".$device_id);
	// die();
}else if(empty($devices->id)){ // Device ID ไม่มีในระบบ
	header("Location: ".DOMAIN."/error-404.php");
	die();
}else if(!$hasPermission){ // ไม่มีสิทธิ์เข้าถึงข้อมูล
	header("Location: ".DOMAIN."/permission-error.php");
	die();
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
<meta name="description" content="<?php echo DESCRIPTION;?>"/>
<meta property="og:title" content="<?php echo $devices->name;?> - <?php echo TITLE;?>"/>
<meta property="og:description" content="<?php echo DESCRIPTION;?>"/>
<meta property="og:url" content="<?php echo DOMAIN;?>/device/<?php echo $devices->id;?>"/>
<meta property="og:image" content="<?php echo DOMAIN;?>/image/ogimage.jpg"/>
<meta property="og:type" content="website"/>
<meta property="og:site_name" content="<?php echo SITENAME;?>"/>

<meta itemprop="name" content="<?php echo $devices->name;?> - <?php echo TITLE;?>">
<meta itemprop="description" content="<?php echo DESCRIPTION;?>">
<meta itemprop="image" content="<?php echo DOMAIN;?>/image/ogimage.jpg">

<title><?php echo $devices->name;?> - <?php echo TITLE;?></title>

<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<div id="loading-bar"></div>
<div id="filter"></div>

<header class="header">
	<a class="btn-icon" href="index.php" target="_parent"><i class="fas fa-arrow-left"></i></a>
	<div class="title"><?php echo $devices->name;?> <span class="font-color"><?php echo $devices->project_name;?></span></div>
	<?php if ($hasPermission && $user_online) {?>
	<a class="btn-text" href="device-editor.php?id=<?php echo $devices->id;?>" target="_parent">ตั้งค่า</a>
	<?php }?>
</header>

<div class="device-info">
	<div class="info">
		<div class="temperature-current">
			<span class="value" id="tempcurrent"></span>
			<span class="unit font-color">°C</span>
		</div>
		<p class="font-color">
			<span>ล่าสุด <strong id="timecurrent"></strong></span>
			<?php if ($devices->notify != 'active') {?>
			<span title="อุปกรณ์นี้ปิดการแจ้งเตือน"><i class="fas fa-bell-slash"></i></span>
			<?php }?>
			<?php if ($devices->status != 'active') {?>
			<span title="อุปกรณ์นี้ปิดรับข้อมูล"><i class="fas fa-lock"></i></span>
			<?php }?>
		</p>
	</div>
	<div class="graph">
		<canvas id="graph"></canvas>
	</div>

	<h2 class="font-color"><i class="fas fa-temperature-frigid"></i>อุณหภูมิตั้งค่า <strong><?php echo $devices->min;?>°C</strong> ถึง <strong><?php echo $devices->max;?>°C</strong></h2>
	<div class="temperature-stat">
		<div class="box bg-secondary">
			<p class="font-color">อุณหภูมิสูงสุด</p>
			<div>
				<span class="value" id="temphighest">H</span>
				<span class="unit font-color">°C</span>
			</div>
			<p class="font-color">เมื่อ <span id="timehighest"></span></p>
		</div>
		<div class="box bg-secondary">
			<p class="font-color">อุณหภูมิต่ำสุด</p>
			<div>
				<span class="value" id="templowest">L</span>
				<span class="unit font-color">°C</span>
			</div>
			<p class="font-color">ต่ำสุด <span id="timelowest"></span></p>
		</div>
		<div class="box bg-secondary">
			<p class="font-color">อุณหภูมิเฉลี่ย</p>
			<div>
				<span class="value" id="tempaverage">A</span>
				<span class="unit font-color">°C</span>
			</div>
			<p class="font-color">ภายในวันนี้</p>
		</div>
	</div>
	<h2 class="font-color"><i class="fas fa-clipboard-list"></i>อุณหภูมิย้อนหลัง</h2>
	<div class="history" id="historylog">
		<div class="loading">กำลังโหลด...</div>
	</div>
</div>

<input type="hidden" id="device_id" value="<?php echo $devices->id;?>">
<input type="hidden" id="device_min" value="<?php echo $devices->min;?>">
<input type="hidden" id="device_max" value="<?php echo $devices->max;?>">
<input type="hidden" id="device_name" value="<?php echo $devices->name;?>">

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/lib/chart.min.js"></script>
<script type="text/javascript" src="js/min/device.feed.min.js"></script>
<script type="text/javascript" src="js/lib/tippy.all.min.js"></script>
</body>
</html>
