<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

header('Content-Type: application/json');

// 檢查有文件是否被上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile']['tmp_name'];

    if (!is_uploaded_file($csvFile)) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
        exit;
    }

    // 開啟上傳的CSV文件
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        // 跳過CSV文件的头部（如果存在）
        fgetcsv($handle);

        // 讀取CSV行
        while (($row = fgetcsv($handle)) !== FALSE) {
            // $bar_saved_id = $row[1];
            $user_id = $row[0];
            $bar_id = $row[1]; 

            // 引入數據庫
            $sql = "INSERT INTO `bar_saved` (user_id, bar_id) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$user_id, $bar_id])) {
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