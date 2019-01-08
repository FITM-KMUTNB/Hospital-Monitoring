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
<title>ผู้ดูแลกลุ่ม - <?php echo $space->title;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>

</head>
<body>
<header class="header -nonfixed">
	<a href="space/<?php echo $space->id;?>" class="btn" target="_parent"><i class="fa fa-arrow-left" aria-hidden="true"></i></a>
	<div class="mobile-title"><a href="index.php">ผู้ดูแลกลุ่ม</a></div>
</header>
<div class="form">
	<h2>ผู้ดูแลกลุ่ม</h2>

	<?php if(!empty($space->id) && isset($space->id)){?>
	<div class="form-items">
		<div class="label">ผู้ดูแล</div>
		<div class="input">
			<div class="list">
				<?php foreach ($allAdmin as $var) {?>
				<div class="list-items" id="admin-<?php echo $var['user_id'];?>" data-user="<?php echo $var['user_id'];?>" title="<?php echo $var['email'];?>">
					<span class="c"><?php echo ($var['permission'] == 1?'<i class="fa fa-user" aria-hidden="true"></i>':'<i class="fa fa-user-o" aria-hidden="true"></i>');?><?php echo $var['fname'].' '.$var['lname'];?></span>
					<?php if($var['permission'] != 1){?>
					<span class="btn btn-amin-delete"><i class="fa fa-times" aria-hidden="true"></i></span>
					<?php }?>
				</div>
				<?php }?>
			</div>
			<div class="list-input">
				<input type="text" id="email_admin" class="input-list-text" placeholder="เชิญผู้ดูแลด้วยอีเมล...">
				<div class="btn" id="btn-admin-add"><i class="fa fa-user-plus" aria-hidden="true"></i></div>
			</div>
		</div>
	</div>

	<div class="form-items">
		<div class="label">เชิญผู้ดูแล</div>
		<div class="input">
			<input type="text" class="input-text" value="<?php echo DOMAIN.'/invite?c='.$space->invite_code;?>">
		</div>
	</div>
	<?php }?>

	<input type="hidden" id="space_id" value="<?php echo $space->id?>">
	<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('space_editor',SECRET_KEY);?>">
</div>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/space.min.js"></script>
</body>
</html>
