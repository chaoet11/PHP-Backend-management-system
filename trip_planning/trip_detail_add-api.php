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

// TODO: 資料輸入之前, 要做檢查
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL)

/* # 會有 SQL injection 的問題
    $sql = "INSERT INTO `address_book`(`name`, `email`, `mobile`, `birthday`, `address`, `created_at`) VALUES (
    '{$_POST['name']}',
    '{$_POST['email']}',
    '{$_POST['mobile']}',
    '{$_POST['birthday']}',
    '{$_POST['address']}',
    NOW() )";

    $stmt = $pdo->query($sql);
    */

// 如果沒有值就設定為空值 null
// $birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
// $birthday = strtotime($birthday); // 轉換為timestamp
// if($birthday===false){
//     $birthday = null;
// }else{
//     $birthday = date('Y-m-d', $birthday);
// }
$movie_id = $_POST['movie_id'] !== '' ? $_POST['movie_id'] : null;
$bar_id = $_POST['bar_id'] !== '' ? $_POST['bar_id'] : null;
$sql = "INSERT INTO `trip_details`(`trip_plan_id`, `block`, `movie_id`, `bar_id`) VALUES (?, ?, ?, ?)";

// $sql = "INSERT INTO `comm_post`(`post_id`, `context`, `created_at`, `updated_at`, `user_id`) VALUES (?, ?, NOW(), NOW(), ?)";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        // $_POST['trip_detail_id'],
        $_POST['trip_plan_id'],
        $_POST['block'],
        $movie_id,
        $bar_id,
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

header('Content-Type: application/json');

echo json_encode($output, JSON_UNESCAPED_UNICODE);
