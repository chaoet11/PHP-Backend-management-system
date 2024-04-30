<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];


$trip_description = $_POST['trip_description'] !== '' ? $_POST['trip_description'] : null;
$trip_notes = $_POST['trip_notes'] !== '' ? $_POST['trip_notes'] : null;

$sql = "INSERT INTO `trip_plans`(`user_id`, `trip_title`, `trip_content`, `trip_description`, `trip_notes`, `trip_date`, `trip_draft`) VALUES (?, ?, ?, ?, ?, ?, ?)";

// $sql = "INSERT INTO `comm_post`(`post_id`, `context`, `created_at`, `updated_at`, `user_id`) VALUES (?, ?, NOW(), NOW(), ?)";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        // $_POST['trip_plan_id'],
        $_POST['user_id'],
        $_POST['trip_title'],
        $_POST['trip_content'],
        $trip_description,
        $trip_notes,
        $_POST['trip_date'],
        $_POST['trip_draft'],
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

header('Content-Type: application/json');

echo json_encode($output, JSON_UNESCAPED_UNICODE);
