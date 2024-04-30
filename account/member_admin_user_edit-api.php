<?php
require '../_admin-required.php';
require '../parts/db_connect.php';
header('Content-Type: application/json');


$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "fileData" => $_FILES,
    "errors" => [],
];

// TODO: 資料輸入之前, 要做檢查
# filter_var('bob@example.com', FILTER_VALIDATE_EMAIL)

$admin_user_id = isset($_POST['admin_user_id']) ? intval($_POST['admin_user_id']) : 0;
if (empty($admin_user_id)) {
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


$sql = "UPDATE `admin_user` SET
`admin_user_id`=?,
`admin_account`=?,
`google_avatar_url`=?,
`admin_password_hash`=?,
`admin_email`=?,
`admin_permission`=?,
`avatar_img`=?
WHERE admin_user_id=?";

$stmt = $pdo->prepare($sql);
try {
    // Check if a file is uploaded
    if (isset($_FILES['admin_photo']) && $_FILES['admin_photo']['size'] > 0) {
        $avatar_img = file_get_contents($_FILES['admin_photo']['tmp_name']);
    } else {
        // If no file is uploaded, set $avatar_img to NULL or an appropriate default value
        $avatar_img = NULL;
    }

    $stmt->execute([
        $admin_user_id,
        $_POST['admin_account'],
        $_POST['avatar_URL'],
        $_POST['admin_password_hash'],
        $_POST['admin_email'],
        $_POST['admin_permission'],
        $avatar_img,
        $admin_user_id
    ]);
    $output['success'] = true;
    $output['message'] = "資料更新成功！";
    echo json_encode($output, JSON_UNESCAPED_UNICODE);



} catch (PDOException $e) {
    // 如果有錯誤，將錯誤訊息儲存在$output中
    $output['success'] = false;
    $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    // 停止腳本執行
    exit();
}

// $stmt->rowCount(); # 資料變更了幾筆
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

header('Content-Type: application/json');
// echo json_encode($output, JSON_UNESCAPED_UNICODE);
