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


    $sql = "INSERT INTO `bar_saved`(`user_id`, `bar_id`) VALUES (?, ?)";


    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            // $_POST['bar_saved_id'],
            $_POST['user_id'],
            $_POST['bar_id']
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