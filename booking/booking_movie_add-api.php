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

// 檢查圖片是否被上傳
if (empty($_FILES['photo']['name'][0])) {
    $output['error'] = '請上傳至少一張照片';
    header('Content-Type: application/json');
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit; // 如果沒有檔案，結束腳本執行
}

$sql = "INSERT INTO `booking_movie`(`title`, `poster_img`, `movie_description`, `movie_rating`, `movie_type_id`) VALUES (?, ?, ?, ?, ?)";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['title'],
        $_POST['poster_img'],
        $_POST['movie_description'],
        $_POST['movie_rating'],
        $_POST['movie_type_id'],
    ]);
    $output['success'] = $stmt->rowCount() > 0;
    $photoId = $pdo->lastInsertId();
} catch (PDOException $e) {
    $output['error'] = 'Upload post failed: ' . $e->getMessage();
}

// 如果貼文新增成功，處理檔案上傳
if ($output['success'] && !empty($_FILES['photo'])) {
    foreach ($_FILES['photo']['name'] as $i => $name) {
        if ($_FILES['photo']['error'][$i] === UPLOAD_ERR_OK) {
            $photoData = file_get_contents($_FILES['photo']['tmp_name'][$i]);
            $sqlPhoto = "UPDATE `booking_movie` SET `movie_img` = ? WHERE `movie_id` = ?";
            $stmtPhoto = $pdo->prepare($sqlPhoto);
            try {
                $stmtPhoto->execute([
                    $photoData,
                    $photoId
                ]);
            } catch (PDOException $e) {
                $output['errors'][] = 'Upload photo failed: ' . $e->getMessage();
            }
        }
    }
}


header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
