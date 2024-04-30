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

    $sql = "INSERT INTO `member_user`(
        `username`, 
        `account`, 
        `password_hash`,
        `email`,
        `profile_picture_url`,
        `gender`,
        `user_active`,
        `birthday`,
        `mobile`,
        `profile_content`,
        `created_at`,
        `updated_at`) 
        VALUES 
        (
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            ?, 
            CURRENT_TIMESTAMP, 
            CURRENT_TIMESTAMP
            )";

    $stmt = $pdo->prepare($sql);
    $memberPW = password_hash($_POST['password'], PASSWORD_DEFAULT);
    try{
        $stmt->execute([
            $_POST['username'],
            $_POST['account'],
            $memberPW,
            $_POST['email'],
            $_POST['profile_picture_url'],
            $_POST['gender'],
            $_POST['user_active'],
            $_POST['birthday'],
            $_POST['mobile'],
            $_POST['profile_content'],
        ]);
        $output['success'] = true;
        $output['message'] = "資料更新成功！";
                
        // 將$output轉換為JSON格式並輸出
        echo json_encode($output, JSON_UNESCAPED_UNICODE);

    }catch(PDOException $e){
        // 如果有錯誤，將錯誤訊息儲存在$output中
        $output['success'] = false;
        $output['error'] = 'SQL有東西出錯了' . $e->getMessage();

        echo json_encode($outpSSut, JSON_UNESCAPED_UNICODE);
        // 停止腳本執行
        exit();
    }

    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());
    $output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK
    header('Content-Type: application/json');



?>