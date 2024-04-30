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

    $comm_event_id = isset($_POST['comm_event_id']) ? intval($_POST['comm_event_id']) : 0;
    if(empty($comm_event_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 取得原始的 created_at 值
    $sqlCreatedAt = "SELECT `created_at` FROM `comm_events` WHERE `comm_event_id`=?";
    $stmtCreatedAt = $pdo->prepare($sqlCreatedAt);
    $stmtCreatedAt->execute([$comm_event_id]);
    $originalCreatedAt = $stmtCreatedAt->fetchColumn();

    $sql = "UPDATE `comm_events` SET 
        `comm_event_id`=?,
        `title`=?,
        `description`=?,
        `status`=?,
        `location`=?,
        `user_id`=?,
        `start_time`=?,
        `end_time`=?,
        `created_at`=?,
        `updated_at`=CURRENT_TIMESTAMP 
        WHERE comm_event_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['comm_event_id'],
            $_POST['title'],
            $_POST['description'],
            $_POST['status'],
            $_POST['location'],
            $_POST['user_id'],
            $_POST['start_time'],
            $_POST['end_time'],
            $originalCreatedAt,
            $comm_event_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>