<?php
    require '../parts/db_connect.php';

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT comm_photo_id, photo_name, post_id, img FROM comm_photo WHERE
            comm_photo_id LIKE :keyword OR
            photo_name LIKE :keyword OR
            post_id LIKE :keyword";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['keyword' => "%$keyword%"]);
    $rows = $stmt->fetchAll();

    foreach ($rows as $i => $row) {
        if (isset($row['img']) && $row['img']) {
            $rows[$i]['img'] = 'data:image/jpeg;base64,' . base64_encode($row['img']);
        }        
    }

    echo json_encode(['data' => $rows]);

?>