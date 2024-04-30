<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT s.*, u.username FROM comm_saved AS s
    JOIN member_user AS u ON s.user_id = u.user_id
    WHERE s.comm_saved_id LIKE ? OR 
        s.post_id LIKE ? OR 
        u.username LIKE ?";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
