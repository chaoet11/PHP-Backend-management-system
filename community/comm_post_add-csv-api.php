<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

header('Content-Type: application/json');

// 檢查是否有檔案被上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile']['tmp_name'];

    if (!is_uploaded_file($csvFile)) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
        exit;
    }

    // 打開上傳的CSV檔案
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // 跳過CSV檔案的頭部（如果存在）
        fgetcsv($handle);

        // 讀取CSV行
        while (($row = fgetcsv($handle)) !== FALSE) {
            // 忽略`post_id`，因为它是自增的
            $context = $row[1];
            $created_at = $row[2]; // 假設這裡你接受CSV提供的日期
            $updated_at = $row[3]; // 假設這裡你接受CSV提供的日期
            $user_id = $row[4];

            // 插入数据库
            $sql = "INSERT INTO `comm_post` (context, user_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$context, $user_id])) {
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
