<?php
    require '/_admin-required.php';
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

    $user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
    if(empty($user_id)){
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


$sql = "UPDATE `member_user` SET
`user_id`=?,
`username`=?,
`account`=?,
`password_hash`=?, 
`email`=?,
`profile_picture_url`=?,
`gender`=?,
`user_active`=?,
`birthday`=?,
`mobile`=?,
`profile_content`=?,
`updated_at`=CURRENT_TIMESTAMP
WHERE user_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['user_id'],
            $_POST['username'],
            $_POST['account'],
            $_POST['password_hash'],
            $_POST['email'],
            $_POST['profile_picture_url'],
            $_POST['gender'],
            $_POST['user_active'],
            $_POST['birthday'],
            $_POST['mobile'],
            $_POST['profile_content'],
            $user_id
        ]);
        $output['success'] = true;
        $output['message'] = "資料更新成功！";
        
        // 將$output轉換為JSON格式並輸出
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
    }
    catch(PDOException $e){
        // 如果有錯誤，將錯誤訊息儲存在$output中
        $output['success'] = false;
        $output['error'] = 'SQL有東西出錯了' . $e->getMessage();
        
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        // 停止腳本執行
        exit();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    // $output['success'] = boolval($stmt->rowCount());

    // echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>