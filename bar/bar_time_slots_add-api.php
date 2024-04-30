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


    $sql = "INSERT INTO `bar_time_slots`(`bar_time_slot_id`, `bar_start_time`, `bar_end_time`, `bar_max_capacity`) VALUES (?, ?, ?, ?)";


    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['bar_time_slot_id'],
            $_POST['bar_start_time'],
            $_POST['bar_end_time'],
            $_POST['bar_max_capacity']
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