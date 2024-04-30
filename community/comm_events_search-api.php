<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT e.*, u.username FROM comm_events AS e
    JOIN member_user AS u ON e.user_id = u.user_id
    WHERE e.comm_event_id LIKE ? OR 
        e.title LIKE ? OR 
        e.description LIKE ? OR 
        e.status LIKE ? OR
        e.location LIKE ? OR 
        e.start_time LIKE ? OR 
        e.end_time LIKE ? OR
        e.created_at LIKE ? OR
        e.updated_at LIKE ? OR
        u.username LIKE ?";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
