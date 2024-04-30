<?php

require '../parts/db_connect.php'; 

header('Content-Type: application/json'); 

$category = isset($_GET['category']) ? $_GET['category'] : ''; // 從URL參數獲取分類


if ($category) {
    // 指定分類
    //$sql = "SELECT * FROM friendships WHERE friendship_status LIKE ?";
    //連到member_user table > username，將user_id link username
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
            f.friendship_status LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%{$category}%"]);
} else {
    // 沒有指定分類，顯示所有資訊
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
                member_user m2 ON f.user_id2 = m2.user_id";
    $stmt = $pdo->query($sql);
}

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode([
    'data' => $results
]);

?>