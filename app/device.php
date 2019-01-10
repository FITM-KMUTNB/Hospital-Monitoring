<?php
include_once 'autoload.php';

$device_id = $_GET['device'];

// ดึงข้อมูลอุปกรณ์
$devices->getdevice($device_id);

// เช็คสิทธิ์การเข้าถึง
$hasPermission = $space->hasPermission($user->id,$devices->space_id);

if(!$user_online){ // ไม่ออนไลน์
	// header("Location: ".DOMAIN."/login.php?redirect=device&id=".$device_id);
	// die();
}else if(empty($devices->id)){ // Device ID ไม่มีในระบบ
	header("Location: ".DOMAIN."/error-400.php");
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

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>

</head>
<body>
<div id="loading-bar"></div>
<div id="filter"></div>

<div id="disconnect-bar"><i class="fa fa-refresh fa-spin" aria-hidden="true"></i>ขาดการติดต่อ!</div>

<header class="header">
	<a class="btn-icon" href="space/<?php echo $devices->space_id;?>" target="_parent"><i class="fal fa-arrow-left"></i></a>
	<?php if($hasPermission && $user_online){?>
	<a class="btn-icon right" href="editdevice/<?php echo $devices->id;?>" target="_parent"><i class="fal fa-cog"></i></a>
	<?php }?>
</header>

<div class="device-info">
	<h1><?php echo ($devices->notify!='active'?'<i class="fal fa-bell-slash"></i> ':'');?><?php echo $devices->name;?> <?php echo $devices->zone_title;?></h1>
	<div class="current">
		<div class="current-now">
			<div class="v" id="tempcurrent">C</div>
			<div class="c">ล่าสุด <span id="timecurrent"></span></div>
		</div>
		<div class="current-items -mini -highest">
			<div class="v" id="temphighest">H</div>
			<div class="c">สูงสุด <span id="timehighest"></span></div>
		</div>
		<div class="current-items -mini -lowest">
			<div class="v" id="templowest">L</div>
			<div class="c">ต่ำสุด <span id="timelowest"></span></div>
		</div>
	</div>

	<div class="info">
		<div class="info-items">
			<div class="c">กลุ่ม</div>
			<div class="v"><a href="space/<?php echo $devices->space_id;?>"><?php echo $devices->space_name;?><i class="far fa-link"></i></a></div>
		</div>

		<?php if(!empty($devices->zone_id)){?>
		<div class="info-items">
			<div class="c">สถานที่</div>
			<div class="v"><a href="space/<?php echo $devices->space_id;?>/<?php echo $devices->zone_id;?>"><?php echo $devices->zone_title;?><i class="far fa-link"></i></a></div>
		</div>
		<?php }?>
		
		<div class="info-items">
			<div class="c">อุณหภูมิตั้งค่า</div>
			<div class="v"><span class="temps"><?php echo $devices->min;?>°</span> ถึง <span class="temps"><?php echo $devices->max;?>°</span></div>
		</div>
	</div>

	<div class="graph">
		<canvas id="graph"></canvas>
	</div>
</div>

<div class="device-history">
	<div class="history" id="historylog">
		<div class="loading">กำลังโหลด...</div>
	</div>
</div>

<input type="hidden" id="device_id" value="<?php echo $devices->id;?>">
<input type="hidden" id="device_min" value="<?php echo $devices->min;?>">
<input type="hidden" id="device_max" value="<?php echo $devices->max;?>">
<input type="hidden" id="device_name" value="<?php echo $devices->name;?>">
<input type="hidden" id="site_title" value="<?php echo TITLE;?>">

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/lib/chart.min.js"></script>
<script type="text/javascript" src="js/min/device.feed.min.js"></script>
</body>
</html>
