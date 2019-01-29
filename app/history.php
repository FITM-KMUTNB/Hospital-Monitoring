<?php
include_once 'autoload.php';
if(!$user_online){
	header("Location: login.php");
	die();
}

$perpage = 200;

$devices->getdevice($_GET['device']);

$page = $_GET['page'];

if(empty($page) || $page < 0) $page = 1;

$start 		= ($perpage * $page) - $perpage;

$total 		= $log->countHistory($devices->id);
$logdata 	= $log->historylog($devices->id,$start,$perpage);

$totalpages = ceil($total / $perpage);
$currentPage = (empty($_GET['page'])? 1 : $_GET['page']);

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
<title><?php echo $devices->name;?><?php echo (!empty($devices->zone_title)?' ('.$devices->zone_title.')':'');?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<header class="header">
	<div class="navigation">
		<a href="device.php?device=<?php echo $devices->id;?>" class="navi-items" target="_parent"><i class="fa fa-arrow-left" aria-hidden="true"></i>กลับไปหน้าอุปกรณ์</a>
	</div>
</header>

<div class="head">
	<h1><?php echo $devices->name;?><?php echo (!empty($devices->zone_title)?' ('.$devices->zone_title.')':'');?></h1>
	<div class="description">ต่ำสุด <span class="temps"><?php echo $devices->min;?><span class="unit">°</span></span> ~ สูงสุด <span class="temps"><?php echo $devices->max;?><span class="unit">°</span></span> Total <span class="temps"><?php echo number_format($total);?></span> items</div>
</div>

<div class="log-history">
	<?php if($totalpages > 1){?>
	<div class="pagination">
		<?php for($i=1;$i<=$totalpages;$i++){?>
		<a class="btn-page <?php echo ($currentPage == $i?'-active':'');?>" href="history.php?device=<?php echo $devices->id?>&page=<?php echo $i;?>"><?php echo $i;?></a>
		<?php }?>
	</div>
	<?php }?>

	<div>
	<?php foreach($logdata as $var) {?>
	<div class="items <?php echo ($var['log_temp'] > $devices->max || $var['log_temp'] < $devices->min?'-alert':'');?>">
		<div class="date"><?php echo $var['log_datetime'];?></div>
		<div class="temp"><?php echo $var['log_temp'];?><span class="unit">°</span></div>
	</div>
	<?php }?>
	</div>

	<?php if($totalpages > 1){?>
	<div class="pagination">
		<?php for($i=1;$i<=$totalpages;$i++){?>
		<a class="btn-page <?php echo ($currentPage == $i?'-active':'');?>" href="history.php?device=<?php echo $devices->id?>&page=<?php echo $i;?>"><?php echo $i;?></a>
		<?php }?>
	</div>
	<?php }?>
</div>
</body>
</html>