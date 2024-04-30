<?php
    require '../parts/db_connect.php';

        $keyword = $_GET['keyword'] ?? '';
        $category = $_GET['category'] ?? 'post_id';
        
        $sql = "SELECT p.*, u.username, ph.comm_photo_id, ph.photo_name, ph.img 
                FROM comm_post AS p
                JOIN member_user AS u ON p.user_id = u.user_id
                LEFT JOIN comm_photo AS ph ON p.post_id = ph.post_id";

    if ($category === 'all') {
        $sql .= " WHERE p.post_id LIKE :post_id_keyword OR 
                    p.context LIKE :context_keyword OR 
                    p.created_at LIKE :created_at_keyword OR 
                    p.updated_at LIKE :updated_at_keyword OR
                    u.username LIKE :username_keyword OR
                    ph.comm_photo_id LIKE :photo_id_keyword OR
                    ph.photo_name LIKE :photo_name_keyword";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'post_id_keyword' => "%$keyword%",
            'context_keyword' => "%$keyword%",
            'created_at_keyword' => "%$keyword%",
            'updated_at_keyword' => "%$keyword%",
            'username_keyword' => "%$keyword%",
            'photo_id_keyword' => "%$keyword%",
            'photo_name_keyword' => "%$keyword%"
        ]);
    } else {
        if ($category === 'photo_name' || $category === 'comm_photo_id') {
            $sql .= " WHERE ph.$category LIKE :keyword";
        } else {
            if ($category === 'username') {
                $sql .= " WHERE u.$category LIKE :keyword";
            } else {
                $sql .= " WHERE p.$category LIKE :keyword";
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['keyword' => "%$keyword%"]);
        }
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['keyword' => "%$keyword%"]);
    }

    $rows = $stmt->fetchAll();
    foreach ($rows as $i => $row) {
        if (isset($row['img']) && $row['img']) {
            $rows[$i]['img'] = 'data:image/jpeg;base64,' . base64_encode($row['img']);
        }
    }

    echo json_encode(['data' => $rows]);
?>