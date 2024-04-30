<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM trip_saved WHERE 
            trip_saved_id LIKE ? OR
            trip_plan_id LIKE ? OR 
            user_id LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
