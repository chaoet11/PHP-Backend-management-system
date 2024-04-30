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

// 檢查圖片是否被上傳
if (!isset($_FILES['img'])) {
    $output['error'] = 'No image uploaded';
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$imgContent = file_get_contents($_FILES['img']['tmp_name']);
$sql = "INSERT INTO `comm_photo`(`photo_name`, `post_id`, `img`) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['photo_name'],
        $_POST['post_id'],
        $imgContent, // 圖片內容
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL Error: ' . $e->getMessage();
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
