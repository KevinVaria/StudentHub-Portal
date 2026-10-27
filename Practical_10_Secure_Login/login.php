<?php
require_once __DIR__.'/../auth.php';
if(!empty($_SESSION['student_id'])) redirect(($_SESSION['role']??'student')==='admin'?'../Practical_14_Admin_Dashboard/index.php':'dashboard.php');
$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
 $email=strtolower(trim($_POST['email']??''));$password=$_POST['password']??'';
 if(!filter_var($email,FILTER_VALIDATE_EMAIL)||$password==='') $error='Enter a valid email and password.';
 else {$s=$conn->prepare('SELECT student_id,name,email,password_hash,role FROM students WHERE email=? LIMIT 1');$s->bind_param('s',$email);$s->execute();$user=$s->get_result()->fetch_assoc();$s->close();
  if($user&&password_verify($password,$user['password_hash'])){session_regenerate_id(true);$_SESSION['student_id']=(int)$user['student_id'];$_SESSION['name']=$user['name'];$_SESSION['email']=$user['email'];$_SESSION['role']=$user['role']??'student';$_SESSION['last_activity']=time();log_action($conn,(int)$user['student_id'],'login','Successful login');redirect($_SESSION['role']==='admin'?'../Practical_14_Admin_Dashboard/index.php':'dashboard.php');}
  else $error='Invalid email or password.';
 }
}
page_start('Practical 10 — Secure Login');if($error)echo '<p class="flash error">'.e($error).'</p>';
?>
<form method="post"><label for="email">Email</label><input id="email" type="email" name="email" required autocomplete="username" value="<?=e($_POST['email']??'')?>"><label for="password">Password</label><input id="password" type="password" name="password" required autocomplete="current-password"><button type="submit">Log in</button></form><p>New here? <a href="../Practical_9_Secure_Registration/register.php">Register</a>.</p><?php page_end(); ?>
