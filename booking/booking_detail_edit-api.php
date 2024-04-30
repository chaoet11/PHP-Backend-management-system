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

    $booking_detail_id = isset($_POST['booking_detail_id']) ? intval($_POST['booking_detail_id']) : 0;
    if(empty($booking_detail_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `booking_detail` SET 
        `booking_detail_id`=?,
        `booking_id`=?,
        `seat_id`=?,
        `booking_type`=?
        WHERE booking_detail_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['booking_detail_id'],
            $_POST['booking_id'],
            $_POST['seat_id'],
            $_POST['booking_type'],
            $booking_detail_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>