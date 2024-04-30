<?php
require '../parts/db_connect.php'; // 引入數據庫連接

header('Content-Type: application/json'); // 設置返回的數據類型為JSON

$category = isset($_GET['category']) ? $_GET['category'] : ''; // 從URL參數獲取分類

// SQL 查詢包含 JOIN 語句和圖片數據
if ($category) {
    // 如果有指定分類，則添加WHERE條件並連接 mpi.user_id表以獲取圖片
    $sql = "SELECT mpi.*, u.username 
            FROM member_points_inc AS mpi
            JOIN member_user AS u 
            ON mpi.user_id = u.user_id
            WHERE mpi.reason LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%{$category}%"]);
} else {
    // 如果沒有指定分類，則查詢所有記錄並連接  mpi.user_id表以獲取圖片
    $sql = "SELECT mpi.*, u.username 
    FROM member_points_inc AS mpi
    JOIN member_user AS u 
    ON mpi.user_id = u.user_id";
    $stmt = $pdo->query($sql);
}
$rows = [];
if ($stmt) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// 返回JSON格式的結果
echo json_encode([
    'data' => $rows
]);
