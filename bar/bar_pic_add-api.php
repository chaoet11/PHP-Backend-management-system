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

    // $sql = "INSERT INTO `bar_pic`(`bar_pic_name`, `bar_id`) VALUES (?, ?)";

    // 檢查圖片是否被上傳
    if (!isset($_FILES['bar_img'])) {
        $output['error'] = 'No image uploaded';
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $imgContent = file_get_contents($_FILES['bar_img']['tmp_name']);
    $sql = "INSERT INTO `bar_pic`(`bar_pic_name`, `bar_id`, `bar_img`) VALUES (?, ?, ?)";
    // 檢查圖片是否被上傳

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            // $_POST['bar_pic_id'],
            $_POST['bar_pic_name'],
            $_POST['bar_id'],
            $imgContent, // 圖片內容
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL Error: '. $e->getMessage();
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    // $stmt->rowCount(); # 新增幾筆
    $output['success'] = boolval($stmt->rowCount());
    $output['lastInsertId'] = $pdo->lastInsertId(); // 取得最新建立資料的 PK

    header('Content-Type: application/json');

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>