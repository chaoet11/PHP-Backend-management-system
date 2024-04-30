<?php
    require __DIR__ . '/parts/db_connect.php';

    header('Content-Type: application/json');

    $output = [
    "success" => false,
    "code" => 0,
    "postData" => $_POST,
    "error" => '',
    ];

    
    if(empty($_POST['email']) or empty($_POST['password'])){
        # 欄位資料不足
        $output['code'] = 401;
        echo json_encode($output);
        exit;
    }

    # 先由帳號找到該筆
    $sql = "SELECT * FROM admin_user WHERE admin_email=?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$_POST['email']]);
    $row = $stmt->fetch();

    if(empty($row)){
        # 帳號是錯的
        $output['code'] = 403;
        echo json_encode($output);
        exit;
    }

    $output['success'] = password_verify($_POST['password'], $row['admin_password_hash']);
    if($output['success']){
        $_SESSION['admin'] = [
            'id' => $row['admin_user_id'],
            'email' => $row['admin_email'],
            'nickname' => $row['admin_account'],

            'picture' => $row['google_avatar_url']

        ];
    } else {
        # 密碼是錯的
        $output['code'] = 405;
    }

echo json_encode($output, JSON_UNESCAPED_UNICODE);