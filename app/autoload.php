<?php
// Start Session and Cookie.
session_start();
// session_regenerate_id(true); // regenerated the session, delete the old one.
ob_start();
define('StTime', microtime(true));

// Time Zone ////////////////////////////
date_default_timezone_set('Asia/Bangkok');
error_reporting(E_ALL ^ E_NOTICE);

include_once'config/config.php';

define("VERSION" 	,'1.0');

// Mobile Detect is a lightweight PHP
// include_once'plugin/mobile-detect/mobile_detect.php';
// include_once'plugin/mobile-detect/desktop_detect.php';

// Database (PDO class) ///////////////
include_once'class/database.class.php';
include_once'class/devices.class.php';
include_once'class/user.class.php';
include_once'class/log.class.php';
include_once'class/api.class.php';
include_once'class/notify.class.php';
include_once'class/space.class.php';
include_once'class/signature.class.php';

$devices 	= new Devices;
$log 		= new Log;
$api 		= new API;
$user 		= new User;
$notify 	= new Notify;
$space 		= new Space;
$signature 	= new Signature;

$user->sec_session_start();
$user_online = $user->loginChecking();

// Device define data
define('DEVICE_TYPE',		$deviceType);
define('DEVICE_MODEL',		$deviceModel);
define('DEVICE_OS', 		$deviceOS);
define('DEVICE_BROWSER',	$deviceBrowser);
?>