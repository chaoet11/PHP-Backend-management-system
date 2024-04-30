<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = sprintf(
    "SELECT 
        mu.username, 
        f.user_id1,
        COUNT(CASE WHEN f.friendship_status = 'accepted' THEN 1 ELSE NULL END) AS accepted_count,
        COUNT(CASE WHEN f.friendship_status = 'blocked' THEN 1 ELSE NULL END) AS blocked_count,
        COUNT(CASE WHEN f.friendship_status = 'pending' THEN 1 ELSE NULL END) AS pending_count
        FROM member_user AS mu 
        LEFT JOIN friendships AS f ON mu.user_id = f.user_id1
        WHERE mu.username LIKE ?
        GROUP BY f.user_id1
        ORDER BY %s %s LIMIT %d, %d",
    'username',  // 將排序欄位設置為 'username'
    'ASC',       // 預設排序方式
    0,           // 不需要偏移量
    1000000000   // 設置一個足夠大的數值，以確保返回所有匹配的結果
);

$stmt = $pdo->prepare($sql);
$stmt->execute(["%$keyword%"]);
$rows = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['data' => $rows]);
?>
