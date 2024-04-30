<?php
require '../parts/db_connect.php';

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

$sql = "INSERT INTO `members`(`id`, `email`, `password`, `mobile`, `address`, `birthday`, `hash`, `activated`, `nickname`, `create_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['id'],
        $_POST['email'],
        $_POST['password'],
        $_POST['mobile'],
        $_POST['address'],
        $_POST['birthday'],
        $_POST['hash'],
        $_POST['activated'],
        $_POST['nickname'],
        $_POST['create_at'],
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

header('Content-Type: application/json');

echo json_encode($output, JSON_UNESCAPED_UNICODE);
