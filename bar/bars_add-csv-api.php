<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

header('Content-Type: application/json');

// 檢查是否有文件有上傳
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csvFile'])) {
    $csvFile = $_FILES['csvFile']['tmp_name'];

    if (!is_uploaded_file($csvFile)) {
        echo json_encode(['success' => false, 'message' => 'No file uploaded.']);
        exit;
    }

    // 上傳的CSV文件
    if (($handle = fopen($csvFile, 'r')) !== FALSE) {
        
        fgetcsv($handle);

        // 讀取CSV
        while (($row = fgetcsv($handle)) !== FALSE) {
            $bar_id = $row[1];
            $bar_name = $row[2];
            $bar_city = $row[3]; 
            $bar_area_id = $row[4];
            $bar_addr = $row[5];
            $bar_opening_time = $row[6];
            $bar_closing_time = $row[7];
            $bar_contact = $row[8];
            $bar_description = $row[9];
            $bar_type_id = $row[10];
            $bar_latitude = $row[11];
            $bar_longtitude = $row[12];

            // 插入数据库
            $sql = "INSERT INTO `bars` (bar_id, bar_name, bar_city, bar_area_id, bar_addr, bar_opening_time, bar_closing_time, bar_contact, bar_description, bar_type_id, bar_latitude, bar_longtitude) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $pdo->prepare($sql);
            if (!$stmt->execute([$bar_id, $bar_name, $bar_city, $bar_area_id, $bar_addr, $bar_opening_time, $bar_closing_time, $bar_contact, $bar_description, $bar_type_id, $bar_latitude, $bar_longtitude])) {
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


    // $sql = sprintf(
    // "SELECT bars.*, bar_area.bar_area_name, bar_type.bar_type_name FROM bars
    // LEFT JOIN bar_area ON bars.bar_area_id = bar_area.bar_area_id
    // LEFT JOIN bar_type ON bars.bar_type_id = bar_type.bar_type_id
    // ORDER BY %s %s LIMIT %s, %s",
    //     $sortColumn,
    //     $order,
    //     ($page - 1) * $perPage,
    //     $perPage
    // );
    // $stmt = $pdo->query($sql);
    // $rows = $stmt->fetchAll();