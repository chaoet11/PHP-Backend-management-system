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

    $sql = "INSERT INTO `member_points_inc`(
        `user_id`, 
        `points_increase`, 
        `reason`) 
        VALUES 
        (?, 
        ?, 
        ?
        )";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['user_id'],
            $_POST['points_increase'],
            $_POST['reason']
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