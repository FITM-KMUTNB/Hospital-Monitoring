<?php
include_once 'autoload.php';
if (!$user_online) {
	header("Location: ".DOMAIN."/login.php");
	die();
}

$project->get($_GET['id']);
$hasPermission = $project->hasPermission($user->id,$project->id);

if(!$hasPermission && !empty($_GET['id'])){
	header("Location: ".DOMAIN."/permission-error.php");
	die();
}

if (!empty($project->id) && isset($project->id)) {
	$allAdmin = $project->listAdmin($project->id);
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
<title>ผู้ดูแลกโปรเจ็ค - <?php echo $project->title;?></title>

<base href="<?php echo DOMAIN;?>">
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<header class="header">
	<div class="title">ผู้ดูแลโปรเจ็ค</div>
	<a class="btn-icon" href="/" target="_parent"><i class="fas fa-times"></i></a>
</header>
<div class="form">
	<?php if(!empty($project->id) && isset($project->id)){?>
	<div class="form-items">
		<label>ผู้ดูแล</label>
		<div class="lists">
			<?php foreach ($allAdmin as $var) {?>
			<div class="list-items" id="admin-<?php echo $var['user_id'];?>" data-user="<?php echo $var['user_id'];?>" title="<?php echo $var['email'];?>">
				<div class="c"><?php echo $var['fname'].' '.$var['lname'];?></div>
				<?php if ($var['permission'] == 1) {?>
				<div class="btn"><i class="fas fa-lock-alt"></i></div>
				<?php } else {?>
				<span class="btn btn-amin-delete"><i class="fas fa-times"></i></span>
				<?php }?>
			</div>
			<?php }?>
		</div>
	</div>

	<div class="form-items">
		<label for="email_admin">เพิ่มผู้ดูแล</label>
		<input type="text" id="email_admin" class="input-text" placeholder="เชิญผู้ดูแลด้วยอีเมล">
	</div>
	<div class="form-items">
		<button class="btn-submit" id="btn-admin-add">เพิ่มผู้ดูแล</button>
	</div>

	<div class="form-items">
		<label for="invite_url">เชิญผู้ดูแล</label>
		<div class="input">
			<input type="text" class="input-text" id="invite_url" value="<?php echo DOMAIN.'/invite.php?c='.$project->invite_code;?>" disabled>
		</div>
	</div>
	<?php }?>

	<input type="hidden" id="project_id" value="<?php echo $project->id?>">
	<input type="hidden" id="sign" name="sign" value="<?php echo $signature->generateSignature('project_editor',SECRET_KEY);?>">
</div>

<script type="text/javascript" src="js/lib/jquery-3.2.1.min.js"></script>
<script type="text/javascript" src="js/min/project.min.js"></script>
</body>
</html>
