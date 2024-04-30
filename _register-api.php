<?php
require __DIR__ . '/parts/db_connect.php';

$output = [
    "success" => false,
    "error" => "",
    "code" => 0,
    "postData" => $_POST,
    "errors" => [],
];

// 对 admin_email 进行处理，删除首尾空白字符
$admin_email = trim($_POST['admin_email']);
// Check for duplicate admin_email
$sqlCheck = "SELECT * FROM `admin_user` WHERE `admin_email` = ?";
$stmtCheck = $pdo->prepare($sqlCheck);
$stmtCheck->execute([ $_POST['admin_email'] ]);
if ($stmtCheck->rowCount() > 0) {
    // Duplicate found
    $output['error'] = 'The email address is already registered.';
    $output['code'] = 1; // Custom code to indicate duplicate
    header('Content-Type: application/json');
    echo json_encode($output, JSON_UNESCAPED_UNICODE);
    exit;
}

// Proceed to insert new record
$sql = "INSERT INTO `admin_user`(`admin_account`, `admin_password_hash`, `admin_email`, `admin_permission`, `google_avatar_url`) VALUES (?, ?, ?, ?, ?)";
$password_hash = password_hash($_POST['admin_password'], PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $_POST['admin_account'],
        $password_hash,
        $_POST['admin_email'],
        $_POST['admin_permission'],
        $_POST['avatar_URL']
    ]);
    $output['success'] = $stmt->rowCount() > 0;
    $output['lastInsertId'] = $pdo->lastInsertId(); // The newly created record ID
} catch (PDOException $e) {
    $output['error'] = 'Database error: ' . $e->getMessage();
}

header('Content-Type: application/json');
echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>
