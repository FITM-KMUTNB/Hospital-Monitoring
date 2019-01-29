<?php require_once 'autoload.php';?>
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
<title>ERROR 404</title>
<base href="<?php echo DOMAIN;?>">

<!-- CSS -->
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" type="text/css" href="plugin/fontawesome-pro-5.6/css/all.min.css"/>

</head>
<body>
<div class="message-box">
	<div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
	<div class="msg">ไม่พบหน้าที่คุณต้องการ (404 Not Found)</div>
	<div class="control"><a class="btn" href="<?php echo DOMAIN;?>">กลับไปหน้าแรก</a></div>
</div>
</body>
</html>