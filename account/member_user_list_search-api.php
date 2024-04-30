<?php
    require '../parts/db_connect.php';

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT member_user.*,member_gender.gender_type FROM member_user JOIN member_gender ON member_user.gender = member_gender.gender
    WHERE 
            user_id LIKE ? OR 
            username LIKE ? OR 
            account LIKE ? OR 
            email LIKE ? OR
            password_hash LIKE ? OR
            profile_picture_url LIKE ? OR
            gender_type LIKE ? OR
            user_active LIKE ? OR
            birthday LIKE ? OR
            mobile LIKE ? OR
            profile_content LIKE ? OR
            created_at LIKE ? OR
            updated_at LIKE ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%", "%$keyword%"]);
    $rows = $stmt->fetchAll();

    echo json_encode(['data' => $rows]);
?>