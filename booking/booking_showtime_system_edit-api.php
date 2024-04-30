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

    $show_time_id = isset($_POST['show_time_id']) ? intval($_POST['show_time_id']) : 0;
    if(empty($show_time_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `booking_showtime_system` SET 
        `show_time_id`=?,
        `room_id`=?,
        `movie_id`=?,
        `movie_time`=?,
        `seat_count`=?,
        `movie_date`=?
        WHERE show_time_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['show_time_id'],
            $_POST['room_id'],
            $_POST['movie_id'],
            $_POST['movie_time'],
            $_POST['seat_count'],
            $_POST['movie_date'],
            $show_time_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>