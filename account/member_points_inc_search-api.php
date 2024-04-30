<?php
    require '../parts/db_connect.php';

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT mpi.*, u.username FROM member_points_inc AS mpi
        JOIN member_user AS u 
        ON mpi.user_id = u.user_id
        WHERE 
        mpi.points_increase_id LIKE ? OR 
        u.username LIKE ? OR 
        mpi.points_increase LIKE ? OR 
        mpi.reason LIKE ? OR
        mpi.created_at LIKE ?";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
        $rows = $stmt->fetchAll();
        echo json_encode(['data' => $rows]);
    } catch (PDOException $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
?>