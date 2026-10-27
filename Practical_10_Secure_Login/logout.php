<?php
require_once __DIR__.'/../auth.php';
$uid=(int)($_SESSION['student_id']??0);if($uid)log_action($conn,$uid,'logout','User logged out');
$_SESSION=[];if(ini_get('session.use_cookies')){$p=session_get_cookie_params();setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']);}session_destroy();header('Location: login.php');exit;
