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
            // 忽略`user_id`，因为它是自增的
            $username = $row[1];
            $account = $row[2];
            $email = $row[3];
            $password_hash = $row[4];
            $profile_picture_url = $row[5];
            $gender = $row[6];
            $user_active = $row[7];
            $birthday = $row[8];
            $mobile = $row[9];
            $profile_content = $row[10];

            // 插入数据库
            $sql = "INSERT INTO `member_user` (username, account, email, password_hash, profile_picture_url, gender, user_active, birthday, mobile, profile_content) VALUES  (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$username, $account, $email, $password_hash, $profile_picture_url, $gender, $user_active, $birthday, $mobile, $profile_content])) {
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
