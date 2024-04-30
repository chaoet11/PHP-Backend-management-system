<?php

require '../parts/db_connect.php';

// Assuming the category is passed via the 'category' query parameter.
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT bm.*, mt.movie_type 
        FROM booking_movie AS bm
        JOIN booking_movie_type AS mt ON bm.movie_type_id = mt.movie_type_id";

if (!empty($category)) {
    $sql .= " WHERE bm.movie_type_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$category]);
} else {
    $stmt = $pdo->query($sql);
}

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Iterate over each row to encode the image data
foreach ($rows as $i => $row) {
    // Check if there is image data to encode
    if (!empty($row['movie_img'])) {
        // Encode the binary data to Base64
        $rows[$i]['movie_img'] = 'data:image/jpeg;base64,' . base64_encode($row['movie_img']);
    } else {
        // If there is no image, you might want to set a default value or leave it empty
        $rows[$i]['movie_img'] = ''; // Or a default image base64 string
    }
}

echo json_encode(['data' => $rows]);
