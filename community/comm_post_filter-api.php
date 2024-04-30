<?php
require '../parts/db_connect.php'; // 引入數據庫連接

header('Content-Type: application/json'); // 設置返回的數據類型為JSON

$category = isset($_GET['category']) ? $_GET['category'] : ''; // 從URL參數獲取分類

// SQL 查詢包含 JOIN 語句和圖片數據
if ($category) {
    // 如果有指定分類，則添加WHERE條件並連接 comm_photo 表以獲取圖片
    $sql = "SELECT cp.*, u.username, ph.comm_photo_id, ph.photo_name, ph.img FROM comm_post AS cp
            JOIN member_user AS u ON cp.user_id = u.user_id
            LEFT JOIN comm_photo AS ph ON cp.post_id = ph.post_id
            WHERE cp.context LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%{$category}%"]);
} else {
    // 如果沒有指定分類，則查詢所有記錄並連接 comm_photo 表以獲取圖片
    $sql = "SELECT cp.*, u.username, ph.comm_photo_id, ph.photo_name, ph.img FROM comm_post AS cp
            JOIN member_user AS u ON cp.user_id = u.user_id
            LEFT JOIN comm_photo AS ph ON cp.post_id = ph.post_id";
    $stmt = $pdo->query($sql);
}

$results = [];
if ($stmt) {
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $row) {
        // 對於每條記錄，如果存在圖片，則將其轉換為base64
        if ($row['img']) {
            $row['img'] = 'data:image/jpeg;base64,' . base64_encode($row['img']);
        }
        $results[] = $row;
    }
}

// 返回JSON格式的結果
echo json_encode([
    'data' => $results
]);
?>
