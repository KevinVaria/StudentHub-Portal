<?php require_once __DIR__.'/../auth.php'; require_login(); page_start('Practical 10 — User Dashboard'); ?>
<p>Welcome, <strong><?=e($_SESSION['name']??'Student')?></strong>.</p><p>Email: <?=e($_SESSION['email']??'')?></p><p>Role: <?=e($_SESSION['role']??'student')?></p>
<?php if(($_SESSION['role']??'student')==='admin'):?><p><a class="btn" href="../Practical_14_Admin_Dashboard/index.php">Open Admin Dashboard</a></p><?php endif;?>
<form method="post" action="logout.php"><button type="submit">Log out</button></form><?php page_end(); ?>
