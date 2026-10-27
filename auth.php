<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    ini_set('session.use_strict_mode', '1');
    session_start();
}
require_once __DIR__ . '/config.php';
function e($value): string { return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8'); }
function redirect(string $url): never { header('Location: '.$url); exit; }
function set_flash(string $message, string $type='success'): void { $_SESSION['_flash']=['message'=>$message,'type'=>$type]; }
function page_start(string $title): void {
    $flash=$_SESSION['_flash']??null; unset($_SESSION['_flash']);
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>'.e($title).'</title><style>
    *{box-sizing:border-box}body{font-family:Arial,sans-serif;margin:0;background:#f1f5f9;color:#172033}header{background:#1d4ed8;color:white;padding:18px 24px}header a{color:white;margin-right:14px}main{max-width:1100px;margin:24px auto;padding:24px;background:white;border-radius:12px;box-shadow:0 4px 16px #0000000d}h1,h2{margin-top:0}label{display:block;margin:14px 0 6px;font-weight:600}input,select,textarea{font:inherit;padding:10px;width:100%;border:1px solid #cbd5e1;border-radius:6px;margin-bottom:8px}button,.btn{display:inline-block;background:#2563eb;color:white;border:0;padding:10px 14px;border-radius:6px;text-decoration:none;cursor:pointer;margin:4px 3px 4px 0}button.danger{background:#b91c1c}.flash{padding:12px;border-radius:6px;margin:12px 0}.success{background:#dcfce7;color:#166534}.error{background:#fee2e2;color:#991b1b}table{width:100%;border-collapse:collapse;margin:18px 0;display:block;overflow-x:auto}th,td{padding:10px;border:1px solid #e2e8f0;text-align:left}th{background:#eff6ff}.inline{display:inline}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:12px}.stat{background:#eff6ff;padding:18px;border-radius:8px}.stat strong{display:block;font-size:28px;margin-bottom:5px}.actions{display:flex;gap:8px;flex-wrap:wrap}footer{text-align:center;padding:20px;color:#64748b}
    </style></head><body><header><strong>StudentHub</strong> &nbsp; <a href="../Practical_3_Responsive_CSS/index.html">Home</a>';
    if (!empty($_SESSION['student_id'])) { echo '<a href="../Practical_10_Secure_Login/dashboard.php">Dashboard</a>'; if(($_SESSION['role']??'student')==='admin') echo '<a href="../Practical_14_Admin_Dashboard/index.php">Admin</a>'; echo '<span style="float:right">'.e($_SESSION['name']??'').'</span>'; }
    echo '</header><main><h1>'.e($title).'</h1>';
    if ($flash) echo '<div class="flash '.e($flash['type']).'">'.e($flash['message']).'</div>';
}
function page_end(): void { echo '</main><footer>StudentHub — PHP & MySQL practical project</footer></body></html>'; }
function require_login(): void {
    if (empty($_SESSION['student_id'])) { set_flash('Please log in to continue.','error'); redirect('../Practical_10_Secure_Login/login.php'); }
    $timeout=1800; $last=(int)($_SESSION['last_activity']??0);
    if (!$last || time()-$last>$timeout) { $_SESSION=[]; if(ini_get('session.use_cookies')) { $p=session_get_cookie_params(); setcookie(session_name(),'',time()-42000,$p['path'],$p['domain'],$p['secure'],$p['httponly']); } session_destroy(); header('Location: ../Practical_10_Secure_Login/login.php'); exit; }
    $_SESSION['last_activity']=time();
}
function require_admin(): void { require_login(); if(($_SESSION['role']??'student')!=='admin'){http_response_code(403);exit('403 Forbidden: administrator access required.');} }
function log_action(mysqli $conn, int $studentId, string $action, string $details=''): void {
    try { $ip=substr((string)($_SERVER['REMOTE_ADDR']??''),0,45); $s=$conn->prepare('INSERT INTO audit_logs (student_id, action, details, ip_address) VALUES (?,?,?,?)'); $s->bind_param('isss',$studentId,$action,$details,$ip); $s->execute(); $s->close(); } catch (Throwable $e) { /* Logging must not break the primary action. */ }
}
