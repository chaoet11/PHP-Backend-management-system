<?php
    require '../parts/db_connect.php';

    $keyword = $_GET['keyword'] ?? '';

    $sql = "SELECT bm.*, mt.movie_type 
            FROM booking_movie AS bm
            JOIN booking_movie_type AS mt 
            ON bm.movie_type_id = mt.movie_type_id
            WHERE bm.movie_id LIKE ? OR 
                    bm.title LIKE ? OR 
                    bm.poster_img LIKE ? OR 
                    bm.movie_description LIKE ? OR 
                    bm.movie_rating LIKE ? OR 
                    mt.movie_type_id LIKE ? ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(["%$keyword%", "%$keyword%","%$keyword%","%$keyword%", "%$keyword%", "%$keyword%"]);
    $rows = $stmt->fetchAll();

    foreach ($rows as $i => $row) {
        if (isset($row['movie_img']) && $row['movie_img']) {
            $rows[$i]['movie_img'] = 'data:image/jpeg;base64,' . base64_encode($row['movie_img']);
        }
    }

    echo json_encode(['data' => $rows]);
?>