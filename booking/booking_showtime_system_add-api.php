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

    $sql = "INSERT INTO `booking_showtime_system`(`show_time_id`, `room_id`, `movie_id`, `movie_time`,  `seat_count`,  `movie_date`) VALUES (?, ?, ?, ?, ?, ?)";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['show_time_id'],
            $_POST['room_id'],
            $_POST['movie_id'],
            $_POST['movie_time'],
            $_POST['seat_count'],
            $_POST['movie_date']
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