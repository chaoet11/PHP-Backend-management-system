<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM trip_details WHERE 
            trip_detail_id LIKE ? OR
            trip_plan_id LIKE ? OR 
            block LIKE ? OR 
            movie_id LIKE ? OR
            bar_id LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
