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

    $comm_saved_id = isset($_POST['comm_saved_id']) ? intval($_POST['comm_saved_id']) : 0;
    if(empty($comm_saved_id)){
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

    $sql = "UPDATE `comm_saved` SET 
        `comm_saved_id`=?,
        `post_id`=?,
        `user_id`=? 
        WHERE comm_saved_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['comm_saved_id'],
            $_POST['post_id'],
            $_POST['user_id'],
            $comm_saved_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>