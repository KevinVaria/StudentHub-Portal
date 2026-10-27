<?php
function clean($v){return trim(strip_tags((string)$v));}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: register.html');exit;}
$name=clean($_POST['name']??''); $email=filter_var(trim($_POST['email']??''),FILTER_SANITIZE_EMAIL);
$mobile=clean($_POST['mobile']??''); $password=$_POST['password']??''; $confirm=$_POST['confirmPassword']??'';
$course=clean($_POST['course']??''); $year=clean($_POST['year']??''); $gender=clean($_POST['gender']??'');
$errors=[];
if(!preg_match('/^[A-Za-z ]{2,50}$/',$name))$errors[]='Invalid name.';
if(!filter_var($email,FILTER_VALIDATE_EMAIL))$errors[]='Invalid email.';
if(!preg_match('/^[6-9][0-9]{9}$/',$mobile))$errors[]='Invalid Indian mobile number.';
if(strlen($password)<8 || !preg_match('/[A-Za-z]/',$password) || !preg_match('/[0-9]/',$password))$errors[]='Password must be 8+ characters with a letter and number.';
if($password!==$confirm)$errors[]='Passwords do not match.';
if($course===''||$year===''||$gender==='')$errors[]='Course, year and gender are required.';
if(($_POST['terms']??'')!=='accepted')$errors[]='Terms must be accepted.';
if($errors){$title='Registration Error';$body='<h2>Please fix these errors</h2><ul>';foreach($errors as $e)$body.='<li>'.htmlspecialchars($e).'</li>'; $body.='</ul><a href="register.html">Back</a>';}
else{
$dir=__DIR__.'/data'; if(!is_dir($dir))mkdir($dir,0777,true);
$hash=password_hash($password,PASSWORD_DEFAULT); $row=[$name,$email,$mobile,$course,$year,$gender,date('Y-m-d H:i:s')];
$csv=$dir.'/students.csv'; $new=!file_exists($csv); $fp=fopen($csv,'a'); if($new)fputcsv($fp,['name','email','mobile','course','year','gender','created_at']);fputcsv($fp,$row);fclose($fp);
$json=$dir.'/students.json'; $records=file_exists($json)?json_decode(file_get_contents($json),true):[]; if(!is_array($records))$records=[];$row['password_hash']=$hash;$records[]=['name'=>$name,'email'=>$email,'mobile'=>$mobile,'course'=>$course,'year'=>$year,'gender'=>$gender,'created_at'=>$row[6],'password_hash'=>$hash];file_put_contents($json,json_encode($records,JSON_PRETTY_PRINT));
$title='Registration Successful';$body='<h2>Registration successful!</h2><p>Your details were validated and stored.</p><a href="register.html">Register another student</a>';
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><title><?=htmlspecialchars($title)?> - StudentHub</title><link rel="stylesheet" href="style.css"></head><body><main><?=$body?></main></body></html>