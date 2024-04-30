<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$output = ['success' => false, 'error' => ''];

// 跳過第一行(標題), 直接處理數據
foreach (array_slice($data, 1) as $row) {
    // 提取 context 和 user_id 的值
    $context = $row[1]; 
    $userId = $row[4];
    
    $sql = "INSERT INTO `comm_post` (`context`, `user_id`) VALUES (?, ?)";
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([$context, $userId]);
        $output['success'] = true;
    } catch (PDOException $e) {
        $output['error'] = 'ERROR: ' . $e->getMessage();
    }
}

header('Content-Type: application/json');
// 返回 JSON 格式的结果
echo json_encode($output);
?>