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

    $sql = "INSERT INTO `bars`(
    `bar_name`, 
    `bar_city`, 
    `bar_area_id`, 
    `bar_addr`, 
    `bar_opening_time`, 
    `bar_closing_time`, 
    `bar_contact`, `bar_description`, `bar_type_id`, `bar_latitude`, `bar_longtitude`) 
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";


    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            // $_POST['bar_id'],
            $_POST['bar_name'],
            $_POST['bar_city'],
            $_POST['bar_area_id'],
            $_POST['bar_addr'],
            $_POST['bar_opening_time'],
            $_POST['bar_closing_time'],
            $_POST['bar_contact'],
            $_POST['bar_description'],
            $_POST['bar_type_id'],
            $_POST['bar_latitude'],
            $_POST['bar_longtitude']
        ]);
        $output['success'] = true;
        $output['message'] = "success";
        echo json_encode($output, JSON_UNESCAPED_UNICODE);  
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }


    // $stmt->rowCount(); # 新增幾筆
    // $output['success'] = boolval($stmt->rowCount());
    // $output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

    header('Content-Type: application/json');

    // echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>