<?php
require '../parts/db_connect.php';

// 接收類型和關鍵字作為查詢參數
$category = isset($_GET['category']) ? $_GET['category'] : '';
$keyword = isset($_GET['keyword']) ? "%" . $_GET['keyword'] . "%" : '%';

$sql = "SELECT bars.*, bar_area.bar_area_name, bar_type.bar_type_name FROM bars
LEFT JOIN bar_area ON bars.bar_area_id = bar_area.bar_area_id
LEFT JOIN bar_type ON bars.bar_type_id = bar_type.bar_type_id";

// 構建WHERE條件
$whereConditions = [];
$params = [];

if (!empty($category)) {
    $whereConditions[] = "bars.bar_type_id = ?";
    $params[] = $category;
}

// 增加關鍵字搜尋條件
if (!empty($_GET['keyword'])) {
    $whereConditions[] = "(bars.bar_name LIKE ? OR bar_area.bar_area_name LIKE ? OR bars.bar_addr LIKE ?)";
    $params = array_merge($params, [$keyword, $keyword, $keyword]);
}

if (count($whereConditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $rows]);


// // Iterate over each row to encode the image data
// foreach ($rows as $i => $row) {
//     // Check if there is image data to encode
//     if (!empty($row['movie_img'])) {
//         // Encode the binary data to Base64
//         $rows[$i]['movie_img'] = 'data:image/jpeg;base64,' . base64_encode($row['movie_img']);
//     } else {
//         // If there is no image, you might want to set a default value or leave it empty
//         $rows[$i]['movie_img'] = ''; // Or a default image base64 string
//     }
// }
