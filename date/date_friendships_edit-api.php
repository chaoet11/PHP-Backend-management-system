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

    $friendship_id = isset($_POST['friendship_id']) ? intval($_POST['friendship_id']) : 0;
    if(empty($friendship_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // 取得原始的 created_at 值
$sqlCreatedAt = "SELECT `created_at` FROM `friendships` WHERE `friendship_id`=?";
$stmtCreatedAt = $pdo->prepare($sqlCreatedAt);
$stmtCreatedAt->execute([$friendship_id]);
$originalCreatedAt = $stmtCreatedAt->fetchColumn();

// 更新 updated_at 和 created_at
$sqlUpdate = "UPDATE `friendships` SET 
    `friendship_id`=?,
    `user_id1`=?,
    `user_id2`=?,
    `friendship_status`=?,
    `updated_at`=CURRENT_TIMESTAMP,
    `created_at`=?  -- 使用原始的 created_at 值
    WHERE `friendship_id`=?";
$stmtUpdate = $pdo->prepare($sqlUpdate);

try {
    $stmtUpdate->execute([
        $_POST['friendship_id'],
        $_POST['user_id1'],
        $_POST['user_id2'],
        $_POST['friendship_status'],
        $originalCreatedAt,
        $friendship_id
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

$output['success'] = boolval($stmtUpdate->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>