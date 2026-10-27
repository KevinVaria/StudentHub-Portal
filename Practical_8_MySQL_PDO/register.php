<?php
require 'config.php';
function clean($v){return trim(strip_tags((string)$v));}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: register.html');exit;}
$name=clean($_POST['name']??'');$email=filter_var(trim($_POST['email']??''),FILTER_VALIDATE_EMAIL);
$mobile=clean($_POST['mobile']??'');$password=$_POST['password']??'';$confirm=$_POST['confirmPassword']??'';
$course=clean($_POST['course']??'');$year=(int)($_POST['year']??0);$gender=clean($_POST['gender']??'');$errors=[];
if(!preg_match('/^[A-Za-z ]{2,50}$/',$name))$errors[]='Invalid name.';
if(!$email)$errors[]='Invalid email.';
if(!preg_match('/^[6-9][0-9]{9}$/',$mobile))$errors[]='Invalid Indian mobile number.';
if(strlen($password)<8 || !preg_match('/[A-Za-z]/',$password)||!preg_match('/[0-9]/',$password))$errors[]='Password must be 8+ characters with a letter and number.';
if($password!==$confirm)$errors[]='Passwords do not match.';
if($course===''||$year<1||$year>4||$gender==='')$errors[]='Course, year and gender are required.';
if(($_POST['terms']??'')!=='accepted')$errors[]='Terms must be accepted.';
if(!$errors){try{
$stmt=$pdo->prepare("INSERT INTO students(name,email,mobile,password_hash,course,year,gender) VALUES(?,?,?,?,?,?,?)");
$stmt->execute([$name,$email,$mobile,password_hash($password,PASSWORD_DEFAULT),$course,$year,$gender]);
$message="Registration successful!";
}catch(PDOException $e){$message=$e->getCode()==23000?"Email already registered.":"Could not save registration.";}}
else{$message=implode(" ",$errors);}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><title>Registration - StudentHub</title><link rel="stylesheet" href="style.css"></head><body><main><h1><?=htmlspecialchars($message)?></h1><a href="register.html">Back to registration</a></main></body></html>