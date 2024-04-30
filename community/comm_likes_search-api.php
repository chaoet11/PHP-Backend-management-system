<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT l.*, u.username FROM comm_likes AS l
    JOIN member_user AS u ON l.user_id = u.user_id
    WHERE l.comm_likes_id LIKE ? OR 
        l.post_id LIKE ? OR 
        u.username LIKE ?";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
