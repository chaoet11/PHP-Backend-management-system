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
            // 忽略`message_id`，因为它是自增的
            $friendship_id = $row[1];
            $sender_id = $row[2]; 
            $receiver_id = $row[3]; 
            $content = $row[4];
            $sended_at = $row[5];

            // 插入数据库
            $sql = "INSERT INTO `friendships_message` (friendship_id, sender_id, receiver_id, content, sended_at) VALUES (?, ?, ?, ?, ? )";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$friendship_id, $sender_id, $receiver_id, $content, $sended_at])) {
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
