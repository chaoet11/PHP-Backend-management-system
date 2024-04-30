<?php
require '../_admin-required.php';
require '../parts/db_connect.php';

$input = file_get_contents('php://input');
$data = json_decode($input, true);
$output = ['success' => false, 'error' => ''];

// 跳過第一行(標題), 直接處理數據
foreach (array_slice($data, 1) as $row) {
    // 提取 context 和 user_id 的值
    $title = $row[1]; 
    $poster_img = $row[2];
    $movie_description = $row[3]; 
    $movie_rating = $row[4];
    $movie_type_id = $row[5]; 
    
    $sql = "INSERT INTO `booking_movie` (title, poster_img, movie_description, movie_rating, movie_type_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    echo "Inserted Data: title=$title, poster_img=$poster_img, movie_description=$movie_description, movie_rating=$movie_rating, movie_type_id=$movie_type_id\n";
    
    try {
        $stmt->execute([$title, $poster_img, $movie_description, $movie_rating, $movie_type_id]);

        $output['success'] = true;
    } catch (PDOException $e) {
        $output['error'] = 'Insertion error: ' . $e->getMessage();
    }
}

header('Content-Type: application/json');
// 返回 JSON 格式的结果
echo json_encode($output);
?>