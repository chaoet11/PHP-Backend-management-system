<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

//use member_user > username to search
$sql = "SELECT 
            f.friendship_id,
            m1.username AS user_id1,
            m2.username AS user_id2,
            f.friendship_status,
            f.created_at,
            f.updated_at
        FROM 
            friendships f
        LEFT JOIN 
            member_user m1 ON f.user_id1 = m1.user_id
        LEFT JOIN 
            member_user m2 ON f.user_id2 = m2.user_id
        WHERE 
            f.friendship_id LIKE ? OR 
            m1.username LIKE ? OR 
            m2.username LIKE ? OR 
            f.friendship_status LIKE ? OR
            f.created_at LIKE ? OR
            f.updated_at LIKE ?";
$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
?>