<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
header('Content-Type: application/json');

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "fileData" => $_FILES,
    "errors" => [],
];

$post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
if (empty($post_id)) {
    $output['error'] = '沒有資料編號';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 更新貼文內容和用戶ID
$sql = "UPDATE `comm_post` SET `context`=?, `user_id`=? WHERE `post_id`=?";
try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['context'],
        $_POST['user_id'],
        $post_id
    ]);
    $output['success'] = $stmt->rowCount() > 0;
} catch (PDOException $e) {
    $output['error'] = 'Update post failed: ' . $e->getMessage();
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 处理文件上传并更新照片记录
if (!empty($_FILES['photo']['name'])) {
    $photoData = file_get_contents($_FILES['photo']['tmp_name']);
    // 生成默认照片名称，这里使用上传时间和原文件名作为默认名称
    $defaultPhotoName = "default_photo_name.jpg";

    // 先删除旧图片
    $sqlDeletePhoto = "DELETE FROM `comm_photo` WHERE `post_id`=?";
    $stmtDeletePhoto = $pdo->prepare($sqlDeletePhoto);
    $stmtDeletePhoto->execute([$post_id]);

    // 插入新照片
    $sqlPhoto = "INSERT INTO `comm_photo` (`post_id`, `photo_name`, `img`) VALUES (?, ?, ?)";
    $stmtPhoto = $pdo->prepare($sqlPhoto);
    try {
        $stmtPhoto->execute([
            $post_id,
            $defaultPhotoName, // 使用默认照片名称
            $photoData
        ]);
        $output['success'] = true; // 假设照片更新成功
    } catch (PDOException $e) {
        $output['errors'][] = 'Upload photo failed: ' . $e->getMessage();
    }
} else {
    $output['errors'][] = 'No photo uploaded.';
}


echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
