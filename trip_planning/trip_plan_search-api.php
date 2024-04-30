<?php

require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';


$sql = "SELECT c.*, u.username FROM trip_plans AS c
        JOIN member_user AS u ON c.user_id = u.user_id
        WHERE c.trip_plan_id LIKE ? OR 
            u.username LIKE ? OR 
            c.trip_title LIKE ? OR 
            c.trip_content LIKE ? OR
            c.trip_description LIKE ? OR 
            c.trip_notes LIKE ? OR
            c.trip_date LIKE ? OR
            c.trip_draft LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
