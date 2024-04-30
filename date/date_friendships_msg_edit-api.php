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

    $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
    if(empty($message_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 取得原始的 created_at 值
    $sqlCreatedAt = "SELECT `sended_at` FROM `friendships_message` WHERE `message_id`=?";
    $stmtCreatedAt = $pdo->prepare($sqlCreatedAt);
    $stmtCreatedAt->execute([$message_id]);
    $originalCreatedAt = $stmtCreatedAt->fetchColumn(); 

    // 更新 updated_at 和 created_at
    $sql = "UPDATE `friendships_message` SET 
        `message_id`=?,
        `friendship_id`=?,
        `sender_id`=?,
        `receiver_id`=?,
        `content`=?,
        `sended_at`=?
        WHERE message_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['message_id'],
            $_POST['friendship_id'],
            $_POST['sender_id'],
            $_POST['receiver_id'],
            $_POST['content'],
            $originalCreatedAt,
            $message_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }
    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>