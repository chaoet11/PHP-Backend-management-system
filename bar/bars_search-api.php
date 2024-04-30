<?php
require '../parts/db_connect.php';

$keyword = isset($_GET['keyword']) ? "%" . $_GET['keyword'] . "%" : '%';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT bars.*, bar_area.bar_area_name, bar_type.bar_type_name FROM bars
LEFT JOIN bar_area ON bars.bar_area_id = bar_area.bar_area_id
LEFT JOIN bar_type ON bars.bar_type_id = bar_type.bar_type_id";

$whereConditions = [];
$params = [];

// 如果有指定類型，則增加條件
if (!empty($category)) {
    $whereConditions[] = "bars.bar_type_id = ?";
    $params[] = $category;
}

// 增加關鍵字搜尋條件
$whereConditions[] = "(bars.bar_name LIKE ? OR bar_area.bar_area_name LIKE ? OR bars.bar_addr LIKE ? OR bars.bar_description LIKE ? OR bar_type.bar_type_name LIKE ?)";
$params = array_merge($params, [$keyword, $keyword, $keyword, $keyword, $keyword]);

$sql .= " WHERE " . implode(" AND ", $whereConditions);

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['data' => $rows]);
