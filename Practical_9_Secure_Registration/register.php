<?php
require_once __DIR__.'/../auth.php';
if (!empty($_SESSION['student_id'])) redirect(($_SESSION['role']??'student')==='admin'?'../Practical_14_Admin_Dashboard/index.php':'../Practical_10_Secure_Login/dashboard.php');
$errors=[]; $row=['name'=>'','email'=>'','mobile'=>'','course'=>'BCA','year'=>'1','gender'=>'Prefer not to say'];
if($_SERVER['REQUEST_METHOD']==='POST'){
 foreach(['name','email','mobile','course','year','gender'] as $k) $row[$k]=trim((string)($_POST[$k]??$row[$k]));
 $password=$_POST['password']??''; $confirm=$_POST['confirm_password']??'';
 if(mb_strlen($row['name'])<2||mb_strlen($row['name'])>100) $errors[]='Name must be 2–100 characters.';
 if(!filter_var($row['email'],FILTER_VALIDATE_EMAIL)) $errors[]='Enter a valid email address.';
 if(!preg_match('/^[0-9]{10,15}$/',$row['mobile'])) $errors[]='Mobile must contain 10–15 digits.';
 if(!in_array($row['course'],['BCA','B.Tech','BBA','MCA'],true)||!in_array((int)$row['year'],[1,2,3,4],true)) $errors[]='Select a valid course and year.';
 if(!in_array($row['gender'],['Female','Male','Other','Prefer not to say'],true)) $errors[]='Select a valid gender option.';
 if(strlen($password)<8||!preg_match('/[A-Za-z]/',$password)||!preg_match('/[0-9]/',$password)) $errors[]='Password must be at least 8 characters and include a letter and a number.';
 if($password!==$confirm) $errors[]='Passwords do not match.';
 if(!$errors){$s=$conn->prepare('SELECT student_id FROM students WHERE email=? LIMIT 1');$s->bind_param('s',$row['email']);$s->execute();$exists=$s->get_result()->num_rows>0;$s->close();if($exists)$errors[]='This email is already registered.';}
 if(!$errors){$hash=password_hash($password,PASSWORD_DEFAULT);$year=(int)$row['year'];$s=$conn->prepare('INSERT INTO students(name,email,mobile,password_hash,course,year,gender,role) VALUES(?,?,?,?,?,?,?,\'student\')');$s->bind_param('sssssis',$row['name'],$row['email'],$row['mobile'],$hash,$row['course'],$year,$row['gender']);try{$s->execute();$newId=(int)$conn->insert_id;$s->close();log_action($conn,$newId,'register','New student registered');set_flash('Registration successful. You can now log in.');redirect('../Practical_10_Secure_Login/login.php');}catch(mysqli_sql_exception $ex){$errors[]=$ex->getCode()===1062?'This email is already registered.':'Registration could not be saved. Check the database schema.';}}
}
page_start('Practical 9 — Secure Registration');
if($errors){echo '<ul class="flash error">';foreach($errors as $er)echo '<li>'.e($er).'</li>';echo '</ul>';}
?>
<form method="post"><label>Full name</label><input name="name" required maxlength="100" value="<?=e($row['name'])?>"><label>Email</label><input type="email" name="email" required value="<?=e($row['email'])?>"><label>Mobile</label><input name="mobile" required pattern="[0-9]{10,15}" value="<?=e($row['mobile'])?>"><label>Password</label><input type="password" name="password" required minlength="8" autocomplete="new-password"><label>Confirm password</label><input type="password" name="confirm_password" required minlength="8" autocomplete="new-password"><label>Course</label><select name="course"><?php foreach(['BCA','B.Tech','BBA','MCA'] as $c):?><option value="<?=e($c)?>" <?=$row['course']===$c?'selected':''?>><?=e($c)?></option><?php endforeach;?></select><label>Year</label><select name="year"><?php for($y=1;$y<=4;$y++):?><option value="<?=$y?>" <?=(int)$row['year']===$y?'selected':''?> >Year <?=$y?></option><?php endfor;?></select><label>Gender</label><select name="gender"><?php foreach(['Female','Male','Other','Prefer not to say'] as $g):?><option value="<?=e($g)?>" <?=$row['gender']===$g?'selected':''?>><?=e($g)?></option><?php endforeach;?></select><button type="submit">Create account</button></form><p>Already registered? <a href="../Practical_10_Secure_Login/login.php">Log in</a>.</p><?php page_end(); ?>
