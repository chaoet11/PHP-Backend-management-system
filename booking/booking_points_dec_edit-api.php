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

    $points_decrease_id = isset($_POST['points_decrease_id']) ? intval($_POST['points_decrease_id']) : 0;
    if(empty($points_decrease_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `booking_points_dec` SET 
        `points_decrease_id`=?,
        `user_id`=?,
        `points_decrease`=?
        WHERE points_decrease_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['points_decrease_id'],
            $_POST['user_id'],
            $_POST['points_decrease'],
            $points_decrease_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>