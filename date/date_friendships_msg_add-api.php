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

// 驗證 Sender 和 Receiver 是否對應到指定的 Friendship ID
$sqlCheckMatch = "SELECT COUNT(*) as count FROM `friendships_message` WHERE 
    `friendship_id` = ? AND
    (`sender_id` = ? OR `receiver_id` = ?)";
$stmtCheckMatch = $pdo->prepare($sqlCheckMatch);

try {
    $stmtCheckMatch->execute([
        $_POST['friendship_id'],
        $_POST['sender_id'],
        $_POST['receiver_id']
    ]);

    $result = $stmtCheckMatch->fetch(PDO::FETCH_ASSOC);

    //確保至少有一條記錄符合條件
    if ($result['count'] < 1) {
        $output['errors'][] = "Sender 和 Receiver 的 ID 與指定的 Friendship ID 不匹配。";
        $output['errorMismatch'] = true;
        $output['error'] = '驗證失敗。';
        http_response_code(400); // Bad Request
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    http_response_code(500); // Internal Server Error
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 驗證通過，執行插入操作
$sqlInsert = "INSERT INTO `friendships_message`(`friendship_id`, `sender_id`, `receiver_id`, `content`) VALUES (?, ?, ?, ?)";
$stmtInsert = $pdo->prepare($sqlInsert);

try {
    $stmtInsert->execute([
        $_POST['friendship_id'],
        $_POST['sender_id'],
        $_POST['receiver_id'],
        $_POST['content'],
    ]);

    // 操作成功
    $output['success'] = true;
    $output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    http_response_code(500); // Internal Server Error
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
