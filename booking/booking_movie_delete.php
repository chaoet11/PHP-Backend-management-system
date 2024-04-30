<?php
    require '../parts/db_connect.php';

    $movie_id = isset($_GET['movie_id']) ? intval($_GET['movie_id']) : 0;

    $sql = "DELETE FROM booking_movie WHERE movie_id=$movie_id";

    $pdo->query($sql);

    # $_SERVER['HTTP_REFERER'] # 人從哪裡來

    $goto = empty($_SERVER['HTTP_REFERER']) ? 'booking_movie_list.php.php' : $_SERVER['HTTP_REFERER'];

    header('Location: '. $goto); 
?>