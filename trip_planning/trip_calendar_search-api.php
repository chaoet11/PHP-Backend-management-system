<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT * FROM trip_calendar WHERE 
            calendar_id LIKE ? OR
            trip_plan_id LIKE ? OR 
            primary_trip_detail_id LIKE ? OR 
            secondary_trip_detail_id LIKE ? OR
            tertiary_trip_detail_id LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
