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

    $bar_rating_id = isset($_POST['bar_rating_id']) ? intval($_POST['bar_rating_id']) : 0;
    if(empty($bar_rating_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `bar_rating` SET 
        `bar_rating_id`=?,
        `bar_id`=?,
        `user_id`=?,
        `bar_rating_star`=?,
        `updated_at`=CURRENT_TIMESTAMP
        WHERE bar_rating_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['bar_rating_id'],
            $_POST['bar_id'],
            $_POST['user_id'],
            $_POST['bar_rating_star'],
            // $_POST['updated_at'],
            $bar_rating_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>