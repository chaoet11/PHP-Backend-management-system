<?php
    require '../_admin-required.php';
    require '../parts/db_connect.php';
    header('Content-Type: application/json');

    $output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
    ];

    // TODO: 資料輸入之前, 要做檢查
    # filter_var('bob@example.com', FILTER_VALIDATE_EMAIL)

    $bar_pic_id = isset($_POST['bar_pic_id']) ? intval($_POST['bar_pic_id']) : 0;
    if(empty($bar_pic_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `bar_pic` SET 
        `bar_pic_id`=?,
        `bar_pic_name`=?,
        `bar_id`=?
        WHERE bar_pic_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['bar_pic_id'],
            $_POST['bar_pic_name'],
            $_POST['bar_id'],
            $bar_pic_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>