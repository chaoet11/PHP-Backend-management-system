<?php



require '../_admin-required.php';
require '../parts/db_connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "fileData" => $_FILES,
    "errors" => [],
];

// 对 admin_email 进行处理，删除首尾空白字符
$admin_email = trim($_POST['admin_email']);
// Check for duplicate admin_email
$sqlCheck = "SELECT * FROM `admin_user` WHERE `admin_email` = ?";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([$_POST['admin_email']]);
if ($stmtCheck->rowCount() > 0) {
    // Duplicate found
    $output['error'] = 'The email address is already registered.';
    $output['code'] = 1; // Custom code to indicate duplicate
    header('Content-Type: application/json');
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

$sql = "INSERT INTO `admin_user` (
    `admin_account`,
    `admin_password_hash`,
    `admin_email`,
    `admin_permission`,
    `google_avatar_url`,
    `avatar_img`
) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $pdo->prepare($sql);

$memberPW = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);
try {
    // Check if a file is uploaded
    if (isset($_FILES['admin_photo']) && $_FILES['admin_photo']['size'] > 0) {
        $avatar_img = file_get_contents($_FILES['admin_photo']['tmp_name']);
    } else {
        // If no file is uploaded, set $avatar_img to NULL or an appropriate default value
        $avatar_img = NULL;
    }

    $stmt->execute([
        $_POST['admin_account'],
        $memberPW,
        $_POST['admin_email'],
        $_POST['admin_permission'],
        $_POST['avatar_URL'],
        $avatar_img
    ]);
    $output['success'] = $stmt->rowCount() > 0;
    $admin_user_id = $pdo->lastInsertId(); // Assuming you want to include this in the response 
    $output['message'] = "資料更新成功！";
    // 將$output轉換為JSON格式並輸出


} catch (PDOException $e) {
    // 如果有錯誤，將錯誤訊息儲存在$output中
    $output['success'] = false;
    $output['error'] = 'Upload failed' . $e->getMessage();
    // 停止腳本執行
    exit();
}

// $stmt->rowCount(); # 新增幾筆
$output['success'] = boolval($stmt->rowCount());
$output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK
header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
