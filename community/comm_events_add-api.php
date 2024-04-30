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

    // $sql = "INSERT INTO `comm_events`(`comm_event_id`, `title`, `description`, `status`, `location`, `user_id`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $sql = "INSERT INTO `comm_events`(`title`, `description`, `status`, `location`, `user_id`, `start_time`, `end_time`) VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            // $_POST['comm_event_id'],
            $_POST['title'],
            $_POST['description'],
            $_POST['status'],
            $_POST['location'],
            $_POST['user_id'],
            $_POST['start_time'],
            $_POST['end_time']
            // $_POST['created_at'],
            // $_POST['updated_at']
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());
    $output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

    header('Content-Type: application/json');

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>