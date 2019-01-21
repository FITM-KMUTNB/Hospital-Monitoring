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
<title>ผู้ดูแลกโปรเจ็ค - <?php echo $space->title;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.0.9/css/fontawesome-all.min.css"/>

</head>
<body>
<header class="header -nonfixed">
	<a href="space/<?php echo $space->id;?>" class="btn-icon" target="_parent"><i class="fal fa-arrow-left" aria-hidden="true"></i></a>
</header>
<div class="form">
	<h2>ผู้ดูแลโปรเจ็ค</h2>

	<?php if(!empty($space->id) && isset($space->id)){?>
	<div class="form-items">
		<label>ผู้ดูแล</label>
		<div class="lists">
			<?php foreach ($allAdmin as $var) {?>
			<div class="list-items" id="admin-<?php echo $var['user_id'];?>" data-user="<?php echo $var['user_id'];?>" title="<?php echo $var['email'];?>">
				<div class="c"><?php echo $var['fname'].' '.$var['lname'];?></div>
				<?php if ($var['permission'] == 1) {?>
				<div class="btn"><i class="fal fa-lock-alt"></i></div>
				<?php } else {?>
				<span class="btn btn-amin-delete"><i class="fal fa-times"></i></span>
				<?php }?>
			</div>
			<?php }?>
		</div>
	</div>

	<div class="form-items">
		<label for="email_admin">เพิ่มผู้ดูแล</label>
		<input type="text" id="email_admin" class="input-text" placeholder="เชิญผู้ดูแลด้วยอีเมล...">
	</div>
	<div class="form-items">
		<button class="btn-submit" id="btn-admin-add">เพิ่มผู้ดูแล</button>
	</div>

	<div class="form-items">
		<label for="invite_url">เชิญผู้ดูแล</label>
		<div class="input">
			<input type="text" class="input-text" id="invite_url" value="<?php echo DOMAIN.'/invite?c='.$space->invite_code;?>" disabled>
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
