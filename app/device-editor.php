<?php
include_once 'autoload.php';

$device_id = $_GET['id'];

// ดึงข้อมูลอุปกรณ์
$devices->getdevice($device_id);

// เช็คสิทธิ์การเข้าถึง
$hasPermission = $project->hasPermission($user->id,$devices->project_id);

if (!$user_online) { // ไม่ออนไลน์
	header("Location: ".DOMAIN."/login.php?redirect=editdevice&id=".$device_id);
	die();
} else if (!$hasPermission && !empty($devices->id)) { // ไม่มีสิทธิ์เข้าถึงข้อมูล
	header("Location: ".DOMAIN."/permission-error.php");
	die();
} else if (empty($devices->id) && empty($_GET['project'])) { // Device ID ไม่มีในระบบ
	header("Location: ".DOMAIN."/error-400.php");
	die();
}

if (empty($_GET['project']) && isset($devices->project_id)) {
	$project_id = $devices->project_id;
} else { // สร้างอุปกรณ์ใหม่
	$project_id = $_GET['project'];
}

// ดึงข้อมูล Space
$project->get($project_id);
$page_title = (!empty($devices->id) ? 'แก้ไขอุปกรณ์' : 'เพิ่มอุปกรณ์ใหม่');
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
<title><?php echo $page_title;?> - <?php echo $project->title;?></title>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<header class="header">
	<div class="title"><?php echo $page_title;?></div>
	<?php if (!empty($devices->id)) {?>
	<a class="btn-icon" href="device.php?id=<?php echo $devices->id;?>" target="_parent"><i class="fas fa-times"></i></a>
	<?php } else {?>
	<a class="btn-icon" href="index.php" target="_parent"><i class="fas fa-times"></i></a>
	<?php }?>
</header>
<div class="form vertical-center">
	<div class="form-items">
		<label for="name">ชื่ออุปกรณ์</label>
		<input class="input-text" type="text" id="name" value="<?php echo $devices->name;?>" placeholder="ไม่เกิน 15 ตัวอักษร" autofocus>
	</div>
	<div class="form-items">
		<label for="min">อุณหภูมิตั้งค่า</label>
		<div class="inputs">
			<input class="input-text" type="number" id="min" value="<?php echo (!empty($devices->min)?$devices->min:0);?>" placeholder="อุณหภูมิต่ำสุด">
			<input class="input-text" type="number" id="max" value="<?php echo (!empty($devices->max)?$devices->max:5);?>" placeholder="อุณหภูมิสูงสุด">
		</div>
	</div>

	<?php if(!empty($devices->id)){?>
	<div class="form-items">
		<label>
			<div>คีย์ (Token)</div>
			<div class="label-button" id="btn-token-reset">สร้างคีย์ใหม่</div>
		</label>
		<div class="input" title="คีย์สำหรับเชื่อมต่ออุปกรณ์">
			<input class="input-text" type="text" value="<?php echo $devices->token;?>" disabled>
		</div>
	</div>
	<?php }?>
	<div class="form-items hidden">
		<label for="description">รายละเอียด</label>
		<div class="input">
			<textarea class="input-textarea" id="description"><?php echo $devices->description;?></textarea>
		</div>
	</div>

	<?php if(!empty($devices->id)){?>
		<div class="toggle-items">
			<div class="label">รับข้อมูลจากอุปกรณ์</div>
			<div id="btn-status-toggle">
				<?php echo ($devices->status == 'active' ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>');?>
			</div>
		</div>

	<div class="toggle-items" id="notify">
		<div class="label">การแจ้งเตือน</div>
		<div id="btn-notify-toggle">
			<?php echo ($devices->notify == 'active' ? '<i class="fas fa-toggle-on"></i>' : '<i class="fas fa-toggle-off"></i>');?>
		</div>
	</div>
	<?php }?>

	<input type="hidden" id="device_id" value="<?php echo $devices->id?>">
	<input type="hidden" id="project_id" value="<?php echo $project->id;;?>">
	<input type="hidden" id="sign" value="<?php echo $signature->generateSignature('device_editor',SECRET_KEY);?>">

	<div class="form-items">
		<button class="btn-submit" id="btn-save"><?php echo (!empty($devices->id) ? 'บันทึก' : 'เพิ่มอุปกรณ์ใหม่');?></button>
	</div>
</div>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/device.manage.min.js"></script>
<script type="text/javascript" src="js/lib/tippy.all.min.js"></script>
</body>
</html>
