<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

// 包含 JOIN 操作的 SQL 查詢
$sql = "SELECT c.*, u.username FROM comm_comment AS c
    JOIN member_user AS u ON c.user_id = u.user_id
    WHERE c.comm_comment_id LIKE ? OR 
        c.context LIKE ? OR 
        c.status LIKE ? OR 
        c.created_at LIKE ? OR
        c.updated_at LIKE ? OR 
        c.post_id LIKE ? OR
        u.username LIKE ?";

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
