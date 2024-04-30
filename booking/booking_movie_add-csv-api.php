<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

header('Content-Type: application/json');

// 检查是否有文件被上传
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile']['tmp_name'];

    if (!is_uploaded_file($csvFile)) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
        exit;
    }

    // 打开上传的CSV文件
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // 跳过CSV文件的头部（如果存在）
        fgetcsv($handle);

        // 读取CSV行
        while (($row = fgetcsv($handle)) !== FALSE) {
            // 忽略`post_id`，因为它是自增的
            $title = $row[1];
            $poster_img = $row[2];
            $movie_description = $row[3]; 
            $movie_rating = $row[4];
            $movie_type_id = $row[5];

            // 插入数据库
            $sql = "INSERT INTO `booking_movie` (title, poster_img, movie_description, movie_rating, movie_type_id) VALUES (?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$title, $poster_img, $movie_description, $movie_rating, $movie_type_id])) {
                echo json_encode(['success' => false, 'message' => 'Failed to insert data.']);
                fclose($handle);
                exit;
            }
        }
        fclose($handle);
        echo json_encode(['success' => true, 'message' => 'CSV data imported successfully.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to open the uploaded file.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
}
