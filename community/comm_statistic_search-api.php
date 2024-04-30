<?php
require '../parts/db_connect.php';

$keyword = $_GET['keyword'] ?? '';

$sql = "SELECT mu.username, mu.user_id, 
        COUNT(DISTINCT cp.post_id) AS posts_count, 
        COUNT(DISTINCT cl.comm_likes_id) AS likes_count, 
        COUNT(DISTINCT cc.comm_comment_id) AS comments_count, 
        COUNT(DISTINCT cs.comm_saved_id) AS saved_count 
        FROM member_user AS mu 
        LEFT JOIN comm_post AS cp ON mu.user_id = cp.user_id 
        LEFT JOIN comm_likes AS cl ON mu.user_id = cl.user_id 
        LEFT JOIN comm_comment AS cc ON mu.user_id = cc.user_id 
        LEFT JOIN comm_saved AS cs ON mu.user_id = cs.user_id 
        WHERE mu.username LIKE CONCAT('%', ?, '%') 
        OR CAST(mu.user_id AS CHAR) LIKE CONCAT('%', ?, '%')
        GROUP BY mu.user_id, mu.username";

$stmt = $pdo->prepare($sql);
$stmt->execute([$keyword, $keyword]);
$rows = $stmt->fetchAll();

header('Content-Type: application/json');
echo json_encode(['data' => $rows]);
?>
