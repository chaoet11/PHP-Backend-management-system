<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "fileData" => $_FILES,
    "errors" => [],
];

// 檢查是否有檔案被上傳
if (empty($_FILES['photo']['name'][0])) {
    $output['error'] = '請上傳至少一張照片';
    header('Content-Type: application/json');
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit; // 如果沒有檔案，結束腳本執行
}

$sql = "INSERT INTO `comm_post`(`context`, `user_id`) VALUES (?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['context'],
        $_POST['user_id']
    ]);
    $output['success'] = $stmt->rowCount() > 0;
    $postId = $pdo->lastInsertId(); // 取得最新新增的貼文 ID
} catch (PDOException $e) {
    $output['error'] = 'Upload post failed: ' . $e->getMessage();
}

// 如果貼文新增成功，處理檔案上傳
if ($output['success'] && !empty($_FILES['photo'])) {
    foreach ($_FILES['photo']['name'] as $i => $name) {
        if ($_FILES['photo']['error'][$i] === UPLOAD_ERR_OK) {
            $photoName = $_FILES['photo']['name'][$i];
            $photoData = file_get_contents($_FILES['photo']['tmp_name'][$i]);
            $sqlPhoto = "INSERT INTO `comm_photo`(`photo_name`, `post_id`, `img`) VALUES (?, ?, ?)";
            $stmtPhoto = $pdo->prepare($sqlPhoto);
            try {
                $stmtPhoto->execute([
                    $photoName,
                    $postId,
                    $photoData
                ]);
            } catch (PDOException $e) {
                $output['errors'][] = 'Upload photo failed: ' . $e->getMessage();
            }
        }
    }
}

header('Content-Type: application/json');

echo json_encode($output, JSON_UNESCAPED_UNICODE);
