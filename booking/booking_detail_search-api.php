<?php
    require '../parts/db_connect.php';

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT * FROM booking_detail WHERE 
            booking_detail_id LIKE ? OR 
            booking_id LIKE ? OR 
            seat_id LIKE ? OR 
            booking_type LIKE ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
    $rows = $stmt->fetchAll();

    echo json_encode(['data' => $rows]);
?>