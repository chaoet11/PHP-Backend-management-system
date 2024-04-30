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

$trip_plan_id = isset($_POST['trip_plan_id']) ? intval($_POST['trip_plan_id']) : 0;
if (empty($trip_plan_id)) {
    $output['error'] = '沒有資料編號';
    $output['code'] = 401;
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// 如果沒有值就設定為空值 null
// $birthday = empty($_POST['birthday']) ? null : $_POST['birthday'];
// $birthday = strtotime($birthday); // 轉換為timestamp
// if($birthday===false){
//     $birthday = null;
// }else{
//     $birthday = date('Y-m-d', $birthday);
// }
$trip_description = $_POST['trip_description'] !== '' ? $_POST['trip_description'] : null;
$trip_notes = $_POST['trip_notes'] !== '' ? $_POST['trip_notes'] : null;

$sql = "UPDATE `trip_plans` SET 
        `trip_plan_id`=?,
        `user_id`=?,
        `trip_title`=?,
        `trip_content`=?,
        `trip_description`=?,
        `trip_notes`=?,
        `trip_date`=?,
        `trip_draft`=?
        WHERE trip_plan_id=?";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['trip_plan_id'],
        $_POST['user_id'],
        $_POST['trip_title'],
        $_POST['trip_content'],
        $trip_description,
        $trip_notes,
        $_POST['trip_date'],
        $_POST['trip_draft'],
        $trip_plan_id
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

// $stmt->rowCount(); # 資料變更了幾筆
$output['success'] = boolval($stmt->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
