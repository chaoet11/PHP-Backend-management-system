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

    $sql = "INSERT INTO `bar_booking`(`user_id`, `bar_id`, `bar_booking_time`, `bar_booking_people_num`, `bar_time_slot_id`) VALUES (?, ?, ?, ?, ?)";


    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            // $_POST['bar_booking_id'],
            $_POST['user_id'],
            $_POST['bar_id'],
            $_POST['bar_booking_time'],
            $_POST['bar_booking_people_num'],
            $_POST['bar_time_slot_id']
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