<?php
require '../config.php';
header('Content-Type: application/json');
$stmt=$pdo->query("SELECT id,title,category,event_date AS date,location,description FROM events ORDER BY event_date");
echo json_encode($stmt->fetchAll());
