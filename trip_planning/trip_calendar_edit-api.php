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

$calendar_id = isset($_POST['calendar_id']) ? intval($_POST['calendar_id']) : 0;
if (empty($calendar_id)) {
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
$secondary_trip_detail_id = isset($_POST['secondary_trip_detail_id']) && $_POST['secondary_trip_detail_id'] !== '' ? intval($_POST['secondary_trip_detail_id']) : null;
$tertiary_trip_detail_id = isset($_POST['tertiary_trip_detail_id']) && $_POST['tertiary_trip_detail_id'] !== '' ? intval($_POST['tertiary_trip_detail_id']) : null;

// SQL 更新語句
$sql = "UPDATE `trip_calendar` SET 
        `calendar_id`=?,
        `trip_plan_id`=?,
        `primary_trip_detail_id`=?,
        `secondary_trip_detail_id`=?,
        `tertiary_trip_detail_id`=?
        WHERE `calendar_id`=?";

$stmt = $pdo->prepare($sql);
try {
    $stmt->execute([
        $_POST['calendar_id'],
        $_POST['trip_plan_id'],
        $_POST['primary_trip_detail_id'],
        $secondary_trip_detail_id,
        $tertiary_trip_detail_id,
        $calendar_id
    ]);
} catch (PDOException $e) {
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
}

// $stmt->rowCount(); # 資料變更了幾筆
$output['success'] = boolval($stmt->rowCount());

echo json_encode($output, JSON_UNESCAPED_UNICODE);
