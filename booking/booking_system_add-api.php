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

    $sql = "INSERT INTO `booking_system`(`booking_id`, `user_id`, `service_id`, `points_change`,`movie_date`,`movie_time`,`order_id`,`order_status`,`price`, `movie_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['booking_id'],
            $_POST['user_id'],
            $_POST['service_id'],
            $_POST['points_change'],
            $_POST['movie_date'],
            $_POST['movie_time'],
            $_POST['order_id'],
            $_POST['order_status'],
            $_POST['price'],
            $_POST['movie_id']
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