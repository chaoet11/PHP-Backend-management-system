<?php
require '../parts/db_connect.php';

$startRating = $_GET['start_rating'] ?? null;
$endRating = $_GET['end_rating'] ?? null;


// 驗證起始評分和結束評分是否提供且為有效數字
if (is_null($startRating) || is_null($endRating) || !is_numeric($startRating) || !is_numeric($endRating)) {
    echo json_encode(['error' => 'Invalid input for rating range.']);
    exit;
}

$sql = "SELECT
movie_id,
title,
poster_img,
movie_description,
movie_rating,
movie_type_id,
movie_img
FROM
booking_movie
WHERE
movie_rating >= ? AND movie_rating <= ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$startRating, $endRating]);
$rows = $stmt->fetchAll();

echo json_encode(['data' => $rows]);
