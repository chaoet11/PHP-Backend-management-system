<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

$output = [
    "success" => false,
    "exists" => false, // 添加標誌以檢測是否有重複
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

// 如果檢查是否有重複的標誌存在
if (isset($_GET['checkDuplicate']) && $_GET['checkDuplicate'] === 'true') {
    // 檢查是否有重複的組合 (user_id1, user_id2)
    $sqlCheckDuplicate = "SELECT COUNT(*) as count FROM friendships WHERE (user_id1 = ? AND user_id2 = ?) OR (user_id1 = ? AND user_id2 = ?)";
    $stmtCheckDuplicate = $pdo->prepare($sqlCheckDuplicate);

    try {
        $stmtCheckDuplicate->execute([$_POST['user_id1'], $_POST['user_id2'], $_POST['user_id2'], $_POST['user_id1']]);
        $resultCheckDuplicate = $stmtCheckDuplicate->fetch(PDO::FETCH_ASSOC);

        if ($resultCheckDuplicate['count'] > 0) {
            // 如果找到重複，將 'exists' 標誌設置為 true
            $output['exists'] = true;
            header('Content-Type: application/json');
            echo json_encode($output, JSON_UNESCAPED_UNICODE);
            exit;
        }
    } catch (PDOException $e) {
        $output['error'] = 'SQL 有東西出錯了' . $e->getMessage();
        header('Content-Type: application/json');
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// 如果沒有重複，繼續原始插入邏輯
$sql = "INSERT INTO `friendships`(`user_id1`, `user_id2`, `friendship_status`) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        $_POST['user_id1'],
        $_POST['user_id2'],
        $_POST['friendship_status'],
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL 有東西出錯了' . $e->getMessage();
}

$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId();

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
