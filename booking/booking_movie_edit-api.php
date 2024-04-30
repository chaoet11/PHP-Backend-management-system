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

    $movie_id = isset($_POST['movie_id']) ? intval($_POST['movie_id']) : 0;
    if(empty($movie_id)){
        $output['error'] = '沒有資料編號';
        $output['code'] = 401;
        echo json_encode($output, JSON_UNESCAPED_UNICODE);
        exit;
    }

    $sql = "UPDATE `booking_movie` SET 
        `movie_id`=?,
        `title`=?,
        `poster_img`=?,
        `movie_description`=?,
        `movie_rating`=?,
        `movie_type_id`=?
        WHERE movie_id=?";

    $stmt = $pdo->prepare($sql);
    try{
        $stmt->execute([
            $_POST['movie_id'],
            $_POST['title'],
            $_POST['poster_img'],
            $_POST['movie_description'],
            $_POST['movie_rating'],
            $_POST['movie_type_id'],
            $movie_id
        ]);
    }catch(PDOException $e){
        $output['error'] = 'SQL有東西出錯了'. $e->getMessage();
    }

    // $stmt->rowCount(); # 資料變更了幾筆
    $output['success'] = boolval($stmt->rowCount());

    echo json_encode($output, JSON_UNESCAPED_UNICODE);
?>