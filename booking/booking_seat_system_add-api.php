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

    $sql = "INSERT INTO `booking_seat_system`(`seat_id`, `seat_uuid`, `seat_status`, `movie_id`) VALUES (?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['seat_id'],
            $_POST['seat_uuid'],
            $_POST['seat_status'],
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