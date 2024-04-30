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

    $booking_id = isset($_POST['booking_id']) ? intval($_POST['booking_id']) : 0;
    if(empty($booking_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `booking_system` SET 
        `booking_id`=?,
        `user_id`=?,
        `service_id`=?,
        `points_change`=?,
        `movie_date`=?,
        `movie_time`=?,
        `order_id`=?,
        `order_status`=?,
        `price`=?,
        `updated_at`=CURRENT_TIMESTAMP,
        `movie_id`=?
        WHERE booking_id=?";

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
            $_POST['movie_id'],
            $booking_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>